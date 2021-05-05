<?php
  if ( ! defined( 'ABSPATH' ) ) exit;
  
function epeken_get_usd_rate($source){
        $license = get_option('epeken_wcjne_license_key');
        $source = 'BI';
        $url = EPEKEN_GET_USDRATE_API.$license.'/'.$source.'/'.'epeken-all-kurir';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
}

  
function epeken_get_list_of_kota_kabupaten ()
	{
		$kotakabreturn = array();
		$file_kota_kab = EPEKEN_KOTA_KAB;
		$file_kota_kab = apply_filters('epeken_kotakab', $file_kota_kab);
		$string = file_get_contents($file_kota_kab);
		$json = json_decode($string,true);
		$array_kota = $json['listkotakabupaten'];
		$kotakabreturn [''] = 'Kota/Kabupaten (City)';
		foreach($array_kota as $element){
			$kotakabreturn[$element['kotakab']] = $element['kotakab'];	
		}
		return $kotakabreturn;
	}

  function epeken_get_all_provinces() {
		$license_key = get_option('epeken_wcjne_license_key');
		$url = EPEKEN_API_GET_PRV.$license_key;
		$ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);
		return $content;
   }

  function epeken_get_track_info($kurir,$awb) {
        $license = get_option('epeken_wcjne_license_key');    
        $ch = curl_init();
        $endpoint = EPEKEN_TRACKING_END_POINT.$license.'/'.$kurir.'/'.$awb;
        curl_setopt($ch, CURLOPT_URL, $endpoint);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $content = curl_exec($ch);
                  curl_close($ch);
                  return $content;
  }  

  function epeken_get_list_of_kota($province) {
	  $countries_obj = new WC_Countries();
	  $states = $countries_obj -> get_states('ID');
	  $province = sanitize_text_field(trim($province));
	  $province = $states[$province];
	  $kotareturn = array();
	  $string = file_get_contents(EPEKEN_PROVINCE);
	  $string = apply_filters('epeken_province', $string);
	  $json = json_decode($string, true);
	  $array_province = $json['prop'];
	
	  foreach($array_province as $element) {
		if($element['province'] === $province) {
			$kotareturn [$element['kota_kabupaten']] = $element['kota_kabupaten'];
		}
	  }
	return $kotareturn;
  } 
  
  function epeken_get_list_of_kecamatan ($kotakab)
	{
		$kotakab = sanitize_text_field(trim($kotakab));
		$kecamatanreturn = array();
		 if ($kotakab === 'init'){
                  $kecamatanreturn [''] = 'Please Select Kecamatan';
                  return $kecamatanreturn;
                }

		$string = file_get_contents(EPEKEN_KOTA_KEC);
		$string = apply_filters('epeken_kecamatan',$string);
		$json = json_decode($string, true);
		$array_kecamatan = $json['listkecamatan'];
		$kecamatanreturn[''] = 'Kecamatan (District)';
		foreach($array_kecamatan as $element){
			if ($element["kota_kabupaten"] === $kotakab) {
				$kecamatanreturn [$element["kecamatan"]] = $element["kecamatan"];
			}	
		}
		return $kecamatanreturn;
	}

	function writelog($logstr){
                        $logdir = plugin_dir_path( __FILE__ )."log/";
                        $sesid = session_id();
                        $logfile = fopen ($logdir."debug.log","a");
                        $now = date("Y-m-d H:i:s");
                        fwrite($logfile,$now.":".$logstr."\n");
	                        fclose($logfile);
                }

  function epeken_code_to_city($code) {
		$string = file_get_contents(EPEKEN_KOTA_KAB);
		$city = "";
		$json = json_decode($string,true);
                $array_kota = $json['listkotakabupaten'];
		foreach($array_kota as $element){
                        if($element['code'] === $code){
                                $city = $element["kotakab"];
                                break;
                        }
                }
		return $city;
  }

  function epeken_city_to_code($city) {
		$string = file_get_contents(EPEKEN_KOTA_KAB);
                $code = "";
                $json = json_decode($string,true);
                $array_kota = $json['listkotakabupaten'];
		$city = urldecode($city);
                foreach($array_kota as $element){
                        if($element['kotakab'] === $city){
                                $code = $element["code"];
                                break;
                        }
                }
                return $code;
  }

  function epeken_get_tarif($kotakab, $kecamatan, $product_origin = false) {		
		$kotakab = urldecode($kotakab);
		$kecamatan = urldecode($kecamatan);
		$license_key = get_option('epeken_wcjne_license_key');
		$options = get_option('woocommerce_epeken_courier_settings');
                $origin_code = isset($options['data_kota_asal']) ? $options['data_kota_asal'] : null;
		$destination_code = "";
		$string = file_get_contents(EPEKEN_KOTA_KAB);
                $json = json_decode($string,true);
		$array_kota = $json['listkotakabupaten'];
                foreach($array_kota as $element){
			if($element['kotakab'] === $kotakab){
				$destination_code = $element["code"];
				break;
			}
                }
		$content = "";	
		
		if ($product_origin != false)
			$origin_code = epeken_city_to_code($product_origin);
	
		if ($destination_code !=="") {	
			$kotakab = str_replace("/","{slash}",$kotakab);
                        $kecamatan = str_replace("/","{slash}",$kecamatan);
		  	$url = EPEKEN_API_DIR_URL.$license_key."/".$origin_code."/".$destination_code."/".urlencode($kotakab)."/".urlencode($kecamatan);
			$ch = curl_init();
	 		curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  		
			$content = curl_exec($ch);
			$error = curl_error($ch);
			if(!empty($error)){
				$logger = new WC_Logger();;
				$logger -> add ('epeken-all-kurir', 'Error Occured:'.$error);
			}
			//writelog($url."\n".$content);
  	 		curl_close($ch);
			if(strpos($content,'404 Page Not Found') !== FALSE) {
			 $content = '';
			}
		}
		return $content;
	}

 function epeken_get_valid_origin($license) {
		$content = "";
		$url = EPEKEN_VALID_ORIGIN.$license;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); //set timed out for 30 seconds
                $content = curl_exec($ch);
                curl_close($ch);
		return $content;
  }

  function epeken_get_tarif_pt_pos_v3($kotakab,$kecamatan,$weight, $price, $length, $width, $height, $product_origin=false ){
		//weight is in gram	
		$license_key = get_option('epeken_wcjne_license_key');
                $kecamatan = str_replace("/","{slash}",$kecamatan);
		$kecamatan = urlencode($kecamatan);
                $options = get_option('woocommerce_epeken_courier_settings');
                $origin_code = $options['data_kota_asal'];
                $destination_code = "";
                $string = file_get_contents(EPEKEN_KOTA_KAB);
                $json = json_decode($string,true);
                $array_kota = $json['listkotakabupaten'];
                foreach($array_kota as $element){
                        if($element['kotakab'] === urldecode($kotakab)){
                                $destination_code = $element["code"];
                                break;
                        }
                }
                $content = "";
		$url = "";
                if ($product_origin != false)
                        $origin_code = epeken_city_to_code($product_origin);
		if ($destination_code !=="") {
                        $url = EPEKEN_API_POS_URL_V3.$license_key."/".$origin_code."/".$destination_code."/".$kecamatan."/".$weight."/".$price."/".$length."/".$width."/".$height;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $content = curl_exec($ch);
                        curl_close($ch);
                }
                return $content;
  }
  
  function epeken_get_tarif_lion ($kotakab, $kecamatan, $weight, $product_origin=false) {
	  $kotakab = urldecode($kotakab);
		if (empty($weight)) 
			$weight = 1;

		if ($weight < 1)
			$weight = 1;
		
		$license_key = get_option('epeken_wcjne_license_key');
		$options = get_option('woocommerce_epeken_courier_settings');
		$origin_code = $options['data_kota_asal'];	
		$origin_city = epeken_code_to_city($origin_code);
		if ($product_origin != false)
			$origin_city = $product_origin;
		
		if(empty($weight) || $weight < 1)
			$weight = 1;

		$kotakab = str_replace("/","{slash}",$kotakab);
                $kecamatan = str_replace("/","{slash}",$kecamatan);
		$origin_city = urlencode($origin_city);
		$kotakab = urlencode ($kotakab);
		$weight = urlencode($weight);
		$kecamatan = urlencode($kecamatan);

		$url = EPEKEN_API_LION.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;

  }

  function epeken_get_wahana_ongkir($kotakab, $kecamatan, $weight, $product_origin=false) {
					      //weight in kg
		$kotakab = urldecode($kotakab);
		if (empty($weight)) 
			$weight = 1;

		if ($weight < 1)
			$weight = 1;

		$license_key = get_option('epeken_wcjne_license_key');
		$options = get_option('woocommerce_epeken_courier_settings');
		$origin_code = $options['data_kota_asal'];	
		$origin_city = epeken_code_to_city($origin_code);
		if ($product_origin != false)
			$origin_city = $product_origin;
		
		if(empty($weight) || $weight < 1)
			$weight = 1;

		$kotakab = str_replace("/","{slash}",$kotakab);
                $kecamatan = str_replace("/","{slash}",$kecamatan);

		$origin_city = urlencode($origin_city);
		$kotakab = urlencode ($kotakab);
		$weight = urlencode($weight);
		$kecamatan = urlencode($kecamatan);

		$url = EPEKEN_API_WAHANA.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

   function epeken_get_custom_tarif($kotakab, $weight, $product_origin = false, $kecamatan = false) {
		//weight in kg
		if (empty($weight)) 
                        $weight = 1;

                if ($weight < 1)
                        $weight = 1;

		$license_key = get_option('epeken_wcjne_license_key');
		$origin_code = $options['data_kota_asal'];         

                if ($product_origin != false) {
                        $origin_code = epeken_city_to_code($product_origin);
		}

                $kotakab_code = epeken_city_to_code($kotakab);
                $weight = urlencode($weight);

		$url = EPEKEN_API_CUSTOM_TARIF.$license_key."/".$origin_code."/".$kotakab_code."/".$weight;
		
		 if(!empty($kecamatan)){
			$kecamatan = urlencode($kecamatan);
			$url .= "/".$kecamatan;
		 }
		$ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);
                return $content;	
   }

   function epeken_get_jne_trucking_tarif($kotakab, $kecamatan, $weight, $product_origin = false) {
                //weight in kg
                if (empty($weight)) 
                        $weight = 1;
                if ($weight < 1)
                        $weight = 1;
                $license_key = get_option('epeken_wcjne_license_key');
                $origin_code = $options['data_kota_asal'];    
                if ($product_origin != false) {
                        $origin_code = epeken_city_to_code($product_origin);
                }   
                $kotakab_code = epeken_city_to_code($kotakab);
                $weight = urlencode($weight);
		$kecamatan = urlencode($kecamatan);
                $url = EPEKEN_API_JNE_TRUCKING.$license_key."/".$origin_code."/".$kotakab_code."/".$kecamatan.'/'.$weight;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);
                return $content;    

   }   

   function epeken_get_dakota_tarif($kotakab, $kecamatan, $weight, $product_origin = false) {
                //weight in kg
                if (empty($weight))
                        $weight = 1;
                if ($weight < 1)
                        $weight = 1;
                $license_key = get_option('epeken_wcjne_license_key');
                $origin_code = $options['data_kota_asal'];
                if ($product_origin != false) {
                        $origin_code = epeken_city_to_code($product_origin);
                }
                $kotakab_code = epeken_city_to_code($kotakab);
                $weight = urlencode($weight);
                $kecamatan = urlencode($kecamatan);
                $url = EPEKEN_API_DAKOTA.$license_key."/".$origin_code."/".$kotakab_code."/".$kecamatan.'/'.$weight;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);
                return $content;
   }

   function epeken_get_jet_ongkir($kotakab, $kecamatan, $weight, $product_origin=false) {
                                              //weight in kg
                if (empty($weight)) 
                        $weight = 1;

                if ($weight < 1)
                        $weight = 1;

                $license_key = get_option('epeken_wcjne_license_key');
                $options = get_option('woocommerce_epeken_courier_settings');
                $origin_code = $options['data_kota_asal'];    
                $origin_city = epeken_code_to_city($origin_code);
		
                if ($product_origin != false)
                        $origin_city = $product_origin;
    
                if(empty($weight) || $weight < 1)
                        $weight = 1;

		$kotakab = str_replace("/","{slash}",$kotakab);
                $kecamatan = str_replace("/","{slash}",$kecamatan);
                $origin_city = urlencode($origin_city);
                $kotakab = urlencode ($kotakab);
                $weight = urlencode($weight);
		$kecamatan = urlencode($kecamatan);

                $url = EPEKEN_API_JET.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);
                return $content;
        } 

   function epeken_get_sicepat_ongkir($kotakab, $kecamatan, $weight, $product_origin=false) {
                                              //weight in kg
                if (empty($weight)) 
                        $weight = 1;

                if ($weight < 1)
                        $weight = 1;

                $license_key = get_option('epeken_wcjne_license_key');
                $options = get_option('woocommerce_epeken_courier_settings');
                $origin_code = $options['data_kota_asal'];    
                $origin_city = epeken_code_to_city($origin_code);
    
                if ($product_origin != false)
                        $origin_city = $product_origin;
    
                if(empty($weight) || $weight < 1)
                        $weight = 1;

		$kotakab = str_replace("/","{slash}",$kotakab);
                $kecamatan = str_replace("/","{slash}",$kecamatan);
                $origin_city = urlencode($origin_city);
                $kotakab = urlencode ($kotakab);
                $weight = urlencode($weight);
                $kecamatan = urlencode($kecamatan);

                $url = EPEKEN_API_SICEPAT.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);
                return $content;
        }   

  function epeken_get_ninja_express_tariff($kotakab,$kecamatan,$weight, $product_origin = false) {
	//EPEKEN_SERVER_URL.'/api/index.php/epeken_get_ninja_express_tarif/';
	$kotakab = urldecode($kotakab);
	$kecamatan = urldecode($kecamatan);
	if (empty($weight))
                        $weight = 1;
        if ($weight < 1)
                        $weight = 1;
	$license_key = get_option('epeken_wcjne_license_key');
        $options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = $options['data_kota_asal'];
        $origin_city = epeken_code_to_city($origin_code);
	if ($product_origin != false)
                        $origin_city = $product_origin;

	$kotakab = str_replace("/","{slash}",$kotakab);
        $kecamatan = str_replace("/","{slash}",$kecamatan);
	$origin_city = urlencode($origin_city);
        $kotakab = urlencode ($kotakab);
        $weight = urlencode($weight);
        $kecamatan = urlencode($kecamatan);	
	
	$url = EPEKEN_API_NINJA.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
  }	
  
  function epeken_get_sap_express_tariff($kotakab, $kecamatan, $weight, $product_origin = false) {
	 $kotakab = urldecode($kotakab);
	 $kecamatan = urldecode($kecamatan);
	  if (empty($weight)) 
                        $weight = 1;
	  if ($weight < 1)
                        $weight = 1;
					
	  $license_key = get_option('epeken_wcjne_license_key');
	  $options = get_option('woocommerce_epeken_courier_settings');
      	  $origin_code = $options['data_kota_asal'];    
      	  $origin_city = epeken_code_to_city($origin_code);
		
	  if ($product_origin != false)
                        $origin_city = $product_origin;
	  
	  $origin_city = urlencode($origin_city);
      	  $kotakab = urlencode ($kotakab);
      	  $weight = urlencode($weight);
      	  $kecamatan = urlencode($kecamatan);
	  
	  $url = EPEKEN_API_SAP_EXPRESS.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
      	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      	  $content = curl_exec($ch);
      	  curl_close($ch);
      	  return $content;
  }
  
  function epeken_get_jmx_tariff($kotakab, $kecamatan, $weight, $product_origin = false) {
		if(empty($weight) || $weight == 0)
			$weight = 1;
		
		$license_key = get_option('epeken_wcjne_license_key');
		$options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = $options['data_kota_asal'];
		$origin_city = epeken_code_to_city($origin_code);
		$content = "";
		if ($product_origin != false)
			$origin_city = $product_origin;
	
		$kotakab = str_replace("/","{slash}",$kotakab);
                        $kecamatan = str_replace("/","{slash}",$kecamatan);
		$url = EPEKEN_API_JMX.$license_key."/".urlencode($origin_city)."/".urlencode($kotakab)."/".urlencode($kecamatan)."/".$weight;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
  }
  
  function epeken_get_nss_tariff($kotakab, $kecamatan, $weight, $product_origin = false) {
	  if(empty($weight) || $weight == 0)
		   $weight = 1;
	   
		$license_key = get_option('epeken_wcjne_license_key');
		$options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = $options['data_kota_asal'];
		$origin_city = epeken_code_to_city($origin_code);
		$content = "";	
		if ($product_origin != false)
			$origin_city = $product_origin;
	
		$kotakab = str_replace("/","{slash}",$kotakab);
		$url = EPEKEN_API_NSS.$license_key."/".urlencode($origin_city)."/".urlencode($kotakab)."/1";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		$content = trim($content);
		$content = str_replace("<br/>","",$content);
		curl_close($ch);
	
		$content = rtrim($content,',}');
		$content = '{"result":'.$content.'}}';
		$array = json_decode($content,true);
		$is_error = $array["result"]["error"];
		if($is_error === "false") {
			$array = $array["result"]["tarif"];
			$json_elem_tarif = "";
			$i=0;
			foreach($array as $array_tariff) {
				$the_cost = $array_tariff['tarif'];
				$tarif_add = $array_tariff['tarif_add'];
				if($weight > 1 && $tarif_add > 0){
					$tarif_add = ($weight-1) * $tarif_add;
					$the_cost = $array_tariff['tarif'] + $tarif_add;
				}
				if($weight > 1 && $tarif_add === '0') {
					$the_cost = $array_tariff['tarif'] * $weight;
				}
				if($i > 0) {
				  $json_elem_tarif = $json_elem_tarif . ",";
				}
				$json_elem_tarif = $json_elem_tarif . '{"service":"'.$array_tariff['layanan'].'", "description":"'.$array_tariff['layanan'].'","cost":[{"value":'.$the_cost.',"etd":"'.$array_tariff['etd'].'","note":""}]}';
				$i++;
			}
			$content =  '{"status":{"code":200,"description":"OK"}, "origin_details":"","destination_details":"","results":[{"code":"nss","name":"Kurir NSS","costs":['.$json_elem_tarif.']}]}';
		}
		return $content;
  }
  
   function epeken_get_currency_rate($currency_name) {
		$license_key = get_option('epeken_wcjne_license_key');
		$url = EPEKEN_API_GET_CURRENCY_RATE.$license_key."/".$currency_name;
		$ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $content = curl_exec($ch);
                curl_close($ch);
                return $content;		
	}
  function epeken_get_tarif_intl($negara_destination, $weight, $length,$width,$height, $price, $product_origin = false) {
                $negara_destination = urldecode($negara_destination);
                if (empty($negara_destination)) {
                        $isshippedifadr = $_SESSION['isshippedifadr']; 
                        if ($isshippedifadr === '1')    {   
                                $negara_destination = $_GET['e_shipping_country'];
                        }else {
                                $negara_destination = $_GET['e_billing_country'];
                        }   
                }   
                $license_key = get_option('epeken_wcjne_license_key');
                $options = get_option('woocommerce_epeken_courier_settings');
                $origin_code = $options['data_kota_asal'];
                $content = ""; 
    
                if ($product_origin != false)
                        $origin_code = epeken_city_to_code($product_origin);

                $ch = curl_init();    
		
		//rollback pt pos version 2 since august 2019
                //$url = EPEKEN_API_DIR_URL_INTL.$license_key."/".$origin_code."/".$negara_destination."/".$weight."/".$length."/".$width."/".$height."/".$price;	
		
                $url = EPEKEN_API_DIR_URL_INTL.$license_key."/intl/".$origin_code."/".$negara_destination."/".$weight;
		curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                $content = curl_exec($ch);
                curl_close($ch);
                return $content;
  }
  
?>
