<?php

namespace Isotop\Cargo\Database;

use Isotop\Cargo\Cargo;
use Isotop\Cargo\Contracts\Database_Interface;

abstract class Abstract_Database implements Database_Interface {

	/**
	 * Cargo instance.
	 *
	 * @var \Isotop\Cargo\Cargo
	 */
	protected $cargo;

	/**
	 * Database Constructor.
	 *
	 * @param \Isotop\Cargo\Cargo $cargo
	 */
	public function __construct( Cargo $cargo ) {
		$this->cargo = $cargo;
		$this->bootstrap();
	}

	/**
	 * Bootstrap database.
	 */
	protected function bootstrap() {
	}

	/**
	 * Get items returns all items that exists in the database.
	 *
	 * @return array
	 */
	abstract public function all();

	/**
	 * Clear all items in database.
	 *
	 * @return mixed
	 */
	abstract public function clear();

	/**
	 * Delete item from database.
	 *
	 * @param  int $id
	 *
	 * @return mixed
	 */
	abstract public function delete( int $id );

	/**
	 * Save item with data to the database.
	 *
	 * @param  string $data
	 *
	 * @return bool
	 */
	abstract public function save( string $data );
}
