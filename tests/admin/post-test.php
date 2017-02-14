<?php

namespace Isotop\Tests\Cargo\Admin;

use Closure;
use Isotop\Cargo\Cargo;
use Isotop\Cargo\Database\MySQL;
use Isotop\Cargo\Pusher\HTTP;
use function Isotop\Cargo\Admin\push_post_or_term;
use function Isotop\Cargo\Admin\push_delete_post_or_term;

class Post_Test extends \WP_UnitTestCase {

	public function test_push_post_or_term() {
		$this->assertFalse( push_post_or_term( null ) );

		$post_id = $this->factory->post->create();
		$this->assertFalse( push_post_or_term( $post_id ) );

		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\push_post_or_term' )->bindTo( $cargo );

		$_POST = ['hello' => 'world'];

		$cargo->set_config( ['database' => ['driver' => 'mysql'], 'pusher' => ['driver' => 'http']] );
		$db   = new MySQL( $cargo );
		$http = new HTTP( $cargo );

		$cargo->bind( 'driver.database.mysql', $db );
		$cargo->bind( 'driver.database.http', $http );

		$this->assertFalse( $fn( $post_id ) );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		$this->assertTrue( $fn( $post_id, get_post( $post_id ) ) );
	}

	public function test_push_delete_post_or_term() {
		$this->assertFalse( push_delete_post_or_term( null ) );

		$post_id = $this->factory->post->create();
		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\push_delete_post_or_term' )->bindTo( $cargo );

		$cargo->set_config( ['database' => ['driver' => 'mysql'], 'pusher' => ['driver' => 'http']] );
		$db   = new MySQL( $cargo );
		$http = new HTTP( $cargo );

		$cargo->bind( 'driver.database.mysql', $db );
		$cargo->bind( 'driver.database.http', $http );

		$this->assertFalse( $fn( $post_id ) );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		$this->assertTrue( $fn( $post_id ) );
	}
}
