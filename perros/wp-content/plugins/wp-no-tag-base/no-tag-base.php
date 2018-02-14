<?php
/*
Plugin Name: WP-No-Tag-Base
Plugin URI: http://www.wordimpress.com/plugins/wordpress-no-tag-base-plugin/
Description: Removes 'tag' from your WordPress tag permalinks without complicated .htaccess file configurations or any other code.  Simply install this plugin and watch your "tag"-based permalinks effectively disappear.  Takes care of redirects for you as well.
Version: 1.2.4
Author: Devin Walker
Author URI: http://imdev.in/
*/

/*  Copyright 2012

    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License as published by

    the Free Software Foundation; either version 2 of the License, or

    (at your option) any later version.

    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Refresh rules on activation/deactivation/tag changes
register_activation_hook( __FILE__, 'no_tag_base_refresh_rules' );

add_action( 'created_post_tag', 'no_tag_base_refresh_rules' );

add_action( 'edited_post_tag', 'no_tag_base_refresh_rules' );

add_action( 'delete_post_tag', 'no_tag_base_refresh_rules' );

function no_tag_base_refresh_rules() {

	global $wp_rewrite;

	$wp_rewrite->flush_rules();

}

register_deactivation_hook( __FILE__, 'no_tag_base_deactivate' );

function no_tag_base_deactivate() {

	remove_filter( 'tag_rewrite_rules', 'no_tag_base_rewrite_rules' );

	no_tag_base_refresh_rules();

}

// Remove tag base permastruct
add_action( 'init', 'no_tag_base_permastruct' );

function no_tag_base_permastruct() {

	global $wp_rewrite, $wp_version;

	if ( version_compare( $wp_version, '3.4', '<' ) ) {

		// For pre-3.4 support

		$wp_rewrite->extra_permastructs['post_tag'][0] = '%post_tag%';

	} else {

		$wp_rewrite->extra_permastructs['post_tag']['struct'] = '%post_tag%';

	}

}

// Add our custom tag rewrite rules
add_filter( 'tag_rewrite_rules', 'no_tag_base_rewrite_rules' );

function no_tag_base_rewrite_rules( $tag_rewrite ) {

	$tag_rewrite = array();

	$tags = get_tags( array( 'hide_empty' => false ) );

	foreach ( $tags as $tag ) {

		$tag_nicename = $tag->slug;

		if ( $tag->parent == $tag_id ) {

			$tag->parent = 0;

		}

		//the magic

		$tag_rewrite[ '(' . $tag_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$' ] = 'index.php?tag=$matches[1]&feed=$matches[2]';

		$tag_rewrite[ '(' . $tag_nicename . ')/page/?([0-9]{1,})/?$' ] = 'index.php?tag=$matches[1]&paged=$matches[2]';

		$tag_rewrite[ '(' . $tag_nicename . ')/?$' ] = 'index.php?tag=$matches[1]';

	}

	// Redirect support from Old Category Base
	global $wp_rewrite;
	$old_tag_base                            = get_option( 'tag_base' ) ? get_option( 'tag_base' ) : 'tag';
	$old_tag_base                            = trim( $old_tag_base, '/' );
	$tag_rewrite[ $old_tag_base . '/(.*)$' ] = 'index.php?tag_redirect=$matches[1]';

	return $tag_rewrite;

}


// Add 'tag_redirect' query variable
add_filter( 'query_vars', 'no_tag_base_query_vars' );

function no_tag_base_query_vars( $public_query_vars ) {

	$public_query_vars[] = 'tag_redirect';

	return $public_query_vars;

}


// Redirect if 'tag_redirect' is set

add_filter( 'request', 'no_tag_base_request' );

function no_tag_base_request( $query_vars ) {

	if ( isset( $query_vars['tag_redirect'] ) ) {

		$tag = user_trailingslashit( $query_vars['tag_redirect'], 'post_tag' );

		$taglink = trailingslashit( get_option( 'home' ) ) . $tag;

		status_header( 301 );

		header( "Location: $taglink" );

		exit();

	}

	return $query_vars;

}