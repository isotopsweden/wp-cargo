<?php

namespace Isotop\Cargo\Contracts;

interface Content_Interface {

	/**
	 * Get JSON string for content data.
	 *
	 * @return mixed
	 */
	public function get_json();

	/**
	 * Is the content data valid?
	 *
	 * @return bool
	 */
	public function valid_data();
}
