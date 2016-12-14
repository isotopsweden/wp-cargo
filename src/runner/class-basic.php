<?php

namespace Isotop\Cargo\Runner;

class Basic extends Abstract_Runner {

	/**
	 * Run all content task.
	 */
	public function run_all() {
		foreach ( $this->posts() as $post ) {
			$post         = (object) $post;
			$post->meta   = get_post_meta( $post );
			$data['type'] = 'post';
			$data['data'] = $post;

			$this->cargo->make( 'pusher' )->send( $data );
			$this->log_info( sprintf( 'Pushed post with id %d to pusher', $post->ID ) );
		}

		foreach ( $this->terms() as $term ) {
			$term         = (object) $term;
			$term->meta   = get_term_meta( $term );
			$data['type'] = 'taxonomy';
			$data['data'] = $term;

			$this->cargo->make( 'pusher' )->send( $data );
			$this->log_info( sprintf( 'Pushed term with id %d to pusher', $term->ID ) );
		}

		$this->log_success( 'Done!' );
	}

	/**
	 * Run queued content task.
	 */
	public function run_queue() {
		foreach ( $this->cargo->make( 'database' )->all() as $item ) {
			if ( ! empty( $item->data ) && cargo_is_json( $item->data ) ) {
				// Bail if we don't get a true when we run through all items.
				if ( $res = $this->cargo->make( 'pusher' )->send( $item->data ) ) {
					if ( is_wp_error( $res ) ) {
						$this->log_error( $res );
					} else {
						$this->log_info( sprintf( 'Pushed item with id: %d', $item->id ) );
					}

					if ( ! $res ) {
						break;
					}
				}
			} else {
				$this->log_info( sprintf( 'Item with id %d is not pushed since data string is empty', $item->id ) );
			}

			// If it can be send delete it from the database.
			if ( ! $this->cargo->make( 'database' )->delete( $item->id ) ) {
				$this->log_error( sprintf( 'Failed to delete item with id: %d', $item->id ) );
			}
		}

		$this->log_success( 'Done!' );
	}
}
