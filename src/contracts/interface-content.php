<?php

namespace Isotop\Cargo\Contracts;

interface Content_Interface {

	/**
	 * Get content data.
	 *
	 * @return mixed
	 */
	public function data();

	/**
	 * Get content type.
	 *
	 * @return mixed
	 */
	public function type();

	/**
	 * Get content action.
	 *
	 * @return mixed
	 */
	public function action();

	/**
	 * Convert content data to JSON.
	 *
	 * @return mixed
	 */
	public function to_json();
}
