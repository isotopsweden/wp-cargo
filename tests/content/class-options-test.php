<?php

namespace Isotop\Tests\Cargo\Content;

use Isotop\Cargo\Content\Options;

class Options_Test extends \WP_UnitTestCase {

	public function test_empty_options() {
		cargo()->set_config( ['content' => ['options' => false]] );
		$options = new Options();

		$this->assertFalse( $options->to_json() );
	}

	public function test_real_options() {
		cargo()->set_config( ['content' => ['options' => ['siteurl', 'home']]] );

		$options = new Options();

		$this->assertTrue( cargo_is_json( $options->to_json() ) );
	}

	public function test_empty_get_data() {
		cargo()->set_config( ['content' => ['options' => false]] );
		$options = new Options();
		$data    = $options->data();

		$this->assertEmpty( $data );
	}

	public function test_real_get_data() {
		cargo()->set_config( ['content' => ['options' => ['siteurl', 'home']]] );

		$options = new Options();
		$data    = $options->data();

		$this->assertSame( get_current_blog_id(), $data[0]['extra']['site_id'] );
	}
}
