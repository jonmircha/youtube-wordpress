<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;

if (!empty($comments_by_type['pings'])) : ?>
<ol class="commentlist">
  <?php wp_list_comments('type=pings&max_depth=1'); ?>
</ol>
<?php else : ?>
<p id="notrackbacks">No Trackbacks.</p>
<?php endif;
