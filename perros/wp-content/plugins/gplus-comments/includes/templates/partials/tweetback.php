<?php
/**
 * @author         Brandon Holtsclaw <me@brandonholtsclaw.com>
 * @copyright      2013 Brandon Holtsclaw
 * @license        GPL
 */
defined('ABSPATH') or exit;

?>
<script src="//widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 8,
  interval: 6000,
  width: 700,
  height: 300,
  theme: {
    shell: {
      background: '#ffffff',
      color: '#b364b3'
    },
    tweets: {
      background: '#ffffff',
      color: '#ffffff',
      links: '#338012'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'default'
  }
}).render().setUser('imbrandon').start();
</script>

