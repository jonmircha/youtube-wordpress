<?php
printf('
	</section>
	<footer class="container  footer">
');
		printf('<div class="item  i-b  paged">');
			wp_pagenavi();
		printf('</div>');
		
		$args = array(
			'theme_location' => 'social_nav',
			'container' => 'nav',
			'container_class' => 'item nav social-nav'
		);

		wp_nav_menu( $args );
printf('
	</footer>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="' . get_bloginfo( 'template_url' ) . '/js/codigos.js"></script>
');

wp_footer();

printf('
</body>
</html>
');