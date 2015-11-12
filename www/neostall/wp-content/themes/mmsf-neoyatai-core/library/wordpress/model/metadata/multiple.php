<?php

namespace wordpress\model\metadata;

/**
 *
 */
class multiple extends base {

	/**
	 * Overloading methods, __get, __set.
	 */

	/**
	 * $this -> name;
	 *
	 * @return array
	 */
	public function __get( $name ) {

		return $this -> get( $name );

	}

	/**
	 * $this -> name = 'value';
	 * If supplied array, custom fields will be whole updated, if not, will be added. 
	 *
	 * @param  unknown $value
	 * @return void
	 */
	public function __set( $name, $value ) {

		$exists = $this -> $name;

		if ( is_array( $value ) ) {
			if ( $adds = array_diff( $value, $exists ) ) {
				foreach ( $adds as $add ) {
					$this -> add( $name, $add );
				}
			}
			if ( $dels = array_diff( $exists, $value ) ) {
				foreach ( $dels as $del ) {
					if ( !$del ) {
						continue;
					}
					$this -> delete( $name, $del );
				}
			}
		} else if ( !in_array( $value, $exists ) ) {
			$this -> add( $name, $value );
		}

	}

}
