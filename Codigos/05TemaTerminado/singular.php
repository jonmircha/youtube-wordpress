<?php 
get_header();
get_template_part('content-posts');
if( is_single() )
{
	comments_template();
}
get_footer();