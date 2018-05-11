<?php
if( !defined( 'ABSPATH')){ exit(); }

/*
title: [ru_RU:]QR-code генератор[:ru_RU][en_US:]QR-code generator[:en_US]
description: [ru_RU:]QR-code генератор[:ru_RU][en_US:]QR-code generator[:en_US]
version: 1.1
category: [ru_RU:]Заявки[:ru_RU][en_US:]Orders[:en_US]
cat: req
*/

add_filter('merchant_footer', 'qr_adress_merchant_footer', 9,3);
function qr_adress_merchant_footer($html, $item, $naps){
global $premiumbox, $wpdb;	
	$key = $naps->m_in;
	$new_html = '';
	$bid = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix ."bids WHERE id='{$item->id}'");
	if(isset($bid->naschet)){
		$naschet = pn_strip_input($bid->naschet);
		if(
			strstr($key, 'blockchain') or 
			strstr($key, 'blockio') or 
			strstr($key, 'wex') or 
			strstr($key, 'edinar') or 
			strstr($key, 'btcup') or 
			strstr($key, 'askbtc') or
			strstr($key, 'coinpayments')
		){
			
			$new_html .= '
			<div style="padding: 20px 0; width: 260px; margin: 0 auto;">
				<div id="qr_adress"></div>
			</div>
			
			<script type="text/javascript" src="'. $premiumbox->plugin_url .'moduls/qr_adress/js/jquery-qrcode-0.14.0.min.js"></script>
			<script type="text/javascript">
			jQuery(function($){
				$("#qr_adress").qrcode({
					size: 260,
					text: "'. $naschet .'"
				});
			});
			</script>
			';
		}
	}
	return $new_html.$html;
}	