<?php
if( !defined( 'ABSPATH')){ exit(); }

/****************************** настройки ************************************************/
add_action('pn_adminpage_title_pn_usve_change', 'pn_adminpage_title_pn_usve_change');
function pn_adminpage_title_pn_usve_change(){
	_e('Settings','pn');
} 

add_action('pn_adminpage_content_pn_usve_change','def_pn_adminpage_content_pn_usve_change');
function def_pn_adminpage_content_pn_usve_change(){
global $premiumbox;	
	
	$options = array();
	$options['top_title'] = array(
		'view' => 'h3',
		'title' => __('Settings','pn'),
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);					
	$options['status'] = array(
		'view' => 'select',
		'title' => __('Allow send request','pn'),
		'options' => array('0'=>__('No','pn'),'1'=>__('Yes','pn')),
		'default' => $premiumbox->get_option('usve','status'),
		'name' => 'status',
	);	
	$options['disable_mtype_check'] = array(
		'view' => 'select',
		'title' => __('Additional security check when images are uploaded','pn'),
		'options' => array('0'=>__('No','pn'),'1'=>__('Yes','pn')),
		'default' => $premiumbox->get_option('usve','disable_mtype_check'),
		'name' => 'disable_mtype_check',
	);		
	$options['verifysk'] = array(
		'view' => 'inputbig',
		'title' => __('Additional discount for verified users','pn').' (%)',
		'default' => $premiumbox->get_option('usve','verifysk'),
		'name' => 'verifysk',
	);	
	$options['line1'] = array(
		'view' => 'line',
		'colspan' => 2,
	);
	$options['text'] = array(
		'view' => 'editor',
		'title' => __('Message on a verification page', 'pn'),
		'default' => $premiumbox->get_option('usve','text'),
		'name' => 'text',
		'work' => 'text',
		'rows' => 14,
		'media' => false,
		'ml' => 1,
	);	
	$options['line2'] = array(
		'view' => 'line',
		'colspan' => 2,
	);
	$options['text_notverify'] = array(
		'view' => 'textarea',
		'title' => __('Message to user in form of exchange if it is not verified','pn'),
		'default' => $premiumbox->get_option('usve','text_notverify'),
		'width' => '',
		'height' => '150px',
		'name' => 'text_notverify',
		'ml' => 1,
	);		
	$tags = array(
		'amount' => __('Amount','pn'),
	);
	$options['text_notverifysum'] = array(
		'view' => 'textareatags',
		'title' => __('Message to user in form of exchange if it is not verified, and limitation of "Send" amount is enabled','pn'),
		'default' => $premiumbox->get_option('usve','text_notverifysum'),
		'tags' => $tags,
		'width' => '',
		'height' => '150px',
		'prefix1' => '[',
		'prefix2' => ']',
		'name' => 'text_notverifysum',
		'ml' => 1,
	);	
	$options['line3'] = array(
		'view' => 'line',
		'colspan' => 2,
	);	
	$uf = $premiumbox->get_option('usve','verify_fields');
	
	$fields = apply_filters('uv_auto_filed', array());
	if(isset($fields[0])){ unset($fields[0]); }
	foreach($fields as $field_key => $field_val){
		$options[$field_key] = array(
			'view' => 'select',
			'title' => sprintf(__('Verify the "%s" field in user profile','pn'), $field_val),
			'options' => array('0'=>__('No','pn'), '1'=>__('Yes','pn')),
			'default' => is_isset($uf, $field_key),
			'name' => $field_key,
		);	
	}	
	
	$options['bottom_title'] = array(
		'view' => 'h3',
		'title' => '',
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);
	pn_admin_one_screen('pn_usvechange_adminform', $options, '');		
} 

add_action('premium_action_pn_usve_change','def_premium_action_pn_usve_change');
function def_premium_action_pn_usve_change(){
global $wpdb, $premiumbox;

	only_post();
	pn_only_caps(array('administrator','pn_userverify'));
	
	$fields = apply_filters('uv_auto_filed', array());
	if(isset($fields[0])){ unset($fields[0]); }	
	$fields_arr = array();
	foreach($fields as $k => $v){
		$fields_arr[$k] = intval(is_param_post($k));
	}	
	$premiumbox->update_option('usve','verify_fields',$fields_arr);
	
	$options = array('status','verifysk','disable_mtype_check');
	foreach($options as $key){
		$val = is_my_money(is_param_post($key));
		$premiumbox->update_option('usve',$key,$val);
	}			
			
	$options_text = array('text_notverify','text', 'text_notverifysum');		
	foreach($options_text as $key){		
		$val = pn_strip_text(is_param_post_ml($key));
		$premiumbox->update_option('usve', $key, $val);
	}

	do_action('pn_usvechange_adminform_post');
			
	$url = admin_url('admin.php?page=pn_usve_change&reply=true');
	wp_redirect($url);
	exit;
}	 