<?php
if( !defined( 'ABSPATH')){ exit(); }

/* 
Данные о версии
*/
global $pn_has_new;
$pn_has_new = get_option('pn_version');

add_action('pn_adminpage_js', 'pn_adminpage_js_update');
function pn_adminpage_js_update() {
?>
    jQuery('.js_pn_version').on('click',function(){
		jQuery('#pn_update_info, .standart_shadow').show();
		
	    var hei = Math.ceil((jQuery(window).height() - jQuery('#pn_update_info').height()) / 2);
		var left = Math.ceil((jQuery(window).width() - jQuery('#pn_update_info').width()) / 2);
		jQuery('#pn_update_info').css({'top': hei, 'left': left});
		
	    return false;
	});
	
    jQuery('.pn_update_close').on('click',function(){
	    jQuery('.js_techwindow, .standart_shadow').hide(); 
	    return false;
	});		
<?php	
}

function pn_has_new(){
global $premiumbox, $pn_has_new;
	$plugin_vers = $premiumbox->plugin_version;
    if(isset($pn_has_new['version']) and version_compare($pn_has_new['version'], $plugin_vers) > 0){
	    return true;
    } else {
	    return false;
	}
}

function pn_update_text(){
global $pn_has_new, $locale;
	$text = '';
	if(isset($pn_has_new['text'])){
		$text = ctv_ml($pn_has_new['text']);
	}
	return $text;
} 

function pn_update_message(){
global $pn_has_new, $locale;
	$text = '';
	if(isset($pn_has_new['message'])){
		$text = ctv_ml($pn_has_new['message']);
	}
	return $text;
} 
 
add_action('admin_footer','pn_update_footer');
function pn_update_footer(){ 
?>
	<div class="standart_window js_techwindow" id="pn_update_info">
		<div class="standart_windowins" id="pn_update_infoins">
			<div class="standart_window_close pn_update_close"></div>
			<div class="standart_window_title"><?php _e('Updates','pn'); ?></div>
		
			<div class="standart_windowcontent">
                <?php if(pn_has_new()){ 
					echo apply_filters('comment_text', pn_update_text());
				} else { ?>
					<?php _e('No updates been mades been made','pn'); ?>
				<?php } ?>
			</div>			
		
		</div>
	</div>
<?php	
} 

add_action('wp_dashboard_setup', 'update_wp_dashboard_setup_premiumbox' );
function update_wp_dashboard_setup_premiumbox() {
	if(pn_update_message()){
		wp_add_dashboard_widget('standart_update_dashboard_widget_premiumbox', __('News from developer','pn'), 'dashboard_update_in_admin_panel_premiumbox');
	}
}
function dashboard_update_in_admin_panel_premiumbox(){
	echo pn_update_message();
}

add_action('pn_adminpage_head','pn_adminpage_head_update', 10, 2);
function pn_adminpage_head_update($page, $prefix){ 
	if($prefix == 'pn'){
		
		if(pn_update_message()){
	?>
		<div class="update_bigwarning">
			<?php echo apply_filters('comment_text', pn_update_message()); ?>
		</div>
	<?php
		}
		
		if(pn_has_new()){
	?>
		<div class="update_bigwarning">
			<?php echo apply_filters('comment_text', pn_update_text()); ?>
		</div>
	<?php	
		}
	}
}

add_action('wp_before_admin_bar_render', 'update_icon_admin_bar_render');
function update_icon_admin_bar_render() {
global $wp_admin_bar, $wpdb, $premiumbox;
	
	$plugin_url = get_premium_url();	
    if(is_admin()){
	    if(pn_has_new()){
			$wp_admin_bar->add_menu( array(
				'id'     => 'new_pn_version',
				'href' => '#',
				'title'  => '<div style="height: 32px; width: 22px; background: url('. $plugin_url .'images/update.png) no-repeat center center"></div>',
				'meta' => array( 'title' => __('Update available','pn'), 'class' => 'js_pn_version' )		
			));	
		}		
	}
} 

function pn_chkv(){
global $or_site_url, $premiumbox;

	$plugin_vers = $premiumbox->plugin_version;	
	
	$options = array(
		CURLOPT_USERAGENT => 'PremiumExchanger v.' . $plugin_vers . ' ('. $or_site_url .')'
	);
	$result = get_curl_parser('https://premiumexchanger.com/update/premiumbox.xml', $options, 'check_version');
	if(!$result['err']){
		$out = $result['output'];
	    if(is_string($out)){
			if(strstr($out, '<?xml')){
		        $res = @simplexml_load_string($out);
                if(is_object($res)){
					$version = (string)$res->version;
					$text = is_isset($res, 'text');
					$text = (string)$text;					
					$message = is_isset($res, 'message');
					$message = (string)$message;					
					
			        $thisis = array();
	                $thisis['text'] = pn_strip_text($text);
					$thisis['message'] = pn_strip_text($message);
                    $thisis['version'] = pn_strip_input($version);
				    update_option('pn_version',$thisis);					
				}
			}
		} 
	} 
}  

add_filter('mycron_1day', 'update_mycron_1day');
function update_mycron_1day($filters){
	$filters['pn_chkv'] = __('Check updates','pn');
	return $filters;
}