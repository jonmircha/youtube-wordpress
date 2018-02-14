<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;
define( 'CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
require_once(CD_PLUGIN_PATH . 'mixpanel-php-master/lib/Mixpanel.php');

function comments_evolved_template($file) {
  global $post, $comments;
  if (!(is_singular() && (have_comments() || 'open' == $post->comment_status))) {
    return;
  }
  return COMMENTS_EVOLVED_TEMPLATES . '/container.php';
}
add_filter('comments_template', 'comments_evolved_template', 4269);

function comments_evolved_get_total_count() {
  $total_count = 0;

  $wordpress_count = comments_evolved_get_wordpress_count();
  //$wordpress_count = get_comments_number();

  $gplus_count = comments_evolved_get_gplus_count();
  $trackback_count = comments_evolved_get_trackback_count();
  $facebook_count = comments_evolved_get_facebook_count();
  $disqus_count = comments_evolved_get_disqus_count();

  $total_count = $total_count + $wordpress_count + $gplus_count + $trackback_count + $facebook_count + $disqus_count;
  return $total_count;
}
//add_filter('get_comments_number', 'comments_evolved_get_total_count', 4269);

function comments_evolved_get_wordpress_count() {
  global $post, $comments, $wp_query, $comments_by_type, $id;
  $get_comments= get_comments('post_id=' . $id);
  $comments_by_type = &separate_comments($get_comments);
  return count($comments_by_type['comment']);
}

function comments_evolved_get_trackback_count() {
  global $post, $comments, $wp_query, $comments_by_type;
  return count($wp_query->comments_by_type['pings']);
}

function comments_evolved_get_facebook_count($url = "") {
  if(empty($url)){ $url = get_permalink(); }
  $link = 'https://graph.facebook.com/?ids=' . urlencode($url);
  $link_body = wp_remote_retrieve_body(wp_remote_get($link));
  $json = json_decode($link_body);
  return $json->$url->comments;
}

function comments_evolved_get_disqus_count($url = "") {
  if(empty($url)){ $url = get_permalink(); }
  $options = get_option("comments-evolved");
  if(!empty($options["disqus_shortname"])){
    $link = 'http://disqus.com/api/3.0/threads/details.json?api_key=qaoZg7DHagkn8xUf9ZqYRacHZI3CuBmGpu5InMmtXgtRzCnq6iGwtn7Fbwq1uysH&forum=' . $options["disqus_shortname"] . '&thread:link=' . urlencode($url);
    $link_body = wp_remote_retrieve_body(wp_remote_get($link));
    $json = json_decode($link_body);
    if(!empty($json)){
      return $json->response->posts;
    }
  }
  return 0;
}

function comments_evolved_get_gplus_count($url = "") {
  include_once COMMENTS_EVOLVED_LIB . '/simple_html_dom.php';
  if(empty($url)){ $url = get_permalink(); }
  $link = 'https://apis.google.com/_/widget/render/commentcount?bsv&href=' . urlencode($url);
  $link_body = str_get_html(wp_remote_retrieve_body(wp_remote_get($link)));
  $count_raw = $link_body->find('#widget_bounds > span', 0);
  $count_arr = split(" ",$count_raw->plaintext);
  return trim($count_arr[0]);
}

function comments_evolved_enqueue_styles() {
  wp_enqueue_style('comments_evolved_tabs_css');
}
add_action('wp_head', 'comments_evolved_enqueue_styles', 4269);

function comments_evolved_enqueue_scripts() {
  echo '<!-- Comments Evolved plugin -->' . PHP_EOL;
  echo '<script>jQuery("#comment-tabs").tabs();</script>' . PHP_EOL;
  echo '<!-- //Comments Evolved plugin -->' . PHP_EOL;
}
add_action('wp_footer', 'comments_evolved_enqueue_scripts', 4269);

function track_comment_posted_event() {
  $string = phpversion();
  if (substr( $string, 0, 1 ) === '5') {
    // get the Mixpanel class instance, replace with your project token
    $mp = Mixpanel::getInstance("d6943462b143b3727b42a1f59c2e27e7");
    // track an event
    $result = $mp->track('Comment Posted', array("plugin_name" => "gplus_comments", "site_domain" => home_url(), "php_version" => phpversion()));
  }
  return $result;
}
add_action('wp_insert_comment', 'track_comment_posted_event');
