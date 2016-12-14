<?php

// Require WordPress files.
require_once __DIR__ . '/src/admin/actions.php';

// Bootstrap Cargo.
cargo()->set_driver( 'database.mysql', '\\Isotop\\Cargo\\Database\\MySQL' );
cargo()->set_driver( 'pusher.http', '\\Isotop\\Cargo\\Pusher\\HTTP' );
cargo()->set_driver( 'runner.basic', '\\Isotop\\Cargo\\Runner\\Basic' );

// Bootstrap Cargo CLI.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'cargo', '\\Isotop\\Cargo\\CLI\\CLI' );
	WP_CLI::add_command( 'cargo runner', '\\Isotop\\Cargo\\CLI\\Commands\\Runner' );
}

// Set default configuration.
cargo()->set_config( [
	'database' => [
		'driver' => 'mysql'
	],
	'pusher'   => [
		'driver' => 'http'
	],
	'runner'   => [
		'driver' => 'basic'
	]
] );
