<?php 
printf( '<main class="container  main">' );
	if( have_posts() ):
		while( have_posts() ):
			the_post();
			$template_html = '
				<a href="%s" class="search-link">
					<figure class="container  post">
						<img src="%s" class="item  i-b  md30">
						<figcaption class="item  i-b  md70">
							<h2>%s</h2>
							<p class="the-excerpt">%s</p>
						</figcaption>
					</figure>
				</a>
				<hr>
			';

			printf( 
				$template_html,
				get_permalink(),
				main_image_url( 'thumbnail' ),
				get_the_title(),
				get_the_excerpt()
			);

		endwhile;
	else:
		printf( '<p class="error">No hay entradas</p>' );
	endif;
	rewind_posts();
printf( '</main>' );