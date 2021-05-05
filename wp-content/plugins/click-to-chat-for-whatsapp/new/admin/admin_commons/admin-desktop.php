<?php
/**
*  Admin Desktop
*
* @package ctc
* @subpackage Administration
* @since 2.11
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// style
$style_desktop = ( isset( $options['style_desktop']) ) ? esc_attr( $options['style_desktop'] ) : '';

// position
$side_1 = ( isset( $options['side_1']) ) ? esc_attr( $options['side_1'] ) : '';
$side_1_value = ( isset( $options['side_1_value']) ) ? esc_attr( $options['side_1_value'] ) : '';
$side_2 = ( isset( $options['side_2']) ) ? esc_attr( $options['side_2'] ) : '';
$side_2_value = ( isset( $options['side_2_value']) ) ? esc_attr( $options['side_2_value'] ) : '';

?>

<ul class="collapsible">
<li class="active">
<div class="collapsible-header"><?php _e( 'Desktop', 'click-to-chat-for-whatsapp' ); ?></div>
<div class="collapsible-body">


<!-- style -->
<p class="description ht_ctc_admin_desktop ht_ctc_subtitle"><?php _e( 'Select Style (Desktop)', 'click-to-chat-for-whatsapp' ); ?>:</p class="description">
<div class="row ht_ctc_admin_desktop">
    <div class="input-field col s12 m12">
        <select name="<?= $dbrow; ?>[style_desktop]" class="chat_select_style select_style_desktop">
            <option value="1" <?= $style_desktop == 1 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-1', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="2" <?= $style_desktop == 2 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-2', 'click-to-chat-for-whatsapp' ); ?></option>
            <!-- <optgroup label="Style 3"> -->
            <option value="3" <?= $style_desktop == 3 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-3', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="3_1" <?= $style_desktop == '3_1' ? 'SELECTED' : ''; ?> >&emsp;<?php _e( 'Style-3 Extend', 'click-to-chat-for-whatsapp' ); ?></option>
            <!-- </optgroup> -->
            <option value="4" <?= $style_desktop == 4 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-4', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="5" <?= $style_desktop == 5 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-5', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="6" <?= $style_desktop == 6 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-6', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="7" <?= $style_desktop == 7 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-7', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="7_1" <?= $style_desktop == '7_1' ? 'SELECTED' : ''; ?> >&emsp;<?php _e( 'Style-7 Extend', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="8" <?= $style_desktop == 8 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-8', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="99" <?= $style_desktop == 99 ? 'SELECTED' : ''; ?> ><?php _e( 'Style-99 (Add your own image / GIF)', 'click-to-chat-for-whatsapp' ); ?></option>
        </select>
        <p class="description"><a target="_blank" href="https://holithemes.com/plugins/click-to-chat/list-of-styles/?utm_source=ctc&utm_medium=admin&utm_campaign=chat"><?php _e( 'List of styles', 'click-to-chat-for-whatsapp' ); ?></a> &emsp; | &emsp; <span><?php _e( 'Customize the styles', 'click-to-chat-for-whatsapp' ); ?>  <a target="_blank" class="customize_styles_link" href="<?= admin_url( 'admin.php?page=click-to-chat-customize-styles' ); ?>">( Click to Chat -> Customize )</a></span> </p>
        <p class="description"><span class="check_select_styles" style="font-size: 0.7em;"><?php _e( 'If Styles for desktop, mobile not selected as expected', 'click-to-chat-for-whatsapp' ); ?> <a target="_blank" href="<?= admin_url( 'admin.php?page=click-to-chat-other-settings#styles_issue:~:text=Check,cache)' ); ?>"><?php _e( 'Check this', 'click-to-chat-for-whatsapp' ); ?></a>, - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/select-styles/?utm_source=ctc&utm_medium=admin&utm_campaign=chat"><?php _e( 'more info', 'click-to-chat-for-whatsapp' ); ?></a></span></p>
    </div>
</div>


<?php
// Action hook - After select style - Desktop
do_action('ht_ctc_ah_admin_desktop_after_select_sytle', $options, $dbrow );
?>

<!-- Dekstop position -->
<!-- side - 1 -->
<p class="description ht_ctc_admin_desktop ht_ctc_subtitle"><?php _e( 'Position to Place (Desktop)', 'click-to-chat-for-whatsapp' ); ?>:</p>
<div class="row ht_ctc_admin_desktop">
    <br>
    <div class="input-field col s6">
        <select name="<?= $dbrow; ?>[side_1]" class="select-2">
            <option value="bottom" <?= $side_1 == 'bottom' ? 'SELECTED' : ''; ?> ><?php _e( 'bottom', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="top" <?= $side_1 == 'top' ? 'SELECTED' : ''; ?> ><?php _e( 'top', 'click-to-chat-for-whatsapp' ); ?></option>
        </select>
        <label>top / bottom </label>
    </div>
    <div class="input-field col s6">
        <input name="<?= $dbrow; ?>[side_1_value]" value="<?= $side_1_value ?>" id="side_1_value" type="text" class="input-margin">
        <label for="side_1_value"><?php _e( 'E.g. 10px', 'click-to-chat-for-whatsapp' ); ?></label>
    </div>
</div>

<!-- side - 2 -->
<div class="row ht_ctc_admin_desktop" style="margin-bottom:0;">
    <div class="input-field col s6">
        <select name="<?= $dbrow; ?>[side_2]" class="select-2">
            <option value="right" <?= $side_2 == 'right' ? 'SELECTED' : ''; ?> ><?php _e( 'right', 'click-to-chat-for-whatsapp' ); ?></option>
            <option value="left" <?= $side_2 == 'left' ? 'SELECTED' : ''; ?> ><?php _e( 'left', 'click-to-chat-for-whatsapp' ); ?></option>
        </select>
        <label><?php _e( 'right / left', 'click-to-chat-for-whatsapp' ); ?></label>
    </div>

    <div class="input-field col s6">
        <input name="<?= $dbrow; ?>[side_2_value]" value="<?= $side_2_value ?>" id="side_2_value" type="text" class="input-margin">
        <label for="side_2_value"><?php _e( 'E.g. 50%', 'click-to-chat-for-whatsapp' ); ?></label>
    </div>
</div>
<p class="description ht_ctc_admin_desktop"><?php _e( 'Add css units as suffix - e.g. 10px, 50%', 'click-to-chat-for-whatsapp' ); ?> - <a target="_blank" href="https://holithemes.com/plugins/click-to-chat/position-to-place/?utm_source=ctc&utm_medium=admin&utm_campaign=chat">more info</a> </p>

<br class="ht_ctc_admin_desktop">
<hr class="ht_ctc_admin_desktop" style="max-width: 500px;">
<br class="ht_ctc_admin_desktop">


<?php
// Hide on Desktop Devices
if ( isset( $options['hideon_desktop'] ) ) {
    ?>
    <p>
        <label>
            <input name="<?= $dbrow; ?>[hideon_desktop]" type="checkbox" value="1" <?php checked( $options['hideon_desktop'], 1 ); ?> class="hidebasedondevice" id="hideon_desktop" />
            <span><?php _e( 'Hide on - Desktop Devices', 'click-to-chat-for-whatsapp' ); ?></span>
        </label>
    </p>
    <?php
} else {
    ?>
    <p>
        <label>
            <input name="<?= $dbrow; ?>[hideon_desktop]" type="checkbox" value="1" class="hidebasedondevice" id="hideon_desktop" />
            <span><?php _e( 'Hide on - Desktop Devices', 'click-to-chat-for-whatsapp' ); ?></span>
        </label>
    </p>
    <?php
}
?>

</div>
</div>
</li>
<ul>