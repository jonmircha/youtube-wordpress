<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em><b>tag.php</b></em> es el archivo que toma por defecto WordPress para mostrar búsquedas de etiquetas.
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();