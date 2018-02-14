<?php 
printf('<section class="item  ph100  comments-form-list">');
	comment_form();
	printf('<ol class="item">');
		wp_list_comments();
	printf('</ol>');
printf('</section>');