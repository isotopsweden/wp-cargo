<?php

namespace Isotop\Tests\Cargo\Admin;

use Closure;
use Isotop\Cargo\Cargo;

class Menu_Test extends \WP_UnitTestCase {

	public function test_push_menus() {
		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\push_menus' )->bindTo( $cargo );

		$this->assertFalse( $fn() );

		$_POST = ['abc' => 'def'];
		$this->assertFalse( $fn() );

		$_SERVER['REQUEST_URI'] = 'http://example.org/wp-admin/nav-menus.php';
		$this->assertFalse( $fn() );

		$this->factory->term->create( ['taxonomy' => 'nav_menu'] );
		$this->assertFalse( $fn() );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		$this->assertTrue( $fn() );
	}
}
