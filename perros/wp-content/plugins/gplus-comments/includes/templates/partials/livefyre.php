<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;

if(!empty($options['livefyre_siteid'])) : ?>
<div id="livefyre-comments"></div>
<script type="text/javascript" src="//zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js"></script>
<script type="text/javascript">
(function () {
  var articleId = <?php echo $post->ID; ?>;
  fyre.conv.load({}, [{
    el: 'livefyre-comments',
    network: "livefyre.com",
    siteId: "<?php echo $options["livefyre_siteid"]; ?>",
    articleId: articleId,
    signed: false,
    collectionMeta: {
      articleId: articleId,
      url: fyre.conv.load.makeCollectionUrl(),
    }
  }], function() {});
}());
</script>
<?php else : ?>
<h2 style="color: #ff0000;">You must fill in your Livefyre SiteID in the Comments Evolved <a href="/wp-admin/options-general.php?page=comments-evolved">plugin options</a>.</h2>
<?php endif;
