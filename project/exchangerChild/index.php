<?php  
if( !defined( 'ABSPATH')){ exit(); } 

get_header();
?>
	<?php if(is_category() or is_tax() or is_tag()){ ?>
		<?php 
		$description = trim(term_description()); 
		if($description){
		?>
			<div class="term_description">
				<div class="text">
					<?php echo apply_filters('the_content',$description); ?>
					<div class="clear"></div>
				</div>	
			</div>
		<?php } ?>
	<?php } ?>	

	<!-- Main content -->	
	<div class="container-fluid sec_gray">
		<div class="container">
            <h1 class="title-h1 top-title"><?php wp_title(); ?></h1>
			<div class="row">
				<div class="col-md-8">

                <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>	

					<div class="sec_white news-item">
						<h4 class="item-title"><?php the_title(); ?></h4>
						<div class="date">
							<span><?php the_time('Y-m-d'); ?></span>
						</div>
						<p><?php the_excerpt(); ?></p>
						<a href="<?php the_permalink() ?>" class="read-more">
							<!-- <span>Подробнее</span> -->
							<img src="http://exs.one/wp-content/themes/exchangerChild/images/arrow-yellow.png" alt="arrow">
						</a>
					</div>
	
    <?php endwhile; ?>
    
    </div>
				<div class="col-md-4">
				
					</div>
                </div>
				</div>
                



		<?php the_pagenavi(); ?>
	<?php else : ?>
	
	<div class="noitem">
		<p><?php _e('Unfortunately this section is empty','pntheme'); ?></p>								
	</div>
	
	<?php endif; ?>
	
<?php 

get_footer();