<?php
/**
 * Main settings page - admin 
 * 
 * this main settings page contains .. 
 * 
 * enable options .. like chat default enabled, group, share, woocommerce
 * 
 * switch option
 * 
 * @package ctc
 * @subpackage admin
 * @since 2.0 
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Admin_Main_Page' ) ) :

class HT_CTC_Admin_Main_Page {

    public function menu() {

        // dashicons-format-chat  /  dashicons-whatsapp
        $icon = 'dashicons-whatsapp';
        if( version_compare( get_bloginfo('version'), '5.6', '<') )  {
            $icon = 'dashicons-format-chat';
        }

        add_menu_page(
            'Click to Chat ',
            'Click to Chat',
            'manage_options',
            'click-to-chat',
            array( $this, 'settings_page' ),
            $icon
        );
    }

    public function settings_page() {

        if ( ! current_user_can('manage_options') ) {
            return;
        }

        ?>

        <div class="wrap">

            <?php settings_errors(); ?>

            <!-- full row -->
            <div class="row">

                <div class="col s12 m12 xl7 options">
                    <form action="options.php" method="post" class="">
                        <?php settings_fields( 'ht_ctc_main_page_settings_fields' ); ?>
                        <?php do_settings_sections( 'ht_ctc_main_page_settings_sections_do' ) ?>
                        <?php submit_button() ?>
                    </form>
                </div>

                <!-- sidebar content -->
                <div class="col s12 m12 l7 xl4 ht-ctc-admin-sidebar sticky-sidebar">
                    <?php include_once HT_CTC_PLUGIN_DIR .'new/admin/admin_commons/admin-sidebar-content.php'; ?>
                </div>
                
            </div>

            <!-- new row - After settings page  -->
            <div class="row">
            </div>

        </div>

        <?php

    }


    public function settings() {


        
        // chat feautes
        register_setting( 'ht_ctc_main_page_settings_fields', 'ht_ctc_chat_options' , array( $this, 'options_sanitize' ) );
    
        add_settings_section( 'ht_ctc_chat_page_settings_sections_add', '', array( $this, 'chat_settings_section_cb' ), 'ht_ctc_main_page_settings_sections_do' );

        add_settings_field( 'number', __( 'WhatsApp Number', 'click-to-chat-for-whatsapp'), array( $this, 'number_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        add_settings_field( 'prefilled', __( 'Pre-Filled Message', 'click-to-chat-for-whatsapp'), array( $this, 'prefilled_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        add_settings_field( 'cta', __( 'Call to Action', 'click-to-chat-for-whatsapp'), array( $this, 'cta_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        add_settings_field( 'ctc_webandapi', __( 'Web WhatsApp', 'click-to-chat-for-whatsapp'), array( $this, 'ctc_webandapi_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        add_settings_field( 'ctc_desktop', __( 'Dekstop', 'click-to-chat-for-whatsapp'), array( $this, 'ctc_desktop_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        add_settings_field( 'ctc_mobile', __( 'Mobile', 'click-to-chat-for-whatsapp'), array( $this, 'ctc_mobile_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        add_settings_field( 'ctc_show_hide', __( 'Display Settings', 'click-to-chat-for-whatsapp'), array( $this, 'ctc_show_hide_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        
        if ( class_exists( 'WooCommerce' ) ) {
            add_settings_field( 'ctc_woo', 'WooCommerce', array( $this, 'ctc_woo_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );
        }

        add_settings_field( 'options', '', array( $this, 'options_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );

        add_settings_field( 'ctc_notes', '', array( $this, 'ctc_notes_cb' ), 'ht_ctc_main_page_settings_sections_do', 'ht_ctc_chat_page_settings_sections_add' );


    }


    public function chat_settings_section_cb() {
        ?>
        <h1 id="chat_settings">Click to Chat - Chat Settings </h1>
        <?php
        do_action('ht_ctc_ah_admin' );
    }


    /**
     * WhatsApp number
     * 
     * 
     * @since 3.2.7 - $cc, $num - updated user interface
     */
    function number_cb() {
        $options = get_option('ht_ctc_chat_options');
        $cc = ( isset( $options['cc']) ) ? esc_attr( $options['cc'] ) : '';
        $num = ( isset( $options['num']) ) ? esc_attr( $options['num'] ) : '';
        $number = ( isset( $options['number']) ) ? esc_attr( $options['number'] ) : '';
        if ('' == $num && '' == $cc ) {
            $num = $number;
        }

        include_once HT_CTC_PLUGIN_DIR .'new/admin/admin_commons/class-ht-ctc-static.php';
        $cc_list = HT_CTC_Static::$cc;

        ?>

        <style>
        .ctc_num_field {
            padding-left: 0px !important;
        }
        .ctc_num_field input {
            border: 1px solid #9e9e9e !important;
            padding-left: 15px !important;
        }
        .ctc_num_field input#whatsapp_cc {
            border-right: none !important;
        }
        </style>

        <!-- Full WhatsApp Number Card -->
        <div class="row">
            <div class="col s12 m8">
                <p class="description card-panel grey lighten-3" style="padding: 5px 24px; display: inline-block;"><?php _e( 'WhatsApp Number', 'click-to-chat-for-whatsapp' ); ?>: <span class="ht_ctc_wn"><?= $number ?></span> </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col s12">

                <!-- country code -->
                <div class="input-field col s3 m3 ctc_num_field">
                    <input name="ht_ctc_chat_options[cc]" list="ctc_cc_list" value="<?= $cc ?>" id="whatsapp_cc" type="text" class="input-margin tooltipped" data-position="left" data-tooltip="Country Code">
                    <!-- todo: display only after user entered any value -->
                    <datalist id="ctc_cc_list_next" style="height: 20px; overflow:hidden;">
                        <?php
                        foreach ( $cc_list as $key => $value ) {
                            if (is_array($value)) {
                                foreach ( $value as $v ) {
                                    ?>
                                    <option value="<?= $key; ?>"><?= $v; ?></option>
                                    <?php
                                }
                            } else {
                                ?>
                                <option value="<?= $key; ?>"><?= $value; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </datalist>
                    <label for="whatsapp_cc"><?php _e( 'Country Code', 'click-to-chat-for-whatsapp' ); ?></label>
                    
                    
                </div>

                <!-- number -->
                <div class="input-field col s9 m7 ctc_num_field">
                    <input name="ht_ctc_chat_options[num]" value="<?= $num ?>" id="whatsapp_number" type="text" class="input-margin tooltipped" data-position="right" data-tooltip="Number">
                    <label for="whatsapp_number"><?php _e( 'Number', 'click-to-chat-for-whatsapp' ); ?></label>
                    <!-- <span class="helper-text ">WhatsApp Number: <span class="ht_ctc_wn"></span></span> -->
                </div>

                <!-- full number - hidden field -->
                <input name="ht_ctc_chat_options[number]" style="display: none;" hidden value="<?= $number ?>" id="ctc_whatsapp_number" type="text">

            </div>
                <p class="description"><?php _e( "WhatsApp or WhatsApp business number with ", 'click-to-chat-for-whatsapp' ); ?> <a target="_blank" href="https://holithemes.com/blog/country-codes/"><?php _e( 'country code', 'click-to-chat-for-whatsapp' ); ?></a> </p>
                <p class="description"><?php _e( '( E.g. 916123456789 - herein e.g. 91 is country code, 6123456789 is the mobile number )', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/whatsapp-number/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> ) </p>

            <?php do_action('ht_ctc_ah_admin_chat_number'); ?>

        </div>
        <?php
    }

    // pre-filled - message
    function prefilled_cb() {
        $options = get_option('ht_ctc_chat_options');
        $value = ( isset( $options['pre_filled']) ) ? esc_attr( $options['pre_filled'] ) : '';
        $blogname = HT_CTC_BLOG_NAME;
        $placeholder = "Hello $blogname!! \nName: \nLike to know more information about {{title}}, {{url}}";
        ?>
        <div class="row">
            <div class="input-field col s12">
                <textarea style="min-height: 84px;" placeholder="<?= $placeholder ?>" name="ht_ctc_chat_options[pre_filled]" id="pre_filled" class="materialize-textarea input-margin"><?= $value ?></textarea>
                <label for="pre_filled"><?php _e( 'Pre-filled message', 'click-to-chat-for-whatsapp' ); ?></label>
                <p class="description"><?php _e( "Text that appears in the WhatsApp Chat window. Add variables {site}, {url}, {title} to replace with site name, current webpage URL, Post title", 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/pre-filled-message/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
            </div>
        </div>
        <?php
    }

    // call to action 
    function cta_cb() {
        $options = get_option('ht_ctc_chat_options');
        $value = ( isset( $options['call_to_action']) ) ? esc_attr( $options['call_to_action'] ) : '';
        ?>
        <div class="row">
            <div class="input-field col s12">
                <input name="ht_ctc_chat_options[call_to_action]" value="<?= $value ?>" id="call_to_action" type="text" class="input-margin">
                <label for="call_to_action"><?php _e( 'Call to Action', 'click-to-chat-for-whatsapp' ); ?></label>
                <p class="description"><?php _e( 'Text that appears along with WhatsApp icon/button', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/call-to-action/">more info</a> </p>
            </div>
        </div>
        <?php
    }

    // If checked web / api whatsapp link. If unchecked wa.me links
    function ctc_webandapi_cb() {
        $options = get_option('ht_ctc_chat_options');
        $dbrow = 'ht_ctc_chat_options';

        if ( isset( $options['webandapi'] ) ) {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_chat_options[webandapi]" type="checkbox" value="1" <?php checked( $options['webandapi'], 1 ); ?> id="webandapi"   />
                    <span><?php _e( 'Web WhatsApp on Desktop', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_chat_options[webandapi]" type="checkbox" value="1" id="webandapi"   />
                    <span><?php _e( 'Web WhatsApp on Desktop', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        }
        ?>
        <p class="description"><?php _e( 'If checked opens Web.WhatsApp directly on Desktop and in mobile WhatsApp App', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/web-whatsapp/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
        <br>

        <?php
    }


    // Dekstop
    function ctc_desktop_cb() {
        $options = get_option('ht_ctc_chat_options');
        $dbrow = 'ht_ctc_chat_options';
        $type = 'chat';

        include_once HT_CTC_PLUGIN_DIR .'new/admin/admin_commons/admin-desktop.php';
    }


    // Mobile
    function ctc_mobile_cb() {
        $options = get_option('ht_ctc_chat_options');
        $dbrow = 'ht_ctc_chat_options';
        $type = 'chat';

        include_once HT_CTC_PLUGIN_DIR .'new/admin/admin_commons/admin-mobile.php';
    }


    // show/hide 
    function ctc_show_hide_cb() {
        $options = get_option('ht_ctc_chat_options');
        $dbrow = 'ht_ctc_chat_options';
        $type = 'chat';

        include_once HT_CTC_PLUGIN_DIR .'new/admin/admin_commons/admin-show-hide.php';
    }


    // WooCommerce related settings
    public function ctc_woo_cb() {
        do_action('ht_ctc_ah_admin_chat_woo_settings');
    }


    // More options - for addon plugins
    function options_cb() {
        do_action('ht_ctc_ah_admin_chat_more_options');
    }

    function ctc_notes_cb() {
        ?>
        <p class="description"><a target="_blank" href="<?= admin_url( 'admin.php?page=click-to-chat-customize-styles' ); ?>">Customize Styles</a></p>
        <p class="description"><a target="_blank" href="<?= admin_url( 'admin.php?page=click-to-chat-other-settings' ); ?>">Other Settings</a></p>
        <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/shortcodes-chat">Shortcodes for Chat: </a>[ht-ctc-chat]</p>
        <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/custom-element">Custom Element: </a>Class name: ctc_chat  |  Href/Link: #ctc_chat</p>
        <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/faq">Frequently Asked Questions (FAQ)</a></p>

        <?php

        $clear_cache_text = 'ctc_no_hover_text';

        if ( function_exists('wp_cache_clear_cache') || function_exists('w3tc_pgcache_flush') || function_exists('wpfc_clear_all_cache') || function_exists('rocket_clean_domain') || function_exists('sg_cachepress_purge_cache') || function_exists('wpo_cache_flush') ) {
            $clear_cache_text = "ctc_save_changes_hover_text";
        }

        if( class_exists('autoptimizeCache') || class_exists( 'WpeCommon' ) || class_exists( 'WpeCommon' ) || class_exists('LiteSpeed_Cache_API') || class_exists('Cache_Enabler') || class_exists('PagelyCachePurge') || class_exists('comet_cache') || class_exists('\Hummingbird\WP_Hummingbird') ) {
            $clear_cache_text = "ctc_save_changes_hover_text";
        }

        ?>
        <!-- hover content for submit button -->
        <span style="display: none;" id="<?= $clear_cache_text ?>"><?php _e( 'Please clear the cache after save changes', 'click-to-chat-for-whatsapp' ); ?></span>
        <?php
        
    }



    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function options_sanitize( $input ) {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'not allowed to modify - please contact admin ' );
        }

        $new_input = array();

        foreach ($input as $key => $value) {
            if( isset( $input[$key] ) ) {

                if ( 'pre_filled' == $key || 'woo_pre_filled' == $key ) {
                    // $new_input[$key] = esc_textarea( $input[$key] );
                    $new_input[$key] = sanitize_textarea_field( $input[$key] );
                } elseif ( 'side_1_value' == $key || 'side_2_value' == $key || 'mobile_side_1_value' == $key || 'mobile_side_2_value' == $key ) {
                    $input[$key] = str_replace( ' ', '', $input[$key] );
                    if ( is_numeric($input[$key]) ) {
                        $input[$key] = $input[$key] . 'px';
                    }
                    if ( '' == $input[$key] ) {
                        $input[$key] = '0px';
                    }
                    $new_input[$key] = sanitize_text_field( $input[$key] );
                } elseif ( 'r_nums' == $key ) {
                    $new_input[$key] = array_map( 'sanitize_text_field', $input[$key] );
                } else {
                    $new_input[$key] = sanitize_text_field( $input[$key] );
                }
            }
        }

        // l10n
        foreach ($input as $key => $value) {
            if ( 'number' == $key || 'pre_filled' == $key || 'call_to_action' == $key || 'woo_pre_filled' == $key || 'woo_call_to_action' == $key ) {
                do_action( 'wpml_register_single_string', 'Click to Chat for WhatsApp', $key, $input[$key] );
            }
        }

        do_action('ht_ctc_ah_admin_after_sanitize' );

        return $new_input;
    }


}

$ht_ctc_admin_main_page = new HT_CTC_Admin_Main_Page();

add_action('admin_menu', array($ht_ctc_admin_main_page, 'menu') );
add_action('admin_init', array($ht_ctc_admin_main_page, 'settings') );

endif; // END class_exists check