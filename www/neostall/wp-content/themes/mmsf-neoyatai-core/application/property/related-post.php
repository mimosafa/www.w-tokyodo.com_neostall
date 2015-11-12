<?php

namespace property;

/**
 *
 */
class related_post extends base {

	/**
	 *
	 */
	protected function construct( $arg ) {
		/**
		 * Require 'model' argument
		 */
		if ( !array_key_exists( 'model', $arg ) ) {
			return false; // throw error
		}

		return true;
	}

	/**
	 *
	 */
	public function filter( $value ) {

		return $value;

		// throw error
	}

}
