<?php

namespace Isotop\Cargo\Content;

use Isotop\Cargo\Contracts\Content_Interface;

abstract class Abstract_Content implements Content_Interface {

	/**
	 * Content type.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Content data.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Create content data.
	 *
	 * @param string $type
	 * @param mixed  $data
	 */
	public function create( string $type, $data ) {
		$this->type = $type;
		$this->data = is_array( $data ) ? $data : [];
	}

	/**
	 * Add value to content data.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function add( string $key, $value ) {
		if ( ! $this->valid_data() ) {
			return;
		}

		$this->data[$key] = $value;
	}

	/**
	 * Get JSON string for content data.
	 *
	 * @return mixed
	 */
	public function get_json() {
		if ( ! $this->valid_data() ) {
			return false;
		}

		return wp_json_encode( [
			'type' => $this->type,
			'data' => $this->data
		] );
	}

	/**
	 * Is the content data valid?
	 *
	 * @return bool
	 */
	protected function valid_data() {
		return ! is_wp_error( $this->data ) && ! empty( $this->data );
	}
}
