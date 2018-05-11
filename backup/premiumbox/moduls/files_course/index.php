<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]Парсер курса обмена из файла[:ru_RU][en_US:]Parser of exchange rate from file[:en_US]
description: [ru_RU:]Парсер курса обмена из файла[:ru_RU][en_US:]Parser of exchange rate from file[:en_US]
version: 1.1
category: [ru_RU:]Направления обменов[:ru_RU][en_US:]Exchange directions[:en_US]
cat: naps
*/

$path = get_extension_file(__FILE__);
$name = get_extension_name($path);

/* BD */
add_action('pn_moduls_active_'.$name, 'bd_pn_moduls_active_filecourse');
function bd_pn_moduls_active_filecourse(){
global $wpdb;	
	
	$query = $wpdb->query("SHOW COLUMNS FROM ".$wpdb->prefix ."naps LIKE 'filecourse'");
    if($query == 0){
		$wpdb->query("ALTER TABLE ".$wpdb->prefix ."naps ADD `filecourse` varchar(250) NOT NULL default '0'");
    }	
	
}
/* end BD */

add_action('tab_naps_tab2','tab_naps_tab_filecourse',11,2);
function tab_naps_tab_filecourse($data, $data_id){
	
	$lists = get_reserv_filecourse();
?>	
	<tr>
		<th><?php _e('Exchange rate from file','pn'); ?></th>
		<td colspan="2">
			<div class="premium_wrap_standart">
				<select name="filecourse" autocomplete="off">
					<option value="0" <?php selected(0, $data->filecourse); ?>><?php echo '--'. __('No','pn') .'--';?></option>
					<?php 
					foreach($lists as $fdata){
					?>						
						<option value="<?php echo $fdata['line']; ?>" <?php selected($fdata['line'], $data->filecourse); ?>><?php printf(__('Exchange rate from file, line %s','pn'), $fdata['line']);?></option>			
					<?php } ?>
				</select>
			</div>
		</td>
	</tr>	
<?php 
} 

add_filter('pn_naps_addform_post', 'filecourse_pn_naps_addform_post');
function filecourse_pn_naps_addform_post($array){
	$array['filecourse'] = intval(is_param_post('filecourse'));
	return $array;
}

function get_reserv_filecourse(){
global $premiumbox;	
	$arr = array();
	
	$url = trim($premiumbox->get_option('fcourse','url'));
	if($url){
		$curl = get_curl_parser($url, '', 'moduls', 'fcourse');
		$string = $curl['output'];
		if(!$curl['err']){
			$lines = explode("\n",$string);
			$r=0;
			foreach($lines as $line){ $r++;
				$pars_line = explode(':',$line);
				if(isset($pars_line[1])){
					$course = trim($pars_line[1]);
					$course_arr = explode('=', $course);
					$arr[$r] = array(
						'line' => $r,
						'title' => pn_strip_input($pars_line[0]),
						'curs1' => is_my_money(is_isset($course_arr, 0)),
						'curs2' => is_my_money(is_isset($course_arr, 1)),
					);
				}					
			}
		}
	}	
	
	return $arr;
}

add_action('myaction_request_fcourse','fcourse_request_cron');
function fcourse_request_cron(){
global $wpdb, $premiumbox;	

	if(check_hash_cron()){

		$in_file = get_reserv_filecourse();
		$now_date = current_time('mysql');
		$naps = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."naps WHERE filecourse > 0");
		foreach($naps as $nap){
			$key = $nap->filecourse;
			$naps_id = $nap->id;
			if(isset($in_file[$key])){
				$ncurs1 = $in_file[$key]['curs1'];
				$ncurs2 = $in_file[$key]['curs2'];
				if($ncurs1 > 0 and $ncurs2 > 0){
					if($ncurs1 != $nap->curs1 or $ncurs2 != $nap->curs2){
						$p_arr = array();
						$p_arr['editdate'] = $now_date;					
						$p_arr['curs1'] = $ncurs1;
						$p_arr['curs2'] = $ncurs2;
						$wpdb->update($wpdb->prefix.'naps', $p_arr, array('id'=>$naps_id));
						do_action('naps_change_course', $naps_id, $nap, $ncurs1, $ncurs2, 'fcourse');
					}
				}				
			}							
		}		
	
	}
	
	_e('Done','pn');
}

add_action('admin_menu', 'pn_adminpage_fcourse');
function pn_adminpage_fcourse(){
global $premiumbox;	
	
	add_submenu_page("pn_moduls", __('Exchange rate from file','pn'), __('Exchange rate from file','pn'), 'administrator', "pn_fcourse", array($premiumbox, 'admin_temp'));
}

add_action('pn_adminpage_title_pn_fcourse', 'pn_admin_title_pn_fcourse');
function pn_admin_title_pn_fcourse($page){
	_e('Exchange rate from file','pn');
} 

add_action('pn_adminpage_content_pn_fcourse','def_pn_admin_content_pn_fcourse');
function def_pn_admin_content_pn_fcourse(){
global $wpdb, $premiumbox;

	$site_url = get_site_url_or();
	$text = '
	<a href="'. $site_url .'/request-fcourse.html'. get_hash_cron('?') .'" target="_blank">CRON-file</a>
	';
	pn_admin_substrate($text);
	
	$options = array();
	$options['top_title'] = array(
		'view' => 'h3',
		'title' => __('Exchange rate from file settings','pn'),
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);	
	$options['url'] = array(
		'view' => 'inputbig',
		'title' => __('URL file with exchange rates', 'pn'),
		'default' => $premiumbox->get_option('fcourse','url'),
		'name' => 'url',
	);		
	$options['bottom_title'] = array(
		'view' => 'h3',
		'title' => '',
		'submit' => __('Save','pn'),
		'colspan' => 2,
	);
	pn_admin_one_screen('', $options);	
}  

add_action('premium_action_pn_fcourse','def_premium_action_pn_fcourse');
function def_premium_action_pn_fcourse(){
global $wpdb, $premiumbox;	

	only_post();
	pn_only_caps(array('administrator'));

	$options = array('url');	
	foreach($options as $key){
		$premiumbox->update_option('fcourse', $key, pn_strip_input(is_param_post($key)));
	}				

	$url = admin_url('admin.php?page=pn_fcourse&reply=true');
	wp_redirect($url);
	exit;
} 