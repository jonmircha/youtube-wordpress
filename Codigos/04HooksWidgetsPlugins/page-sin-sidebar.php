<?php
/*
Template name: Plantilla de página estática sin sidebar
*/
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>page-sin-sidebar.php</em> es un archivo de template para páginas estáticas en WordPress se activan en el Administrador, si en el archivo existe al inicio de este un comentario PHP que diga:<br><code>/* Template name: nombre de la plantilla */</code>.
	</h1>	
');
get_template_part('content');
get_footer();