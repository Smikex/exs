<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]Уведомление в шапке [:ru_RU][en_US:]Warning messages in header[:en_US]
description: [ru_RU:]Блок уведомления на красном фоне в шапке сайта[:ru_RU][en_US:]Warning messages column marked in red located in header[:en_US]
version: 1.1
category: [ru_RU:]Безопасность[:ru_RU][en_US:]Security[:en_US]
cat: secur
*/

$path = get_extension_file(__FILE__);
$name = get_extension_name($path);

/* BD */
add_action('pn_moduls_active_'.$name, 'bd_pn_moduls_active_wmess');
function bd_pn_moduls_active_wmess(){
global $wpdb;
	
	$table_name= $wpdb->prefix ."warning_mess";
    $sql = "CREATE TABLE IF NOT EXISTS $table_name(
		`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		`datestart` datetime NOT NULL,
		`dateend` datetime NOT NULL,
        `url` longtext NOT NULL,
		`text` longtext NOT NULL,
		`status` int(1) NOT NULL default '0',
		`theclass` varchar(250) NOT NULL,
		PRIMARY KEY ( `id` )	
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	$wpdb->query($sql);	
	
	$query = $wpdb->query("SHOW COLUMNS FROM ".$wpdb->prefix ."warning_mess LIKE 'theclass'");
	if ($query == 0){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix ."warning_mess ADD `theclass` varchar(250) NOT NULL");
	}	
}

add_action('pn_bd_activated', 'bd_pn_moduls_migrate_wmess');
function bd_pn_moduls_migrate_wmess(){
global $wpdb;

	$query = $wpdb->query("SHOW COLUMNS FROM ".$wpdb->prefix ."warning_mess LIKE 'theclass'");
	if ($query == 0){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix ."warning_mess ADD `theclass` varchar(250) NOT NULL");
	}
}
/* end BD */

add_filter('pn_caps','wmess_pn_caps');
function wmess_pn_caps($pn_caps){
	
	$pn_caps['pn_wmess'] = __('Warning messages in header','pn');
	
	return $pn_caps;
}

/* 
Подключаем к меню
*/
add_action('admin_menu', 'pn_adminpage_wmess');
function pn_adminpage_wmess(){
global $premiumbox;	
	if(current_user_can('administrator') or current_user_can('pn_wmess')){
		$hook = add_menu_page(__('Warning messages in header','pn'), __('Warning messages in header','pn'), 'read', 'pn_wmess', array($premiumbox, 'admin_temp'), $premiumbox->get_icon_link('icon'));  
		add_action( "load-$hook", 'pn_trev_hook' );
		add_submenu_page("pn_wmess", __('Add','pn'), __('Add','pn'), 'read', "pn_add_wmess", array($premiumbox, 'admin_temp'));	
	}
}

add_action('pn_header_theme','pn_header_theme_wmess');
function pn_header_theme_wmess(){
global $wpdb;
	$now_date = current_time('mysql');
	$mess = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."warning_mess WHERE status='1' AND datestart < '$now_date' AND dateend > '$now_date'");
	foreach($mess as $mes){
		$text = pn_strip_text(ctv_ml($mes->text));
		$url = pn_strip_input(ctv_ml($mes->url));
		$closest = intval(get_mycookie('wmes'.$mes->id));
		if($closest != 1){
			$cl = '';
			$theclass = pn_strip_input($mes->theclass);
			if($theclass){
				$cl = ' '.$theclass;
			}
	?>	
	<div class="wclosearea <?php echo $cl; ?> js_wmess" id="wmess_<?php echo $mes->id; ?>">
		<div class="wclosearea_ins">
			<div class="wclosearea_hide js_wmess_close"><div class="wclosearea_hide_ins"></div></div>
			<div class="wclosearea_text">
				<div class="wclosearea_text_ins">
					<?php if($url){ ?><a href="<?php echo $url; ?>"><?php } ?>
						<?php echo $text; ?>
					<?php if($url){ ?></a><?php } ?>
				</div>	
			</div>
		</div>
	</div>
	<?php }
	} 
} 

add_action('siteplace_js','siteplace_js_wmess');
function siteplace_js_wmess(){	
?>	 
jQuery(function($){ 
    $('.js_wmess_close').on('click',function(){
		
		var thet = $(this);
		var id = $(this).parents('.js_wmess').attr('id').replace('wmess_','');
		thet.addClass('active');
		
		Cookies.set("wmes"+id, 1, { expires: 7, path: '/' });
		
		$('#wmess_' + id).hide();
		thet.removeClass('active');
 
        return false;
    });
});	
<?php	
} 

global $premiumbox;
$premiumbox->file_include($path.'/add');
$premiumbox->file_include($path.'/list'); 