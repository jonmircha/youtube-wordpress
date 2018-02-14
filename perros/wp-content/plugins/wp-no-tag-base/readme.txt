=== WP-No-Tag-Base ===
Contributors: dlocc, wordimpress
Donate link: http://www.wordimpress.com/
Tags: tags, tag base, tag, no tag base, permalinks, permastruct, links, seo, cms, wp-no-tag-base
Requires at least: 3.6
Tested up to: 4.1.1
Stable tag: 1.2.4

This plugin will completely remove the mandatory 'Tag Base' from your tag permalinks (e.g. `/tag/my-tag/` to `/my-tag/`).

== Description ==

WP-No-tag-base will completely remove the mandatory tag base from your permalinks ( e.g. `wordimpress.com/tag/my-tag/` to `wordimpress.com/my-tag/` ).

The plugin requires no setup or modifying core WordPress files and should not break any links.   It will also take care of redirecting your old tag links to the new ones.

<h3>Important Links:</h3>

[Get Support](http://www.wordimpress.com/plugins/wp-admin-icons-plugin "WP-Admin Icons Support")
[Rate Plugin 5-Stars](http://wordpress.org/support/view/plugin-reviews/wp-no-tag-base "WP-Admin Icons Rating")
[Visit WordImpress](http://www.wordimpress.com/ "WordImpress - Developer's Site")

= Features =

1. Short and logical permalink structures.
2. Simple plugin - barely adds any overhead.
3. Works out of the box - no setup needed.
4. No need to modify WordPress files.
5. Doesn't require other plugins to work.
6. Compatible with sitemap plugins.
8. Redirects old tag permalinks to the new ones (301 redirect, good for SEO).

== Installation ==

1. Upload `wp-no-tag-base.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to access your tags via http://myblog.com/my-tag/

Note: If you have WordPress 2.7 or above you can simply go to 'Plugins' &gt; 'Add New' in the WordPress admin and search for "WP No Tag Base" and install it from there.

== Frequently Asked Questions ==

= Why should I use this plugin? =

Use this plugin if you want to get rid of WordPress' "tag" base completely. The normal behaviour of WordPress is to add '/tag' to your tag permalinks if you leave "Tag base" blank in the Permalink settings. So your tag links look like `myblog.com/tag/my-tag/`. With this plugin your tag links will look like `myblog.com/my-tag/` (or `myblog.com/my-tag/` in case of sub categories).

Why do this?  Well, shorter URLs mean betting indexing and bottom line is: it's good for SEO.  Having the meaningless "tag" in your permalinks will dilute their keyword relevance.  Keep your tag permalinks tight with this plugin.


== Screenshots ==

1. WP-Admin Icons Logo
2. Wp-Admin Icons Default Screen
3. WP-Admin Icons Packaged Icon View

== Changelog ==

= 1.2.4 =
* Added thumbnail icons for plugin repo search results
* Revised tag naming structure in repo
* Bumped up plugin version to get rid of 2-year warning
* Removed unnecessary index.php file from root plugin directory

= 1.2.3 =
* Debugged/Fixed issues with 500 redirect error notice for some environments
* Fixed issue with tag base 301 redirects not working as expected
* Further debugged custom tag base 301 redirect support

= 1.2.2 =
* Fixed issue with rewrite causing media upload urls to be improperly redirected
* Minor code clean up

= 1.2.1 =
* Removed function get_tag_parents() that was causing fatal errors in some environments
* Spelling improvements for description of plugin and additional format for plugin readme file


= 1.2 =
* Updated plugin for WP 3.4.1 and debugged optional Tag base feature in WordPress' Permalink settings to ensure 301 redirects are working properly even if user has provided an alternate tag base
* Updated description of plugin, added plugin header image and add additional code comments
* Verified plugin works properly with Google XML Sitemaps and WordPress SEO by Yoast sitemap generators.

= 1.1 =
* Updated plugin to refresh tag-base rewrite rules for newly created tags, deleted tags and edited tags
* Modified 1.0 Modified iDope's code to work for tags.  Could add both features and a GUI but have no time for now
