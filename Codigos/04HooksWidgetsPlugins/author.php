<?php 
/*
https://codex.wordpress.org/Function_Reference/the_author
https://codex.wordpress.org/Function_Reference/get_the_author_meta
https://codex.wordpress.org/Function_Reference/get_avatar
*/
get_header();
printf('
	<h1 class="item  title-template">
		El archivo <em>author.php</em> es el archivo que toma por defecto WordPress para mostrar la página de perfil del autor (usuario) actual.
	</h1>
');

$template_author = '
	<ul class="item  author-info">
		<li>Autor: <b>%s</b></li>
		<li>ID Autor: <b>%s</b></li>
		<li>Correo: <b>%s</b></li>
		<li>Login: <b>%s</b></li>
		<li>Password: <b>%s</b></li>
		<li>Nicename: <b>%s</b></li>
		<li>URL Autor: <b>%s</b></li>
		<li>URL Página Autor: <b>%s</b></li>
		<li>Fecha y Hora de Registro: <b>%s</b></li>
		<li>Rol: <b>%s</b></li>
		<li>Nombre para mostrar: <b>%s</b></li>
		<li>Alias: <b>%s</b></li>
		<li>Nombre: <b>%s</b></li>
		<li>Apellido: <b>%s</b></li>
		<li>Descripción: <b>%s</b></li>
		<li>Número de Publicaciones: <b>%s</b></li>
		<li>Avatar: %s</li>
	</ul>
';

printf(
	$template_author,
	get_the_author(),
	get_the_author_id(),
	get_the_author_meta('user_email'),
	get_the_author_meta('user_login'),
	get_the_author_meta('user_pass'),
	get_the_author_meta('user_nicename'),
	get_the_author_meta('user_url'),
	get_author_posts_url( get_the_author_id() ),
	get_the_author_meta('user_registered'),
	get_the_author_meta('roles')[0],
	get_the_author_meta('display_name'),
	get_the_author_meta('nickname'),
	get_the_author_meta('first_name'),
	get_the_author_meta('last_name'),
	get_the_author_meta('description'),
	get_the_author_posts(),
	get_avatar( get_the_author_id(), 50 )
);

get_template_part('content');
get_sidebar();
get_footer();