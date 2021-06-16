<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('epeken_custom_tariff', 'epeken_invoke_pos');
function epeken_invoke_pos($shipping) {
		$en_pos_bi = get_option('epeken_enabled_pos_biasa');
			$en_pos_kk = get_option('epeken_enabled_pos_kilat_khusus');
			$en_pos_end = get_option('epeken_enabled_pos_express_nextday');
			$en_pos_vg = get_option('epeken_enabled_pos_val_good');
			$en_pos_kprt = get_option('epeken_enabled_pos_kprt');
			$en_pos_kpru = get_option('epeken_enabled_pos_kpru');

			if(epeken_is_multi_vendor_mode()  && epeken_is_vendor_id($shipping -> vendor_id))
			{
				$en_pos_bi_v = get_user_meta($shipping->vendor_id, 'vendor_pos_biasa', true);
				if($en_pos_bi_v !== 'on' || $en_pos_bi !== 'on')
					$en_pos_bi = '';

				$en_pos_kk_v = get_user_meta($shipping->vendor_id, 'vendor_pos_kilat_khusus', true);
				if($en_pos_kk_v !== 'on' || $en_pos_kk !== 'on')
					$en_pos_kk = '';

				$en_pos_end_v = get_user_meta($shipping->vendor_id, 'vendor_pos_express_next_day', true);
				if($en_pos_end_v !== 'on' || $en_pos_end !== 'on')
					$en_pos_end = '';

				$en_pos_vg_v = get_user_meta($shipping->vendor_id, 'vendor_pos_valuable_goods', true);
				if($en_pos_vg_v !== 'on' || $en_pos_vg !== 'on')
					$en_pos_vg = '';

				$en_pos_kprt_v = get_user_meta($shipping->vendor_id, 'vendor_pos_kprt', true);
				if($en_pos_kprt_v !== 'on' || $en_pos_kprt !== 'on')
					$en_pos_kprt = '';
				
				$en_pos_kpru_v = get_user_meta($shipping->vendor_id, 'vendor_pos_kpru', true);
				if($en_pos_kpru_v !== 'on' || $en_pos_kpru !== 'on')
					$en_pos_kpru = '';

			}	

			if ($en_pos_bi === "on" || $en_pos_kk === "on" || $en_pos_end === "on" || $en_pos_vg === "on" || $en_pos_kprt === "on" || $en_pos_kpru === "on") {
			 $weight = 1000;
			 $length = 0;
			 $width = 0;
			 $height = 0;
			 $price = 0;

			 if ($opt_vol_matrix === "yes") {
			  $shipping -> count_cart_weight_and_dimension($package);
			  $weight = $shipping -> shipping_total_weight*1000;
			  $length = $shipping -> shipping_total_length;
			  $width = $shipping -> shipping_total_width;
			  $height = $shipping -> shipping_total_height;
			  $price = $shipping -> get_cart_total() - $shipping -> get_discount();
			 }

			 if($shipping -> current_currency !== "IDR") {
				$price = $price * ($shipping -> current_currency_rate);
			 }

			 $cache_input_key = $shipping->shipping_city.'-'.$shipping->shipping_kecamatan.'-'.$shipping->origin_city.'-'.$weight.'-'.$price.'-'.$length.'-'.$width.'-'.$height.'_pos';
			 $cache_input_key = preg_replace( '/[^\da-z]/i', '_', $cache_input_key );
			 $content_pos = '';
			 if(!empty($_SESSION[$cache_input_key])) {
				$content_pos = $_SESSION[$cache_input_key];
			 }else{
			 	$content_pos = epeken_get_tarif_pt_pos_v3(
			  	$shipping -> shipping_city,
				$shipping -> shipping_kecamatan, 
				$weight, $price, $length, $width, $height, 
				$shipping -> origin_city );
				$_SESSION[$cache_input_key] = $content_pos;
			 }
			 
			 if(!empty($content_pos)) {
			  $content_pos_json_decode = json_decode($content_pos);
			  $content_pos_json_decode = $content_pos_json_decode -> {'tarifpos'};
			  $is_eta = get_option('epeken_setting_eta');
			  if(!empty($content_pos_json_decode)) {
			   foreach($content_pos_json_decode as $element){
				   $package_name = $element -> {'class'};
				   $label = "PT POS - ". $package_name;
				$cost_value = $element -> {'cost'};
				   $etd = $element -> {'etd'};
				   if($is_eta === 'on' && !empty($etd)) {
					   $etd = str_replace(' HARI','',$etd);
					   $label .= ' ('.$etd.' hari)';
				   }
				$markup = $shipping->additional_mark_up('pos',$shipping -> shipping_total_weight);
                                $cost_value = $cost_value + $markup;
				if((trim($package_name) === "PAKET KILAT KHUSUS" && $en_pos_kk === "on") ||
					 (trim($package_name) === "EXPRESS NEXT DAY BARANG" && $en_pos_end === "on") ||
					 (trim($package_name) === "PAKETPOS VALUABLE GOODS" && $en_pos_vg === "on") ||
					 (trim($package_name) === "PAKETPOS BIASA" && $en_pos_bi === "on") || 
					 (trim($package_name) === "KARGOPOS RITEL TRAIN" && $en_pos_kprt === "on") || 
					 (trim($package_name) === "KARGOPOS RITEL UDARA DN" && $en_pos_kpru === "on")
				  )
				  array_push($shipping -> array_of_tarif, array(
					  'id' => $package_name,
					  'label' => $label, 
					  'cost' => $cost_value));
			   }
			  } 
			 }
			}


}
?>
