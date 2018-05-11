<?php
if( !defined( 'ABSPATH')){ exit(); }

add_filter('account_list_pages','archive_bids_account_list_pages', 10);
function archive_bids_account_list_pages($account_list_pages){	
global $wpdb, $premiumbox;	
	
	$lang_data = '';
	if(is_ml()){
		$lang = get_locale();
		$lang_key = get_lang_key($lang);
		$lang_data = '?lang='.$lang_key;
	}
	
	$show_files = intval($premiumbox->get_option('archivebids','loadhistory'));
	if($show_files == 1){
		$account_list_pages['archive_bids'] = array(
			'title' => __('Download operations archive','pn'),
			'url' => get_site_url_or() .'/request-archivebids.html'.$lang_data,
			'type' => 'target_link',
		);
	}
	
	return $account_list_pages;
}

add_action('myaction_request_archivebids','def_request_archivebids');
function def_request_archivebids(){ 
global $wpdb, $premiumbox;

	$premiumbox->up_mode();

	$ui = wp_get_current_user();
	$user_id = intval($ui->ID);	

	if($user_id){
	
		$lang = is_param_get('lang');
	
		$my_dir = wp_upload_dir();
		$path = $my_dir['basedir'].'/';		
		
		$file = $path.'archive-'. $user_id . '-' . date('Y-m-d-H-i') .'.txt';           
		$fs=@fopen($file, 'w');
	
		$datas = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."archive_bids WHERE user_id = '$user_id' AND status='success' ORDER BY createdate DESC");
		
		$content = __('ID','pn') . ';' . __('Date','pn') . ';' . __('Rate','pn') . ';' . __('Send','pn') . ';' . __('Receive','pn') . ';' . __('Status','pn') . ';';
		$content .= "\n";
		
		$date_format = get_option('date_format');
		$time_format = get_option('time_format');
		
		if(is_array($datas)){
			foreach($datas as $data){
				$arch = @unserialize($data->archive_content);
				$item = (object)$arch;
				$line = '';
				$line .= $item->id .';';
				$line .= get_mytime($item->createdate, "{$date_format}, {$time_format}") .';';
				$line .= is_out_sum(is_my_money($item->curs1), 12, 'course') .''. is_site_value($item->vtype1) .'='. is_out_sum(is_my_money($item->curs2), 12, 'course') .''. is_site_value($item->vtype2) .';';
				$line .= is_out_sum(is_my_money($item->summ1_dc), 12, 'all') .' '. pn_strip_input(ctv_ml($item->valut1)) .' '. is_site_value($item->vtype1) .';';
				$line .= is_out_sum(is_my_money($item->summ2c), 12, 'all') .' '. pn_strip_input(ctv_ml($item->valut2)) .' '. is_site_value($item->vtype2) .';';
				$line .= get_bid_status($item->status).';';
				$line .= "\n";
				$content .= $line;
			}	
		}
		
		@fwrite($fs, $content);
		@fclose($fs);	
	
		if(is_file($file)) {
			if (ob_get_level()) {
				ob_end_clean();
			}
			if($lang == 'ru'){
				header('Content-Type: text/html; charset=CP1251');
			} else {
				header('Content-Type: text/html; charset=UTF8');
			}
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			unlink($file);
			exit;
		} else {
			pn_display_mess(__('Error! Unable to create file!','pn'));
		}	
		
	}	
}		