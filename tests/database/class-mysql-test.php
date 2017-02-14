<?php

namespace Isotop\Tests\Cargo\Database;

use Isotop\Cargo\Database\MySQL;
use Isotop\Cargo\Cargo;

class MySQL_Test extends \PHPUnit_Framework_TestCase {

	public function test_all() {
		$db = new MySQL( Cargo::instance() );
		$this->assertTrue( is_array( $db->all() ) );

		$db->save( json_encode( ['hello' => 'world'] ) );
		$found = false;
		foreach ( $db->all() as $item ) {
			if ( json_encode( ['hello' => 'world'] ) === $item->data ) {
				$found = true;
				$this->assertTrue( true );
			}
		}

		if ( ! $found ) {
			$this->assertTrue( $found );
		}

		$db->clear();
	}

	public function test_delete() {
		$db = new MySQL( Cargo::instance() );
		$id = $db->save( json_encode( ['hello' => 'world'] ) );

		$this->assertTrue( $db->delete( $id ) );
	}

	public function test_save() {
		$db = new MySQL( Cargo::instance() );
		$this->assertNotFalse( $db->save( json_encode( ['hello' => 'world'] ) ) );

		$db->clear();
	}
}
