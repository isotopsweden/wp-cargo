<?php

namespace Isotop\Cargo\CLI\Commands;

use Isotop\Cargo\CLI\Command;

class Run extends Command {

	/**
	 * Run Cargo Runner.
	 *
	 * [--all]
	 * : Try to push all content to the pusher.
	 *
	 * [--driver=<value>]
	 * : The driver to use. Default is 'basic'.
	 *
	 * @param array $args
	 * @param array $assoc_args
	 */
	public function __invoke( $args, $assoc_args ) {
		$driver = $assoc_args['driver'] ?? 'basic';

		if ( isset( $assoc_args['all'] ) ) {
			cargo()->make( sprintf( 'driver.runner.%s', $driver ) )->run_all();
		} else {
			cargo()->make( sprintf( 'driver.runner.%s', $driver ) )->run_queue();
		}
	}
}
