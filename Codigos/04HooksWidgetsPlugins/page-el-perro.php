<?php 
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>page-el-perro.php</em> es el archivo que toma WordPress para mostrar la página estática con el slug "el-perro".
	</h1>	
');
get_template_part('content');
get_sidebar();
get_footer();