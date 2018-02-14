<?php 
printf( '<main class="container  main">' );
	if( have_posts() ):
		while( have_posts() ):
			the_post();
			
			$template_html = '
				<section class="container  post">
					<div class="main-image" style="background-image:url(%s)">
						<h1 class="title">%s</h1>
					</div>
					<article class="item">
						<div class="the-content">%s</div>
						<div class="info-post">
							<p>
								<i class="fa fa-user"></i>
								<a href="%s">%s</a>
							</p>
							<p><i class="fa fa-calendar"></i> %s</p>
							<p class="post-categories">%s</p>
							<p>%s</p>
						</div>
					</article>
				</section>
			';

			printf( 
				$template_html,
				main_image_url( 'full' ),
				get_the_title(),
				get_the_content(),
				get_author_posts_url( get_the_author_id() ),
				get_the_author(),
				get_the_date(),
				get_the_category_list( ', ' ),
				get_the_tag_list( '<i class="fa fa-tag"></i> <i>', ', ', '</i>' )
			);
		endwhile;
	else:
		printf( '<p class="error">No hay entradas</p>' );
	endif;
	rewind_posts();
printf( '</main>' );