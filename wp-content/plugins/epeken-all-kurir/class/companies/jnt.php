<?php 
if (!defined ('ABSPATH')) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_jnt');
function epeken_invoke_jnt($shipping) {
$en_jetez = get_option('epeken_enabled_jetez');
			 if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($shipping -> vendor_id)) {
				 $en_jetez_v = get_user_meta($shipping->vendor_id, 'vendor_jnt_ez', true);
				 if($en_jetez_v !== 'on' || $en_jetez !== 'on')
					 $en_jetez = '';
			 }
                         if($en_jetez === "on") {
                                $content_jet = epeken_get_jet_ongkir($shipping -> shipping_city, $shipping -> shipping_kecamatan, $shipping->bulatkan_berat($shipping->shipping_total_weight), $shipping -> origin_city);
                                $content_jet_decoded = json_decode($content_jet);
                                if(!empty($content_jet_decoded)) {
                                        $content_jet_decoded = $content_jet_decoded -> {'tarifjnt'};
					if(!empty($content_jet_decoded)) {
					$is_eta = get_option('epeken_setting_eta');
                                       foreach($content_jet_decoded as $element) {
					       $package_name = $element -> {'class'}; 
					       $cost_value = $element -> {'cost'};
					       $etd = $element -> {'etd'};	
					       $markup = $shipping -> additional_mark_up('jnt',$shipping -> shipping_total_weight);
					       $cost_value = $cost_value + $markup;
					       $label = 'J&T '.$package_name;
					       if($is_eta === 'on' && !empty($etd))
						       $label .= '('.$etd.' hari)';
					       if ($cost_value !== "0") 
						       array_push($shipping -> array_of_tarif, 
						       array('id' => 'jet.co.id_'.$package_name,
						             'label' => $label, 
							     'cost' => $cost_value));
                                        }    }
                                }    
     
                         }
}
?>
