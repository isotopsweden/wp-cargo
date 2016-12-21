<?php

namespace Isotop\Cargo\Content;

class Term extends Abstract_Content {

	/**
	 * Term constructor.
	 *
	 * @param int $id
	 */
	public function __construct( $id ) {
		$term = get_term( $id, '' );

		// Bail if empty.
		if ( empty( $term ) ) {
			return;
		}

		// Bail if wp error.
		if ( is_wp_error( $term ) ) {
			return;
		}

		// Bail if `nav_menu` taxonomy.
		if ( $term->taxonomy === 'nav_menu' ) {
			return;
		}

		// Create term object.
		$this->create( 'term', $term );

		// Add meta data.
		$this->add( 'meta', $this->prepare_meta( $term->term_id, get_term_meta( $id ) ) );

		// Add extra data.
		$this->add( 'extra', [
			'site_id' => get_current_blog_id()
		] );
	}
}
