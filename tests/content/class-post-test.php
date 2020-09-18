<?php

namespace Isotop\Tests\Cargo\Content;

use Isotop\Cargo\Content\Post;

class Post_Test extends \WP_UnitTestCase {

	public function test_empty_post() {
		$post_id = 0;
		$post    = new Post( $post_id );

		$this->assertFalse( $post->to_json() );
	}

	public function test_real_post() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );

		$this->assertTrue( cargo_is_json( $post->to_json() ) );
	}

	public function test_real_post_modify() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );

		add_filter( 'cargo_modify_content_data', function ( $data ) {
			return [];
		} );

		$this->assertSame( '{"action":"update","type":"post","data":[]}', $post->to_json() );
	}

	public function test_action() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );

		add_filter( 'cargo_modify_content_data', function ( $data ) {
			return [];
		} );

		$post->set_action( 'delete' );

		$this->assertSame( '{"action":"delete","type":"post","data":[]}', $post->to_json() );
	}

	public function test_empty_get_data() {
		$post_id = 0;
		$post    = new Post( $post_id );
		$data    = $post->data();

		$this->assertEmpty( $data );
	}

	public function test_real_get_data() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );
		$data    = $post->data();

		$this->assertSame( get_permalink( $post_id ), $data['extra']['permalink'] );
		$this->assertSame( get_current_blog_id(), $data['extra']['site_id'] );
	}

	public function test_empty_get_type() {
		$post_id = 0;
		$post    = new Post( $post_id );
		$type    = $post->type();

		$this->assertEmpty( $type );
	}

	public function test_real_get_type() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );
		$type    = $post->type();

		$this->assertSame( \get_post_type( $post_id ), $type );
	}

	public function test_real_get_action() {
		$post_id = $this->factory->post->create();
		$post    = new Post( $post_id );
		$action  = $post->action();

		$this->assertSame( 'update', $action );
	}

	public function test_real_change_action_and_get_action() {
		$expected_action = 'delete';
		$post_id         = $this->factory->post->create();
		$post            = new Post( $post_id );
		$post->set_action( $expected_action );
		$action = $post->action();

		$this->assertSame( $expected_action, $action );
	}

}
