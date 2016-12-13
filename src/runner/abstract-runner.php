<?php

namespace Isotop\Cargo\Runner;

use Isotop\Cargo\Cargo;
use Isotop\Cargo\Contracts\Runner_Interface;

abstract class Abstract_Runner implements Runner_Interface {

	/**
	 * Cargo instance.
	 *
	 * @var \Isotop\Cargo\Cargo
	 */
	protected $cargo;

	/**
	 * Publisher Constructor.
	 *
	 * @param \Isotop\Cargo\Cargo $cargo
	 */
	public function __construct( Cargo $cargo ) {
		$this->cargo = $cargo;
	}

	/**
	 * Determine if WP CLI is used or not.
	 *
	 * @return bool
	 */
	protected function cli() {
		return defined( 'WP_CLI' ) && WP_CLI;
	}

	/**
	 * Log error to WP CLI.
	 *
	 * @param string $message
	 */
	protected function log_error( $message ) {
		if ( $this->cli() ) {
			\WP_CLI::error( $message );
		}
	}

	/**
	 * Log error to WP CLI.
	 *
	 * @param string $message
	 */
	protected function log_info( $message ) {
		if ( $this->cli() ) {
			\WP_CLI::info( $message );
		}
	}

	/**
	 * Log success to WP CLI.
	 *
	 * @param string $message
	 */
	protected function log_success( $message ) {
		if ( $this->cli() ) {
			\WP_CLI::success( $message );
		}
	}

	/**
	 * Bootstrap the runner.
	 */
	public function bootstrap() {
	}

	/**
	 * Run the task.
	 */
	abstract public function run();
}
