<?php 
if (! defined ('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_lion');
function epeken_invoke_lion($shipping) {
	$en_lion_onepack = get_option('epeken_enabled_lion_onepack');
	$en_lion_regpack = get_option('epeken_enabled_lion_regpack');
	if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
		$en_lion_regpack_v = get_user_meta($shipping->vendor_id, 'vendor_lion_regpack', true);
		if($en_lion_regpack_v !== 'on' || $en_lion_regpack !== 'on')
			$en_lion_regpack = '';
		$en_lion_onepack_v = get_user_meta($shipping->vendor_id, 'vendor_lion_onepack', true);
		if($en_lion_onepack_v !== 'on' || $en_lion_onepack !== 'on')
			$en_lion_onepack = '';
	}
	if($en_lion_regpack === "on" || $en_lion_onepack === "on") {
		$content_lion_tarif = epeken_get_tarif_lion($shipping->shipping_city, $shipping->shipping_kecamatan,
				$shipping -> bulatkan_berat($shipping -> shipping_total_weight), $shipping->origin_city);

		$content_lion_decoded = json_decode($content_lion_tarif);
		if (!empty($content_lion_decoded)) {
		$content_lion_decoded = $content_lion_decoded -> {'tariflion'};
		if(!empty($content_lion_decoded)) {
		foreach($content_lion_decoded as $element) {
		 				 				 
		 $package_name = $element -> {'class'};
		 if($package_name === 'onepack' && $en_lion_onepack !== 'on')
			 continue;
		 if($package_name === 'regpack' && $en_lion_regpack !== 'on')
			 continue;
		 
		 
		 $cost_value = $element -> {'cost'};
		 if ($cost_value > 0) {
		  $markup = $shipping -> additional_mark_up('lion',$shipping -> shipping_total_weight);
		  $cost_value = $cost_value + $markup;
		  array_push($shipping -> array_of_tarif, array('id' => 'lion_'.$package_name,'label' => 'lion parcel '.$package_name, 'cost' => $cost_value));
		 }
		}}
	 }
	}
	
}
?>
