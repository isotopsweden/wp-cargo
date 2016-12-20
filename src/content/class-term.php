<?php

namespace Isotop\Cargo\Content;

class Term extends Abstract_Content {

	/**
	 * Term constructor.
	 *
	 * @param int $id
	 */
	public function __construct( $id ) {
		// Create term object.
		$this->create( 'term', get_term( $id, '' ) );

		// Add meta data.
		$this->add( 'meta', get_term_meta( $id ) );

		// Add extra data.
		$this->add( 'extra', [
			'site_id' => get_current_blog_id()
		] );
	}
}
