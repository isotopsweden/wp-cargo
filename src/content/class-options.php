<?php

namespace Isotop\Cargo\Content;

class Options extends Abstract_Content {

	/**
	 * Options constructor.
	 */
	public function __construct() {
		$slugs  = apply_filters( 'cargo_options', [] );
		$result = [];

		foreach ( $slugs as $slug ) {
			$result[] = $this->create_item( $slug );
		}

		$this->create( 'options', $result );
	}

	/**
	 * Create option item.
	 *
	 * @param  string $slug
	 *
	 * @return array
	 */
	protected function create_item( $slug ) {
		return [
			'slug'  => $slug,
			'value' => get_option( $slug ),
			'extra' => [
				'site_id' => get_current_blog_id()
			]
		];
	}
}
