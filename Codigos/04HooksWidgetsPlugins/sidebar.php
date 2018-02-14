<?php
//https://codex.wordpress.org/Function_Reference/get_search_form
printf('<aside class="item  i-b  w-30  aside">');
	get_search_form();
	dynamic_sidebar(1);
	dynamic_sidebar('widget 2');
	dynamic_sidebar('widget personalizado');
printf('</aside>');