<?php

namespace Isotop\Cargo\Admin;

use Isotop\Cargo\Content\Post;
use Isotop\Cargo\Content\Term;

/**
 * Push post or terms to external service.
 *
 * @param  int  $id
 * @param  null $post
 *
 * @return bool
 */
function push_post_or_term( $id, $post = null ) {
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
cargo()->action( 'save_post', __NAMESPACE__ . '\\push_post_or_term', 999, 2 );

// Handle terms.
cargo()->action( 'created_term', __NAMESPACE__ . '\\push_post_or_term', 999 );
cargo()->action( 'edited_term', __NAMESPACE__ . '\\push_post_or_term', 999 );

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
		'token'   => cargo_user_token()
	];

	// Fetch preview fields.
	$fields = $this->config( 'preview.fields', ['post_id' => 'ID'] );

	foreach ( $fields as $key => $value ) {
		if ( is_numeric( $key ) ) {
			$key = $value;
		}

		if ( isset( $post->$value ) ) {
			$args[$key] = $post->$value;
		}
	}

	// Create preview link.
	return add_query_arg( $args, $this->config( 'preview.url', home_url( '/' ) ) );
}

// Handle preview link.
cargo()->action( 'preview_page_link', __NAMESPACE__ . '\\modify_preview_link', 999, 2 );
cargo()->action( 'preview_post_link', __NAMESPACE__ . '\\modify_preview_link', 999, 2 );

/**
 * Push post to external service as when trash status is set or removed.
 *
 * @param  string  $new_status
 * @param  string  $old_status
 * @param  WP_Post $post
 *
 * @return bool
 */
function push_trash_post( $new_status, $old_status, $post ) {
	// Check if there was a multisite switch before.
	if ( is_multisite() && ms_is_switched() ) {
		return false;
	}

	// Bail if id is empty.
	if ( empty( $post ) ) {
		return false;
	}

	// Bail if new status or old status isn't trash.
	if ( $new_status !== 'trash' && $old_status !== 'trash' ) {
		return;
	}

	$data = new Post( $post );

	// If new status is trash we should add delete action to post data.
	if ( $new_status === 'trash' ) {
		$data->set_action( 'delete' );
	}

	// Send post data to pusher.
	$res = $this->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

// Handle post status.
cargo()->action( 'transition_post_status', __NAMESPACE__ . '\\push_trash_post', 999, 3 );

/**
 * Push post or term with a delete notice to external service.
 *
 * @param  int    $id
 * @param  int    $tt_id
 * @param  string $taxonomy
 *
 * @return bool
 */
function push_delete_post_or_term( $id, $tt_id = 0, $taxonomy = '' ) {
	// Check if there was a multisite switch before.
	if ( is_multisite() && ms_is_switched() ) {
		return false;
	}

	// Bail if id is empty.
	if ( empty( $id ) ) {
		return false;
	}

	if ( empty( $tt_id ) && empty( $taxonomy ) ) {
		$data = new Post( $id );
	} else {
		$data = new Term( $id );
	}

	// Set content action to delete.
	$data->set_action( 'delete' );

	// Send post or taxonomy data to pusher.
	$res = $this->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

// Handle posts.
cargo()->action( 'delete_post', __NAMESPACE__ . '\\push_delete_post_or_term', 999 );

// Handle terms.
cargo()->action( 'delete_term', __NAMESPACE__ . '\\push_delete_post_or_term', 999, 3 );
