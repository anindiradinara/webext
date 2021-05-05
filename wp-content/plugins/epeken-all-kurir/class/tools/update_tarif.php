<?php

function set_weight_1() {
        $full_product_list = array();
        $loop = new WP_Query( array( 'post_type' => array('product', 'product_variation'), 'posts_per_page' => -1 ) );
	$weight_unit = get_option('woocommerce_weight_unit');
        while ( $loop->have_posts() ) : $loop->the_post();
                $theid = get_the_ID();
                $product = new WC_Product($theid);
                $weight = get_post_meta($theid,'_weight',true);
                if(empty($weight) || $weight === '0' || $weight === '1') {
		    if ($weight_unit === 'g')
			update_post_meta($theid, '_weight', '1000' );
		    else
                        update_post_meta($theid, '_weight', '1' );
                }   
        endwhile; wp_reset_query();
} 
if(!empty ($_POST['save']) && $_POST['save'] === 'Set Berat Barang 1 Kg') {
        set_weight_1(); 
}


function reset_dropship_origin_for_all_products () {
	$full_product_list = array();
        $loop = new WP_Query( array( 'post_type' => array('product', 'product_variation'), 'posts_per_page' => -1 ) );
        while ( $loop->have_posts() ) : $loop->the_post();
                $theid = get_the_ID();
                delete_post_meta($theid,'product_origin');
        endwhile; wp_reset_query();
}

if(!empty ($_POST['save']) && $_POST['save'] === 'Reset Dropship') {
        reset_dropship_origin_for_all_products(); 
}
 
 update_option('epeken_data_server', $_POST['data_server']);
 update_option('epeken_enable_cod',$_POST['woocommerce_epeken_enable_cod']);
 update_option('epeken_cod_label', $_POST['woocommerce_epeken_cod_label']);
 update_option('epeken_subsidi_min_purchase', $_POST['txt_subsidi_min_purchase']);
 update_option('epeken_free_pc', $_POST['woocommerce_epeken_free_pc']);
 update_option('epeken_free_pc_q', $_POST['woocommerce_epeken_free_pc_q']);
 update_option('epeken_is_provinsi_free' , $_POST['epeken_is_provinsi_free']);
 update_option('epeken_province_for_free_shipping',$_POST['woocommerce_wc_shipping_tikijne_province_for_free_shipping']);
 update_option('epeken_mode_kode_pembayaran', $_POST['mode_kode_pembayaran']);
 update_option('epeken_freeship_n_province_for_free_shipping' , $_POST['freeship_n_province_for_free_shipping']);
 update_option('epeken_multiple_rate_setting', $_POST['epeken_multiple_rate_setting']);
 if(empty($_POST['epeken_multiple_rate_setting']))
  update_option('epeken_multiple_rate_setting', 'manual');

 update_option('epeken_enabled_jne', $_POST['enabled_jne']);
 update_option('epeken_subsidi_ongkir', $_POST['txt_subsidi_ongkir']);
 update_option('epeken_enabled_ninja_next_day', $_POST['enabled_ninja_next_day']);
 update_option('epeken_enabled_ninja_standard', $_POST['enabled_ninja_standard']);
 update_option('epeken_enabled_tiki', $_POST['enabled_tiki']);
 update_option('epeken_enabled_sap_sds', $_POST['enabled_sap_sds']);
 update_option('epeken_enabled_sap_ods', $_POST['enabled_sap_ods']);
 update_option('epeken_enabled_sap_reg', $_POST['enabled_sap_reg']);
 update_option('epeken_enabled_nss_sds', $_POST['enabled_nss_sds']);
 update_option('epeken_enabled_nss_ods', $_POST['enabled_nss_ods']);
 update_option('epeken_enabled_nss_reg', $_POST['enabled_nss_reg']);
 update_option('epeken_enabled_pos_biasa', $_POST['enabled_pos_biasa']);
 update_option('epeken_enabled_pos_kilat_khusus', $_POST['enabled_pos_kilat_khusus']);
 update_option('epeken_enabled_pos_express_nextday', $_POST['enabled_pos_express_nextday']);
 update_option('epeken_enabled_pos_val_good', $_POST['enabled_pos_val_good']);
 update_option('epeken_enabled_pos_kprt', $_POST['enabled_pos_kprt']);
 update_option('epeken_enabled_pos_kpru', $_POST['enabled_pos_kpru']);
 update_option('epeken_enabled_rpx_sdp', $_POST['enabled_rpx_sdp']);   
 update_option('epeken_enabled_rpx_mdp', $_POST['enabled_rpx_mdp']);   
 update_option('epeken_enabled_rpx_ndp', $_POST['enabled_rpx_ndp']); 
 update_option('epeken_enabled_rpx_rgp', $_POST['enabled_rpx_rgp']);   
 update_option('epeken_enabled_rpx_insurance', $_POST['enabled_rpx_insurance']);   
 update_option('epeken_enabled_esl', $_POST['enabled_esl']);
 update_option('epeken_data_asal_kota', $_POST['data_asal_kota']);
 update_option('epeken_enabled_jne_reg',$_POST['enabled_jne_reg']);
 update_option('epeken_enabled_jne_oke',$_POST['enabled_jne_oke']);
 update_option('epeken_enabled_jne_yes',$_POST['enabled_jne_yes']);
 update_option('epeken_enabled_tiki_hds',$_POST['enabled_tiki_hds']);
 update_option('epeken_enabled_tiki_ons',$_POST['enabled_tiki_ons']);
 update_option('epeken_enabled_tiki_reg',$_POST['enabled_tiki_reg']);
 update_option('epeken_enabled_tiki_eco',$_POST['enabled_tiki_eco']); 
 update_option('epeken_enabled_wahana', $_POST['enabled_wahana']);
 update_option('epeken_enabled_custom_tarif', $_POST['enabled_custom_tarif']);
 update_option('epeken_enabled_jne_trucking_tarif', $_POST['enabled_jne_trucking_tarif']);
 update_option('epeken_enabled_dakota_tarif', $_POST['enabled_dakota_tarif']);
 update_option('epeken_enabled_jetez', $_POST['enabled_jetez']);
 update_option('epeken_enabled_sicepat_reg', $_POST['enabled_sicepat_reg']);
 update_option('epeken_enabled_sicepat_best', $_POST['enabled_sicepat_best']);
 update_option('epeken_enabled_pos_ems_priority_doc' , $_POST['enabled_pos_ems_priority_doc']);
 update_option('epeken_enabled_pos_ems_priority_mar' , $_POST['enabled_pos_ems_priority_mar']);
 update_option('epeken_enabled_pos_ems_doc' , $_POST ['enabled_pos_ems_doc']);
 update_option('epeken_enabled_pos_ems_mar' , $_POST['enabled_pos_ems_mar']);
 update_option('epeken_enabled_pos_ems_epacket_lx', $_POST['enabled_pos_ems_epacket_lx']);
 update_option('epeken_enabled_pos_rln' , $_POST['enabled_pos_rln']);
 update_option('epeken_perhitungan_biaya_tambahan',$_POST['epeken_perhitungan_biaya_tambahan']);
 update_option('epeken_markup_tarif_jne', $_POST['epeken_markup_tarif_jne']);
 update_option('epeken_markup_tarif_tiki', $_POST['epeken_markup_tarif_tiki']);
 update_option('epeken_markup_tarif_pos' , $_POST['epeken_markup_tarif_pos']);
 update_option('epeken_markup_tarif_jnt' , $_POST['epeken_markup_tarif_jnt']);
 update_option('epeken_markup_tarif_lion' , $_POST['epeken_markup_tarif_lion']);
 update_option('epeken_diskon_tarif_jne', $_POST['epeken_diskon_tarif_jne']);
 update_option('epeken_diskon_tarif_tiki', $_POST['epeken_diskon_tarif_tiki']);
 update_option('epeken_diskon_tarif_pos' , $_POST['epeken_diskon_tarif_pos']);
 update_option('epeken_diskon_tarif_jnt' , $_POST['epeken_diskon_tarif_jnt']);
 update_option('epeken_diskon_tarif_lion' , $_POST['epeken_diskon_tarif_lion']);
 update_option('epeken_freeship_n_city_for_free_shipping' , $_POST['freeship_n_city_for_free_shipping']);
 update_option('epeken_nama_tarif_flat', $_POST['epeken_nama_tarif_flat']);
 update_option('epeken_nominal_tarif_flat', $_POST['epeken_nominal_tarif_flat']);
 update_option('epeken_kode_kupon_subsidi_ongkir', trim($_POST['kode_kupon_subsidi_ongkir'])); 
 update_option('epeken_nominal_subsidi_ongkir_dengan_kupon', $_POST['nominal_subsidi_ongkir_dengan_kupon']);
 update_option('epeken_enabled_jmx_lts', $_POST['enabled_jmx_lts']);
 update_option('epeken_enabled_jmx_cos', $_POST['enabled_jmx_cos']);
 update_option('epeken_enabled_jmx_sms', $_POST['enabled_jmx_sms']);
 update_option('epeken_enabled_jmx_sos', $_POST['enabled_jmx_sos']);
 update_option('epeken_enabled_lion_regpack', $_POST['enabled_lion_regpack']);
 update_option('epeken_enabled_lion_onepack', $_POST['enabled_lion_onepack']);
 update_option('epeken_enable_error_message_setting', $_POST['epeken_en_er_msg']);
 update_option('epeken_email_korespondensi', $_POST['email_korespondensi']);
 update_option('epeken_setting_eta', $_POST['epeken_setting_eta']);
 update_option('epeken_email_optional', $_POST['email_optional']);

 $ongkir_per_vendor = 'on';
 if(empty($_POST['epeken_ongkir_per_vendor'])){
  $ongkir_per_vendor = 'off';
 }
 update_option('epeken_ongkir_per_vendor', $ongkir_per_vendor);

 if(epeken_is_multi_vendor_mode()) {
        update_option('epeken_enabled_flat', $_POST['enabled_flat']);
 }

 $enable_cekresi = $this -> settings['enable_cekresi_page'];
 if($enable_cekresi === 'yes') {
       $this->create_cek_resi_page();
       $this->add_cek_resi_page_to_prim_menu();
 }else{
       $this -> delete_cek_resi();
 }

 $epeken_biaya_tambahan_name = get_option('epeken_biaya_tambahan_name');
 $epeken_biaya_tambahan_amount  = get_option('epeken_biaya_tambahan_amount');
 $biaya_tambahan_name = $_POST['epeken_biaya_tambahan_name'];
 $biaya_tambahan_amount = $_POST['epeken_biaya_tambahan_amount'];

 if (!is_null($epeken_biaya_tambahan_name)){
        update_option ('epeken_biaya_tambahan_name',$biaya_tambahan_name);
 }else{
        add_option('epeken_biaya_tambahan_name',$biaya_tambahan_name,'','no');
 }

 if (!is_null($epeken_biaya_tambahan_amount)){
      if(is_numeric($biaya_tambahan_amount))
        update_option ('epeken_biaya_tambahan_amount',$biaya_tambahan_amount);
 }else{
        add_option('epeken_biaya_tambahan_amount','0','','no');
 }

 $epeken_packing_kayu_enabled = get_option('epeken_packing_kayu_enabled');
 $packing_kayu_enabled = $_POST['woocommerce_epeken_packing_kayu_enabled'];
 if ($packing_kayu_enabled === "on") {
                $packing_kayu_enabled = "yes";
 } else 
 {
                $packing_kayu_enabled = "no";
 }
 if(!is_null($epeken_packing_kayu_enabled)) {
        update_option('epeken_packing_kayu_enabled',$packing_kayu_enabled);
        if(is_numeric(trim($_POST['woocommerce_epeken_pengali_packing_kayu']))) {
         update_option('epeken_pengali_packing_kayu',trim($_POST['woocommerce_epeken_pengali_packing_kayu']));
        }   
        update_option('epeken_pc_packing_kayu', $_POST['woocommerce_epeken_pc_packing_kayu']);
 }else{
        add_option('epeken_packing_kayu_enabled',$packing_kayu_enabled,'','no');
        if(is_numeric(trim($_POST['woocommerce_epeken_pengali_packing_kayu']))) {
         add_option('epeken_pengali_packing_kayu',trim($_POST['woocommerce_epeken_pengali_packing_kayu'],'','no'));
        } else {
         add_option('epeken_pengali_packing_kayu','1','','no');
        }   
        add_option('epeken_pc_packing_kayu', $_POST['woocommerce_epeken_pc_packing_kayu'],'','no');
 }

do_action('epeken_save_other_settings');

?>
