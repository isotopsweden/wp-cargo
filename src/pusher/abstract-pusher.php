<?php

namespace Isotop\Cargo\Pusher;

use Isotop\Cargo\Cargo;
use Isotop\Cargo\Contracts\Pusher_Interface;

abstract class Abstract_Pusher implements Pusher_Interface {

	/**
	 * Cargo instance.
	 *
	 * @var \Isotop\Cargo\Cargo
	 */
	protected $cargo;

	/**
	 * Pusher Constructor.
	 *
	 * @param \Isotop\Cargo\Cargo $cargo
	 */
	public function __construct( Cargo $cargo ) {
		$this->cargo = $cargo;
	}

	/**
	 * Convert data to JSON or empty string.
	 *
	 * @param  mixed $data
	 *
	 * @return string
	 */
	protected function to_json( $data ) {
		$data = is_string( $data ) ? $data : wp_json_encode( $data );
		$data = is_string( $data ) ? $data : '';

		return $data;
	}

	/**
	 * Save data.
	 *
	 * @param mixed $data
	 */
	protected function save( $data ) {
		$data = $this->to_json( $data );

		if ( empty( $data ) ) {
			return;
		}

		$this->cargo->make( 'database' )->save( $data );
	}
}
