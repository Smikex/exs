<?php 
if( !defined( 'ABSPATH')){ exit(); } 
get_header(); 
?>

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
						
<div class="container-fluid sec_gray">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="sec_white single-page">
                        <h1 class="title-h1"><?php the_title_attribute(); ?></h1>
						<div class="editor">
                        <?php the_content(); ?>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
				
<?php endwhile; ?>								
<?php endif; ?>					

<?php get_footer();?>