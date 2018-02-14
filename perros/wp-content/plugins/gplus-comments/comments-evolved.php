<?php
/*
Plugin Name: Comments Evolved for WordPress
Plugin URI: http://wordpress.org/plugins/gplus-comments/
Description: The Comments Evolved for WordPress plugin adds the ability to enable native WordPress, Google+, Facebook, Disqus, Livefyre comment systems easily.
Author: BenSh
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Version: 1.6
*/

defined('ABSPATH') or exit;

define('COMMENTS_EVOLVED_VERSION', comments_evolved_get_version());
defined('COMMENTS_EVOLVED_DEBUG') or define('COMMENTS_EVOLVED_DEBUG', false);
defined('COMMENTS_EVOLVED_DIR') or define('COMMENTS_EVOLVED_DIR', __DIR__);
defined('COMMENTS_EVOLVED_URL') or define('COMMENTS_EVOLVED_URL', rtrim(plugin_dir_url(__FILE__),"/"));
defined('COMMENTS_EVOLVED_LIB') or define('COMMENTS_EVOLVED_LIB', COMMENTS_EVOLVED_DIR . "/includes/lib");
defined('COMMENTS_EVOLVED_TEMPLATES') or define('COMMENTS_EVOLVED_TEMPLATES', COMMENTS_EVOLVED_DIR . "/includes/templates");
defined('COMMENTS_EVOLVED_DEFAULT_TAB_ORDER') or define('COMMENTS_EVOLVED_DEFAULT_TAB_ORDER', 'gplus,facebook,wordpress');
defined('COMMENTS_EVOLVED_PLUGIN_FILE') or define('COMMENTS_EVOLVED_PLUGIN_FILE', __FILE__);

if (version_compare(phpversion(), '5.3', '<')) {
  function comments_evolved_php_too_low() {
    echo "<div class='error'><p>";
    echo "Comments Evolved for WordPress requires PHP 5.3+ and will not be activated,";
    echo "your current server configuration is running PHP version '" . phpversion();
    echo "Any PHP version less than 5.3.0 has reached 'End of Life' from PHP.net and no longer receives bugfixes or";
    echo "security updates. The official information on how to update and why at <a href='http://php.net/eol.php' ";
    echo "target='_blank'><strong>php.net/eol.php</strong></a>";
    echo "</p></div>";
  }
  add_action('admin_notices', 'comments_evolved_php_too_low');
  return;
}

function comments_evolved_get_version() {
  $version = "0.0.1";
  $plugin_file = file_get_contents(__FILE__);
  preg_match('#^\s*Version\:\s*(.*)$#im', $plugin_file, $matches);
  if (!empty($matches[1])) {
    $version = $matches[1];
  }
  return $version;
}

require COMMENTS_EVOLVED_LIB . '/plugin.php';

if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
  function comments_evolved_admin() { require COMMENTS_EVOLVED_LIB . '/admin.php'; }
} else {
  require COMMENTS_EVOLVED_LIB . '/frontend.php';
}

