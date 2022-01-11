<?php

namespace Isotop\Cargo\Admin;

use Isotop\Cargo\Content\Menus;

/**
 * Push menus to external service.
 *
 * @return bool
 */
function push_menus() {
	if ( empty( $_POST ) ) {
		return false;
	}

	if ( ! is_admin() ) {
		return false;
	}

	// Create menus content object.
	$data = new Menus();

	// Send menus to pusher.
	$res = cargo()->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

cargo()->action( 'wp_after_admin_bar_render', __NAMESPACE__ . '\\push_menus' );
cargo()->action( 'save_post', __NAMESPACE__ . '\\push_menus', 999 );
cargo()->action( 'created_term', __NAMESPACE__ . '\\push_menus', 999 );
cargo()->action( 'edit_term', __NAMESPACE__ . '\\push_menus', 999 );
