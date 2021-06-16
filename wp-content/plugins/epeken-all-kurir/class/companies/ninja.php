<?php
/* This code is included from set_shipping_cost method of WC_Tikijne_Shipping class */
if(! defined ('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_ninja');
function epeken_invoke_ninja($shipping) {
  $en_ninja_next_day = get_option('epeken_enabled_ninja_next_day'); $en_ninja_standard = get_option('epeken_enabled_ninja_standard');
			if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)){
				$en_ninja_next_day_v = get_user_meta($shipping -> vendor_id, 'vendor_ninja_next_day', true);
				if ($en_ninja_next_day_v !== 'on' || $en_ninja_next_day !== 'on')
					$en_ninja_next_day = '';

				$en_ninja_standard_v = get_user_meta($shipping -> vendor_id, 'vendor_ninja_standard', true);
				if ($en_ninja_standard_v !== 'on' || $en_ninja_standard !== 'on')
				       $en_ninja_standard = '';	
			}
			if($en_ninja_next_day === 'on' || $en_ninja_standard === 'on') {
				$weight = $shipping -> bulatkan_berat($shipping -> shipping_total_weight);
				$cache_input_key = $shipping->shipping_city.'-'.$shipping->shipping_kecamatan.'-'.$shipping->origin_city.'-'.$weight.'_ninja';
                         	$cache_input_key = preg_replace( '/[^\da-z]/i', '_', $cache_input_key );
                         	$content_ninja = '';
                         	if(!empty($_SESSION[$cache_input_key])) {
                                	$content_ninja = $_SESSION[$cache_input_key];
                         	}else{
                                 	$content_ninja = epeken_get_ninja_express_tariff($shipping -> shipping_city, $shipping -> shipping_kecamatan, 
                                                $shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping -> origin_city);
					$_SESSION[$cache_input_key] = $content_ninja;
                         	}	
				$content_ninja_decode = json_decode($content_ninja, true);
				if(!empty($content_ninja_decode)) {
					foreach($content_ninja_decode['tarifninja'] as $rate){
						$class = $rate['class']; $cost = $rate['cost'];
						if($class === 'NEXT_DAY') {$class = 'NEXT DAY';}
						if(($en_ninja_next_day === 'on' && $class === 'NEXT DAY') || ($en_ninja_standard === 'on' && $class === 'STANDARD')) {
						 array_push($shipping -> array_of_tarif, array('id' => 'ninja_'.strtolower($class),
								'label' => 'NINJA '.$class,'cost' => $cost));
						}
					}
				}
			}
			


}
?>
