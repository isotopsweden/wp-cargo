<?php

namespace Isotop\Tests\Cargo\Content;

use Isotop\Cargo\Content\User;

class User_Test extends \WP_UnitTestCase {

	public function test_empty_user() {
		$user_id = 0;
		$user    = new User( $user_id );

		$this->assertFalse( $user->to_json() );
	}

	public function test_real_user() {
		$user_id = $this->factory->user->create( ['role' => 'administrator'] );
		$user    = new User( $user_id );

		$this->assertTrue( cargo_is_json( $user->to_json() ) );
	}

	public function test_empty_get_data() {
		$user_id = 0;
		$user    = new User( $user_id );
		$data    = $user->data();

		$this->assertEmpty( $data );
	}

	public function test_real_get_data() {
		$user_id = $this->factory->user->create( ['role' => 'administrator'] );
		$user    = new User( $user_id );
		$data    = $user->data();

		$this->assertSame( $user_id, $data['user']['id'] );
		$this->assertSame( get_current_blog_id(), $data['extra']['site_id'] );
	}

	public function test_empty_get_type() {
		$user_id = 0;
		$user    = new User( $user_id );
		$type    = $user->type();

		$this->assertEmpty( $type );
	}

	public function test_real_get_type() {
		$user_id = $this->factory->user->create( ['role' => 'administrator'] );
		$user    = new User( $user_id );
		$type    = $user->type();

		$this->assertSame( 'user', $type );
	}
	public function test_real_get_action() {
		$user_id = $this->factory->user->create( ['role' => 'administrator'] );
		$user    = new User( $user_id );
		$action    = $user->action();

		$this->assertSame( 'update', $action );
	}
}
