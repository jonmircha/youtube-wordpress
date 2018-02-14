<?php
// No direct access
defined('ABSPATH') or exit;

?>

<div class="wrap">
  <div class="pure-g-r">
  <div class="pure-u-1-2">
<h2>Comments Evolved <small>( v<?php echo COMMENTS_EVOLVED_VERSION; ?> )</small></h2>
<form method="POST" action="options.php">
<?php
settings_fields( 'comments-evolved-options' );
$options = get_option( 'comments-evolved' );

if(empty($options['tab_order']))
{
  $options['tab_order'] = COMMENTS_EVOLVED_DEFAULT_TAB_ORDER;
}
?>
<table>
    <tr>
        <td>Tab Order:</td>
        <td><input type="text" name="comments-evolved[tab_order]" value="<?php echo $options['tab_order'];?>"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
          <strong>Notes:</strong> Comma Separated List, First listed is the default, <br>
          If left empty it will use default value below, only tabs listed will be shown.<br><br>
          <strong>Possible Values:</strong> gplus,facebook,livefyre,disqus,wordpress,trackback<br><br>
          <strong>Default Value:</strong> <?php echo COMMENTS_EVOLVED_DEFAULT_TAB_ORDER; ?><br>
        </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td>Disqus Shortname:</td>
        <td><input type="text" name="comments-evolved[disqus_shortname]" value="<?php echo $options['disqus_shortname'];?>"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <strong>Notes:</strong> Required if showing the Disqus Tab
        </td>
    </tr>
    <tr>
        <td>Livefyre SiteID:</td>
        <td><input type="text" name="comments-evolved[livefyre_siteid]" value="<?php echo $options['livefyre_siteid'];?>"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <strong>Notes:</strong> Required if showing the Livefyre Tab
        </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
      <td>Icon Theme:</td>
      <td>
        <select name="comments-evolved[icon_theme]">
            <option selected="selected" value="default">Default</option>
            <option value="monotone">Monotone</option>
        </select>
      </td>
    </tr>
    <tr>
        <td>Hide Icons:</td>
        <td><input type="checkbox" name="comments-evolved[hide_icons]" value="1" <?php checked( '1', $options['hide_icons']);?>></td>
    </tr>
    <tr>
        <td>Comment Area Label:</td>
        <td><input type="text" name="comments-evolved[comment_area_label]" value="<?php echo $options['comment_area_label'];?>"></td>
    </tr>
    <tr>
        <td>G+ Label:</td>
        <td><input type="text" name="comments-evolved[gplus_label]" value="<?php echo $options['gplus_label'];?>"></td>
    </tr>
    <tr>
        <td>Facebook Label:</td>
        <td><input type="text" name="comments-evolved[facebook_label]" value="<?php echo $options['facebook_label'];?>"></td>
    </tr>
    <tr>
        <td>Disqus Label:</td>
        <td><input type="text" name="comments-evolved[disqus_label]" value="<?php echo $options['disqus_label'];?>"></td>
    </tr>
    <tr>
        <td>Livefyre Label:</td>
        <td><input type="text" name="comments-evolved[livefyre_label]" value="<?php echo $options['livefyre_label'];?>"></td>
    </tr>
    <tr>
        <td>WordPress Label:</td>
        <td><input type="text" name="comments-evolved[wordpress_label]" value="<?php echo $options['wordpress_label'];?>"></td>
    </tr>
    <tr>
        <td>Trackbacks Label:</td>
        <td><input type="text" name="comments-evolved[trackback_label]" value="<?php echo $options['trackback_label'];?>"></td>
    </tr>
</table>
    <?php submit_button(); ?>
</form>
  </div>
  <div class="pure-u-1-2">
  <div style="padding: 15px; margin: 5px; border: 2px solid #008fff; border-radius: 6px; max-width: 350px; background-color: #d5edff;">
    <h2>Comments Evolved for WordPress is 100% free.</h2>
    <p>If you enjoy using this plugin consider a donation via <a href="https://www.wepay.com/donations/brandonholtsclaw">WePay</a> using the button below for any amount you see fit... <strong>thank you</strong>!</p>
    <p style="float: left;margin-left: 20px;">
      <a class="wepay-widget-button wepay-green" id="wepay_widget_anchor_51beee51397d5" href="https://www.wepay.com/donations/338811">Donate</a><script type="text/javascript">var WePay = WePay || {};WePay.load_widgets = WePay.load_widgets || function() { };WePay.widgets = WePay.widgets || [];WePay.widgets.push( {object_id: 338811,widget_type: "donation_campaign",anchor_id: "wepay_widget_anchor_51beee51397d5",widget_options: {donor_chooses: true,allow_cover_fee: true,enable_recurring: true,button_text: "Donate"}});if (!WePay.script) {WePay.script = document.createElement('script');WePay.script.type = 'text/javascript';WePay.script.async = true;WePay.script.src = 'https://static.wepay.com/min/js/widgets.v2.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(WePay.script, s);} else if (WePay.load_widgets) {WePay.load_widgets();}</script>
    </p>
    <p style="float: right;margin-right: 20px;">
      <a href="https://www.wepay.com/donations/brandonholtsclaw/"><img src="<?php echo COMMENTS_EVOLVED_URL . '/assets/images/admin/wepay_logo.png'; ?>" border="0"></a>
    </p>
    <div style="clear: both;"></div>
  </div>
  <div style="padding: 15px; margin: 5px; border: 2px solid #008fff; border-radius: 6px; max-width: 350px; background-color: #d5edff;">
    <h2>Work Somewhere Awesome?</h2>
    <a href="https://www.linkedin.com/in/brandonholtsclaw"><img src="<?php echo COMMENTS_EVOLVED_URL . '/assets/images/admin/hire-me.png'; ?>" border="0"></a>
  </div>
  </div>
</div>
</div>
