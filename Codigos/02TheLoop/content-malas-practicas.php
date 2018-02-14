<main class="item  i-b  w-70  main">
	<?php 
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
			//echo '<p>Imprimiendo entrada</p>';
	?>
			<article class="post">
				<h1><?php the_title(); ?></h1>
				<a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a>
				<p>
					<?php
					//Devuelve la fecha, si hay varias entradas publicadas en una misma fecha, solo muestra la última
					the_date();
					the_time( 'd-M-Y' );
					?>
				</p>
				<p><?php the_excerpt(); ?></p>
				<p><?php the_category(); ?></p>
				<p><?php the_tags(); ?></p>
				<p><?php the_author(); ?></p>
				<div class="the-content"><?php the_content(); ?></div>
			</article>
			<hr>
	<?php
		endwhile;
	else:
		echo '<p class="error">No hay entradas</p>';
	endif;
	rewind_posts();
	?>
</main>