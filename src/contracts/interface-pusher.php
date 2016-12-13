<?php

namespace Isotop\Cargo\Contracts;

interface Pusher_Interface extends Driver_Interface {

	/**
	 * Send data to receiver.
	 *
	 * @param  mixed $data
	 *
	 * @return mixed
	 */
	public function send( $data );
}
