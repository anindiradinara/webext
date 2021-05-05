<?php
/**
 * sidebar content
 */


if ( ! defined( 'ABSPATH' ) ) exit;

$othersettings = get_option('ht_ctc_othersettings');

?>

<div class="sidebar-content">

    <div class="col s12 m8 l12 xl12">
        <div class="row">
            <ul class="collapsible popout">
                <li class="active">
                    <ul class="collapsible popout">	
                        <li class="active">	
                        <div class="collapsible-header"><?php _e( 'Contact Us', 'click-to-chat-for-whatsapp' ); ?></div>	
                        <div class="collapsible-body">	
                            <p class="description"><?php _e( 'Please let us know if you have any suggestions or feedback!!', 'click-to-chat-for-whatsapp' ); ?> <br><br> <a href="http://api.whatsapp.com/send?phone=919494429789&text=<?= get_bloginfo('url'); ?>%0AHi%20HoliThemes,%0AI%20have%20a%20Suggestion/Feedback:" target="_blank"><?php _e( 'WhatsApp', 'click-to-chat-for-whatsapp' ); ?></a></p>	
                            <p class="description"><?php _e( 'Mail', 'click-to-chat-for-whatsapp' ); ?>:<a href="mailto: ctc@holithemes.com"> ctc@holithemes.com</a></p>	
                            <p class="description">GitHub <a target="_blank" href="https://github.com/holithemes/ctc/discussions"> Discussions</a></p>	
                            <?php
                            do_action('ht_ctc_ah_admin_sidebar_contact' );
                            ?>
                        </div>	
                        </li>	
                    </ul>
                </li>
            </ul>
        </div>
    </div>

</div>