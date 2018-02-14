<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>page.php</em> es el archivo que toma por defecto WordPress para mostrar una página estática.
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();