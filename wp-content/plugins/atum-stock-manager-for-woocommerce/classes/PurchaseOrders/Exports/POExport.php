<?php
/**
 * Extends the Purchase Order Class and exports it as PDF
 *
 * @package         Atum\DataExport
 * @subpackage      Reports
 * @author          Be Rebel - https://berebel.io
 * @copyright       ©2021 Stock Management Labs™
 *
 * @since           1.3.9
 */

namespace Atum\PurchaseOrders\Exports;

defined( 'ABSPATH' ) || die;

use Atum\Inc\Helpers;
use Atum\PurchaseOrders\Models\PurchaseOrder;
use Atum\PurchaseOrders\PurchaseOrders;
use Atum\Suppliers\Supplier;


class POExport extends PurchaseOrder {
	
	/**
	 * The company data
	 *
	 * @var array
	 */
	private $company_data = [];

	/**
	 * The shipping data
	 *
	 * @var array
	 */
	private $shipping_data = [];
	
	/**
	 * POModel constructor
	 *
	 * @since 1.3.9
	 *
	 * @param int $id
	 */
	public function __construct( $id = 0 ) {
		
		$post_type = get_post_type( $id );
		
		if ( PurchaseOrders::get_post_type() !== $post_type ) {
			/* translators: the post ID */
			wp_die( sprintf( esc_html__( 'Not a Purchase Order (%d)', ATUM_TEXT_DOMAIN ), (int) $id ) );
		}
		
		// Always read items.
		parent::__construct( $id );
		
		$this->load_extra_data();
		
	}

	/**
	 * Get all extra data not present in a PO by default
	 *
	 * TODO: THIS NEEDS A FULL REFACTORY AND TO CREATE A MODEL FOR THE STORE DETAILS. ALSO NOT IT SHOULDN'T STORE SEPARATED META KEYS.
	 *
	 * @since 1.3.9
	 */
	private function load_extra_data() {
		
		$default_country = get_option( 'woocommerce_default_country' );
		$country_state   = wc_format_country_state_string( Helpers::get_option( 'country', $default_country ) );

		// Company data.
		$this->company_data = array(
			'company'    => Helpers::get_option( 'company_name' ),
			'address_1'  => Helpers::get_option( 'address_1' ),
			'address_2'  => Helpers::get_option( 'address_2' ),
			'city'       => Helpers::get_option( 'city' ),
			'state'      => $country_state['state'],
			'postcode'   => Helpers::get_option( 'zip' ),
			'country'    => $country_state['country'],
			'tax_number' => Helpers::get_option( 'tax_number' ),
		);
		
		if ( 'yes' === Helpers::get_option( 'same_ship_address' ) ) {
			$this->shipping_data = $this->company_data;
		}
		else {

			// Shipping data.
			$country_state = wc_format_country_state_string( Helpers::get_option( 'ship_country', $default_country ) );
			
			$this->shipping_data = array(
				'company'   => Helpers::get_option( 'ship_to' ),
				'address_1' => Helpers::get_option( 'ship_address_1' ),
				'address_2' => Helpers::get_option( 'ship_address_2' ),
				'city'      => Helpers::get_option( 'ship_city' ),
				'state'     => $country_state['state'],
				'postcode'  => Helpers::get_option( 'ship_zip' ),
				'country'   => $country_state['country'],
			);

		}
		
	}

	/**
	 * Return header content if exist
	 *
	 * @since 1.3.9
	 *
	 * @return string
	 */
	public function get_content() {
		
		$total_text_colspan = 3;
		$post_type          = get_post_type_object( get_post_type( $this->get_id() ) );
		$currency           = $this->currency;
		$discount           = $this->get_total_discount();

		if ( $discount ) {
			$desc_percent = 50;
			$total_text_colspan++;
		}
		else {
			$desc_percent = 60;
		}

		$taxes               = $this->get_taxes();
		$n_taxes             = count( $taxes );
		$desc_percent       -= $n_taxes * 10;
		$total_text_colspan += $n_taxes;

		$line_items_fee      = $this->get_items( 'fee' );
		$line_items_shipping = $this->get_items( 'shipping' );
		$po                  = $this;
		
		ob_start();

		Helpers::load_view( 'reports/purchase-order-html', compact( 'po', 'total_text_colspan', 'post_type', 'currency', 'discount', 'desc_percent', 'taxes', 'n_taxes', 'line_items_fee', 'line_items_shipping' ) );

		return ob_get_clean();
		
	}
	
	/**
	 * Return formatted company address
	 *
	 * @return string
	 */
	public function get_company_address() {
		
		return apply_filters( 'atum/purchase_orders/po_export/company_address', WC()->countries->get_formatted_address( $this->company_data ), $this->company_data );

	}
	
	/**
	 * Return formatted supplier address (includes VAT number if saved)
	 *
	 * @return string
	 */
	public function get_supplier_address() {
		
		$address     = '';
		$supplier_id = $this->get_supplier( 'id' );
		
		if ( $supplier_id ) {

			$supplier = new Supplier( $supplier_id );
			
			$address = WC()->countries->get_formatted_address( array(
				'first_name' => $supplier->name,
				'company'    => $supplier->tax_number,
				'address_1'  => $supplier->address,
				'city'       => $supplier->city,
				'state'      => $supplier->state,
				'postcode'   => $supplier->zip_code,
				'country'    => $supplier->country,
			) );
			
		}
		
		return apply_filters( 'atum/purchase_orders/po_export/supplier_address', $address, $supplier_id );
		
	}
	
	/**
	 * Return formatted company address
	 *
	 * @since 1.3.9
	 *
	 * @return string
	 */
	public function get_shipping_address() {
		
		return apply_filters( 'atum/purchase_orders/po_export/shipping_address', WC()->countries->get_formatted_address( $this->shipping_data ), $this->shipping_data, $this->id );
		
	}

	/**
	 * Getter for the company's Tax/VAT number
	 *
	 * @since 1.7.0
	 *
	 * @return string
	 */
	public function get_tax_number() {

		return $this->company_data['tax_number'];
	}

	/**
	 * Return an array with stylesheets needed to include in the pdf
	 *
	 * @since 1.3.9
	 *
	 * @param string $output Whether the output array of stylesheets are returned as a path or as an URL.
	 *
	 * @return array
	 */
	public function get_stylesheets( $output = 'path' ) {
		
		$prefix = 'url' === $output ? ATUM_URL : ATUM_PATH;
		
		return apply_filters( 'atum/purchase_orders/po_export/css', array(
			$prefix . 'assets/css/atum-po-export.css',
		), $output, $this );
	}

}
