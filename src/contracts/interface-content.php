<?php

namespace Isotop\Cargo\Contracts;

interface Content_Interface {

	/**
	 * Get content data.
	 *
	 * @return mixed
	 */
	public function get_data();

	/**
	 * Get JSON string for content data.
	 *
	 * @return mixed
	 */
	public function get_json();
}
