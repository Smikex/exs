<?php
if( !defined( 'ABSPATH')){ exit(); }

/****************************** настройки ************************************************/

add_action('pn_adminpage_title_pn_blacklistbest', 'pn_admin_title_pn_blacklistbest');
function pn_admin_title_pn_blacklistbest(){
	_e('Settings','pn');
}

add_action('pn_adminpage_content_pn_blacklistbest','def_pn_admin_content_pn_blacklistbest');
function def_pn_admin_content_pn_blacklistbest(){
global $premiumbox;

	$options = array();	
	$options['top_title'] = array(
		'view' => 'h3',
		'title' => __('Settings','pn'),
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);
	$options['id'] = array(
		'view' => 'input',
		'title' => __('ID','pn'),
		'default' => $premiumbox->get_option('blacklistbest','id'),
		'name' => 'id',
	);
	$options['key'] = array(
		'view' => 'inputbig',
		'title' => __('Key','pn'),
		'default' => $premiumbox->get_option('blacklistbest','key'),
		'name' => 'key',
	);	
	$options['check'] = array(
		'view' => 'user_func',
		'func_data' => array(),
		'func' => 'pn_checkblacklistbest_option',	
	);	
	$options['bottom_title'] = array(
		'view' => 'h3',
		'title' => '',
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);
	pn_admin_one_screen('pn_blacklistbest_configform', $options, '');	
}

function pn_checkblacklistbest_option(){
global $premiumbox;
	
	$checks = $premiumbox->get_option('blacklistbest','check');
	if(!is_array($checks)){ $checks = array(); }
	
	$fields = array(
		'0'=> __('Invoice Send','pn'),
		'1'=> __('Invoice Receive','pn'),
		'2'=> __('Phone no.','pn'),
		'3'=> __('Skype','pn'),
		'4'=> __('E-mail','pn'),
		'5'=> __('IP', 'pn'),
	);
	?>
	<tr>
		<th><?php _e('Check selected fields','pn'); ?></th>
		<td>
			<div class="premium_wrap_standart">
				<?php 
				if(is_array($fields)){
					foreach($fields as $key => $val){ 
					?>
						<div><label><input type="checkbox" name="check[]" <?php if(in_array($key,$checks)){ ?>checked="checked"<?php } ?> value="<?php echo $key; ?>" /> <?php echo $val; ?></label></div>
					<?php 
					} 
				}
				?>							
			</div>
		</td>		
	</tr>				
	<?php	
}

add_action('premium_action_pn_blacklistbest','def_premium_action_pn_blacklistbest');
function def_premium_action_pn_blacklistbest(){
global $wpdb, $premiumbox;

	only_post();
	pn_only_caps(array('administrator','pn_blacklistbest'));	
	
	$options = array('id');		
	foreach($options as $key){
		$premiumbox->update_option('blacklistbest', $key, intval(is_param_post($key)));
	}
	
	$options = array('key');		
	foreach($options as $key){
		$premiumbox->update_option('blacklistbest', $key, pn_strip_input(is_param_post($key)));
	}	
	
	$check = is_param_post('check');
	$premiumbox->update_option('blacklistbest', 'check', $check);

	do_action('pn_blacklistbest_configform_post');
			
	$url = admin_url('admin.php?page=pn_blacklistbest&reply=true');
	wp_redirect($url);
	exit;
}	