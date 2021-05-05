<?php
/**
 * Init WooCommerce
 * 
 * @included from ht-ctc.php using init hook
 */


if ( class_exists( 'WooCommerce' ) ) {
    
    if ( is_admin() ) {
        include_once HT_CTC_PLUGIN_DIR .'new/tools/woo/class-ht-ctc-admin-woo.php';
    } else {
        include_once HT_CTC_PLUGIN_DIR .'new/tools/woo/class-ht-ctc-woo.php';
    }

}