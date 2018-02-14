=== Comments Evolved for WordPress ===
Contributors: bensh
Tags: comments, threaded, email, notification, spam, avatars, community, profile, widget, google, google plus, facebook, disqus, google+, +1, plus one, widget, tabs, comment tabs
License: GPLv3+
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 3.5
Tested up to: 3.6
Stable Tag: 1.6

Comments Evolved ( formerly Google+ Comments for WordPress ) makes the comment section tabbed seamlessly adding tabs for Comments & More

== Description ==
Comments Evolved ( formerly Google+ Comments for WordPress ) makes the comment section tabbed seamlessly adding tabs for G+ Comments, Facebook, Disqus, WordPress Comments, and Trackbacks

If a user is required to create an account with your site before they can comment, you might as well ask them to fetch you a soda from mars. It’s probably not going to happen. However, if you can give them the option to comment with an account they’ve already set up, then you will be much more likely to get some interaction.

**Source Code @ Github: ** [comments-evolved@github](https://github.com/bholtsclaw/comments-evolved)



Using this plugin or JavaScript to load comments **does _NOT_** have a negative effect on your SEO, Google now indexes content ( and specifically comments ) loaded via JavaScript. See [this link](http://blog.optimum7.com/safon/google/google-now-indexing-fb-comments.html) and also [this one](http://webmasters.stackexchange.com/questions/27042/effect-on-seo-of-lazy-scrolling-on-html-comments) for more details.

* You should have [Google Authorship](http://plus.google.com/authorship) already setup and in use for your site.
* I've setup a page so if you want to [Donate](http://www.wepay.com/donations/brandonholtsclaw) to this plugins development, now you can.

_This plug-in is not endorsed or associated with the Google, Twitter, Disqus, Facebook, WordPress.org, Automattic, or WordPress HelpCenter._

== Screenshots ==
1. In Action.
2. Admin Options

== Installation ==
**NOTE: You should always [backup your database](http://codex.wordpress.org/Backing_Up_Your_Database) before installing the plug-ins.**

1. Unpack archive to this archive to the 'wp-content/plugins/' directory inside
   of WordPress

  * Maintain the directory structure of the archive (all extracted files
    should exist in 'wp-content/plugins/gplus-comments/'

2. From your blog administration, activate the plugin.

3. Enjoy!

== Frequently Asked Questions ==
= Google+ Comments for WordPress doesn't work ? =
_A.K.A. the tabs are in list form instead, or do not show up at all_

Keep Calm and lets step through this, the plugin does indeed work though, It's used by thousands of websites already every day so if it doesn't work for your website that because there is some problem with how it interacts with the theme or other plugins and the info below will help you track what one it is exactly.

The _most common cause for it not working is a **jQuery conflict** caused by a theme or a plugin_ that either does not properly instantiate jQuery causing an old version and new version to both try and load themselfs unknown to each other, or instantiates a very old version of jQuery that is not compatable with the jQuery this plugin requests to be loaded ( e.g. we tell WordPress to load the jQuery.js + jQuery-UI-Core.js + jQuery-UI-Tabs.js that are both included with WordPress 3.3+ ) ... below may be of some help.

_Here are things to try in order to see what the problem is._

1. Check jQuery version and make sure you are using the lastest included in WordPress
  If it's an older version or not enqueued correctly it _will_ cause problems.
  It may be in your theme or another plugin

2. You may be lacking wp_footer() function in your footer.php of your WordPress theme.
  Look at the default theme to see how it is implemented and add it into your theme.

3. Try deactivating all plugins one at a time besides Google+ Comments for WordPress and see if if it starts working
  Make sure and to clear both WordPress and your Browsers Cache after any plugin changes to make sure you see the correct pages
  and not a cached version of the same (broken) one. If it does work after deactivating on then there's a plugin conflict.

4. If deactivating plugins doesn't work then there might be a conflict with your theme
  To isolate you can try an different theme and see if it works.

5. Be sure to clear ALL cache's that may be in effect, both in WP like WP-SuperCache or W3TC and also
  locally on your machine like the browser and you can even reboot your Internet router as a precaution ( and a bit of
  overkill but hey, why not check everything ) as it could be running a squid transparent cache proxy for you
  when running DD-WRT or similar as well and you may not have noticed / known before something like this.

6. There may be a style conflict of some sort, check the Developer console for your browser ( any of them anymore have
  a debug console / js console , even IE7+ native and others via extensions too ) when the page is loading to check for
  error messaged that may help narrow it down.

7. Try deleting the plugin and reinstalling - it may have not completely updated correctly.
  You'll have to reset your settings so you might want to write them down.

= Do you need any other plugins to use the other commenting systems like Disqus ? =
No. All needed code for any of the available commenting systems is included with this plugin.

= I'd like support for X commenting system, will you add it ? =
Sure, most likely. If there is not a technical reason not to then I'd be glad to add other commenting systems. Just file a support request asking for it and we'll go from there.

= Will loading Comments with Javascript hurt my SEO ? =
No, that information is based on the way things used to be. Now not only does it not hurt your SEO it can actually help it with more +1's for your content, as well as JS comments from G+, Facebook, Twitter, and others is now fully indexed by google. Plus more engagement and thus content as well. For more info see [this link](http://blog.optimum7.com/safon/google/google-now-indexing-fb-comments.html) and also [this one](http://webmasters.stackexchange.com/questions/27042/effect-on-seo-of-lazy-scrolling-on-html-comments) for more details.

= Does the normal commenting stay active ? =
Yes, both systems can be active at the same time.

= Can I choose what Tab to make Default? =
Yes, as of 1.4.0 you can.

= Can I enable or disable commenting systems I'm not using ? =
Yes, only choose to show the tabs you wish to use via the G+ Comments Options Page inside the Admin area of your WordPress install.

= Can I disable WordPress Native comments so all new comments are made via Google+ but still show the historic WordPress Comments ? =
Yes, just disable WP comments like you normally would and then leave the Show WordPress Comments option checked in the Google+ Comments Options.

= What if I ... =
I can be reached many different ways all listed on http://en.gravatar.com/imbrandon , including Email, Phone, or Other Social Networks.

== Change Log ==
= 1.6 =
* slow page load fixed 

= 1.5.8 =
* improved performance
* bug fixes 

= 1.5.6 =
* reverted comment counting code for now until preformace issues can be resolved in a future update

= 1.5.5 =
* udates to the disqus comment count
* fixes for the comment count total

= 1.5.4 =
* even more css fixes

= 1.5.3 =
* css fixes

= 1.5.2 =
* css updates

= 1.5.1 =
* reworked css so that the embeded iFrames are now **responsive** and more mobile friendly.
* fixed JS for loading Livefyre so that comments made with the official plugin and this plugin are loaded in the same way.
* added option to show comment count on each tab label
* added shortcode `[comments_evolved]` and template tag `display_comments_evolved()` to display comment section in anywhere.
* added shortcodes and template tags to show individual and combined comment counts ( @see the [install page](http://wordpress.org/plugins/gplus-comments/installation/) for listing )


= 1.5.0 =
* WARNING: if you are upgrading from Google+ Comments for WordPress to Comments Evolved then the plugin will need to be manually reactivated and settings will be reset back to defaults.
* rebranding from "Google+ Comments" to "Comments Evolved" ... completed
* major code cleanups ( seperated plugin init, frontend, and backend code )

= 1.4.17 =
* tab font adjustment

= 1.4.16 =
* refactor of most display code so its easier to add new commenting systems
* tweaked tab fonts
* added livefyre tab option
* streamlined js and css
* added icon themes ( only default and monotone in this release )
* added donation button to admin interface
* lots of code cleanup in prep to add shortcodes

= 1.4.11 =
* finally fixed the long standing bug where some themes could not click on content in the single post area

= 1.4.10 =
* jQuery typo

= 1.4.9 =
* Bugfixes for various issues where you could not click the main content area
* misc css cleanup
* misc old code deletion
* split main plugin for better maint
* fb-root error fixed
* FB javascript problem mitigated

= 1.4.7 + 1.4.8 =
* Parse Error Hotfix. Please Update ASAP.

= 1.4.6 =
* load a default comments file with no native comments shown but an error message if comments.php cannot be found for native comments
* better error message with link to PHP info on php.net about PHP version 5.3+ requirement and upgrade proceedures
* loading google+ and facebook javascript the common way shown in the examples instead of a custom js function for better compatability with Google +1, Pintrest, Facbook "Like", and other 3rd party buttons.

= 1.4.5 =
* load comments.php in a alternate way if the default does not exist in the current theme

= 1.4.4 =
* make the comments template load at a higher priority so it correctly loads when child themes are used
* moved the defines out of init so they are always available ( like during activation ), this fixes default tab order is correctly set for new installs

= 1.4.3 =
* load wp comments template from current theme if it exists so original styles take effect

= 1.4.2 =
* added missing trackback icon
* tighened up css margins for tabs

= 1.4.1 =
* minor css hotfixes for tab margin and spacing

= 1.4.0 =
* You can now change the default order of the tabs
* The first tab is now default so something other than G+ can be default
* Official color icons now used instead of the icon font
* JS now loaded inline in the footer reducing http calls
* Icons can now optionally be hidden
* Misc css cleanups and tweaks

= 1.3.0 =
* completely overhauled the css used to show the tabs
* added optional labels for each of the tabs
* added optional label for commenting area
* tweaked the ordering of the css and js loading to work in more circumstances
* added a check for PHP 5.3+ or newer
* updated screenshots

= 1.2.9 =
* lets REALLY not set everyones background to white ... srsly.

= 1.2.8 =
* dont set everyones page background to #fff ( white )

= 1.2.7 =
* move missing styles from theme to plugin

= 1.2.6 =
* make all tab css !important and scoped so it does not inherit from the page easily

= 1.2.5 =
* version bump to fix svn botch
* minor css adjustments for tabs

= 1.2.1 =
* updates to the tab css so it dont mix with some themes
* updates to the font css so it dont mix with some themes
* added a check for php 5.3+ with a sensible error message
* ensured jquery-ui-tabs is loaded now explicitly

= 1.2.0 =
* added Plugin options page to show or hide various comment tabs from the wp-admin area
* comment tabs are now responsive and should adjust to nearly any layout
* now using async javascript to load G+ comments for faster page loading
* added option to show facebook comments
* added option to show disqus comments
* updated screenshot

= 1.1.0 =
* many typo fixes
* initial ( non-functional ) options page placeholder and associated hooks added
* fixed bugs that removed native WordPress comment functionality by accident, any native options should work now
* better loading of the css/js via wp_register/wp_encueue system to let other plug-ins possibly modify our
  scripts and styles as needed as well as using CDN resources wisely
* everything loaded with https:// or [protocol relative urls](http://paulirish.com/2010/the-protocol-relative-url/) so that https:// works without warnings generated due to
  "unsafe" resource loading from http://

= 1.0.4 =
* hot-fix to fix accidental loading of css and js in the admin area

= 1.0.3 =
* fixed automatically a bug where it would disable wp comments by default, that should not have been the case.

= 1.0.2 =
* fixed bumping the version

= 1.0.1 =
* Removed the need for jquery-ui-tab fixing the tab display for some versions of WordPress
* changed the tab colors to closer match the G+ Comments and blend into more websites seamlessly
* fixed a typo when no comments had been made yet

= 1.0.0 =
* Release via WordPress.org

= 0.8.0 =
* Initial public release
