<?php

/**
 * Plugin Name: Cargo
 * Plugin URI: https://github.com/isotopsweden/wp-cargo
 * Description: Cargo will push content to other services.
 * Author: Isotop
 * Author URI: https://www.isotop.se
 * Version: 1.0.0
 * Textdomain: wp-cargo
 */

// Load Composer autoload if it exists.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Bootstrap Cargo plugin.
 */
if ( ! apply_filters( 'dont_bootstrap_cargo_plugin', false ) ) {
	add_action( 'plugins_loaded', static function () {
		require_once __DIR__ . '/src/bootstrap.php';
	} );
}
