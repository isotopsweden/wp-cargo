<?php

namespace Isotop\Tests\Cargo\Content;

use Isotop\Cargo\Content\Term;

class Term_Test extends \WP_UnitTestCase {

	public function test_empty_term() {
		$term_id = 0;
		$term    = new Term( $term_id );

		$this->assertFalse( $term->to_json() );
	}

	public function test_real_term() {
		$term_id = $this->factory->category->create();
		$term    = new Term( $term_id );

		$this->assertTrue( cargo_is_json( $term->to_json() ) );
	}

	public function test_empty_get_data() {
		$term_id = 0;
		$term    = new Term( $term_id );
		$data    = $term->data();

		$this->assertEmpty( $data );
	}

	public function test_real_get_data() {
		$term_id = $this->factory->category->create();
		$term    = new Term( $term_id );
		$data    = $term->data();

		$this->assertSame( get_current_blog_id(), $data['extra']['site_id'] );
	}

	public function test_empty_get_type() {
		$term_id = 0;
		$term    = new Term( $term_id );
		$type    = $term->type();

		$this->assertEmpty( $type );
	}

	public function test_real_get_type() {
		$term_id = $this->factory->category->create();
		$term    = new Term( $term_id );
		$type    = $term->type();

		$this->assertSame( 'term', $type );
	}
	public function test_real_get_action() {
		$term_id = $this->factory->category->create();
		$term    = new Term( $term_id );
		$action    = $term->action();

		$this->assertSame( 'update', $action );
	}
}
