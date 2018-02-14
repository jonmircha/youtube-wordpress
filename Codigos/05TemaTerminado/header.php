<?php 
printf('
<!DOCTYPE html>
<html lang="' . get_bloginfo('language') . '">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="' . get_bloginfo('charset') . '">
	<title>' . get_bloginfo('name') . '</title>
	<meta name="description" content="' . get_bloginfo('description') . '">
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,800" rel="stylesheet">
	<link rel="stylesheet" href="' . get_bloginfo('stylesheet_url') . '">
	<link rel="stylesheet" href="' . get_bloginfo('template_url') . '/css/font-awesome.min.css">
');
	wp_head();
	if( is_admin_bar_showing() )
	{
		printf('
			<style>
				.mobile-nav { top: 3.25rem; }

				.header { top: 3rem; }
			</style>
		');
	}
printf('
</head>
<body>
	<header id="header" class="header">
		<div class="container">
			<h1 class="item  i-b  ph100  lg20  logo"  style="background-image:url(' . get_bloginfo('template_url') . '/img/perro.png)">' . get_bloginfo('name') . '</h1>
			<div id="mobile-nav" class="mobile-nav">
				<i class="fa  fa-bars"></i>
			</div>
');
			$args = array(
				'theme_location' => 'main_nav',
				'container' => 'nav',
				'container_class' => 'item i-b ph100 lg60 nav main-nav',
				'container_id' => 'main-nav'
			);

			wp_nav_menu( $args );
printf('
			<aside id="search" class="item  i-b  ph100  lg20  search">' . get_search_form(false) . '</aside>
		</div>
	</header>
	<section class="container  fixed-top">
');