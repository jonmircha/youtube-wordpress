<?php 
get_header();
printf(
	'<p class="item  search-message">Los resultados para la búsqueda <mark>%s</mark> son:</p>',
	get_search_query()
);
get_template_part('content-search');
get_footer();