<?php

namespace wordpress\model\metadata;

/**
 *
 */
class single extends base {

	/**
	 * Overloading methods, __get, __set.
	 */

	/**
	 * $this -> name;
	 *
	 * @return string
	 */
	public function __get( $name ) {

		/**
		 *
		 */
		if ( current_user_can( 'edit_themes' ) ) {

			$values = $this -> get( $name );

			if ( 1 < count( $values ) ) {
				// throw error
			}

			return $values[0];

		} else {

			$value = $this -> get( $name, true );

		}

		return $value;

	}

	/**
	 * $this -> name = 'value';
	 *
	 * @return void
	 */
	public function __set( $name, $value ) {

		if ( is_array( $value ) ) {
			// throw error
		}

		$exists = $this -> $name;
		
		if ( $exists === $value ) {
			return;
		}

		$this -> update( $name, $value );
		
	}

}
