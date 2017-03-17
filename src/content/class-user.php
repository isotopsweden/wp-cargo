<?php

namespace Isotop\Cargo\Content;

use WP_User;

class User extends Abstract_Content {

	/**
	 * User constructor.
	 *
	 * @param WP_User|int $user
	 */
	public function __construct( $user ) {
		if ( is_numeric( $user ) ) {
			$user = get_user_by( 'ID', $user );
		}

		if ( empty( $user ) ) {
			return;
		}

		if ( ! isset( $user->data->ID ) ) {
			return;
		}

		$this->create( 'user', [
			'token' => cargo_user_token( $user ),
			'user'  => [
				'id'   => intval( $user->data->ID ),
				'user' => $user->data->user_login
			]
		] );

		// Add extra data.
		$this->add( 'extra', [
			'site_id' => get_current_blog_id()
		] );
	}
}
