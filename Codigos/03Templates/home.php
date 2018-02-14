<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>home.php</em> es el archivo que toma por defecto WordPress para mostrar la página de inicio (home) de nuestro sitio.
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();