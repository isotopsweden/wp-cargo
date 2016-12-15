<?php

namespace Isotop\Cargo\Content;

class Post extends Abstract_Content {

	/**
	 * Post constructor.
	 *
	 * @param mixed $post
	 */
	public function __construct( $post ) {
		if ( wp_is_post_revision( $post ) ) {
			return;
		}

		// Create post object.
		$this->create( 'post', get_post( $post ) );

		// Add meta data.
		$this->add( 'meta', get_post_meta( $post ) );

		// Add extra data.
		$this->add( 'extra', [
			'permalink' => get_permalink( $post )
		] );
	}
}
