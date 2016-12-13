<?php

namespace Isotop\Cargo\Contracts;

interface Runner_Interface extends Driver_Interface {

	/**
	 * Bootstrap the runner.
	 */
	public function bootstrap();

	/**
	 * Run the task.
	 */
	public function run();
}
