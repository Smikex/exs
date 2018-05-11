<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]Черный список Bestchange[:ru_RU][en_US:]Blacklist Bestchange[:en_US]
description: [ru_RU:]Черный список Bestchange[:ru_RU][en_US:]Blacklist Bestchange[:en_US]
version: 0.1
category: [ru_RU:]Направления обменов[:ru_RU][en_US:]Exchange directions[:en_US]
cat: naps
*/

$path = get_extension_file(__FILE__);
$name = get_extension_name($path);

/* 
Подключаем к меню
*/
add_action('admin_menu', 'pn_adminpage_blacklistbest');
function pn_adminpage_blacklistbest(){
global $premiumbox;
	if(current_user_can('administrator') or current_user_can('pn_blacklistbest')){
		add_menu_page(__('Blacklist Bestchange','pn'), __('Blacklist Bestchange','pn'), 'read', 'pn_blacklistbest', array($premiumbox, 'admin_temp'), $premiumbox->get_icon_link('blacklist'));  
	}
}

add_filter('pn_caps','blacklistbest_pn_caps');
function blacklistbest_pn_caps($pn_caps){
	$pn_caps['pn_blacklistbest'] = __('Work with a blacklist Bestchange','pn');
	return $pn_caps;
}

add_filter('cf_auto_form_value','blacklistbest_cf_auto_form_value',1,4);
function blacklistbest_cf_auto_form_value($cauv,$value,$item,$naps){
global $wpdb, $premiumbox;

	$cf_auto = $item->cf_auto;
	if($value){
		
		$checks = $premiumbox->get_option('blacklistbest','check');
		if(!is_array($checks)){ $checks = array(); }
		
		if($cf_auto == 'user_email' and in_array(4, $checks)){
			$blacklist = check_data_for_bestchange($value);
			if($blacklist > 0){
				$cauv = array(
					'error' => 1,
					'error_text' => __('System error','pn')
				);					
			}
		} elseif($cf_auto == 'user_phone' and in_array(2, $checks)){
			$value = str_replace('+','',$value);
			$blacklist = check_data_for_bestchange($value);
			if($blacklist > 0){
				$cauv = array(
					'error' => 1,
					'error_text' => __('System error','pn')
				);									
			}
		} elseif($cf_auto == 'user_skype' and in_array(3, $checks)){	
			$blacklist = check_data_for_bestchange($value);
			if($blacklist > 0){
				$cauv = array(
					'error' => 1,
					'error_text' => __('System error','pn')
				);					
			}							
		}
	}	
	
	return $cauv;
}

add_filter('account1_bids','blacklistbest_account_bids1',1,2);
function blacklistbest_account_bids1($account_bids, $account){
global $wpdb, $premiumbox;

	if($account_bids['error'] == 0){
		
		$checks = $premiumbox->get_option('blacklistbest','check');
		if(!is_array($checks)){ $checks = array(); }
		
		if(in_array(0, $checks)){
			$blacklist = check_data_for_bestchange($account); 
			if($blacklist > 0){
				$account_bids = array(
					'error' => 1,
					'error_text' => __('System error','pn'),
				);		
			}	
		}
	}
	
	return $account_bids;
}

add_filter('account2_bids','blacklistbest_account_bids2',1,2);
function blacklistbest_account_bids2($account_bids, $account){
global $wpdb, $premiumbox;

	if($account_bids['error'] == 0){
		
		$checks = $premiumbox->get_option('blacklistbest','check');
		if(!is_array($checks)){ $checks = array(); }
		
		if(in_array(1, $checks)){
			$blacklist = check_data_for_bestchange($account); 
			if($blacklist > 0){
				$account_bids = array(
					'error' => 1,
					'error_text' => __('System error','pn'),
				);		
			}	
		}
	}
	
	return $account_bids;
}

add_filter('error_bids','blacklistbest_error_bids',1,3);
function blacklistbest_error_bids($error_bids, $account1, $account2){
global $wpdb, $premiumbox;

	$user_ip = pn_real_ip();
	
	$checks = $premiumbox->get_option('blacklistbest','check');
	if(!is_array($checks)){ $checks = array(); }
	
	if(in_array(5, $checks)){
		$blacklist = check_data_for_bestchange($user_ip);
		if($blacklist > 0){
			$error_bids['error'] = 1;
			$error_bids['error_text'][] = __('Error! For your exchange denied','pn');	
		}
	}
	
	return $error_bids;
}

function check_data_for_bestchange($item){
global $wpdb, $premiumbox;
	
	$check = 0;
	
	$id = trim($premiumbox->get_option('blacklistbest','id'));
	$key = trim($premiumbox->get_option('blacklistbest','key'));
	if($id and $key){
		$curl = get_curl_parser('https://www.bestchange.org/member/scamapi.php?id='. $id .'&key='. $key .'&where=c&query='.$item, array(), 'moduls', 'blacklistbest');
		$string = $curl['output'];
		if(!$curl['err']){
			$res = @simplexml_load_string($string);
			if(is_object($res) and isset($res->request->results)){
				return intval($res->request->results);
			}
		}	
	}
	
	return $check;
}

global $premiumbox;
$premiumbox->file_include($path.'/config');