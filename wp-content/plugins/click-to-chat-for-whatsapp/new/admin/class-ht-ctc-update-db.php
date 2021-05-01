<?php
/**
 * When plugin upgrades
 * 
 * update the db values to compatibile with in versions
 *
 * @package ctc
 * @since 3.2.2
 * @from ht-ctc-db.php -> db()
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Update_DB' ) ) :

class HT_CTC_Update_DB {


    public function __construct() {
        $this->ht_ctc_updatedb();
    }
    
    
    /**
     * update db - First
     * @since 3.2.2 ( intiall 3.0, later 3.2.2 moved form class-ht-ctc-db.php )
     */
    public function ht_ctc_updatedb() {

        $ht_ctc_plugin_details = get_option('ht_ctc_plugin_details');

        // $ht_ctc_chat_options = get_option('ht_ctc_chat_options');
        // $ht_ctc_group = get_option('ht_ctc_group');
        // $ht_ctc_share = get_option('ht_ctc_share');
        
        // only if already installed.
        if ( isset( $ht_ctc_plugin_details['version'] ) ) {

            // v3: if not yet updated to v3 or above  (in v3 $ht_ctc_plugin_details['v3'] is added)
            if ( !isset( $ht_ctc_plugin_details['v3'] ) ) {
                $this->v3_update();
            }

        }


    }


    /**
     * Database updates.. 
     */

    /**
     * updating to v3 or above. 
     *  - style 3 Extend to Style-3_1 
     *  - analytics, .. switch to other settings..
     */
    public function v3_update() {

        $ht_ctc_othersettings = get_option('ht_ctc_othersettings');
        $ht_ctc_s3 = get_option('ht_ctc_s3');
        
        // ht_ctc_main_options to ht_ctc_othersettings
        $ht_ctc_main_options = get_option('ht_ctc_main_options');

        if ( $ht_ctc_main_options ) {

            $os = array(
                'hello' => 'world',
            );
            
            if ( isset ( $ht_ctc_main_options['google_analytics'] ) ) {
                $os['google_analytics'] = '1';
            }
            if ( isset ( $ht_ctc_main_options['fb_pixel'] ) ) {
                $os['fb_pixel'] = '1';
            }
            if ( isset ( $ht_ctc_main_options['enable_group'] ) ) {
                $os['enable_group'] = '1';
            }
            if ( isset ( $ht_ctc_main_options['enable_share'] ) ) {
                $os['enable_share'] = '1';
            }

            $db_os = get_option( 'ht_ctc_othersettings', array() );
            $update_os = array_merge($os, $db_os);
            update_option('ht_ctc_othersettings', $update_os);

            // delete ht_ctc_main_options settings, as transfered to other settings
            delete_option( 'ht_ctc_main_options' );
        }
        

        // style-3 type extend is selected.. and if style 3 to 3_1
        if ( isset($ht_ctc_s3['s3_type']) && 'extend' == $ht_ctc_s3['s3_type'] ) {

            $ht_ctc_chat_options = get_option('ht_ctc_chat_options');
            $ht_ctc_group = get_option('ht_ctc_group');
            $ht_ctc_share = get_option('ht_ctc_share');
            
            // this works as s3 type extend came later version of select style dekstop, mobile.
            // chat
            if ( isset($ht_ctc_chat_options['style_desktop']) && isset($ht_ctc_chat_options['style_mobile']) ) {
                if ( '3' == $ht_ctc_chat_options['style_desktop']) {
                    $ht_ctc_chat_options['style_desktop'] = '3_1';
                }
                if ( '3' == $ht_ctc_chat_options['style_mobile']) {
                    $ht_ctc_chat_options['style_mobile'] = '3_1';
                }
                update_option( 'ht_ctc_chat_options', $ht_ctc_chat_options);
            }

            // group
            if (isset($ht_ctc_group['style_desktop'])) {
                if ( '3' == $ht_ctc_group['style_desktop']) {
                    $ht_ctc_group['style_desktop'] = '3_1';
                }
                if ( '3' == $ht_ctc_group['style_mobile']) {
                    $ht_ctc_group['style_mobile'] = '3_1';
                }
                update_option( 'ht_ctc_group', $ht_ctc_group);
            }

            // share
            if (isset($ht_ctc_share['style_desktop'])) {
                if ( '3' == $ht_ctc_share['style_desktop']) {
                    $ht_ctc_share['style_desktop'] = '3_1';
                }
                if ( '3' == $ht_ctc_share['style_mobile']) {
                    $ht_ctc_share['style_mobile'] = '3_1';
                }
                update_option( 'ht_ctc_share', $ht_ctc_share);
            }

        }

    }







}

new HT_CTC_Update_DB();

endif; // END class_exists check