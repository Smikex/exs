<?php
if( !defined( 'ABSPATH')){ exit(); } 

function get_usac_files($usac_id){
global $wpdb;
	
	$html = '<div class="verify_accline_wrap">';
	$items = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."uv_accounts_files WHERE uv_id='$usac_id'");
	foreach($items as $item){
		$html .='<div class="verify_accline accline_'. $item->id .'"><a href="'. get_usac_doc($item->id) .'" target="_blank">'. pn_strip_input($item->uv_data) .'</a> | <a href="#" data-id="'. $item->id .'" class="bred js_usac_del">'. __('Delete','pn') .'</a></div>';
	}	
	$html .= '</div>';
	
	return $html;
}

/* ссылка на файл */
function get_usac_doc($id){
	return get_ajax_link('usacdoc').'&id='. $id;
}
/* end ссылка на файл */

add_action('myaction_site_usacdoc', 'def_myaction_ajax_usacdoc');
function def_myaction_ajax_usacdoc(){
global $wpdb, $premiumbox; 

	$premiumbox->up_mode();

	$id = intval(is_param_get('id'));
	if(!$id){
		pn_display_mess(__('Error!','pn'));
	}

	$data = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix ."uv_accounts_files WHERE id='$id'");
	if(!isset($data->id)){
		pn_display_mess(__('Error!','pn'));
	}	
	
	$dostup = 0;

	$ui = wp_get_current_user();
	$user_id = intval($ui->ID);
	
	if($data->user_id == $user_id or current_user_can('administrator') or current_user_can('pn_accountverify')){
		$dostup = 1;
	}

	if($dostup != 1){
		pn_display_mess(__('Error! Access denied','pn'));
	}

	$wp_dir = wp_upload_dir();

	$file = $wp_dir['basedir'].'/accountverify/'. $data->uv_id .'/'. $data->uv_data;

	if(is_file($file)){
		if (ob_get_level()) {
			ob_end_clean();
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		@readfile($file);
		exit;
		
	} else {
		pn_display_mess(__('Error! File does not exist','pn'));
	}
}