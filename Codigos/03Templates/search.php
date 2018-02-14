<?php 
get_header();

$search_template = '
	<h1 class="item  title-template">
		El archivo <em>search.php</em> es el archivo que toma por defecto WordPress para mostrar búsquedas por formularios (<code>?s=mi+busqueda</code>).
	</h1>
	<p class="item">
		Los resultados para la búsqueda <mark>%s</mark> son:
	</p>
';

printf( $search_template, get_search_query() );

get_template_part('content');
get_sidebar();
get_footer();