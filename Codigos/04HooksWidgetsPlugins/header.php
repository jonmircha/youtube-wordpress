<!DOCTYPE html>
<html lang="<?php bloginfo('language'); ?>">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php bloginfo('name'); ?></title>
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<?php wp_head(); ?>
</head>
<body>
	<header id="header" class="container  header">
		<h1 class="item  i-b  w-30  logo">Logo</h1>
		<?php
			//https://developer.wordpress.org/reference/functions/wp_nav_menu/
			$args = array(
				'theme_location' => 'main_nav',
				'container' => 'nav',
				'container_class' => 'item i-b w-70 nav'
			);

			wp_nav_menu( $args );
		?>
	</header>
	<section class="container">