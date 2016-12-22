<?php

namespace Isotop\Tests\Cargo\Admin;

use Closure;
use Isotop\Cargo\Cargo;

class User_Test extends \WP_UnitTestCase {

	public function test_push_login() {
		$user_id = 0;
		$cargo   = Cargo::instance();
		$fn      = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\push_login' )->bindTo( $cargo );

		$this->assertFalse( $fn( $user_id, null ) );

		$user_id = $this->factory->user->create( ['role' => 'administrator'] );

		$this->assertFalse( $fn( $user_id, null ) );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		$this->assertTrue( $fn( $user_id, get_user_by( 'ID', $user_id ) ) );
	}
}
