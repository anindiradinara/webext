<?php
/**
 * woocommerce related front end.
 * 
 * @package ctc
 * @since 2.9
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Admin_WOO' ) ) :

class HT_CTC_Admin_WOO {

    public function __construct() {
        $this->woo_hooks();
    }

    // Hooks
    function woo_hooks() {

        add_filter( 'ht_ctc_fh_chat', array($this, 'chat') );

        // add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_styles' ) );
        // add_action( 'woocommerce_after_shop_loop_item', array( $this, 'add_styles' ) );

    }

    function add_styles() {
        
        $woo = get_option('ht_ctc_woo_options');
        $chat = get_option('ht_ctc_chat_options');

        $type = 'chat';
        $style = 1;
        // $side_2 = 'right';

        $call_to_action = "WhatsApp us";
        $space_left = '100px';
        $space_right = '10px';
        $space_top = '10px';
        $space_bottom = '10px';

        $margin_left = "margin-left: $space_left;";
        $margin_right = "margin-right: $space_right;";
        $margin_top = "margin-top: $space_top;";
        $margin_bottom = "margin-bottom: $space_left;";

        $path = plugin_dir_path( HT_CTC_PLUGIN_FILE ) . 'new/inc/styles/style-' . $style. '.php';

        ?>
        <!-- <button style="margin-top: 100px;">buy now from whatsapp</button> -->
        <div><?php include $path; ?></div>
        <?php
    }


    function chat( $ht_ctc_chat ) {
        
        $woo_options = get_option('ht_ctc_woo_options');

        // $chat = get_option('ht_ctc_chat_options');

        // if woocommerce single product page
        if ( function_exists( 'is_product' ) && function_exists( 'wc_get_product' )) {
            if ( is_product() ) {

                $product = wc_get_product();

                $name = $product->get_name();
                // $title = $product->get_title();
                $price = $product->get_price();
                $regular_price = $product->get_regular_price();
                $sku = $product->get_sku();

                // pre-filled
                if ( isset( $woo_options['woo_pre_filled'] ) && '' !== $woo_options['woo_pre_filled'] ) {
                    $ht_ctc_chat['pre_filled'] = esc_attr( $woo_options['woo_pre_filled'] );
                    $ht_ctc_chat['pre_filled'] = apply_filters( 'wpml_translate_single_string', $ht_ctc_chat['pre_filled'], 'Click to Chat for WhatsApp', 'woo_pre_filled' );
                }
                // variables works in default pre_filled also for woo pages.
                $ht_ctc_chat['pre_filled'] = str_replace( array('{product}', '{price}', '{regular_price}', '{sku}' ),  array( $name, $price, $regular_price, $sku ), $ht_ctc_chat['pre_filled'] );

                // call to action
                if ( isset( $woo_options['woo_call_to_action'] ) && '' !== $woo_options['woo_call_to_action'] ) {
                    $ht_ctc_chat['call_to_action'] = esc_attr( $woo_options['woo_call_to_action'] );
                    $ht_ctc_chat['call_to_action'] = apply_filters( 'wpml_translate_single_string', $ht_ctc_chat['call_to_action'], 'Click to Chat for WhatsApp', 'woo_call_to_action' );
                    $ht_ctc_chat['call_to_action'] = str_replace( array('{product}', '{price}', '{regular_price}', '{sku}' ),  array( $name, $price, $regular_price, $sku ), $ht_ctc_chat['call_to_action'] );
                }

            }
        }

        return $ht_ctc_chat;
    }


    








}

new HT_CTC_Admin_WOO();

endif; // END class_exists check