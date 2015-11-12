<?php

namespace wordpress\model\metadata;

/**
 *
 */
class ordered_multiple extends base {

	/**
	 * Overloading methods, __isset, __get, __set, and __unset.
	 */

	/**
	 *
	 */
	public function __isset( $name ) {
		$num = $this -> get( $name, true );
		if ( !preg_match( '/\A[1-9][0-9]*\z/', $num ) ) {
			// throw error
		} else {
			$num = intval( $num );
		}
		//
	}

	/**
	 * $this -> name;
	 *
	 * @return array
	 */
	public function __get( $name ) {

		$num = (int) $this -> get( $name, true );
		if ( !$num ) {
			return null;
		}

		$return = [];
		for ( $i = 0; $i < $num; $i++ ) { 
			$key = "{$name}_{$i}";
			$return[] = $this -> get( $key, true );
		}

		return $return;

	}

	/**
	 * $this -> name = 'value';
	 *
	 * @return void
	 */
	public function __set( $name, $value ) {

		$exists = $this -> $name;
		$num = count( $exists );

		if ( is_array( $value ) ) {
			//
		} else if ( !in_array( $value, $exists ) ) {
			$num++;
			$key = "{$name}_{$num}";
			$this -> add( $key, $value );
			$this -> update( $name, $num );
		}

	}

	/**
	 *
	 */
	public function __unset( $name ) {
		//
	}

}
