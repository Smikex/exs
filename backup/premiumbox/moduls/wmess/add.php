<?php
if( !defined( 'ABSPATH')){ exit(); }

add_action('pn_adminpage_title_pn_add_wmess', 'pn_adminpage_title_pn_add_wmess');
function pn_adminpage_title_pn_add_wmess(){
	$id = intval(is_param_get('item_id'));
	if($id){
		_e('Edit message','pn');
	} else {
		_e('Add message','pn');
	}
}

add_action('pn_adminpage_content_pn_add_wmess','def_pn_adminpage_content_pn_add_wmess');
function def_pn_adminpage_content_pn_add_wmess(){
global $wpdb;

	$id = intval(is_param_get('item_id'));
	$data_id = 0;
	$data = '';
	
	if($id){
		$data = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."warning_mess WHERE id='$id'");
		if(isset($data->id)){
			$data_id = $data->id;
		}	
	}

	if($data_id){
		$title = __('Edit message','pn');
	} else {
		$title = __('Add message','pn');
	}
	
	$back_menu = array();
	$back_menu['back'] = array(
		'link' => admin_url('admin.php?page=pn_wmess'),
		'title' => __('Back to list','pn')
	);
	if($data_id){
		$back_menu['add'] = array(
			'link' => admin_url('admin.php?page=pn_add_wmess'),
			'title' => __('Add new','pn')
		);	
	}
	pn_admin_back_menu($back_menu, $data);
	 
	$options = array();
	$options['hidden_block'] = array(
		'view' => 'hidden_input',
		'name' => 'data_id',
		'default' => $data_id,
	);	
	$options['top_title'] = array(
		'view' => 'h3',
		'title' => $title,
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);	
	$options['datestart'] = array(
		'view' => 'datetime',
		'title' => __('Start date','pn'),
		'default' => is_isset($data, 'datestart'),
		'name' => 'datestart',
		'work' => 'datetime',
	);
	$options['dateend'] = array(
		'view' => 'datetime',
		'title' => __('End date','pn'),
		'default' => is_isset($data, 'dateend'),
		'name' => 'dateend',
		'work' => 'datetime',
	);
	$options['theclass'] = array(
		'view' => 'inputbig',
		'title' => __('CSS class','pn'),
		'default' => is_isset($data, 'theclass'),
		'name' => 'theclass',
		'work' => 'input',
	);	
	$options['url'] = array(
		'view' => 'inputbig',
		'title' => __('Link','pn'),
		'default' => is_isset($data, 'url'),
		'name' => 'url',
		'work' => 'input',
		'ml' => 1,
	);
	$options['text'] = array(
		'view' => 'textarea',
		'title' => __('Text','pn'),
		'default' => is_isset($data, 'text'),
		'name' => 'text',
		'width' => '',
		'height' => '150px',
		'work' => 'text',
		'ml' => 1,
	);		
	$options['status'] = array(
		'view' => 'select',
		'title' => __('Status','pn'),
		'options' => array('1'=>__('published','pn'),'0'=>__('moderating','pn')),
		'default' => is_isset($data, 'status'),
		'name' => 'status',
		'work' => 'int',
	);		
	$options['bottom_title'] = array(
		'view' => 'h3',
		'title' => '',
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);
	pn_admin_one_screen('', $options, $data);
}

/* обработка формы */
add_action('premium_action_pn_add_wmess','def_premium_action_pn_add_wmess');
function def_premium_action_pn_add_wmess(){
global $wpdb;	

	only_post();

	pn_only_caps(array('administrator','pn_wmess'));

	$data_id = intval(is_param_post('data_id')); 
	$last_data = '';
	if($data_id > 0){
		$last_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "warning_mess WHERE id='$data_id'");
		if(!isset($last_data->id)){
			$data_id = 0;
		}
	}
	
	$array = array();
	$array['datestart'] = get_mytime(is_param_post('datestart'),'Y-m-d H:i:s');
	$array['dateend'] = get_mytime(is_param_post('dateend'),'Y-m-d H:i:s');
	$array['url'] = pn_strip_input(is_param_post_ml('url'));
	$array['text'] = pn_strip_input(is_param_post_ml('text'));
	$array['theclass'] = pn_strip_input(is_param_post('theclass'));
	$array['status'] = intval(is_param_post('status'));
	
	$array = apply_filters('pn_wmess_addform_post',$array, $last_data);
	
	if($data_id){
		do_action('pn_wmess_edit_before', $data_id, $array, $last_data);
		$result = $wpdb->update($wpdb->prefix.'warning_mess', $array, array('id'=>$data_id));
		do_action('pn_wmess_edit', $data_id, $array, $last_data);
		if($result){
			do_action('pn_wmess_edit_after', $data_id, $array, $last_data);
		}
	} else {
		$wpdb->insert($wpdb->prefix.'warning_mess', $array);
		$data_id = $wpdb->insert_id;	
		do_action('pn_wmess_add', $data_id, $array);
	}

	$url = admin_url('admin.php?page=pn_add_wmess&item_id='. $data_id .'&reply=true');
	wp_redirect($url);
	exit;
} 
/* end обработка формы */