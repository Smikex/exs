<?php
if( !defined( 'ABSPATH')){ exit(); }

add_action('siteplace_js','siteplace_js_courselogs', 1000);
function siteplace_js_courselogs(){	
global $wpdb, $premiumbox;
	$out_bids = intval($premiumbox->get_option('courselogs','out_bids'));
	$out_course = intval($premiumbox->get_option('courselogs','out_course'));
	if($out_bids == 1 or $out_course == 1){
?>	
/* changes course */
jQuery(function($){ 
	$('#last_events_option').on('change', function(){
		if($(this).prop('checked')){
			var hidecourselogs = 1;
			$('.last_events').hide();
		} else {
			var hidecourselogs = 0;
			$('.last_events').show();
		}
		Cookies.set("hidecourselogs", hidecourselogs, { expires: 7, path: '/' });
	});
	
	$(document).on('click', '.levents_close', function(){
		$(this).parents('.levents').hide();
	});
});
/* end changes course */	
<?php	
	}
} 

add_action('wp_footer','wp_footer_courselogs');
function wp_footer_courselogs(){
global $wpdb, $premiumbox;	
	$out_bids = intval($premiumbox->get_option('courselogs','out_bids'));
	$out_course = intval($premiumbox->get_option('courselogs','out_course'));
	if(!function_exists('is_mobile') or function_exists('is_mobile') and !is_mobile()){
		if($out_bids == 1 or $out_course == 1){	
			$hidecourselogs = intval(get_mycookie('hidecourselogs'));
			$style = '';
			$ch_style = '';
			if($hidecourselogs == 1){
				$style = 'style="display: none;"';
				$ch_style = 'act';
			}
			$place = intval($premiumbox->get_option('courselogs','place'));
			$cl = '';
			if($place == 1){
				$cl = 'toright';
			}
	?>
	<div class="last_events_wrap <?php echo $cl; ?>">
		<div class="last_events_div">
			<div id="last_events" class="last_events" <?php echo $style; ?>></div>
			<div class="last_events_option">
				<div class="checkbox <?php echo $ch_style; ?>"><input type="checkbox" name="leven" <?php checked($hidecourselogs, 1); ?> id="last_events_option" value="1" /> <?php _e('Disable notifications','pn'); ?></div>
			</div>
		</div>
	</div>
	<?php  
		}
	}
}

add_filter('globalajax_wp_data_request', 'globalajax_wp_data_request_courselogs');
function globalajax_wp_data_request_courselogs($params){
	$params['ltime'] = current_time('timestamp');
	return $params;
}

add_filter('globalajax_wp_data', 'globalajax_wp_data_courselogs');
function globalajax_wp_data_courselogs($log){
global $wpdb, $premiumbox;
	
	$courselogs = array();
	$count = 0;
	$hidecourselogs = intval(get_mycookie('hidecourselogs'));
	if($hidecourselogs != 1){
		$ltime = pn_strip_input(is_param_post('ltime'));
		$ldate = date('Y-m-d H:i:s', $ltime);
		
		$count = intval($premiumbox->get_option('courselogs','count')); 
		if($count < 1){ $count = 3; }
		
		$v = get_valuts_data();
		
		$nlogs = array();
		$out_bids = intval($premiumbox->get_option('courselogs','out_bids'));
		$out_course = intval($premiumbox->get_option('courselogs','out_course'));
		if($out_bids == 1){
			$bids = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."bids WHERE status='success' AND editdate >= '$ldate' ORDER BY editdate DESC LIMIT 1");
			foreach($bids as $bid){
				$time = strtotime($bid->editdate);
				$nlogs[$time][] = array(
					'type' => 'bid',
					'date' => $bid->editdate,
					'data' => $bid,
				);
			}
		}
		if($out_course == 1){
			$course_logs = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix ."course_logs WHERE createdate >= '$ldate' ORDER BY createdate DESC LIMIT 1");
			foreach($course_logs as $course_logs){
				$time = strtotime($course_logs->createdate);
				$nlogs[$time][] = array(
					'type' => 'course',
					'date' => $course_logs->createdate,
					'data' => $course_logs,
				);
			}
		}
		
		krsort($nlogs); //ksort
		
		$nlogs_work = array();
		foreach($nlogs as $nlog){
			foreach($nlog as $nl){ 
				$nlogs_work[] = $nl;
			}
		}	
		
		foreach($nlogs_work as $item){
			$html = '';
			$vd1 = $vd2 = '';
			if($item['type'] == 'course'){
				$key = 'c_' . $item['data']->id;
				$title = __('Exchange rate has been changed','pn');
				if(isset($v[$item['data']->v1]) and isset($v[$item['data']->v2])){
					$vd1 = $v[$item['data']->v1];
					$vd2 = $v[$item['data']->v2];
					$toup_class = '';
					$c1 = $item['data']->curs1;
					$c2 = $item['data']->curs2;
					$lc1 = $item['data']->lcurs1;
					$lc2 = $item['data']->lcurs2;					
					$nc1 = $nc2 = 0;
					if($c1 == $lc1){
						$nc1 = $c2;
						$nc2 = $lc2;
					} else {
						$nc1 = $c1;
						$nc2 = $lc1;						
					}
					if($nc1 > $nc2){
						$toup_class = 'levents_up';
					} else {
						$toup_class = 'levents_down';
					}
					
					$html = '
					<div class="levents levents_'. $item['type'] .' '. $toup_class .'" id="levents_'. $key .'">
						<div class="levents_close"></div>
						<div class="levents_ins">
							<div class="levents_title">'. $title .'</div>
							<div class="levents_line"><span>'. is_out_sum(is_my_money($item['data']->curs1),$vd1->valut_decimal,'course') .'</span> '. get_valut_title($vd1) .'</div>
							<div class="levents_arr"></div>
							<div class="levents_line"><span>'. is_out_sum(is_my_money($item['data']->curs2),$vd2->valut_decimal,'course') .'</span> '. get_valut_title($vd2) .'</div>
						</div>
					</div>				
					';		
				}
			} elseif($item['type'] == 'bid'){
				$key = 'b_' . $item['data']->id;
				$title = __('Just have exchanged','pn');
				if(isset($v[$item['data']->valut1i]) and isset($v[$item['data']->valut2i])){
					$vd1 = $v[$item['data']->valut1i];
					$vd2 = $v[$item['data']->valut2i];				
					$html = '
					<div class="levents levents_'. $item['type'] .'" id="levents_'. $key .'">
						<div class="levents_close"></div>
						<div class="levents_ins">
							<div class="levents_title">'. $title .'</div>
							<div class="levents_line"><span>'. is_out_sum(is_my_money($item['data']->summ1_dc),$vd1->valut_decimal,'course') .'</span> '. get_valut_title($vd1) .'</div>
							<div class="levents_arr"></div>
							<div class="levents_line"><span>'. is_out_sum(is_my_money($item['data']->summ2c),$vd1->valut_decimal,'course') .'</span> '. get_valut_title($vd2) .'</div>
						</div>
					</div>				
					';	
				}
			}
			$html = apply_filters('courselogs_list', $html, $item['type'], $item['data'], $vd1, $vd2);
			if($html){
				$courselogs[$key] = $html;
			}
			break;
		}
	}
	
	$log['courselogs'] = $courselogs;
	$log['courselogs_count'] = $count;
	
	return $log;
}

add_action('globalajax_wp_data_jsresult', 'globalajax_wp_data_jsresult_courselogs');
function globalajax_wp_data_jsresult_courselogs(){
?>	
	var c_count = res['courselogs_count'];
	var courselogs = res['courselogs'];
	for (var c_key in courselogs){
		var c_data = courselogs[c_key];
		if($('#levents_' + c_key).length == 0){
			$('#last_events').append(c_data);
			var c_len = $('#last_events .levents').length;	
				
			$('#levents_' + c_key).fadeIn(800);
			if(c_len > c_count){
				$('.levents:first').remove();
			}
		}		
	}
<?php	
}