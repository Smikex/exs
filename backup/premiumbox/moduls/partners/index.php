<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]Блок партнеры[:ru_RU][en_US:]Partners column[:en_US]
description: [ru_RU:]Вывод логотипов партнеров[:ru_RU][en_US:]Show partners logo[:en_US]
version: 1.1
category: [ru_RU:]Настройки[:ru_RU][en_US:]Settings[:en_US]
cat: sett
*/

$path = get_extension_file(__FILE__);
$name = get_extension_name($path);

/* BD */
add_action('pn_moduls_active_'.$name, 'bd_pn_moduls_active_partners');
function bd_pn_moduls_active_partners(){
global $wpdb;
	
	/* партнеры */	
	$table_name = $wpdb->prefix ."partners";
    $sql = "CREATE TABLE IF NOT EXISTS $table_name(
		`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT ,
        `title` longtext NOT NULL,
		`link` tinytext NOT NULL,
		`img` longtext NOT NULL,
		`site_order` bigint(20) NOT NULL default '0',
		`status` bigint(20) NOT NULL default '1',
		PRIMARY KEY ( `id` )	
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	$wpdb->query($sql);

	$query = $wpdb->query("SHOW COLUMNS FROM ".$wpdb->prefix ."partners LIKE 'status'");
	if ($query == 0){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix ."partners ADD `status` int(1) NOT NULL default '1'");
	}	
	
}

add_action('pn_bd_activated', 'bd_pn_moduls_migrate_partners');
function bd_pn_moduls_migrate_partners(){
global $wpdb;

	$query = $wpdb->query("SHOW COLUMNS FROM ".$wpdb->prefix ."partners LIKE 'status'");
	if ($query == 0){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix ."partners ADD `status` int(1) NOT NULL default '1'");
	}
}
/* end BD */

add_action('admin_menu', 'pn_adminpage_partners');
function pn_adminpage_partners(){
global $premiumbox;
	
	$hook = add_menu_page(__('Partners','pn'), __('Partners','pn'), 'administrator', 'pn_partners', array($premiumbox, 'admin_temp'), $premiumbox->get_icon_link('partners'));  
	add_action( "load-$hook", 'pn_trev_hook' );
	add_submenu_page("pn_partners", __('Add','pn'), __('Add','pn'), 'administrator', "pn_addpartners", array($premiumbox, 'admin_temp'));
	add_submenu_page("pn_partners", __('Sort','pn'), __('Sort','pn'), 'administrator', "pn_sortpartners", array($premiumbox, 'admin_temp'));	
	
}

function get_partners(){
global $wpdb;
	$datas = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."partners WHERE status='1' ORDER BY site_order ASC");
	return $datas;
}


global $premiumbox;
$premiumbox->file_include($path.'/add');
$premiumbox->file_include($path.'/list');
$premiumbox->file_include($path.'/sort');