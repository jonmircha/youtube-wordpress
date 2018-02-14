<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;

?>
<div id="fb-root"></div>
<div id="fb-comments">Loading Facebook Comments ...</div>
<script type="text/javascript">
  jQuery(document).ready(function($)
  {
    $('#fb-comments').html('<div class="fb-comments" data-width="'+window.comment_tab_width+'" data-href="<?php echo the_permalink(); ?>" data-num-posts="20" data-colorscheme="light" data-mobile="auto"></div>');
  });
</script>
<script async type="text/javascript" src="//connect.facebook.net/en_US/all.js#xfbml=1">FB.init();</script>
<noscript>Please enable JavaScript to view the <a href="https://www.facebook.com/">comments powered by Facebook.</a></noscript>


