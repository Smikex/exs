<?php
if( !defined( 'ABSPATH')){ exit(); }

add_action('pn_adminpage_title_pn_settings_archive_bids', 'pn_admin_title_pn_settings_archive_bids');
function pn_admin_title_pn_settings_archive_bids($page){
	_e('Archiving settings','pn');
}

add_action('pn_adminpage_content_pn_settings_archive_bids','pn_admin_content_pn_settings_archive_bids');
function pn_admin_content_pn_settings_archive_bids(){
global $wpdb, $premiumbox;

	$options = array();
	$options['top_title'] = array(
		'view' => 'h3',
		'title' => __('Archiving settings','pn'),
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);
	$options['txt'] = array(
		'view' => 'select',
		'title' => __('Delete TXT files of orders during archiving','pn'),
		'options' => array('0'=>__('No','pn'),'1'=>__('Yes','pn')),
		'default' => $premiumbox->get_option('archivebids','txt'),
		'name' => 'txt',
		'work' => 'int',
	);
	$options['loadhistory'] = array(
		'view' => 'select',
		'title' => __('Allow users to download their exchange history','pn'),
		'options' => array('0'=>__('No','pn'),'1'=>__('Yes','pn')),
		'default' => $premiumbox->get_option('archivebids','loadhistory'),
		'name' => 'loadhistory',
		'work' => 'int',
	);	
	$options['bottom_title'] = array(
		'view' => 'h3',
		'title' => '',
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);			
	pn_admin_one_screen('', $options);  
}  

add_action('premium_action_pn_settings_archive_bids','def_premium_action_pn_settings_archive_bids');
function def_premium_action_pn_settings_archive_bids(){
global $wpdb, $premiumbox;	

	only_post();
	pn_only_caps(array('administrator'));
	
	$options = array();
	$options['txt'] = array(
		'name' => 'txt',
		'work' => 'int',
	);
	$options['loadhistory'] = array(
		'name' => 'loadhistory',
		'work' => 'int',
	);		
	$data = pn_strip_options('', $options);
	foreach($data as $key => $val){
		$premiumbox->update_option('archivebids', $key, $val);
	}				

	$back_url = is_param_post('_wp_http_referer');
	$back_url .= '&reply=true';
			
	wp_safe_redirect($back_url);
	exit;
}  