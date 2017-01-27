<?php

namespace Isotop\Cargo\Pusher;

class HTTP extends Abstract_Pusher {

	/**
	 * Send data to receiver.
	 *
	 * @param  mixed $data
	 *
	 * @return mixed
	 */
	public function send( $data ) {
		$options = $this->cargo->config( 'pusher.http', ['url' => ''] );
		$options = is_array( $options ) ? $options : ['url' => ''];

		$json = $this->to_json( $data );

		if ( empty( $json ) ) {
			return false;
		}

		$args = array_merge( [
			'headers' => [
				'Content-Type' => 'application/json; charset=utf-8'
			],
			'body'    => $json,
			'timeout' => 30
		], $options );

		$res = wp_remote_post( $options['url'], $args );

		if ( is_wp_error( $res ) ) {
			$this->save( $args['body'], $res );

			return $res;
		}

		$body = (array) json_decode( $res['body'] );

		if ( empty( $body ) ) {
			return false;
		}

		if ( $body['success'] ) {
			return true;
		}

		$this->save( $args['body'], $res );

		return false;
	}
}
