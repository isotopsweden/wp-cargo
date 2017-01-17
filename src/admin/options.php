<?php

namespace Isotop\Cargo\Admin;

use Isotop\Cargo\Content\Options;

/**
 * Push options to external service.
 *
 * @return bool
 */
function push_options() {
	if ( empty( $_POST ) ) {
		return false;
	}

	// Create options content object.
	$data = new Options();

	// Send options to pusher.
	$res = $this->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

cargo()->action( 'admin_footer', __NAMESPACE__ . '\\push_options' );
cargo()->action( 'save_post', __NAMESPACE__ . '\\push_options', 999 );
cargo()->action( 'created_term', __NAMESPACE__ . '\\push_options', 999 );
cargo()->action( 'edit_term', __NAMESPACE__ . '\\push_options', 999 );
