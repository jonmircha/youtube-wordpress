<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>singular.php</em> es el archivo que toma por defecto WordPress para mostrar una entrada (un post) o página estática, disponible apartir de WordPress 4.3.
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();