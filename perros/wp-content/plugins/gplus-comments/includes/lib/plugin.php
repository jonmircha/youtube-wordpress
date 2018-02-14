<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;

add_action('init', function() {
  wp_register_style('comments_evolved_tabs_css', COMMENTS_EVOLVED_URL . '/assets/styles/plugin.css', null, COMMENTS_EVOLVED_VERSION, "all");
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-tabs');
});

add_action('admin_init', function() {
  wp_register_style('yui-pure', COMMENTS_EVOLVED_URL . '/assets/styles/pure-min.css', null, "0.2.0", "all");
  wp_enqueue_style('yui-pure');
  register_setting( 'comments-evolved-options', 'comments-evolved' );
});

register_activation_hook(COMMENTS_EVOLVED_PLUGIN_FILE, function() {
  $options = array();
  $options = get_option('comments-evolved');
  $options["tab_order"] = COMMENTS_EVOLVED_DEFAULT_TAB_ORDER;
  update_option('comments-evolved', $options);
});

function comments_evolved_plugin_action_links($links, $file) {
  if (basename($file) == basename(COMMENTS_EVOLVED_PLUGIN_FILE)) {
    $settings_link = '<a href="options-general.php?page=comments-evolved">Settings</a>';
    array_unshift($links, $settings_link);
  }
  return $links;
}
add_filter('plugin_action_links', 'comments_evolved_plugin_action_links', 10, 2);

function comments_evolved_admin_menu() {
  add_submenu_page('options-general.php', 'Comments Evolved', 'Comments Evolved', 'manage_options', 'comments-evolved', 'comments_evolved_admin');
}
add_action('admin_menu', 'comments_evolved_admin_menu', 10);

function comments_evolved_shortcode($atts) {
  //extract( shortcode_atts( array( 'width' => '600',  ), $atts ) );
  ob_start();
  include(COMMENTS_EVOLVED_TEMPLATES . '/container.php');
  $container_content = ob_get_contents();
  ob_end_clean ();
  return $container_content;
}
add_shortcode('comments_evolved', 'comments_evolved_shortcode');

function display_comments_evolved() {
  echo do_shortcode('[comments_evolved]');
}

function insert_comments_evolved_gpauthor_in_head() {
  $options = get_option( 'comments-evolved' );
  if(!empty($options['gp_author'])){
    echo '<!-- comments-evolved plugin -->' . PHP_EOL;
    echo '<link href="https://plus.google.com/'.$options['gp_author'].'" rel="author" />' . PHP_EOL;
    echo '<!-- //comments-evolved plugin -->' . PHP_EOL;
  }
}
add_action('wp_head', 'insert_comments_evolved_gpauthor_in_head',5);


