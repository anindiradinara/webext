<?php
if (!defined('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_wahana');
function epeken_invoke_wahana($shipping) {
$en_wahana = get_option('epeken_enabled_wahana');
			if(epeken_is_multi_vendor_mode()  && epeken_is_vendor_id($shipping -> vendor_id)) {
				$en_wahana_v = get_user_meta($shipping->vendor_id, 'vendor_wahana', true);
				if($en_wahana_v !== 'on' || $en_wahana !== 'on')
					$en_wahana = '';
			}
			if ($en_wahana === "on") {
			 $content_wahana = epeken_get_wahana_ongkir($shipping->shipping_city,$shipping-> shipping_kecamatan,$shipping->bulatkan_berat($shipping->shipping_total_weight), $shipping->origin_city);	

			 $content_wahana_decoded = json_decode($content_wahana);
			 if (!empty($content_wahana_decoded)) {
			 $content_wahana_decoded = $content_wahana_decoded -> {'tarifwahana'};
				if(!empty($content_wahana_decoded)) {
				foreach($content_wahana_decoded as $element) {
				 $package_name = $element -> {'class'};
				 $cost_value = $element -> {'cost'};
				 if ($cost_value !== "0")
				 array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
				}}
			 }
			}



}
?>
