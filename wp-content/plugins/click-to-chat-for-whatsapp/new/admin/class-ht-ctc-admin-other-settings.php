<?php
/**
 * Other settings page - admin 
 * 
 * this main settings page contains .. 
 * 
 *  Analytics, .. 
 * 
 * @package ctc
 * @subpackage admin
 * @since 3.0 
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Admin_Other_Settings' ) ) :

class HT_CTC_Admin_Other_Settings {

    public function menu() {

        add_submenu_page(
            'click-to-chat',
            'Other-Settings',
            'Other Settings',
            'manage_options',
            'click-to-chat-other-settings',
            array( $this, 'settings_page' )
        );
    }

    public function settings_page() {

        if ( ! current_user_can('manage_options') ) {
            return;
        }

        ?>

        <div class="wrap">

            <?php settings_errors(); ?>

            <div class="row">
                <div class="col s12 m12 xl8 options">
                    <form action="options.php" method="post" class="">
                        <?php settings_fields( 'ht_ctc_os_page_settings_fields' ); ?>
                        <?php do_settings_sections( 'ht_ctc_os_page_settings_sections_do' ) ?>
                        <?php submit_button() ?>
                    </form>
                </div>
                <!-- <div class="col s12 m12 xl6 ht-ctc-admin-sidebar">
                </div> -->
            </div>

            <!-- new row - After settings page  -->
            <div class="row">
                
                <!-- after settings page -->
                <?php // include_once HT_CTC_PLUGIN_DIR .'new/admin/admin_commons/admin-after-settings-page.php'; ?>
                    
            </div>


        </div>

        <?php

    }

    public function settings() {

        register_setting( 'ht_ctc_os_page_settings_fields', 'ht_ctc_othersettings' , array( $this, 'options_sanitize' ) );
        
        add_settings_section( 'ht_ctc_os_settings_sections_add', '', array( $this, 'main_settings_section_cb' ), 'ht_ctc_os_page_settings_sections_do' );
        
        add_settings_field( 'ht_ctc_animations', 'Animations', array( $this, 'ht_ctc_animations_cb' ), 'ht_ctc_os_page_settings_sections_do', 'ht_ctc_os_settings_sections_add' );
        add_settings_field( 'ht_ctc_analytics', 'Analytics', array( $this, 'ht_ctc_analytics_cb' ), 'ht_ctc_os_page_settings_sections_do', 'ht_ctc_os_settings_sections_add' );
        add_settings_field( 'ht_ctc_webhooks', 'Webhooks', array( $this, 'ht_ctc_webhooks_cb' ), 'ht_ctc_os_page_settings_sections_do', 'ht_ctc_os_settings_sections_add' );
        add_settings_field( 'ht_ctc_othersettings', 'Other Settings', array( $this, 'ht_ctc_othersettings_cb' ), 'ht_ctc_os_page_settings_sections_do', 'ht_ctc_os_settings_sections_add' );
        
    }

    public function main_settings_section_cb() {
        ?>
        <h1>Other Settings</h1>
        <?php
        do_action('ht_ctc_ah_admin' );
    }

    function ht_ctc_analytics_cb() {

        $options = get_option('ht_ctc_othersettings');
        $dbrow = 'ht_ctc_othersettings';

        ?>
        <ul class="collapsible" data-collapsible="accordion" id="ht_ctc_analytics">
        <li class="active">
        <div class="collapsible-header"><?php _e( 'Google Analytics, Facebook Pixel', 'click-to-chat-for-whatsapp' ); ?></div>
        <div class="collapsible-body">
        
        <?php

        // Google Analytics
        if ( isset( $options['google_analytics'] ) ) {
            ?>
            <p>
                <label>
                    <input name="<?= $dbrow; ?>[google_analytics]" type="checkbox" value="1" <?php checked( $options['google_analytics'], 1 ); ?> id="google_analytics" />
                    <span><?php _e( 'Google Analytics', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
        ?>
        <p>
            <label>
                <input name="<?= $dbrow; ?>[google_analytics]" type="checkbox" value="1" id="google_analytics" />
                <span><?php _e( 'Google Analytics', 'click-to-chat-for-whatsapp' ); ?></span>
            </label>
        </p>
        <?php
        }

        // ga4
        if ( isset( $options['ga4'] ) ) {
            ?>
            <p class="ctc_ga4" style="margin-left:40px;">
                <label>
                    <input name="<?= $dbrow; ?>[ga4]" type="checkbox" value="1" <?php checked( $options['ga4'], 1 ); ?> id="ga4" />
                    <span><?php _e( 'If Google Analytics 4 is installed', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
            ?>
            <p class="ctc_ga4" style="margin-left:40px;">
                <label>
                    <input name="<?= $dbrow; ?>[ga4]" type="checkbox" value="1" id="ga4" />
                    <span><?php _e( 'If Google Analytics 4 is installed', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
            }
        ?>
        <!-- todo link ga4 -->
        <!-- <p class="description ctc_ga4" style="margin-left:40px;"> <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/google-analytics-4/"><?php _e( 'Google Analytics 4', 'click-to-chat-for-whatsapp' ); ?></a> </p> -->

        <p class="description"><?php _e( 'If Google Analytics installed creates an Event there', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/google-analytics/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
        <br>


        <?php

        // Facebook Pixel
        if ( isset( $options['fb_pixel'] ) ) {
            ?>
            <p>
                <label>
                    <input name="<?= $dbrow; ?>[fb_pixel]" type="checkbox" value="1" <?php checked( $options['fb_pixel'], 1 ); ?> id="fb_pixel" />
                    <span><?php _e( 'Facebook Pixel', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
        ?>
        <p>
            <label>
                <input name="<?= $dbrow; ?>[fb_pixel]" type="checkbox" value="1" id="fb_pixel" />
                <span><?php _e( 'Facebook Pixel', 'click-to-chat-for-whatsapp' ); ?></span>
            </label>
        </p>
        <?php
        }
        ?>
        <p class="description"><?php _e( 'If Facebook Pixel installed creates an Event there', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/facebook-pixel/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
        <br>

        <?php

        // Google Ads gtag_report_conversion
        if ( isset( $options['ga_ads'] ) ) {
            ?>
            <p>
                <label>
                    <input name="<?= $dbrow; ?>[ga_ads]" type="checkbox" value="1" <?php checked( $options['ga_ads'], 1 ); ?> id="ga_ads" />
                    <span><?php _e( 'Google Ads Conversion', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
        ?>
        <p>
            <label>
                <input name="<?= $dbrow; ?>[ga_ads]" type="checkbox" value="1" id="ga_ads" />
                <span><?php _e( 'Google Ads Conversion', 'click-to-chat-for-whatsapp' ); ?></span>
            </label>
        </p>
        <?php
        }

        // change function name
        ?>
        <!-- call gtag_report_conversion function when user clicks on WhatsApp icon/button -->
        <p class="description"><?php _e( 'call gtag_report_conversion function, when user clicks', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/google-ads-conversion/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
        <p class="description">Beta feature. ( please contact us for suggestions, improvements. )</p>
        <br>


        </div>
        </li>
        </ul>
        <?php
    }

    // webhook
    function ht_ctc_webhooks_cb() {

        $options = get_option('ht_ctc_othersettings');
        $dbrow = 'ht_ctc_othersettings';

        $hook_url = isset($options['hook_url']) ? esc_attr( $options['hook_url'] ) : '';

        ?>
        <ul class="collapsible" data-collapsible="accordion" id="ht_ctc_analytics">
        <li class="active">
        <div class="collapsible-header"><?php _e( 'Webhooks', 'click-to-chat-for-whatsapp' ); ?></div>
        <div class="collapsible-body">
        
        <p class="description" style="margin-bottom: 20px;">Integarte, Automation using Webhooks.</p>
        <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/webhooks/"><?php _e( 'Webhooks', 'click-to-chat-for-whatsapp' ); ?></a></p>
        <br><br><br>

        <!-- Webhook URL -->
        <div class="row">
            <div class="input-field col s12">
                <input name="<?= $dbrow; ?>[hook_url]" value="<?= $hook_url ?>" id="hook_url" type="text" class="input-margin">
                <label for="hook_url"><?php _e( 'Webhook URL', 'click-to-chat-for-whatsapp' ); ?></label>
                <p class="description"><?php _e( 'Calls this webhook url after user clicks on WhatsApp Icon/Image', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/webhooks/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
            </div>
        </div>

        <div class="row">
        
            <br>
            <div class="ctc_hook_value">
                <?php

                // hook values
                $hook_v = (isset($options['hook_v'])) ? $options['hook_v'] : '' ;
                $count = 1;
                $num = '';

                if ( is_array($hook_v) ) {
                    $hook_v = array_filter($hook_v);
                    $hook_v = array_values($hook_v);
                    $count = count($hook_v);
                }

                // hook values
                if ( isset( $hook_v[0] ) ) {
                    for ($i=0; $i < $count ; $i++) {
                        $dbrow = "ht_ctc_othersettings[hook_v][$i]";
                        $num = $hook_v[$i];
                        ?>
                        <div class="additional-value row" style="margin-bottom: 15px;">
                            <div class="col s3">
                                <p class="description">Value<?= $i+1; ?></p>
                            </div>
                            <div class="col s9 m6">
                                <p style="display: flex;">
                                    <input name="<?= $dbrow; ?>" value="<?= $num; ?>" type="text"/>
                                    <span style="color:lightgrey; cursor:pointer;" class="hook_remove_value dashicons dashicons-no-alt"></span>
                                </p>
                            </div>
                        </div>
                        <?php
                    }
                }
                
                ?>
            </div>
                    
            <span style="color:#039be5; cursor:pointer; font-size:16px;" 
            class="add_hook_value dashicons dashicons-plus-alt2 col s12" 
            data-html='<div class="row"><div class="col s3"><p class="description"><?php _e( "Add Value", "click-to-chat-for-whatsapp" ); ?></p></div><div class="input-field col s9 m6"><input name="ht_ctc_othersettings[hook_v][]" value="" id="hook_v" type="text" class="input-margin"><label for="hook_v"><?php _e( "Value", "click-to-chat-for-whatsapp" ); ?></label></div></div>' 
            ><?php _e( "Add Value", "click-to-chat-for-whatsapp" ); ?></span>
            
        </div>

        <p class="description">New feature: since v3.3.1 ( please contact us for suggestions, improvements. )</p>
        <br>

        
        </div>
        </li>
        </ul>
        <?php
    }

    // animations
    function ht_ctc_animations_cb() {
        $options = get_option('ht_ctc_othersettings');
        $dbrow = 'ht_ctc_othersettings';

        $show_effect = ( isset( $options['show_effect']) ) ? esc_attr( $options['show_effect'] ) : 'no-show-effects';
        $an_delay = ( isset( $options['an_delay']) ) ? esc_attr( $options['an_delay'] ) : '';
        $an_itr = ( isset( $options['an_itr']) ) ? esc_attr( $options['an_itr'] ) : '';
        $show_effect_list = array(
            'no-show-effects',
            'From Center',
            'From Corner',
        );

        $an_type = ( isset( $options['an_type']) ) ? esc_attr( $options['an_type'] ) : '';
        
        $an_list = array(
            'no-animation',
            'bounce',
            'flash',
            'pulse',
            'heartBeat',
            'flip',
        );

        ?>
        <ul class="collapsible" data-collapsible="accordion" id="ht_ctc_animations">
        <li class="active">
        <div class="collapsible-header"><?php _e( 'Animations', 'click-to-chat-for-whatsapp' ); ?></div>
        <div class="collapsible-body">

        <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/animations/"><?php _e( 'Animations', 'click-to-chat-for-whatsapp' ); ?></a></p>
        <br><br><br>


        <!-- animation on load -->
        <div class="row">
            <div class="col s6">
                <p><?php _e( 'Animations', 'click-to-chat-for-whatsapp' ); ?></p>
            </div>
            <div class="input-field col s6">
                <select name="ht_ctc_othersettings[an_type]" class="select_an_type">
                <?php 
                
                foreach ( $an_list as $value ) {
                ?>
                <option value="<?= $value ?>" <?= $an_type == $value ? 'SELECTED' : ''; ?> ><?= $value ?></option>
                <?php
                }

                ?>
                </select>
                <label><?php _e( 'Animations', 'click-to-chat-for-whatsapp' ); ?></label>
            </div>
        </div>

        <!-- animation delay -->
        <div class="row an_delay">
            <div class="col s6">
                <p><?php _e( 'Animation Delay', 'click-to-chat-for-whatsapp' ); ?></p>
            </div>
            <div class="input-field col s6">
                <input name="<?= $dbrow; ?>[an_delay]" value="<?= $an_delay ?>" id="an_delay" type="number" min="0" class="" >
                <label for="an_delay"><?php _e( 'Animation Delay', 'click-to-chat-for-whatsapp' ); ?></label>
                <p class="description"><?php _e( 'E.g. Add 1 for 1 second delay', 'click-to-chat-for-whatsapp' ); ?></p>
            </div>
        </div>

        <!-- animation iteration -->
        <div class="row an_itr">
            <div class="col s6">
                <p><?php _e( 'Animation Iteration', 'click-to-chat-for-whatsapp' ); ?></p>
            </div>
            <div class="input-field col s6">
                <input name="<?= $dbrow; ?>[an_itr]" value="<?= $an_itr ?>" id="an_itr" type="number" min="1" class="" >
                <label for="an_itr"><?php _e( 'Animation Iteration', 'click-to-chat-for-whatsapp' ); ?></label>
                <p class="description"><?php _e( 'E.g. Add 2 to repeat animation 2 times', 'click-to-chat-for-whatsapp' ); ?></p>
            </div>
        </div>

        <hr style="width: 50%;">
        <br><br>

        <!-- Show effect -->
        <div class="row">
            <div class="col s6">
                <p><?php _e( 'Show Effects', 'click-to-chat-for-whatsapp' ); ?></p>
            </div>
            <div class="input-field col s6">
                <select name="ht_ctc_othersettings[show_effect]" class="show_effect">
                <?php 
                
                foreach ( $show_effect_list as $value ) {
                ?>
                <option value="<?= $value ?>" <?= $show_effect == $value ? 'SELECTED' : ''; ?> ><?= $value ?></option>
                <?php
                }

                ?>
                </select>
                <label><?php _e( 'How the Icon/Button displays', 'click-to-chat-for-whatsapp' ); ?></label>
            </div>
        </div>

        </div>
        </li>
        </ul>
        <?php
    }

    // Other settings
    //      detect device
    function ht_ctc_othersettings_cb() {

        $options = get_option('ht_ctc_othersettings');
        $dbrow = 'ht_ctc_othersettings';

        // start other settings
        do_action('ht_ctc_ah_admin_start_os');

        $li_active_gr_sh = ( isset( $options['enable_group'] ) || isset( $options['enable_share'] ) ) ? "class='active'" : '';
        ?>

        <ul class="collapsible" data-collapsible="accordion" id="ht_ctc_othersettings">
        <li class="active">
        <div class="collapsible-header"><?php _e( 'Other Settings', 'click-to-chat-for-whatsapp' ); ?></div>
        <div class="collapsible-body">

        <?php
        // cache issue while selecting styles
        if ( isset( $options['select_styles_issue'] ) ) {
            ?>
            <p id="styles_issue">
                <label>
                    <input name="<?= $dbrow; ?>[select_styles_issue]" type="checkbox" value="1" <?php checked( $options['select_styles_issue'], 1 ); ?> id="select_styles_issue" />
                    <!-- <span>Style for device is not as expected(due to cache)</span> -->
                    <span><?php _e( 'Check this only, If styles for mobile, desktop not selected as expected(due to cache)', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
            ?>
            <p id="styles_issue">
                <label>
                    <input name="<?= $dbrow; ?>[select_styles_issue]" type="checkbox" value="1" id="select_styles_issue" />
                    <span><?php _e( 'Check this only, If styles for mobile, desktop not selected as expected(due to cache)', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        }
        ?>
        <br>


        <?php
        // hook
        // in other settings
        do_action('ht_ctc_ah_admin_in_os');
        ?>

        </div>
        </li>
        </ul>


        <br>

        <!-- enable group, share features -->
        <ul class="collapsible" data-collapsible="accordion" id="ht_ctc_enable_share_group">
        <li <?= $li_active_gr_sh; ?>>
        <div class="collapsible-header"><?php _e( 'Group, Share features', 'click-to-chat-for-whatsapp' ); ?></div>
        <div class="collapsible-body">
        
        <?php

        // enable group
        if ( isset( $options['enable_group'] ) ) {
        ?>
        <p>
            <label>
                <input name="ht_ctc_othersettings[enable_group]" type="checkbox" value="1" <?php checked( $options['enable_group'], 1 ); ?> id="enable_group" />
                <span><?php _e( 'Enable Group Features', 'click-to-chat-for-whatsapp' ); ?></span>
            </label>
            <p class="description"> <?php _e( 'Adds WhatsApp Icon for Group', 'click-to-chat-for-whatsapp' ); ?> - <a href="<?= admin_url( 'admin.php?page=click-to-chat-group-feature' ); ?>"><?php _e( 'Group Settings page', 'click-to-chat-for-whatsapp' ); ?></a> </p>
        </p>
        <?php
        } else {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_othersettings[enable_group]" type="checkbox" value="1" id="enable_group" />
                    <span><?php _e( 'Enable Group Features', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <p class="description"> <?php _e( 'Adds WhatsApp Icon for Group', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/enable-group-feature/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
            <?php
        }
        ?>
        <br>
        <?php


        // enable share
        if ( isset( $options['enable_share'] ) ) {
        ?>
        <p>
            <label>
                <input name="ht_ctc_othersettings[enable_share]" type="checkbox" value="1" <?php checked( $options['enable_share'], 1 ); ?> id="enable_share" />
                <span><?php _e( 'Enable Share Features', 'click-to-chat-for-whatsapp' ); ?></span>
            </label>
            <p class="description"> <?php _e( 'Adds WhatsApp Icon for Share', 'click-to-chat-for-whatsapp' ); ?> - <a href="<?= admin_url( 'admin.php?page=click-to-chat-share-feature' ); ?>"><?php _e( 'Share Settings page', 'click-to-chat-for-whatsapp' ); ?></a> </p>
        </p>
        <?php
        } else {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_othersettings[enable_share]" type="checkbox" value="1" id="enable_share" />
                    <span><?php _e( 'Enable Share Features', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <p class="description"> <?php _e( 'Adds WhatsApp Icon for Share', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/enable-share-feature/"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
            <?php
        }
        ?>
        <br>
        
        <!-- chat -->
        <p class="description"><?php _e( "Chat settings are enabled by default. If like to hide chat on all pages select", 'click-to-chat-for-whatsapp' ); ?> <a target="_blank" href="<?= admin_url( 'admin.php?page=click-to-chat#showhide_settings' ); ?>"><?php _e( 'show on selected pages', 'click-to-chat-for-whatsapp' ); ?></a> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/enable-chat"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a> </p>
        <br>


        </div>
        </li>
        </ul>

        <br>

        <!-- Troubleshoot, Debug, ..  -->
        <ul class="collapsible" data-collapsible="accordion" id="ht_ctc_debug">
        <li>
        <div class="collapsible-header"><?php _e( 'Debug, Troubleshoot, ..', 'click-to-chat-for-whatsapp' ); ?></div>
        <div class="collapsible-body">
        <?php

        // AMP Compatibility - enabled by default.  (if an issue uncheck this..)
        // later version remove this option and make enable by default..
        // if amp related issue, uncheck this option
        if ( function_exists( 'amp_is_request' ) ) {
            if ( isset( $options['amp'] ) ) {
                ?>
                <p id="amp_compatibility">
                    <label>
                        <input name="<?= $dbrow; ?>[amp]" type="checkbox" value="1" <?php checked( $options['amp'], 1 ); ?> id="amp" />
                        <span><?php _e( 'AMP Compatibility', 'click-to-chat-for-whatsapp' ); ?></span>
                    </label>
                </p>
                <?php
            } else {
                ?>
                <p id="amp_compatibility">
                    <label>
                        <input name="<?= $dbrow; ?>[amp]" type="checkbox" value="1" id="amp" />
                        <span><?php _e( 'AMP Compatibility', 'click-to-chat-for-whatsapp' ); ?></span>
                    </label>
                </p>
                <?php
            }
            ?>
            <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/amp-compatibility/"><?php _e( 'AMP Compatibility', 'click-to-chat-for-whatsapp' ); ?></a> New feature: If any issue, uncheck this option and please contact us</p>
            <br>
            <?php
        }

        // debug mode 
        if ( isset( $options['debug_mode'] ) ) {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_othersettings[debug_mode]" type="checkbox" value="1" <?php checked( $options['debug_mode'], 1 ); ?> id="debug_mode"   />
                    <span><?php _e( 'Debug mode', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_othersettings[debug_mode]" type="checkbox" value="1" id="debug_mode"   />
                    <span><?php _e( 'Debug mode', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        }

        ?>

        <p class="description">
            <ul>
            Basic Troubleshoot
                <ol>Clear Cache</ol>
                <ol>Check Display Settings</ol>
            </ul>
        </p>
        <!-- <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/amp-compatibility/">Basic Troubleshooting</a></p> -->
        <br>

        <?php

        // delete options 
        if ( isset( $options['delete_options'] ) ) {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_othersettings[delete_options]" type="checkbox" value="1" <?php checked( $options['delete_options'], 1 ); ?> id="delete_options"   />
                    <span><?php _e( 'Delete this plugin settings when uninstalls', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        } else {
            ?>
            <p>
                <label>
                    <input name="ht_ctc_othersettings[delete_options]" type="checkbox" value="1" id="delete_options"   />
                    <span><?php _e( 'Delete this plugin settings when uninstalls', 'click-to-chat-for-whatsapp' ); ?></span>
                </label>
            </p>
            <?php
        }
        ?>

        <br><br>
        <p class="description"><?php _e( 'If any issue: Please contact us', 'click-to-chat-for-whatsapp' ); ?> <br> <?php _e( 'Mail', 'click-to-chat-for-whatsapp' ); ?>: <a href="mailto:ctc@holithemes.com">ctc@holithemes.com</a> <br> <a target="_blank" href="http://api.whatsapp.com/send?phone=919494429789&text=Hi%20HoliThemes,%20I%20have%20a%20question"><?php _e( 'WhatsApp Us', 'click-to-chat-for-whatsapp' ); ?></a></p>


        </div>
        </li>
        </ul>

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

            if ( 'placeholder' == $key ) {
                $new_input[$key] = sanitize_textarea_field( $input[$key] );
            } elseif ( 'hook_v' == $key ) {
                $new_input[$key] = array_map( 'sanitize_text_field', $input[$key] );
            } elseif ( isset( $input[$key] ) ) {
                $new_input[$key] = sanitize_text_field( $input[$key] );
            }

        }
        
        do_action('ht_ctc_ah_admin_after_sanitize' );

        return $new_input;
    }





}

$ht_ctc_admin_other_settings = new HT_CTC_Admin_Other_Settings();

add_action('admin_menu', array($ht_ctc_admin_other_settings, 'menu') );
add_action('admin_init', array($ht_ctc_admin_other_settings, 'settings') );

endif; // END class_exists check