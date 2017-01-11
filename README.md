# Cargo [![Build Status](https://travis-ci.org/isotopsweden/wp-cargo.svg?branch=master)](https://travis-ci.org/isotopsweden/wp-cargo)

> Requires PHP 7.1 and WordPress 4.6

Cargo will push content to other services. If the push failes the content JSON will be saved in the database for the queue.

## Installation

```
composer require isotopsweden/wp-cargo
```

## Usage

Example configuration:

```php
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

Configure what options that should be pushed:

```php
add_filter( 'cargo_options', function () {
  return ['siteurl', 'home'];
} );
```

Prepare meta fields, so you can hook into a custom fields plugin or something else:

```php
add_filter( 'cargo_prepare_meta_value', function( $object_id, $slug, $value, $type ) {
  return $value;
}, 10, 4 );
```

Modify content data before push:

```php
add_filter( 'cargo_modify_content_data', function ( $data, $type ) {
  return $data;
}, 10, 2 );
```

Run queue with WP-CLI:

```
wp cargo run
```

Push all content with WP-CLI:

```
wp cargo run --all
```

## License

MIT © Isotop
