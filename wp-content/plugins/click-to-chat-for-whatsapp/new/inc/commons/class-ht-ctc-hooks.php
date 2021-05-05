<?php
/**
 * Hooks
 * @since 2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Hooks' ) ) :

class HT_CTC_Hooks {

    public $version = HT_CTC_VERSION;

    public $main_options = '';
    public $other_options = '';

    public function __construct() {
        $this->hooks();
        $this->main_options = get_option('ht_ctc_main_options');
        $this->other_options = get_option('ht_ctc_othersettings');
    }

    private function hooks() {
        // ## Action Hooks ##
        add_action( 'ht_ctc_ah_before_fixed_position', array($this, 'comment') );
        add_action( 'ht_ctc_ah_before_fixed_position', array($this, 'css_styles') );


        // ## Filter Hooks ##
        add_filter( 'ht_ctc_fh_chat', array($this, 'chat_settings') );
        add_filter( 'ht_ctc_fh_load_app_js_bottom', array($this, 'load_app_js_bottom') );

        // other settings
        add_filter( 'ht_ctc_fh_os', array($this, 'other_settings') );
        
    }

    /**
     * Action Hooks
     */

    // css styles - before fixed position
    function css_styles() {

        $othersettings = get_option('ht_ctc_othersettings');


        // display effect - if ? scale from center
        if ( isset($othersettings['show_effect']) && 'From Center' == $othersettings['show_effect'] ) {
            ?>
            <style id="ht_ctc_fromcenter">@keyframes ht_ctc_fromcenter{from{transform:scale(0);}to{transform: scale(1);}}.ht-ctc{animation: ht_ctc_fromcenter .25s;}</style>
            <?php
        }

        // Animation styles
        $an_type = ( isset( $othersettings['an_type']) ) ? esc_attr( $othersettings['an_type'] ) : '';
        
        if ( '' !== $an_type && 'no-animation' !== $an_type ) {

            $an_delay = ( isset( $othersettings['an_delay']) ) ? esc_attr( $othersettings['an_delay'] ) : '0';
            $an_delay = "{$an_delay}s";
            $an_itr = ( isset( $othersettings['an_itr']) ) ? esc_attr( $othersettings['an_itr'] ) : '1';

            include_once HT_CTC_PLUGIN_DIR .'new/inc/commons/class-ht-ctc-animations.php';
            $animations = new HT_CTC_Animations();
            $animations->animations( $an_type, $an_delay, $an_itr );
        }
        
    }
    
    // comment before floting styles
    function comment() {
        $comment = "<!-- Click to Chat - https://holithemes.com/plugins/click-to-chat/  v$this->version -->";
        echo $comment;
    }


    /**
     * Filter Hooks
     */

     // number format
    function chat_settings( $ht_ctc_chat ) {

        // Number format
        // if random number feature, this have to modify (ltrim, preg_replace)
        // $ht_ctc_chat['number'] = preg_replace('/[^0-9,\s]/', '', $ht_ctc_chat['number'] );
        if( isset($ht_ctc_chat['number']) ) {
            $ht_ctc_chat['number'] = preg_replace('/\D/', '', $ht_ctc_chat['number'] );
            $ht_ctc_chat['number'] = ltrim( $ht_ctc_chat['number'], '0' );
        }


        return $ht_ctc_chat;
    }

    // other settings
    function other_settings( $ht_ctc_os ) {

        $othersettings = get_option('ht_ctc_othersettings');

        $ht_ctc_os['show_effect'] = (isset($othersettings['show_effect'])) ? esc_attr($othersettings['show_effect']) : '';
        $ht_ctc_os['is_ga_enable'] = (isset( $othersettings['google_analytics'] )) ? 'yes' : 'no';
        $ht_ctc_os['ga4'] = (isset( $othersettings['ga4'] )) ? 'yes' : 'no';
        $ht_ctc_os['is_fb_pixel'] = (isset( $othersettings['fb_pixel'] )) ? 'yes' : 'no';
        $ht_ctc_os['ga_ads'] = (isset( $othersettings['ga_ads'] )) ? 'yes' : 'no';

        // version number
        $ht_ctc_os['v'] = HT_CTC_VERSION;
        

        // show effect ? if 'From Corner' - then return time (from center - this->css_styles() handles)
        if ( 'From Corner' == $ht_ctc_os['show_effect'] ) {
            $ht_ctc_os['show_effect'] = 120;
        } 

        // Animations
        $ht_ctc_os['an_type'] = "no-animations";
        $an_type = ( isset( $othersettings['an_type']) ) ? esc_attr( $othersettings['an_type'] ) : 'no-animation';
        if ( 'no-animation' !== $an_type ) {
            $ht_ctc_os['data-attributes'] .= "data-an_type='ht_ctc_an_$an_type' ";
            $ht_ctc_os['an_type'] = "ht_ctc_an_$an_type";
        }

        return $ht_ctc_os;
    }



    function load_app_js_bottom( $load_app_js_bottom ) {
        
        // compatibility
        // autoptimize cache plugin
        if( class_exists('autoptimizeCache') ) {
            $load_app_js_bottom = false;
        }
        return $load_app_js_bottom;
    }



    


}

new HT_CTC_Hooks();

endif; // END class_exists check