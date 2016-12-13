<?php

namespace Isotop\Cargo\Runner;

class Basic extends Abstract_Runner {

	/**
	 * Run the basic runner.
	 */
	public function run() {
		$pusher   = $this->cargo->make( 'pusher' );
		$database = $this->cargo->make( 'database' );

		foreach ( $database->get_items() as $item ) {
			// Bail if we don't get a true when we run through all items.
			if ( $res = $pusher->send( $item->data ) ) {
				if ( is_wp_error( $res ) ) {
					$this->log( $res );
				} else {
					$this->log( sprintf( 'Pushed item with id: %d', $item->id ) );
				}

				if ( ! $res ) {
					break;
				}
			}

			// If it can be send delete it from the database.
			if ( ! $database->delete( $item->id ) ) {
				$this->log( sprintf( 'Failed to delete item with id: %d', $item->id ) );
			}
		}
	}
}
