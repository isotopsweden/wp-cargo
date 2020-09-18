# Cargo [![Build Status](https://travis-ci.org/isotopsweden/wp-cargo.svg?branch=master)](https://travis-ci.org/isotopsweden/wp-cargo) [![codecov](https://codecov.io/gh/isotopsweden/wp-cargo/branch/master/graph/badge.svg)](https://codecov.io/gh/isotopsweden/wp-cargo) [![Maintainability](https://api.codeclimate.com/v1/badges/efa790c3c13f5a8ed770/maintainability)](https://codeclimate.com/github/isotopsweden/wp-cargo/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/efa790c3c13f5a8ed770/test_coverage)](https://codeclimate.com/github/isotopsweden/wp-cargo/test_coverage)

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
  'content' => [
    'options' => ['siteurl', 'home']
  ],
  'database' => [
    'driver' => 'mysql',
    'mysql'  => [
      'table' => 'wp_cargo'
    ]
  ],
  'preview' => [
    'fields' => ['post_id' => 'ID', 'post_type'],
    'url'    => 'http://example.com/_preview'
  ],
  'pusher'   => [
    'driver' => 'http',
    'http'   => [
      'url' => 'http://localhost:9988'
    ]
  ]
] );
```

Prepare meta fields, so you can hook into a custom fields plugin or something else:

```php
add_filter( 'cargo_prepare_meta_value', function ( $object_id, $slug, $value, $type ) {
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
