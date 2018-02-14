<?php 
get_header();
printf('
	<article class="item  error">
		<h2>ERROR 404</h2>
		<p>Parece que lo que buscas se lo comió el perro</p>
		<img src="%s/img/loading-dog.gif">
	</article>	
', get_bloginfo('template_url') );
get_footer();