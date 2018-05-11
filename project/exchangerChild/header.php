<?php if( !defined( 'ABSPATH')){ exit(); } 
global $user_ID, $premiumbox;
?>
<!DOCTYPE html>
<!-- <html <?php language_attributes(); ?>> -->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title(); ?></title>
<?php wp_head(); ?>	
</head>

<body class="custom-background" <?php //body_class(); ?>>
<header id="header">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<nav class="navbar navbar-default">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<div class="nav-menu__icon">
					                <span></span>
					                <span></span>
					                <span></span>
					            </div>
							</button>
							<?php if($change['linkhead'] == 1 and !is_front_page() or $change['linkhead'] != 1){ ?>
							<a class="logo" href="<?php echo get_site_url_ml(); ?>">
						<?php } ?>
							
							<?php
							$logo = get_logotype();
							$textlogo = get_textlogo();
							if($logo){
							?>
								<img src="<?php echo $logo; ?>" alt="" />
							<?php } elseif($textlogo){ ?>
								<?php echo $textlogo; ?>
							<?php } else { 
								$textlogo = str_replace(array('http://','https://','www.'),'',get_site_url_or()); 
							?>
								<?php echo get_caps_name($textlogo); ?>
							<?php } ?>
							
						<?php if($change['linkhead'] == 1 and !is_front_page() or $change['linkhead'] != 1){ ?>	
							</a>
						<?php } ?>	
						</div>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


					<?php
					if($user_ID){
						$theme_location = 'the_top_menu_user';
						$fallback_cb = 'no_menu_standart';
					} else {
						$theme_location = 'the_top_menu';	
						$fallback_cb = 'no_menu';
					}
					wp_nav_menu(array(
						'sort_column' => 'menu_order',
						'container' => 'div',
						'container_class' => 'menu',
						'menu_class' => 'nav navbar-nav',
						'menu_id' => '',
						'depth' => '3',
						'fallback_cb' => $fallback_cb,
						'theme_location' => $theme_location
					));					
					?>				
					<ul class="nav navbar-nav navbar-right">  				
<?php if($user_ID){ 
					$user_id = intval($user_ID);
					$ui = get_userdata($user_id);
				?>
				<li class="log_in"><button data-toggle="modal" data-target="#authorization" onclick="window.location.href='<?php echo $premiumbox->get_page('account'); ?>'"><?php echo get_caps_name($ui->user_login); ?></button></li>
					<li class="registration"><button class="yellow-btn" data-toggle="modal" data-target="#registration"  onclick="window.location.href='<?php echo get_ajax_link('logout', 'get'); ?>'"><?php _e('Exit','pntheme'); ?></button></li>			
					
				<?php } else { ?>
					<li class="log_in"><button  onclick="window.location.href='<?php echo $premiumbox->get_page('login'); ?>'"><?php _e('Sign in','pntheme'); ?></button></li>
					<li class="registration"><button class="yellow-btn" onclick="window.location.href='<?php echo $premiumbox->get_page('register'); ?>'" ><?php _e('Sign up','pntheme'); ?></button></li>
				<?php } ?>	
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
				</div>
			</div>
		</div>
	</header>
<div id="container">

<?php
$h_change = get_option('h_change');
$array = array('fixheader','phone','icq','skype','email','linkhead','telegram','viber','whatsup','jabber');
$change = array();
foreach($array as $opt){
	$change[$opt] = ctv_ml(is_isset($h_change,$opt));	
}
?>

	<?php do_action('pn_header_theme'); ?>
	
	<div class="wrapper">	


		<?php if(!is_front_page()){ ?>	
		<!-- <div class="contentwrap">
			<div class="thecontent"> -->
		<?php } ?>	