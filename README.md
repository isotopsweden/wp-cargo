# Cargo [![Build Status](https://travis-ci.org/isotopsweden/wp-cargo.svg?branch=master)](https://travis-ci.org/isotopsweden/wp-cargo)

> Requires PHP 7.1 and WordPress 4.6

Cargo will push content to other services.

## Installation

```
composer require isotopsweden/wp-cargo
```

## Usage

Example configuration:

```php
/**
 * Set Cargo config.
 */
cargo()->set_config( [
	'database' => [
		'driver' => 'mysql',
		'mysql'  => [
			'table' => 'wp_cargo'
		]
	],
	'pusher'   => [
		'driver' => 'http',
		'http'   => [
			'url' => 'http://localhost:9988'
		]
	]
] );
```

## License

MIT © Isotop
