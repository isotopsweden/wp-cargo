<?php

namespace Isotop\Cargo\Contracts;

use Isotop\Cargo\Cargo;

interface Driver_Interface {

	/**
	 * Driver Constructor.
	 *
	 * @param \Isotop\Cargo\Cargo $cargo
	 */
	public function __construct( Cargo $cargo );
}
