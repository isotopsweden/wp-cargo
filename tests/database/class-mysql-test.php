<?php

namespace Isotop\Tests\Cargo\Database;

use Isotop\Cargo\Database\MySQL;
use Isotop\Cargo\Cargo;

class MySQL_Test extends \PHPUnit_Framework_TestCase {

	public function test_all() {
		$db = new MySQL( Cargo::instance() );
		$this->assertEmpty( $db->all() );

		$db->save( json_encode( ['hello' => 'world'] ) );
		$this->assertEquals( json_encode( ['hello' => 'world'] ), $db->all()[0]->data );

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
