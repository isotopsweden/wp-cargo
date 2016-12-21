<?php

namespace Isotop\Tests\Cargo\Content;

use Isotop\Cargo\Content\Post;

class Post_Test extends \WP_UnitTestCase {

	public function test_fake_post() {
		$post_id = 0;
		$post    = new Post( $post_id );

		$this->assertFalse( $post->get_json() );
	}

	public function test_real_post() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );

		$this->assertTrue( cargo_is_json( $post->get_json() ) );
	}

	public function test_fake_get_data() {
		$post_id = 0;
		$post    = new Post( $post_id );
		$data    = $post->get_data();

		$this->assertEmpty( $data );
	}

	public function test_real_get_data() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );
		$data    = $post->get_data();

		$this->assertSame( get_permalink( $post_id ), $data['extra']['permalink'] );
		$this->assertSame( get_current_blog_id(), $data['extra']['site_id'] );
	}
}
