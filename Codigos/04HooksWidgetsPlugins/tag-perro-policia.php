<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>category-perro-policia.php</em> es el archivo que toma WordPress para mostrar la etiqueta con el slug <i>"perro-policia"</i>.
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();