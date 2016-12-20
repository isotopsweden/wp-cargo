<?php

namespace Isotop\Cargo\Content;

class Post extends Abstract_Content {

	/**
	 * Post constructor.
	 *
	 * @param mixed $post
	 */
	public function __construct( $post ) {
		// Bail if a revision post.
		if ( wp_is_post_revision( $post ) ) {
			return;
		}

		// Bail if `nav_menu_item` post type.
		if ( get_post_type( $post ) === 'nav_menu_item' ) {
			return;
		}

		// Create post object.
		$this->create( 'post', get_post( $post ) );

		// Add meta data.
		$this->add( 'meta', get_post_meta( $post ) );

		// Add extra data.
		$this->add( 'extra', [
			'permalink' => get_permalink( $post ),
			'site_id'   => get_current_blog_id()
		] );
	}
}
