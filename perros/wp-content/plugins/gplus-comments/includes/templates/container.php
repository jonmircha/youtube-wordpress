<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;

echo "\n\n";
echo "<!-- *******************************************************************************************************************-->\n";
echo "<!-- Comments Evolved for Wordpress v".COMMENTS_EVOLVED_VERSION." ( http://wordpress.org/plugins/gplus-comments/ ) -->\n";
echo "<!-- *******************************************************************************************************************-->\n";
echo "\n";

if (post_password_required()) {
  echo "<p class='nocomments'>This post is password protected.</p>";
  return;
}

$options = get_option("comments-evolved");
if(empty($options['tab_order'])) {
  $options['tab_order'] = COMMENTS_EVOLVED_DEFAULT_TAB_ORDER;
}
?>
<!-- comment-tabs -->
<script type="text/javascript">
jQuery(document).ready(function($) {
  window.comment_tab_width = $('#comment-tabs').innerWidth();
});
</script>
<div id="comment-tabs">
<a name="comments"></a>
  <?php
    if(!empty($options['comment_area_label'])) {
      echo "<h4 id='comment-tabs-label'>".$options['comment_area_label']."</h4>";
    }
  ?>
  <ul class="controls inline clearfix">
    <?php
      $tab_order = explode(',',$options['tab_order']);
      if(empty($options['icon_theme']))
      {
        $options['icon_theme'] = 'default';
      }
      $active = ' class="active"';
      /*
      $wordpress_count = comments_evolved_get_wordpress_count();
      $gplus_count = comments_evolved_get_gplus_count();
      $trackback_count = comments_evolved_get_trackback_count();
      $facebook_count = comments_evolved_get_facebook_count();
      $disqus_count = comments_evolved_get_disqus_count();
      */
      foreach ($tab_order as &$tab) {
        $tab = trim($tab);
        if(empty(${$tab . '_count'})) {
          ${$tab . '_count'} = 0;
        }
        echo "<li" . $active . " id='".$tab."-control'><a href='#".$tab."-tab'>";
        if(!$options['hide_icons']) {
          echo "<img id='" . $tab . "-icon' src='" . COMMENTS_EVOLVED_URL . "/assets/images/icons/" . $options['icon_theme'] . "/" . $tab . ".png'>";
        }
        echo "<span id='" . $tab . "-label'>" . $options[${tab} . '_label'] . "</span>";
        //echo "<span id='" . $tab . "-count'> (${$tab . '_count'})</span>";
        echo "</a></li>" . PHP_EOL;
        $active = '';
      }
    ?>
  </ul>
  <?php
  foreach ($tab_order as &$tab) {
    echo "<!-- " . $tab . "-tab -->" . PHP_EOL;
    echo "<div id='" . $tab . "-tab' class='embed-container content-tab clearfix'>" . PHP_EOL;
    require_once COMMENTS_EVOLVED_TEMPLATES . '/partials/' . $tab . '.php';
    echo "</div>" . PHP_EOL;
    echo "<!-- //" . $tab . "-tab -->" . PHP_EOL;
  }
?>
</div>
<!-- //comment-tabs -->
