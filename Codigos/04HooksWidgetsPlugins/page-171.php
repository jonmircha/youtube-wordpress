<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>page-171.php</em> es el archivo que toma WordPress para mostrar la página estática con el id 171 (Sentidos) en la BD.
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();