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

	if ( ! strpos( $_SERVER['REQUEST_URI'], 'nav-menus.php' ) ) {
		return false;
	}

	// Create menus content object.
	$data = new Menus();

	// Send menus to pusher.
	$res = $this->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

cargo()->action( 'admin_footer', __NAMESPACE__ . '\\push_menus' );
