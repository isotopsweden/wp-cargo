<?php

namespace Isotop\Tests\Cargo\Content;

use Isotop\Cargo\Content\Menu;

class Menu_Test extends \WP_UnitTestCase {

	public function test_fake_menu() {
		$menu = new Menu();

		$this->assertFalse( $menu->get_json() );
	}

	public function test_real_menu() {
		$this->factory->term->create( ['taxonomy' => 'nav_menu'] );

		$menu = new Menu();

		$this->assertTrue( cargo_is_json( $menu->get_json() ) );
	}

	public function test_fake_get_data() {
		$menu = new Menu();
		$data = $menu->get_data();

		$this->assertEmpty( $data );
	}

	public function test_real_get_data() {
		$this->factory->term->create( ['taxonomy' => 'nav_menu'] );

		$menu = new Menu();
		$data = $menu->get_data();

		$this->assertSame( get_current_blog_id(), $data[0]['extra']['site_id'] );
	}
}
