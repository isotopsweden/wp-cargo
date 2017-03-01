<?php

namespace Isotop\Cargo\Content;

use Isotop\Cargo\Contracts\Content_Interface;

abstract class Abstract_Content implements Content_Interface {

	/**
	 * Content data.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Set action.
	 *
	 * @var string
	 */
	protected $action = 'update';

	/**
	 * Content type.
	 *
	 * @var string
	 */
	protected $type;

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
	 * Cast string value.
	 *
	 * @param  mixed $str
	 *
	 * @return mixed
	 */
	protected function cast_string( $str ) {
		if ( ! is_string( $str ) ) {
			return $str;
		}

		if ( is_numeric( $str ) ) {
			return $str == (int) $str ? (int) $str : (float) $str;
		}

		if ( $str === 'true' || $str === 'false' ) {
			return $str === 'true';
		}

		return maybe_unserialize( $str );
	}

	/**
	 * Create content data.
	 *
	 * @param string $type
	 * @param mixed  $data
	 */
	public function create( string $type, $data ) {
		$this->type = $type;
		$this->data = (array) $data;
	}

	/**
	 * Get content data.
	 *
	 * @return array
	 */
	public function data() {
		return $this->data;
	}

	/**
	 * Prepare meta value.
	 *
	 * @param  int   $object_id
	 * @param  array $meta
	 *
	 * @return mixed
	 */
	protected function prepare_meta( $object_id, $meta ) {
		if ( ! is_array( $meta ) ) {
			return [];
		}

		$result = [];

		foreach ( $meta as $slug => $value1 ) {
			if ( is_array( $value1 ) && count( $value1 ) === 1 ) {
				$value1 = $value1[0];
			}

			/**
			 * Modify meta value.
			 *
			 * @param  int    $object_id
			 * @param  string $slug
			 * @param  mixed  $value1
			 * @param  string $type
			 *
			 * @return mixed
			 */
			$value2 = apply_filters( 'cargo_prepare_meta_value', $object_id, $slug, $value1, $this->type );

			if ( is_null( $value2 ) ) {
				continue;
			}

			if ( $value1 === $value2 || ! is_array( $value2 ) ) {
				$value2 = $this->cast_string( $value2 );
				$type   = gettype( $value2 );

				if ( empty( $type ) ) {
					continue;
				}

				$value2 = [
					'slug'  => $slug,
					'title' => '',
					'type'  => $type,
					'value' => $value2
				];
			}

			$result[] = $value2;
		}

		return $result;
	}

	/**
	 * Set content action.
	 *
	 * @param  string $action
	 */
	public function set_action( string $action ) {
		$this->action = $action;
	}

	/**
	 * Get JSON string for content data.
	 *
	 * @return mixed
	 */
	public function to_json() {
		if ( ! $this->valid_data() ) {
			return false;
		}

		/**
		 * Modify content data before it's encoded to JSON.
		 *
		 * @param  array $data
		 * @param  array $type
		 *
		 * @return array
		 */
		$data = apply_filters( 'cargo_modify_content_data', $this->data, $this->type );

		return wp_json_encode( [
			'action' => $this->action,
			'type'   => $this->type,
			'data'   => $data
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
