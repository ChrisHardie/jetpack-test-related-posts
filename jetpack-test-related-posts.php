<?php

/**
 * @package Jetpack_Test_Related_Posts
 * @version 1.0
 */
/*
Plugin Name: Jetpack Test Related Posts
Description: Enable Jetpack related posts for testing in a local development environment. Based on https://gist.github.com/delputnam/e6bb2f596f2995ae42f00b20957b5b07 by Del Putnam.
Author: Chris Hardie
Version: 1.0
*/

/**
 * Modify Jetpack module properties for testing/development
 *
 * @param array $mod Module to look at modifying the properties of.
 * @return array
 */
function jp_amp_module_override( $mod ) {
	switch ( $mod['name'] ) {
		case 'Related posts':
			$mod['requires_connection'] = false;
			break;
	}
	return $mod;
}
add_filter( 'jetpack_get_module', 'jp_amp_module_override' );

/**
 * Set the Jetpack 'enabled' option to true so we can test it.
 *
 * @param  array $options The list of Jetpack's Related Post options.
 * @return array          The modified options list.
 */
function jp_amp_relatedposts_options_override( $options ) {
	$options['enabled'] = true;
	return $options;
}
add_filter( 'jetpack_relatedposts_filter_options', 'jp_amp_relatedposts_options_override' );

/**
 * Filter the Jetpack related posts query to return a list of random post IDs
 * @param $hits
 * @return array
 */
function jp_random_related_posts_hits( $hits ) {

	$query = new WP_Query( array(
		'posts_per_page'      => 100,
		'fields'              => 'ids',
		'post_type'           => 'post',
		'no_found_rows'       => true,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => false,
		'meta_key'            => '_thumbnail_id',
	) );
	$post_ids = $query->posts;
	shuffle( $post_ids );
	$post_ids = array_splice( $post_ids, 0, 3 );

	foreach ( $post_ids as $id ) {
		$hits[] = array(
			'id' => $id,
		);
	}

	return $hits;

}

add_filter( 'jetpack_relatedposts_filter_hits', 'jp_random_related_posts_hits', 100 );
