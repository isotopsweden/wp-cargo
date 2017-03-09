<?php

namespace Isotop\Tests\Cargo\Admin;

use Closure;
use Isotop\Cargo\Cargo;

class Menus_Test extends \WP_UnitTestCase {

	public function test_push_menus() {
		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\push_menus' )->bindTo( $cargo );

		$this->assertFalse( $fn() );

		$_POST = ['abc' => 'def'];
		$this->assertFalse( $fn() );

		$this->factory->term->create( ['taxonomy' => 'nav_menu'] );
		$this->assertFalse( $fn() );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		global $current_screen;
		$current_screen = \WP_Screen::get( 'admin_init' );

		$this->assertTrue( $fn() );

		$current_screen = null;
	}
}
