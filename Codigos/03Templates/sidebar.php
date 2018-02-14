<?php
//https://codex.wordpress.org/Function_Reference/get_search_form
$sidebar = '<aside class="item  i-b  w-30  aside">%s</aside>';
printf( $sidebar, get_search_form(false) );