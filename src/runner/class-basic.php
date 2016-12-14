<?php

namespace Isotop\Cargo\Runner;

class Basic extends Abstract_Runner {

	/**
	 * Run the basic runner.
	 */
	public function run() {
		$pusher   = $this->cargo->make( 'pusher' );
		$database = $this->cargo->make( 'database' );

		foreach ( $database->all() as $item ) {
			if ( ! empty( $item->data ) && cargo_is_json( $item->data ) ) {
				// Bail if we don't get a true when we run through all items.
				if ( $res = $pusher->send( $item->data ) ) {
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
			if ( ! $database->delete( $item->id ) ) {
				$this->log_error( sprintf( 'Failed to delete item with id: %d', $item->id ) );
			}
		}

		$this->log_success( 'Done!' );
	}
}
