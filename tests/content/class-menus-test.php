<?php

namespace Isotop\Tests\Cargo\Content;

use Isotop\Cargo\Content\Menus;

class Menus_Test extends \WP_UnitTestCase {

	public function test_empty_menu() {
		$menus = new Menus();

		$this->assertFalse( $menus->to_json() );
	}

	public function test_real_menu() {
		$this->factory->term->create( ['taxonomy' => 'nav_menu'] );

		$menus = new Menus();

		$this->assertTrue( cargo_is_json( $menus->to_json() ) );
	}

	public function test_empty_get_data() {
		$menus = new Menus();
		$data = $menus->data();

		$this->assertEmpty( $data );
	}

	public function test_real_get_data() {
		$this->factory->term->create( ['taxonomy' => 'nav_menu'] );

		$menus = new Menus();
		$data = $menus->data();

		$this->assertSame( get_current_blog_id(), $data[0]['extra']['site_id'] );
	}
}
