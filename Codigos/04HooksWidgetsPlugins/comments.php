<?php 
/*
https://codex.wordpress.org/Function_Reference/comments_template
https://codex.wordpress.org/Function_Reference/comment_form
http://codex.wordpress.org/Function_Reference/wp_list_comments
*/
printf('<section class="item  w-70  comments-form-list">');
	comment_form();
	printf('<ol class="item">');
		wp_list_comments();
	printf('</ol>');
printf('</section>');