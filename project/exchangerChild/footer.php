</div>
<footer id="footer">
		<div class="menu-wrap">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
					
						<?php
					wp_nav_menu(array(
					'sort_column' => 'menu_order',
					'container' => 'div',
					'container_class' => 'menu',
					'menu_class' => 'ft_menu',
					'menu_id' => '',
					'depth' => '1',
					'fallback_cb' => 'no_menu',
					'theme_location' => 'the_bottom_menu'
					)); 
					?>
						<div class="langs">
						<?php the_lang_list('tolbar_lang'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="copy">
			&copy; <?php echo date(Y); ?> EXS.ONE — <?php wp_title(); ?>.
		</div>
	</footer>

<div id="topped"></div>

<?php do_action('pn_footer_theme'); ?>
<?php wp_footer(); ?>

</body>
</html>