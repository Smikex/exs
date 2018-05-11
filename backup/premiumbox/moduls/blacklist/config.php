<?php
if( !defined( 'ABSPATH')){ exit(); }

/****************************** настройки ************************************************/

add_action('pn_adminpage_title_pn_config_blacklist', 'pn_admin_title_pn_config_blacklist');
function pn_admin_title_pn_config_blacklist(){
	_e('Settings','pn');
}

add_action('pn_adminpage_content_pn_config_blacklist','def_pn_admin_content_pn_config_blacklist');
function def_pn_admin_content_pn_config_blacklist(){
global $premiumbox;
?>
	<div class="premium_default_window">
		<?php _e('Cron URL for updating the black list of details from the checkfraud.info service','pn'); ?><br /> 
		<a href="<?php echo get_site_url_or(); ?>/request-blackping.html<?php echo get_hash_cron('?'); ?>" target="_blank"><?php echo get_site_url_or(); ?>/request-blackping.html<?php echo get_hash_cron('?'); ?></a>
	</div>	
<?php	
	$options = array();	
	$options['top_title'] = array(
		'view' => 'h3',
		'title' => __('Settings','pn'),
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);		
	$options['api'] = array(
		'view' => 'select',
		'title' => __('Enable checkfraud.info API','pn'),
		'options' => array('0'=>__('No','pn'),'1'=>__('Yes','pn')),
		'default' => $premiumbox->get_option('blacklist','api'),
		'name' => 'api',
	);
	$options['check'] = array(
		'view' => 'user_func',
		'func_data' => array(),
		'func' => 'pn_checkblacklist_option',	
	);	
	$options['bottom_title'] = array(
		'view' => 'h3',
		'title' => '',
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);
	pn_admin_one_screen('pn_blacklist_configform', $options, '');	
}

function pn_checkblacklist_option(){
global $premiumbox;
	
	$checks = $premiumbox->get_option('blacklist','check');
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

add_action('premium_action_pn_config_blacklist','def_premium_action_pn_config_blacklist');
function def_premium_action_pn_config_blacklist(){
global $wpdb, $premiumbox;

	only_post();
	pn_only_caps(array('administrator','pn_blacklist'));	
	
	$options = array('api');		
	foreach($options as $key){
		$premiumbox->update_option('blacklist', $key, intval(is_param_post($key)));
	}
	
	$check = is_param_post('check');
	$premiumbox->update_option('blacklist', 'check', $check);

	do_action('pn_blacklist_configform_post');
			
	$url = admin_url('admin.php?page=pn_config_blacklist&reply=true');
	wp_redirect($url);
	exit;
}	