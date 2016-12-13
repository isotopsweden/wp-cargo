<?php

namespace Isotop\Cargo\Admin;

/**
 * Handle post or taxonomies save.
 *
 * @param  int  $id
 * @param  null $post
 *
 * @return bool
 */
function save_post_or_taxonomy( $id, $post = null ) {
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

	$data = [];

	if ( is_null( $post ) ) {
		$term = get_term( $id, '' );

		if ( is_wp_error( $term ) ) {
			return false;
		}

		$term         = (object) $term;
		$term->meta   = get_term_meta( $term );
		$data['type'] = 'taxonomy';
		$data['data'] = $term;
	} else {
		$post         = (object) $post;
		$post->meta   = get_post_meta( $id );
		$data['type'] = 'post';
		$data['data'] = $post;
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
cargo()->action( 'save_post', __NAMESPACE__ . '\\save_post_or_taxonomy', 10, 2 );

// Handle taxonomies.
cargo()->action( 'created_term', __NAMESPACE__ . '\\save_post_or_taxonomy' );
cargo()->action( 'edit_term', __NAMESPACE__ . '\\save_post_or_taxonomy' );
