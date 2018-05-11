<?php
if( !defined( 'ABSPATH')){ exit(); }

add_action('pn_adminpage_title_pn_parser', 'pn_adminpage_title_pn_parser');
function pn_adminpage_title_pn_parser(){
	_e('Parsers','pn');
}

add_action('pn_adminpage_content_pn_parser','def_pn_adminpage_content_pn_parser');
function def_pn_adminpage_content_pn_parser(){
global $premiumbox;
	
	$parsers = get_list_parsers();
	$curs_parser = get_option('curs_parser');
	if(!is_array($curs_parser)){ $curs_parser = array(); }
	$work_parser = get_option('work_parser');
	if(!is_array($work_parser)){ $work_parser = array(); }
	$config_parser = get_option('config_parser');
	if(!is_array($config_parser)){ $config_parser = array(); }
	
	$date = __('No','pn');
	$time_parser = get_option('time_parser');
	if($time_parser){
		$date = date('d.m.Y H:i',$time_parser);
	}	
	?>
	<div class="parser_up_time"><?php echo $date; ?></div>
		<div class="premium_clear"></div>
	
	<?php
	$list_parsers = array();
	$birgs = array();
	if(is_array($parsers)){
		foreach($parsers as $data){
			$birg = is_isset($data, 'birg');
			if(!in_array($birg, $birgs)){
				$birgs[] = $birg;
			}
			$list_parsers[$birg][] = $data;
		}	
	}	
	
	$r=0;
	foreach($birgs as $birg){ $r++;
		$cl = '';
		foreach($list_parsers[$birg] as $data){
			$key = intval(is_isset($data,'id'));
			$work = intval(is_isset($work_parser,$key));
			if($work == 1){
				$cl = 'showed';
			}
		}
	?>
	<div class="parser_birg_wrap">
		<div class="parser_birg_title"><?php echo $birg; ?></div>
		<div class="parser_birg_checher"><label><input type="checkbox" class="parserchange_change_all" name="" value="1" /> <?php _e('Enable/disable all parsers','pn'); ?></label></div>
			<div class="premium_clear"></div>
		<div class="parser_birg_div <?php echo $cl; ?>">
			<?php 
			foreach($list_parsers[$birg] as $data){ 
				$birg = is_isset($data, 'birg');
				$key = intval(is_isset($data,'id'));
				$title = is_isset($data,'para');
				$curs_data = is_isset($curs_parser,$key);
				$curs1 = is_my_money(is_isset($curs_data,'curs1'));
				$curs2 = is_my_money(is_isset($curs_data,'curs2'));
				$work = intval(is_isset($work_parser,$key));
				$options = is_isset($data,'options');
				$dp = trim(is_isset($config_parser,$key));
				if(!$dp){ $dp = is_isset($options, 0); }
			?>
			<div class="parser_div">
				<div class="parser_title"><?php if($premiumbox->is_debug_mode()){ ?>[<?php echo $key; ?>] <?php } ?><?php echo $title; ?></div>
				<div class="parser_curs"><?php echo $curs1.' &rarr; '.$curs2; ?></div>
					<div class="premium_clear"></div>
				<div class="parser_enable">
					<label><input type="checkbox" name="" <?php checked($work, 1); ?> data-key="<?php echo $key; ?>" class="parserchange_change" value="1" /> <?php _e('Enable parser','pn'); ?></label>
				</div>
				<?php if(is_array($options) and count($options) > 0){ ?>
				<div class="parser_config">
					<select name="" class="parserselect_change" data-key="<?php echo $key; ?>">
						<?php foreach($options as $k => $v){ ?>
							<option value="<?php echo $v; ?>" <?php selected($v, $dp); ?>><?php echo $v; ?></option>
						<?php } ?>
					</select>
				</div>	
				<?php } ?>
			</div>		
			<?php
			}
			?>
		</div>
	</div>	
	<?php
	}	
	?>
<script type="text/javascript">	
jQuery(function($){

	$('.parser_birg_title').on('click', function(){ 
		$(this).parents('.parser_birg_wrap').find('.parser_birg_div').toggle();
        return false;
	});	
	
function parserchange_request(){
	var id_parsers = '';
	$('.parserchange_change:checked').each(function(){
		var id = $(this).attr('data-key');
		id_parsers = id_parsers + ',' + id;
	});
	
	$('#premium_ajax').show();
	var param ='ids=' + id_parsers;
    $.ajax({
		type: "POST",
		url: "<?php pn_the_link_post('work_parser_save'); ?>",
		dataType: 'json',
		data: param,
		error: function(res, res2, res3){
			<?php do_action('pn_js_error_response', 'ajax'); ?>
		},			
		success: function(res)
		{
			$('#premium_ajax').hide();	
		}
    });	
}	
	
	$('.parserchange_change_all').on('change', function(){
		var all_input = $(this).parents('.parser_birg_wrap').find('input.parserchange_change');
		if($(this).prop('checked')){
			all_input.prop('checked', true);
		} else {
			all_input.prop('checked', false);
		}
		parserchange_request();
	});	
	
	$('.parserchange_change').on('change', function(){
		parserchange_request();
	});

	$('.parserselect_change').on('change', function(){ 
		var id = $(this).attr('data-key');
		var wid = $(this).val();
		var thet = $(this);
		thet.prop('disabled',true);
		
		$('#premium_ajax').show();
		var dataString='id=' + id + '&wid=' + wid;
		
        $.ajax({
			type: "POST",
			url: "<?php pn_the_link_post('config_parser_save'); ?>",
			dataType: 'json',
			data: dataString,
			error: function(res, res2, res3){
				<?php do_action('pn_js_error_response', 'ajax'); ?>
			},			
			success: function(res)
			{
				$('#premium_ajax').hide();	
				thet.prop('disabled',false);
			}
        });
	
        return false;
	}); 	
	
});
</script>	
<?php
}

add_action('premium_action_work_parser_save', 'pn_premium_action_work_parser_save');
function pn_premium_action_work_parser_save(){
global $wpdb;

	only_post();

	$log = array();	
	$log['response'] = '';
	$log['status'] = '';
	$log['status_code'] = 0;
	$log['status_text'] = '';	
	
	if(current_user_can('administrator')){
		
		$ids = explode(',', is_param_post('ids'));
		$has_ids = array();
		foreach($ids as $id){
			$id = intval($id);
			if($id){
				$has_ids[] = $id;
			}
		}
		
		$work_parser = get_option('work_parser');
		if(!is_array($work_parser)){ $work_parser = array(); }
		
		$parsers = get_list_parsers();
		if(is_array($parsers)){
			foreach($parsers as $data){
				$key = intval(is_isset($data,'id'));
				$en = 0;
				if(in_array($key, $has_ids)){
					$en = 1;
				}
				$work_parser[$key] = $en;
			}	
		}	
			
		update_option('work_parser', $work_parser);
		
	} 

	echo json_encode($log);	
	exit;	
}

add_action('premium_action_config_parser_save', 'pn_premium_action_config_parser_save');
function pn_premium_action_config_parser_save(){
global $wpdb;

	only_post();
	
	$log = array();	
	$log['response'] = '';
	$log['status'] = '';
	$log['status_code'] = 0;
	$log['status_text'] = '';	
	
	if(current_user_can('administrator')){
		
		$id = intval(is_param_post('id'));
		$wid = pn_strip_input(is_param_post('wid'));
		
		$config_parser = get_option('config_parser');
		if(!is_array($config_parser)){ $config_parser = array(); }
		
		$config_parser[$id] = $wid;
		
		update_option('config_parser', $config_parser);	
		
	}  	

	echo json_encode($log);	
	exit;
}