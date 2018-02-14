<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;

if(!empty($options['disqus_shortname'])) : ?>
<div class="embed-container clearfix" id="disqus_thread">Loading Disqus Comments ...</div>
<script type="text/javascript">
  var disqus_shortname = '<?php echo $options["disqus_shortname"]; ?>';
  (function(d) {
    var dsq = d.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
    (d.getElementsByTagName('head')[0] || d.getElementsByTagName('body')[0]).appendChild(dsq);
  })(document);
</script>
<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<?php else : ?>
<h2 style="color: #ff0000;">You must fill in your Disqus "shortname" in the Comments Evolved <a href="/wp-admin/options-general.php?page=comments-evolved">plugin options</a>.</h2>
<?php endif;

