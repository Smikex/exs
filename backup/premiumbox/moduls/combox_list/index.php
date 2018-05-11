<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]Доп. комиссия обменного пункта в общей таблице[:ru_RU][en_US:]Additional fee of exchange office in general table[:en_US]
description: [ru_RU:]Дополнительная комиссия обменного пункта в общей таблице направлений обмена в панели управления[:ru_RU][en_US:]Additional fee of the exchange office in the general table of exchange directions in the control panel[:en_US]
version: 1.1
category: [ru_RU:]Направления обменов[:ru_RU][en_US:]Exchange directions[:en_US]
cat: naps
*/

$path = get_extension_file(__FILE__);
$name = get_extension_name($path);

add_action('pn_naps_save', 'comboxlist_pn_naps_save');
function comboxlist_pn_naps_save(){
global $wpdb;	
	
	if(isset($_POST['com_box_summ1']) and is_array($_POST['com_box_summ1'])){
		foreach($_POST['com_box_summ1'] as $id => $com_box_summ1){
			$id = intval($id);
			$com_box_summ1 = is_my_money($com_box_summ1);
			$com_box_summ2 = is_my_money($_POST['com_box_summ2'][$id]);			
			$com_box_pers1 = is_my_money($_POST['com_box_pers1'][$id]);	
			$com_box_pers2 = is_my_money($_POST['com_box_pers2'][$id]);				
						
			$array = array();
			$array['com_box_summ1'] = $com_box_summ1;
			$array['com_box_summ2'] = $com_box_summ2;
			$array['com_box_pers1'] = $com_box_pers1;
			$array['com_box_pers2'] = $com_box_pers2;					
			$wpdb->update($wpdb->prefix.'naps', $array, array('id'=>$id));			
		}
	}		
}

add_filter('naps_manage_ap_columns', 'comboxlist_naps_manage_ap_columns');
function comboxlist_naps_manage_ap_columns($columns){
	$columns['comboxlist_give'] = __('Additional sender fee','pn');
	$columns['comboxlist_get'] = __('Additional recipient fee','pn');
	return $columns;
}

add_filter('naps_manage_ap_col', 'comboxlist_naps_manage_ap_col', 10, 3);
function comboxlist_naps_manage_ap_col($show, $column_name, $item){
global $wpdb;
	
	if($column_name == 'comboxlist_give'){	
		$show = '
		<div><input type="text" style="width: 100%; max-width: 80px;" name="com_box_summ1['. $item->id .']" value="'. is_my_money($item->com_box_summ1) .'" /> S</div>
		<div><input type="text" style="width: 100%; max-width: 80px;" name="com_box_pers1['. $item->id .']" value="'. is_my_money($item->com_box_pers1) .'" /> %</div>
		';
	}
	if($column_name == 'comboxlist_get'){	
		$show = '
		<div><input type="text" style="width: 100%; max-width: 80px;" name="com_box_summ2['. $item->id .']" value="'. is_my_money($item->com_box_summ2) .'" /> S</div>
		<div><input type="text" style="width: 100%; max-width: 80px;" name="com_box_pers2['. $item->id .']" value="'. is_my_money($item->com_box_pers2) .'" /> %</div>
		';
	}	
	
	return $show;
}