<?php

namespace Isotop\Cargo\Admin;

use Isotop\Cargo\Content\User;

/**
 * Push login details to external details.
 *
 * @param  int      $user_id
 * @param  \WP_User $user
 *
 * @return bool
 */
function push_login( $user_id, $user ) {
	$data = new User( $user );

	// Send menu to pusher.
	$res = cargo()->make( 'pusher' )->send( $data );

	// Handle error.
	if ( is_wp_error( $res ) ) {
		return false;
	}

	return $res;
}

cargo()->action( 'wp_login', __NAMESPACE__ . '\\push_login', 10, 2 );
