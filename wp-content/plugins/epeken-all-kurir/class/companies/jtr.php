<?php 
if (! defined('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_jtr');
function epeken_invoke_jtr($shipping) {
		$en_jtr_tarif = get_option('epeken_enabled_jne_trucking_tarif');
		if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)){
				$en_jtr_tarif_v = get_user_meta($shipping -> vendor_id, 'vendor_jtr', true);
				if ($en_jtr_tarif_v !== 'on' || $en_jtr_tarif !== 'on')
					$en_jtr_tarif = '';
			}
			if($en_jtr_tarif === 'on') {
				$content_jtr_tarif = epeken_get_jne_trucking_tarif($shipping->shipping_city, 
			         $shipping -> shipping_kecamatan, 
				 $shipping -> bulatkan_berat($shipping -> shipping_total_weight), 
			  	 $shipping->origin_city );	
				$content_jtr_tarif_decoded = json_decode($content_jtr_tarif);
				if(!empty($content_jtr_tarif_decoded)){
					$content_jtr_tarif_decoded = $content_jtr_tarif_decoded -> {'tarifcustom'};		
			 	 for($i=0; $i <= sizeof($content_jtr_tarif_decoded); $i++) {
                                        $package_name = $content_jtr_tarif_decoded[$i]->{'class'};
                                        $cost_value = $content_jtr_tarif_decoded[$i]->{'cost'};
                                        if ($cost_value !== "0") 
                                        array_push($shipping -> array_of_tarif, array('id' => $package_name,'label' => $package_name, 'cost' => $cost_value));
                                 }    
				}		
			}



}
?>
