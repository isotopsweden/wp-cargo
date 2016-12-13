<?php

namespace Isotop\Tests\Cargo\Pusher;

use Isotop\Cargo\Cargo;
use Isotop\Cargo\Database\MySQL;
use Isotop\Cargo\Pusher\HTTP;

class HTTP_Test extends \PHPUnit_Framework_TestCase {

	public function test_send_failed() {
		$cargo = Cargo::instance();
		$cargo->set_config( ['database' => ['driver' => 'mysql']] );
		$db   = new MySQL( $cargo );
		$http = new HTTP( $cargo );

		$cargo->bind( 'driver.database.mysql', $db );

		$this->assertTrue( is_wp_error( $http->send( json_encode( ['hello' => 'world'] ) ) ) );

		$db->clear();
	}

	public function test_send_success() {
		$cargo = Cargo::instance();
		$cargo->set_config( ['database' => ['driver' => 'mysql']] );
		$db   = new MySQL( $cargo );
		$http = new HTTP( $cargo );

		$cargo->bind( 'driver.database.mysql', $db );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		$this->assertTrue( $http->send( json_encode( ['hello' => 'world'] ) ) );

		$db->clear();
	}
}
