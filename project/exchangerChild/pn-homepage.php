<?php 
if( !defined( 'ABSPATH')){ exit(); }

/*

Template Name: Home page template

*/

get_header(); 

global $premiumbox;

$ho_change = get_option('ho_change');
$array = array('wtitle','wtext','ititle','itext','blocknews','blocreviews','catnews','lastobmen','hidecurr');
$change = array();
foreach($array as $opt){
	$change[$opt] = ctv_ml(is_isset($ho_change,$opt));	
}
?>
<div class="homepage_wrap">
<?php
if($change['wtext']){
?>
<div class="home_wtext_wrap">
	<div class="home_wtext_ins">
		<div class="home_wtext_block">
			<div class="home_wtext_title"><?php echo pn_strip_input($change['wtitle']); ?></div>
			<div class="home_wtext_div">
				<div class="text">
					<?php echo apply_filters('the_content',$change['wtext']); ?>
					<div class="clear"></div>
				</div>
			</div>
		</div>	
	</div>
</div>	
<?php } 
?>
	<?php if(function_exists('the_exchange_home')){ the_exchange_home(); }  ?>
</div>
</div>
</div>

<?php if(function_exists('the_exchange_widget')){ the_exchange_widget(); } ?>

<?php
if($change['itext']){
?>
</div>
</div>
</div>
</div>
	<div class="last-changes">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="editor">
						<h1><?php echo pn_strip_input($change['ititle']); ?></h1>
						<p><?php echo apply_filters('the_content',$change['itext']); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } 
?>

<?php 
if($change['blocknews'] == 1){  

$sof = get_option('show_on_front'); 
if($sof == 'page'){
	$blog_url = get_permalink(get_option('page_for_posts'));
} else {
	$blog_url = get_site_url_ml();
}

$catnews = intval($change['catnews']);
$args = array(
	'post_type' => 'post',
	'posts_per_page' => 3
);	
if($catnews){
	$args['cat'] = $catnews;
}

$data_posts = get_posts($args);
?>
<div class="home_news_wrap">
	<div class="home_white_blick"></div>
	
	<div class="home_news_ins">
		<div class="home_news_block">
			<div class="home_news_title"><?php _e('News','pntheme'); ?></div>
			
			<div class="home_news_div">
			
				<?php 
				$date_format = get_option('date_format');
				foreach($data_posts as $item){ ?>
				
					<div class="home_news_one">
						<div class="home_news_date"><?php echo get_the_time( $date_format, $item->ID); ?></div>
							<div class="clear"></div>
						<div class="home_news_content"><a href="<?php echo get_permalink($item->ID); ?>"><?php echo pn_strip_input(ctv_ml($item->post_title)); ?></a></div>
					</div>			
				
				<?php } ?>
			
				<div class="clear"></div>
			</div>
			
			<div class="home_news_more"><a href="<?php echo $blog_url; ?>"><?php _e('All news','pntheme'); ?></a></div>
		</div>
	</div>
</div>
<?php } ?>

			
			<?php 
 			if(function_exists('get_partners')){
				$partners = get_partners(); 
				if(is_array($partners) and count($partners) > 0){			
			?>			

	<div class="container-fluid sec_gray partners-wrap">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h2 class="title-h1"><?php _e('Partners','pntheme'); ?></h2>
				</div>
				<div class="partners">
						<?php  
				foreach($partners as $item){ 
					$link = esc_url($item->link);
					?>
					
							<?php if($link){ ?><a class="item" href="<?php echo $link; ?>" target="_blank"><?php } ?>
								<img src="<?php echo is_ssl_url(pn_strip_input($item->img)); ?>" alt="" />
							<?php if($link){ ?></a><?php } ?>
			
					<?php  
				}
			}
		} 
				?>

				</div>
				</div>
			</div>
	</div>
</div>

</div>
		
<?php get_footer(); ?>