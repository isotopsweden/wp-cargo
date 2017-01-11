<?php

namespace Isotop\Tests\Cargo;

use Isotop\Cargo\Cargo;

class Cargo_Test extends \PHPUnit_Framework_TestCase {

	public function test_instance() {
		$cargo = new Cargo;
		$this->assertTrue( Cargo::instance() instanceof $cargo );
	}

	public function test_action() {
		$self  = $this;
		$cargo = new Cargo;
		$cargo->action( 'hello', function () use ( $self ) {
			$self->assertTrue( true );
		} );
		do_action( 'hello' );
	}

	public function test_filter() {
		$cargo = new Cargo;
		$cargo->filter( 'hello_name', function ( $name ) {
			return sprintf( 'Hello %s', $name );
		} );
		$this->assertSame( 'Hello Isotop', apply_filters( 'hello_name', 'Isotop' ) );
	}

	public function test_config() {
		$cargo = new Cargo;

		$this->assertFalse( $cargo->config( 'on', false ) );
		$cargo->set_config( ['on' => true] );
		$this->assertTrue( $cargo->config( 'on', false ) );

		$this->assertEmpty( $cargo->config( 'user.name', '' ) );
		$cargo->set_config( ['user' => ['name' => 'Isotop']] );
		$this->assertSame( 'Isotop', $cargo->config( 'user.name', '' ) );
	}

	public function test_make() {
		$cargo = new Cargo;

		$cargo->set_config( ['database' => ['driver' => 'mysql']] );

		try {
			$cargo->make( 'database' );
		}
		catch ( \Exception $e ) {
			$this->assertTrue( true );
		}

		$cargo->set_driver( 'database.mysql', '\\Isotop\\Cargo\\Database\\MySQL' );

		$this->assertTrue( is_object( $cargo->make( 'database' ) ) );
	}

	public function test_set_driver() {
		$cargo = new Cargo;

		try {
			$cargo->set_driver( 'database.mysql', false );
			$this->assertTrue( false );
		}
		catch ( \Exception $e ) {
			$this->assertTrue( true );
		}

		try {
			$cargo->set_driver( 'database.mysql', '\\Isotop\\Cargo\\Cargo' );
			$this->assertTrue( false );
		}
		catch ( \Exception $e ) {
			$this->assertTrue( true );
		}

		try {
			$cargo->set_driver( 'database.mysql', '\\Isotop\\Cargo\\Database\\MySQL' );
			$this->assertTrue( true );
		}
		catch ( \Exception $e ) {
			$this->assertTrue( false );
		}
	}
}
