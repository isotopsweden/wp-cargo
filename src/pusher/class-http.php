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

		$args = array_merge( [
			'headers' => [
				'Content-Type' => 'application/json; charset=utf-8'
			],
			'body'    => $this->to_json( $data ),
			'method'  => 'post',
			'timeout' => 30
		], $options );

		$res = wp_remote_request( $options['url'], $args );

		if ( is_wp_error( $res ) ) {
			$this->save( $args['body'] );

			return $res;
		}

		$body = (array) json_decode( $res['body'] );

		if ( empty( $body ) ) {
			return false;
		}

		return $body['success'];
	}
}
