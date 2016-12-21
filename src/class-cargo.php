<?php

namespace Isotop\Cargo;

use Frozzare\Tank\Container;
use Isotop\Cargo\Admin\Admin;
use Isotop\Cargo\Contracts\Driver_Interface;
use Closure;
use ReflectionClass;
use Exception;
use InvalidArgumentException;

class Cargo extends Container {

	/**
	 * The Cargo configuration.
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * The class instance.
	 *
	 * @var \Isotop\Cargo\Cargo
	 */
	protected static $instance;

	/**
	 * Get class instance.
	 *
	 * @return \Isotop\Cargo\Cargo
	 */
	public static function instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Add action hook that will work with Cargo.
	 *
	 * @param  string   $tag
	 * @param  callable $fn
	 * @param  int      $priority
	 * @param  int      $accepted_args
	 *
	 * @throws Exception When WordPress is not loaded.
	 *
	 * @return bool
	 */
	public function action( string $tag, callable $fn, int $priority = 10, int $accepted_args = 1 ) {
		$fn = Closure::bind( Closure::fromCallable( $fn ), $this );

		if ( ! function_exists( 'add_action' ) ) {
			throw new Exception( 'WordPress not loaded' );
		}

		return add_action( $tag, $fn, $priority, $accepted_args );
	}

	/**
	 * Add filter hook that will work with Cargo.
	 *
	 * @param  string   $tag
	 * @param  callable $fn
	 * @param  int      $priority
	 * @param  int      $accepted_args
	 *
	 * @throws Exception When WordPress is not loaded.
	 *
	 * @return bool
	 */
	public function filter( string $tag, callable $fn, int $priority = 10, int $accepted_args = 1 ) {
		$fn = Closure::bind( Closure::fromCallable( $fn ), $this );

		if ( ! function_exists( 'add_filter' ) ) {
			throw new Exception( 'WordPress not loaded' );
		}

		return add_filter( $tag, $fn, $priority, $accepted_args );
	}

	/**
	 * Get config value.
	 *
	 * @param  string     $key
	 * @param  mixed|null $default
	 * @param  array      $source
	 *
	 * @return mixed
	 */
	public function config( string $key, $default = null, array $source = [] ) {
		if ( isset( $source[$key] ) ) {
			return $source[$key];
		}

		$keys = explode( '.', $key );

		if ( empty( $source ) ) {
			$source = $this->config;
		}

		foreach ( $keys as $i => $k ) {
			if ( ! isset( $source[$k] ) ) {
				continue;
			}

			unset( $keys[$i] );

			if ( is_array( $source[$k] ) && count( $keys ) > 0 ) {
				return $this->config( implode( '.', $keys ), $default, $source[$k] );
			}

			return $source[$k];
		}

		return $default;
	}

	/**
	 * Set config by file or array.
	 *
	 * @param  string|mixed $config
	 */
	public function set_config( $config ) {
		if ( is_string( $config ) && file_exists( $config ) ) {
			$config = require_once $config;
		}

		if ( is_array( $config ) ) {
			$this->config = array_replace_recursive( $this->config, $config );
		}
	}

	/**
	 * Resolve the given type from the container.
	 *
	 * @param  string $id
	 * @param  array  $parameters
	 *
	 * @throws InvalidArgumentException If identifier is not bound.
	 *
	 * @return mixed
	 */
	public function make( $id, array $parameters = [] ) {
		try {
			return parent::make( $id, $parameters );
		}
		catch ( InvalidArgumentException $e ) {
			if ( $driver = $this->get_driver( $id ) ) {
				return $driver;
			}

			throw $e;
		}
	}

	/**
	 * Get driver.
	 *
	 * @param  string $type
	 *
	 * @throws Exception If driver for given type can't be found.
	 *
	 * @return mixed
	 */
	protected function get_driver( string $type ) {
		$driver = $this->config( sprintf( '%s.driver', $type ) );
		$key    = sprintf( 'driver.%s.%s', $type, $driver );

		if ( $this->bound( $key ) ) {
			return $this->make( $key );
		}

		throw new Exception( sprintf( '%s driver cannot be found', ucfirst( $type ) ) );
	}

	/**
	 * Set driver.
	 *
	 * @param string $key
	 * @param string $driver
	 */
	public function set_driver( string $key, string $driver ) {
		if ( ! class_exists( $driver ) ) {
			return;
		}

		$key = sprintf( 'driver.%s', $key );
		$rc  = new ReflectionClass( $driver );
		$obj = $rc->newInstanceArgs( [$this] );

		if ( $obj instanceof Driver_Interface === false ) {
			return;
		}

		$this->bind( $key, $obj );
	}
}
