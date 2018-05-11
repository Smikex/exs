<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]Ограничения для пользователей[:ru_RU][en_US:]Restriction for users[:en_US]
description: [ru_RU:]Ограничение для пользователей по IP адресу, номеру счета, логину и т.п. при создании заявок[:ru_RU][en_US:]Restriction for users by IP address, account number, login, etc. when orders are created[:en_US]
version: 1.1
category: [ru_RU:]Направления обменов[:ru_RU][en_US:]Exchange directions[:en_US]
cat: naps
*/

$path = get_extension_file(__FILE__);
$name = get_extension_name($path);

/* BD */
add_action('pn_moduls_active_'.$name, 'bd_pn_moduls_active_napsip');
function bd_pn_moduls_active_napsip(){
global $wpdb;	
	
/*
naps_lang - языки
*/	
	
	$query = $wpdb->query("SHOW COLUMNS FROM ".$wpdb->prefix ."naps LIKE 'not_ip'");
    if ($query == 0){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix ."naps ADD `not_ip` longtext NOT NULL");
    }
	
}
/* end BD */

add_action('tab_naps_tab8', 'napsip_tab_naps_tab8', 30, 2);
function napsip_tab_naps_tab8($data, $data_id){

	$string = pn_strip_input(is_isset($data, 'not_ip'));
	$def = array();
	if(preg_match_all('/\[d](.*?)\[\/d]/s',$string, $match, PREG_PATTERN_ORDER)){
		$def = $match[1];
	}
	
	$naps_constraints = get_naps_meta($data_id, 'naps_constraints');
	if(!is_array($naps_constraints)){ $naps_constraints = array(); }
	?>
	<tr>
		<th><?php _e('Prohibited IP and masks (at the beginning of a new line)','pn'); ?></th>
		<td colspan="2">
			<div class="premium_wrap_standart">
				<textarea name="not_ip" style="width: 100%; height: 100px;"><?php echo join("\n",$def); ?></textarea>
			</div>
		</td>
	</tr>	
	<tr>
		<th><?php _e('Max. amount of exchange orders from same IP','pn'); ?></th>
		<td colspan="2">
			<div class="premium_wrap_standart">
				<input type="text" name="napsip_max_ip" style="width: 200px;" value="<?php echo intval(is_isset($naps_constraints, 'max_ip')); ?>" />
			</div>
		</td>
	</tr>
	<tr>
		<th><?php _e('Max. amount of exchange orders from same account Send','pn'); ?></th>
		<td colspan="2">
			<div class="premium_wrap_standart">
				<input type="text" name="napsip_max_account1" style="width: 200px;" value="<?php echo intval(is_isset($naps_constraints, 'max_account1')); ?>" />
			</div>
		</td>
	</tr>
	<tr>
		<th><?php _e('Max. amount of exchange orders from same account Receive','pn'); ?></th>
		<td colspan="2">
			<div class="premium_wrap_standart">
				<input type="text" name="napsip_max_account2" style="width: 200px;" value="<?php echo intval(is_isset($naps_constraints, 'max_account2')); ?>" />
			</div>
		</td>
	</tr>
	<tr>
		<th><?php _e('Max. amount of exchange orders from same user login','pn'); ?></th>
		<td colspan="2">
			<div class="premium_wrap_standart">
				<input type="text" name="napsip_max_user" style="width: 200px;" value="<?php echo intval(is_isset($naps_constraints, 'max_user')); ?>" />
			</div>
		</td>
	</tr>
	<tr>
		<th><?php _e('Max. amount of exchange orders from same e-mail','pn'); ?></th>
		<td colspan="2">
			<div class="premium_wrap_standart">
				<input type="text" name="napsip_max_email" style="width: 200px;" value="<?php echo intval(is_isset($naps_constraints, 'max_email')); ?>" />
			</div>
		</td>
	</tr>	
	<?php 		
}


add_filter('pn_naps_addform_post', 'napsip_pn_naps_addform_post');
function napsip_pn_naps_addform_post($array){

	$not_ip = explode("\n", is_param_post('not_ip'));
	$item = '';
	foreach($not_ip as $v){
		$v = pn_strip_input($v);
		if($v){
			$item .= '[d]'. $v .'[/d]';
		}
	}
	$array['not_ip'] = $item;
	
	return $array;
}

add_action('pn_naps_edit', 'pn_naps_edit_napsip', 10, 2);
add_action('pn_naps_add', 'pn_naps_edit_napsip', 10, 2);
function pn_naps_edit_napsip($data_id, $array){
	
	$naps_constraints = array(
		'max_ip' => intval(is_param_post('napsip_max_ip')),
		'max_account1' => intval(is_param_post('napsip_max_account1')),
		'max_account2' => intval(is_param_post('napsip_max_account2')),
		'max_user' => intval(is_param_post('napsip_max_user')),
		'max_email' => intval(is_param_post('napsip_max_email')),
	);
	update_naps_meta($data_id, 'naps_constraints', $naps_constraints);	
}	

add_action('admin_menu', 'admin_init_napsip');
function admin_init_napsip(){
global $premiumbox;	
	add_submenu_page("pn_moduls", __('Users restrictions','pn'), __('Users restrictions','pn'), 'administrator', "pn_napsip", array($premiumbox, 'admin_temp'));
}

add_action('pn_adminpage_title_pn_napsip', 'def_adminpage_title_pn_napsip');
function def_adminpage_title_pn_napsip($page){
	_e('Users restrictions','pn');
} 

/* настройки */
add_action('pn_adminpage_content_pn_napsip','def_pn_adminpage_content_pn_napsip');
function def_pn_adminpage_content_pn_napsip(){
global $wpdb;

	$bid_status_list = apply_filters('bid_status_list',array());
	
	$napsip = get_option('napsip');
	if(!is_array($napsip)){ $napsip = array(); }	
?>
<div class="premium_body">
		
	<form method="post" action="<?php pn_the_link_post(); ?>">
		<table class="premium_standart_table">
			<?php
				pn_h3(__('Status settings','pn'), __('Save','pn'));	
				?>
				<tr>
					<th><?php _e('Which orders are considered executed','pn'); ?></th>
					<td>
						<div class="premium_wrap_standart">
							<?php 
							if(is_array($bid_status_list)){
								foreach($bid_status_list as $key => $val){ ?>
									<div><label><input type="checkbox" name="napsip[]" <?php if(in_array($key,$napsip)){ ?>checked="checked"<?php } ?> value="<?php echo $key; ?>" /> <?php echo $val; ?></label></div>
							<?php 
								} 
							}
							?>							
						</div>
					</td>		
				</tr>							
				<?php	
				pn_h3('', __('Save','pn'));								
			?>
		</table>
	</form>
		
</div>	
<?php
} 

/* обработка */
add_action('premium_action_pn_napsip','def_premium_action_pn_napsip');
function def_premium_action_pn_napsip(){
global $wpdb;	

	only_post();
	pn_only_caps(array('administrator'));
	
	$new_napsip = array();
	$napsip = is_param_post('napsip');
	if(is_array($napsip)){
		foreach($napsip as $v){
			$v = is_status_name($v);
			if($v){
				$new_napsip[] = $v;
			}
		}
	}
	update_option('napsip',$new_napsip); 	

	$url = admin_url('admin.php?page=pn_napsip&reply=true');
	wp_redirect($url);
	exit;
} 

add_filter('error_bids', 'error_bids_napsip', 99 ,7);
function error_bids_napsip($error_bids, $account1, $account2, $naps, $vd1, $vd2, $auto_data){
global $wpdb;

	$user_ip = pn_real_ip();

	if(!enable_to_ip($user_ip, $naps->not_ip)){
		$error_bids['error'] = 1;
		$error_bids['error_text'][] = __('Error! For your exchange denied','pn');			
	} else {
		
		$naps_constraints = @unserialize(is_isset($naps,'naps_constraints'));
		if(!is_array($naps_constraints)){ $naps_constraints = array(); }		
		
		$napsip = get_option('napsip');
		if(!is_array($napsip)){ $napsip = array(); }
		
		$status = array();
		foreach($napsip as $st){
			$status[] = "'". $st ."'";
		}
		$where = '';
		if(count($status) > 0){
			$st_join = join(',',$status);
			$where = " AND status IN($st_join)";
		} 
			
		$time = current_time('timestamp');
		$date = date('Y-m-d 00:00:00',$time);
		$naps_id = $naps->id;		
		
		$error = 0;
		
		$max_ip = intval(is_isset($naps_constraints, 'max_ip'));
		if($max_ip > 0 and $error == 0){
			$now_cou = $wpdb->query("SELECT id FROM ".$wpdb->prefix."bids WHERE user_ip='$user_ip' AND createdate >= '$date' AND status != 'auto' $where AND naps_id='$naps_id'");
			if($now_cou >= $max_ip){
				$error_bids['error'] = 1;
				$error_bids['error_text'][] = __('Error! For your exchange denied','pn');			
			}
		}
		$max_account1 = intval(is_isset($naps_constraints, 'max_account1'));
		if($max_account1 > 0 and $error == 0){
			$n_item = $account1;
			if($n_item){
				$now_cou = $wpdb->query("SELECT id FROM ".$wpdb->prefix."bids WHERE account1='$n_item' AND createdate >= '$date' AND status != 'auto' $where AND naps_id='$naps_id'");
				if($now_cou >= $max_account1){
					$error_bids['error'] = 1;
					$error_bids['error_text'][] = __('Error! For your exchange denied','pn');			
				}
			}
		}
		$max_account2 = intval(is_isset($naps_constraints, 'max_account2'));
		if($max_account2 > 0 and $error == 0){		
			$n_item = $account2;
			if($n_item){
				$now_cou = $wpdb->query("SELECT id FROM ".$wpdb->prefix."bids WHERE account2='$n_item' AND createdate >= '$date' AND status != 'auto' $where AND naps_id='$naps_id'");
				if($now_cou >= $max_account2){
					$error_bids['error'] = 1;
					$error_bids['error_text'][] = __('Error! For your exchange denied','pn');			
				}	
			}
		}	
		$max_user = intval(is_isset($naps_constraints, 'max_user'));
		if($max_user > 0 and $error == 0){	
			$ui = wp_get_current_user();
			$n_item = intval($ui->ID);		
			if($n_item){
				$now_cou = $wpdb->query("SELECT id FROM ".$wpdb->prefix."bids WHERE user_id='$n_item' AND createdate >= '$date' AND status != 'auto' $where AND naps_id='$naps_id'");
				if($now_cou >= $max_user){
					$error_bids['error'] = 1;
					$error_bids['error_text'][] = __('Error! For your exchange denied','pn');			
				}		
			}
		}
		$max_email = intval(is_isset($naps_constraints, 'max_email'));
		if($max_email > 0 and $error == 0){	
			$n_item = is_isset($auto_data, 'user_email');
			if($n_item){
				$now_cou = $wpdb->query("SELECT id FROM ".$wpdb->prefix."bids WHERE user_email='$n_item' AND createdate >= '$date' AND status != 'auto' $where AND naps_id='$naps_id'");
				if($now_cou >= $max_email){
					$error_bids['error'] = 1;
					$error_bids['error_text'][] = __('Error! For your exchange denied','pn');			
				}		
			}
		}		
	}
	
	return $error_bids;
}