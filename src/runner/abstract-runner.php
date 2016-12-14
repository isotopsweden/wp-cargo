<?php

namespace Isotop\Cargo\Runner;

use Isotop\Cargo\Cargo;
use Isotop\Cargo\Contracts\Runner_Interface;
use WP_Query;
use WP_Term_Query;

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
	 * Bootstrap the runner.
	 */
	public function bootstrap() {
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
			\WP_CLI::log( $message );
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
	 * Get all posts.
	 *
	 * @return array
	 */
	protected function posts() {
		return (array) ( new WP_Query( [
			'posts_per_page'         => - 1, // This time we like to fetch all posts.
			'post_type'              => 'any',
			'update_post_meta_cache' => false,
			'update_term_meta_cache' => false
		] ) )->posts;
	}

	/**
	 * Run all content task.
	 */
	abstract public function run_all();

	/**
	 * Run queued content task.
	 */
	abstract public function run_queue();

	/**
	 * Get all taxonomies.
	 *
	 * @return array
	 */
	protected function terms() {
		return (array) ( new WP_Term_Query( [
			'taxonomy'               => 'any',
			'hide_empty'             => false,
			'update_term_meta_cache' => false
		] ) )->terms;
	}
}
