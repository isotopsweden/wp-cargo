<?php

namespace Isotop\Cargo\Admin;

use Isotop\Cargo\Content\Menu;

/**
 * Push menu to external service.
 *
 * @return bool
 */
function push_menu() {
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

cargo()->action( 'admin_init', __NAMESPACE__ . '\\push_menu' );
