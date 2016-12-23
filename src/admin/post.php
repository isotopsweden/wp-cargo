<?php

namespace Isotop\Cargo\Admin;

use Isotop\Cargo\Content\Post;
use Isotop\Cargo\Content\Term;

/**
 * Push post or taxonomies to external service.
 *
 * @param  int  $id
 * @param  null $post
 *
 * @return bool
 */
function push_post_or_taxonomy( $id, $post = null ) {
	// Check if there was a multisite switch before.
	if ( is_multisite() && ms_is_switched() ) {
		return false;
	}

	// Bail if id is empty.
	if ( empty( $id ) ) {
		return false;
	}

	// Bail if global http post is empty.
	if ( empty( $_POST ) ) {
		return false;
	}

	if ( is_null( $post ) ) {
		$data = new Term( $id );
	} else {
		$data = new Post( $id );
	}

	// Send post or taxonomy data to pusher.
	$res = $this->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

// Handle posts.
cargo()->action( 'save_post', __NAMESPACE__ . '\\push_post_or_taxonomy', 999, 2 );

// Handle taxonomies.
cargo()->action( 'created_term', __NAMESPACE__ . '\\push_post_or_taxonomy', 999 );
cargo()->action( 'edit_term', __NAMESPACE__ . '\\push_post_or_taxonomy', 999 );

/**
 * Modify preview link for both page and post.
 *
 * @param  string  $link
 * @param  WP_Post $post
 *
 * @return string
 */
function modify_preview_link( $link, $post ) {
	if ( empty( $_POST ) ) {
		return $link;
	}

	$args = [
		'preview' => true,
		'post_id' => $post->ID,
		'token'   => cargo_user_token(),
	];

	return add_query_arg( $args, home_url( '/' ) );
}

// Handle preview link.
cargo()->action( 'preview_page_link', __NAMESPACE__ . '\\modify_preview_link', 999, 2 );
cargo()->action( 'preview_post_link', __NAMESPACE__ . '\\modify_preview_link', 999, 2 );