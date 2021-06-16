<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_sicepat');
function epeken_invoke_sicepat($shipping) {
	$en_sicepat_reg = get_option('epeken_enabled_sicepat_reg');
	$en_sicepat_best = get_option('epeken_enabled_sicepat_best');

	if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
		$en_sicepat_reg_v = get_user_meta($shipping -> vendor_id, 'vendor_sicepat_reg', true);
		if($en_sicepat_reg_v !== 'on' || $en_sicepat_reg !== 'on') {
			$en_sicepat_reg = '';
		}
		$en_sicepat_best_v = get_user_meta($shipping -> vendor_id, 'vendor_sicepat_best', true);
		if($en_sicepat_best_v !== 'on' || $en_sicepat_best !== 'on') {
			$en_sicepat_best = '';
		}

	}
	if($en_sicepat_reg === "on" || $en_sicepat_best === "on") {
		$content_sicepat = epeken_get_sicepat_ongkir($shipping -> shipping_city, 
			$shipping -> shipping_kecamatan, 
			$shipping->bulatkan_berat($shipping->shipping_total_weight), 
			$shipping -> origin_city
		);
		$content_sicepat_decoded = json_decode($content_sicepat);
		$content_sicepat_decoded = $content_sicepat_decoded -> {'tarifsicepat'};
		if(!empty($content_sicepat_decoded)) {
		foreach($content_sicepat_decoded as $element) {
		    $package_name = $element -> {'class'}; 
		    if($package_name === "REGULAR" && $en_sicepat_reg !== "on") continue; 
		    if($package_name === "BEST" && $en_sicepat_best !== "on") continue; 
		    $cost_value = $element -> {'cost'}; 
		    $etd = $element -> {'etd'};
		    $label = 'SICEPAT '.$package_name;
		    $is_eta = get_option('epeken_setting_eta');
		    if($is_eta === 'on')
			    $label .= ' ('.$etd.')';
		    if ($cost_value !== "0") 
		    array_push($shipping -> array_of_tarif, 
   			array('id' => 'sicepat_'.$package_name,
						          'label' => $label, 
							  'cost' => $cost_value));
		   }
		  }
		 }


}
?>
