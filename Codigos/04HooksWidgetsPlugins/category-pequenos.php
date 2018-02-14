<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>category-pequenos.php</em> es el archivo que toma WordPress para mostrar la categoría con el slug <i>"pequenos"</i>.
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();