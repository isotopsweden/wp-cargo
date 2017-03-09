<?php

namespace Isotop\Cargo\Content;

class Options extends Abstract_Content {

	/**
	 * Content type.
	 *
	 * @var string
	 */
	protected $type = 'options';

	/**
	 * Options constructor.
	 */
	public function __construct() {
		$slugs  = cargo()->config( 'content.options', [] );
		$slugs  = is_array( $slugs ) ? $slugs : [];

		// Create options data.
		$options = [];
		foreach ( $slugs as $slug ) {
			if ( $meta = $this->prepare_meta( 0, [$slug => get_option( $slug )] ) ) {
				$options[] = $meta[0];
			}
		}

		// Bail if empty options array.
		if ( empty( $options ) ) {
			return;
		}

		// Add options data.
		$this->add( 'options', $options );

		// Add extra data.
		$this->add( 'extra', [
			'site_id' => get_current_blog_id()
		] );
	}
}
