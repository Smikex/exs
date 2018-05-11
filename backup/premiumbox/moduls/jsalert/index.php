<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]JS alert[:ru_RU][en_US:]JS alert[:en_US]
description: [ru_RU:]Окошко с сообщением об ошибке[:ru_RU][en_US:]Window with an error message[:en_US]
version: 1.1
category: [ru_RU:]Javascript[:ru_RU][en_US:]Javascript[:en_US]
cat: js
*/

remove_action('pn_js_alert_response', 'jserror_js_alert_response');

add_action('pn_js_alert_response', 'jsalert_js_alert_response');
function jsalert_js_alert_response(){ 
?>
	$('.js_techwindow').hide();
	$('.standart_shadow, #the_shadow, #jserror_alert').show();		
	var hei = Math.ceil(($(window).height() - $('#jserror_alert .jserror_box').height()) / 2);
	$('#jserror_alert').css({'top':hei});
	
	<?php if(is_admin()){ ?>
	var left = Math.ceil(($(window).width() - $('#jserror_alert').width()) / 2);
	$('#jserror_alert').css({'left' : left});	
	<?php } ?>
	
	if(res['status_text']){
		$('.jserror_alert').html(res['status_text']);
	}
<?php
}

add_action('wp_footer','jsalert_wp_footer');
function jsalert_wp_footer(){
?>
<div class="jserror_wrap js_techwindow" id="jserror_alert">
	<div class="jserror_box">
		<div class="jserror_box_title"><?php _e('Attention!','pn'); ?></div>
		<div class="jserror_box_close" id="jsalert_box_close"></div>
		<div class="jserror_box_ins">
			<div class="jserror_box_text jserror_alert"></div>
		</div>	
	</div>
</div>
<script type="text/javascript">
jQuery(function($){ 	
    $('#jsalert_box_close').on('click', function(){
		$('.js_techwindow').hide();
    });	
});	
</script>
<?php	
}

add_action('admin_footer','jsalert_admin_footer');
function jsalert_admin_footer(){
?>
<div class="jserror_wrap js_techwindow" id="jserror_alert">
	<div class="jserror_box">
		<div class="jserror_box_title"><?php _e('Attention!','pn'); ?></div>
		<div class="jserror_box_close" id="jsalert_box_close"></div>
		<div class="jserror_box_ins">
			<div class="jserror_box_text jserror_alert"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(function($){ 	
    $('#jsalert_box_close').on('click', function(){
		$('.js_techwindow').hide();
    });	
});	
</script>
<?php	
}