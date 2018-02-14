<?php
/*
Plantillas en WordPress: Son los archivos que nuestro tema va utilizando dependiendo del contenido solicitado
	https://developer.wordpress.org/files/2014/10/wp-template-hierarchy.jpg
	index.php	>>	template principal
		home.php	>>	template del home del sitio
		archive.php	>>	template de categorías y etiquetas
			category.php	>>	template de categorías
			tag.php	>>	template de etiquetas
		singular.php	>>	template de entradas y páginas
			single.php	>>	template de entradas
			page.php	>>	template de páginas estáticas
		404.php	>>	template para Error 404
		search.php	>>	template para búsquedas
		comments.php	>>	template para los comentarios
		author.php	>>	template para mostrar la página de autor
		***Templates personalizados:
			Podemos tener templates personalizados para: 
				categorías, etiquetas y páginas estáticas
			Podemos crear templates personalizados por:
				slug, id o comentario php
*/

get_header();
//get_header('secundario');
//include TEMPLATEPATH . '/content.php';
printf('
	<article class="item  title-template">
		<h1>
			El archivo <em>index.php</em> es el archivo principal que toma por defecto WordPress al acceder a la carpeta de nuestro Tema.
		</h1>
	</article>	
');
get_template_part('content');
get_sidebar();
get_footer();