<?php

namespace Isotop\Cargo\Admin;

use Isotop\Cargo\Content\Menu;
use Isotop\Cargo\Content\Post;
use Isotop\Cargo\Content\Term;

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
cargo()->action( 'save_post', __NAMESPACE__ . '\\save_post_or_taxonomy', 999, 2 );

// Handle taxonomies.
cargo()->action( 'created_term', __NAMESPACE__ . '\\save_post_or_taxonomy', 999 );
cargo()->action( 'edit_term', __NAMESPACE__ . '\\save_post_or_taxonomy', 999 );

/**
 * Handle menu save.
 *
 * @return bool
 */
function save_menu() {
	if ( empty( $_POST ) ) {
		return false;
	}

	if ( ! strpos( $_SERVER['REQUEST_URI'], 'nav-menus.php' ) ) {
		return false;
	}

	// Create menu content object.
	$data = new Menu();

	// Send menu to pusher.
	$res = $this->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

cargo()->action( 'admin_init', __NAMESPACE__ . '\\save_menu' );
