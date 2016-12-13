<?php

namespace Isotop\Cargo\Contracts;

interface Database_Interface extends Driver_Interface {

	/**
	 * Get items returns all items that exists in the database.
	 *
	 * @return array
	 */
	public function all();

	/**
	 * Clear database table.
	 *
	 * @return mixed
	 */
	public function clear();

	/**
	 * Delete item from database.
	 *
	 * @param  int $id
	 *
	 * @return mixed
	 */
	public function delete( int $id );

	/**
	 * Save item with data to the database.
	 *
	 * @param  string $data
	 *
	 * @return bool
	 */
	public function save( string $data );
}
