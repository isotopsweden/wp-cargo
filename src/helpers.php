<?php

/**
 * Get Cargo instance.
 *
 * @return \Isotop\Cargo\Cargo
 */
function cargo() {
	return \Isotop\Cargo\Cargo::instance();
}

/**
 * Determine if the given object is a JSON string or not.
 *
 * @param  mixed $obj
 *
 * @return false
 */
function cargo_is_json( $obj ) {
	return is_string( $obj ) && is_array( json_decode( $obj, true ) ) && json_last_error() === JSON_ERROR_NONE;
}
