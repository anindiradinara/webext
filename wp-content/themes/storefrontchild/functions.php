<?php
function mychildtheme_enqueue_styles() {
    $parent_style = 'parent-style';
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'mychildtheme_enqueue_styles' );

if ( ! function_exists( 'storefront_credit' ) ) {
	/**
	 * Display the theme credit
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_credit() {
		$links_output = '';
		?>
		<div class="site-info">
			<?php echo esc_html( apply_filters( 'storefront_copyright_text', $content = '&copy; ' . gmdate( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.' ) ); ?>
			<?php if ( ! empty( $links_output ) ) { ?>
				<br />
				<?php echo wp_kses_post( $links_output ); ?>
			<?php } ?>
		</div><!-- .site-info -->
		<?php
	}
}

function anin_sale_badge( $html, $post, $product ) {
    /*
    if( $product->is_type('variable')) {
        $percentages = array();

        $prices = $product->get_variation_prices();

        foreach( $prices['price'] as $key => $price ) {
            if( $prices['regular_price'][$key] !== $price ) {
                $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
            }
        }
        $percentage = "up to " . round(max($percentages)) . '%';
    } else {
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
    }
    return '' . esc_html__( 'SALE', 'woocommerce' ) . ' ' . $percentage . '';
    */
    return false;
}
//add_action( 'woocommerce_sale_flash', 'anin_sale_badge', 20, 3 );
add_filter( 'woocommerce_sale_flash', '__return_null' );