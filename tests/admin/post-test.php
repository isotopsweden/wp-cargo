<?php

namespace Isotop\Tests\Cargo\Admin;

use Closure;
use Isotop\Cargo\Cargo;
use Isotop\Cargo\Database\MySQL;
use Isotop\Cargo\Pusher\HTTP;
use function Isotop\Cargo\Admin\modify_preview_link;
use function Isotop\Cargo\Admin\push_post_or_term;
use function Isotop\Cargo\Admin\push_delete_post_or_term;

class Post_Test extends \WP_UnitTestCase {

	public function test_push_post_or_term() {
		$this->assertFalse( push_post_or_term( null ) );

		$post_id = $this->factory->post->create();
		$this->assertFalse( push_post_or_term( $post_id ) );

		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\push_post_or_term' )->bindTo( $cargo );

		$_POST = ['hello' => 'world'];

		$cargo->set_config( ['database' => ['driver' => 'mysql'], 'pusher' => ['driver' => 'http']] );
		$db   = new MySQL( $cargo );
		$http = new HTTP( $cargo );

		$cargo->bind( 'driver.database.mysql', $db );
		$cargo->bind( 'driver.database.http', $http );

		$this->assertFalse( $fn( $post_id ) );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		$this->assertTrue( $fn( $post_id, get_post( $post_id ) ) );
	}

	public function test_modify_preview_link() {
		$this->assertEmpty( modify_preview_link( '', null ) );

		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\modify_preview_link' )->bindTo( $cargo );

		$this->assertEmpty( $fn( '', null ) );

		$post_id = $this->factory->post->create();
		$_POST = ['test' => true];

		$out = $fn( '', get_post( $post_id ) );
		$this->assertTrue( strpos( $out, 'preview=1' ) !== false );
		$this->assertTrue( strpos( $out, 'post_id=' . $post_id ) !== false );

		$cargo->set_config( [
			'preview' => [
				'url'    => '/_preview',
				'fields' => ['post_id' => 'ID', 'post_type']
			]
		] );

		$out = $fn( '', get_post( $post_id ) );
		$this->assertTrue( strpos( $out, 'preview=1' ) !== false );
		$this->assertTrue( strpos( $out, 'post_type=post' ) !== false );
		$this->assertTrue( strpos( $out, 'post_id=' . $post_id ) !== false );
	}

	public function test_push_delete_post_or_term() {
		$this->assertFalse( push_delete_post_or_term( null ) );

		$post_id = $this->factory->post->create();
		$cargo = Cargo::instance();
		$fn    = Closure::fromCallable( '\\Isotop\\Cargo\\Admin\\push_delete_post_or_term' )->bindTo( $cargo );

		$cargo->set_config( ['database' => ['driver' => 'mysql'], 'pusher' => ['driver' => 'http']] );
		$db   = new MySQL( $cargo );
		$http = new HTTP( $cargo );

		$cargo->bind( 'driver.database.mysql', $db );
		$cargo->bind( 'driver.database.http', $http );

		$this->assertFalse( $fn( $post_id ) );

		add_filter( 'pre_http_request', function () {
			return ['body' => '{"success":true}'];
		} );

		$this->assertTrue( $fn( $post_id ) );
	}
}
