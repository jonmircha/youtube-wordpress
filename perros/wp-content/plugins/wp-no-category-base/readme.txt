=== WP No Category Base ===
Contributors: iDope
Donate link: http://efextra.com/
Tags: categories, category base, category, permalinks, permastruct, links, seo, cms
Requires at least: 3.1
Tested up to: 3.4.1
Stable tag: trunk

This plugin removes the mandatory 'Category Base' from your category permalinks (e.g. `/category/my-category/` to `/my-category/`).

== Description ==

As the name suggests this plugin will completely remove the mandatory 'Category Base' from your category permalinks ( e.g. `myblog.com/category/my-category/` to `myblog.com/my-category/` ).

The plugin requires no setup or modifying core wordpress files and will not break any links. It will also take care of redirecting your old category links to the new ones.

= Features =

1. Better and logical permalinks like `myblog.com/my-category/` and `myblog.com/my-category/my-post/`.
2. Simple plugin - barely adds any overhead.
3. Works out of the box - no setup needed.
4. No need to modify wordpress files.
5. Doesn't require other plugins to work.
6. Compatible with sitemap plugins.
7. Works with multiple sub-categories.
8. Works with WordPress Multisite.
9. Redirects old category permalinks to the new ones (301 redirect, good for SEO).

== Installation ==

1. Upload `no-category-base.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to access your categories via http://myblog.com/my-category/

Note: If you have Wordpress 2.7 or above you can simply go to 'Plugins' &gt; 'Add New' in the Wordpress admin and search for "No Category Base" and install it from there.

== Frequently Asked Questions ==

= Why should I use this plugin? =

Use this plugin if you want to get rid of Wordpress' "Category base" completely. The normal behaviour of Wordpress is to add '/category' to your category permalinks if you leave "Category base" blank in the Permalink settings. So your category links look like `myblog.com/category/my-category/`. With this plugin your category links will look like `myblog.com/my-category/` (or `myblog.com/my-category/sub-category/` in case of sub categories).

= Will it break any other plugins? =

As far as I can tell, no. I have been using this on several blogs for a while and it doesn't break anything.

= Won't this conflict with pages? =

Simply don't have a page and category with the same slug. Even if they do have the same slug it won't break anything, just the category will get priority (Say if a category and page are both 'xyz' then `myblog.com/xyz/` will give you the category). This can have an useful side-effect. Suppose you have a category 'news', you can add a page 'news' which will show up in the page navigation but will show the 'news' category.

= Can you add a feature X? =

Depends, if its useful enough and I have time for it.


== Screenshots ==

1. Look Ma, No Category Base!

== Changelog ==

= 1.1 =
* Support for WordPress 3.4.

= 1.0 =
* Support for WordPress 3.1 (no longer supports older version).
* Now even more efficient.

= 0.7 =
* Now the plugin will automatically redirect the old category permalinks to the new ones (without the help of any other plugin).

= 0.6 =
* The plugin will now update the rewrite rules when categories are added/edited.

= 0.5 =
* Fixed bug where it would override tags with the same name.

= 0.4 =
* Added proper support for category feed permalinks.
