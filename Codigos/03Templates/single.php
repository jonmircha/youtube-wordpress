<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>single.php</em> es el archivo que toma por defecto WordPress para mostrar una entrada (un post).
	</h1>	
');
get_template_part('content');
get_sidebar();
comments_template();
get_footer();