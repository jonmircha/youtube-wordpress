<?php 
printf( '<main class="item  i-b  w-70  main">' );
	//Para busquedas personalizadas
	//https://codex.wordpress.org/Function_Reference/query_posts
	//https://core.trac.wordpress.org/browser/trunk/src/wp-includes/query.php#L89
	//query_posts( 'order=ASC&category_name=gigantes&posts_per_page=3' );

	//Lógica de the_loop
	//Si (hay entradas)
		//mientras (hay entradas)
			//muestra la info de las entradas
	//Si no
		//no hay entradas publicadas

	if( have_posts() ):
		while( have_posts() ):
			the_post();
			
			//printf( '<p>Imprimiendo entrada</p>' );
			//http://php.net/manual/es/function.printf.php
			//http://php.net/manual/es/function.sprintf.php
			$template_html = '
				<article class="post">
					<h1>%s</h1>
					<a href="%s">%s</a>
					<p>%s - %s</p>
					<p>%s</p>
					<p class="post-categories">%s</p>
					<p>%s</p>
					<p>
						<a href="%s">%s</a>
					</p>
					<div class="the-content">%s</div>
				</article>
				<hr>
			';

			printf( 
				$template_html,
				get_the_title(),
				get_the_permalink(),
				get_the_permalink(),
				get_the_date(),
				get_the_time(),
				get_the_excerpt(),
				//get_the_category(),
				get_the_category_list( ' - ' ),
				//get_the_tags(),
				get_the_tag_list( '<i>', ', ', '</i>' ),
				get_author_posts_url( get_the_author_id() ),
				get_the_author(),
				get_the_content()
			);
		endwhile;
	else:
		printf( '<p class="error">No hay entradas</p>' );
	endif;
	rewind_posts();
printf( '</main>' );