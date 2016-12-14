<?php

namespace Isotop\Cargo\Contracts;

interface Runner_Interface extends Driver_Interface {

	/**
	 * Bootstrap the runner.
	 */
	public function bootstrap();

	/**
	 * Run all content task.
	 */
	public function run_all();

	/**
	 * Run queued content task.
	 */
	public function run_queue();
}
