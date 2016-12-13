<?php

namespace Isotop\Tests\Cargo\Admin;

use Closure;
use Isotop\Cargo\Cargo;
use Isotop\Cargo\Database\MySQL;
use Isotop\Cargo\Pusher\HTTP;
use function Isotop\Cargo\Admin\save_post_or_taxonomy;

class Actions_Test extends \WP_UnitTestCase {

	public function test_save_post_or_taxonomy() {
		$this->assertFalse( save_post_or_taxonomy( null ) );

		$post_id = $this->factory->post->create();
		$this->assertFalse( save_post_or_taxonomy( $post_id ) );

		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\save_post_or_taxonomy' )->bindTo( $cargo );

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

		$this->assertTrue( $fn( $post_id ) );
	}
}
