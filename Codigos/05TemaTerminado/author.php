<?php 
get_header();
$template_author = '
	<article class="conteiner  author-info">
		<figure class="item  i-b  ph100  lg20">
			%s
			<figcaption>
				<h2>%s</h2>
				<p><b>%s</b> Publicaciones</p>
			</figcaption>
		</figure>
		<ul class="item  i-b  ph100  lg80">
			<li>%s %s</li>
			<li><a href="mailto:%s">%s</a></li>
			<li><a href="%s" target="_blank">%s</a></li>
			<li>%s</li>
		</ul>
	</article>
';

printf(
	$template_author,
	get_avatar( get_the_author_id(), 50 ),
	get_the_author(),
	get_the_author_posts(),
	get_the_author_meta('first_name'),
	get_the_author_meta('last_name'),
	get_the_author_meta('user_email'),
	get_the_author_meta('user_email'),
	get_the_author_meta('user_url'),
	get_the_author_meta('user_url'),
	get_the_author_meta('description')
);

get_template_part('content');
get_footer();