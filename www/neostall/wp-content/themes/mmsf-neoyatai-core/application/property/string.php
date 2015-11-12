<?php

namespace property;

/**
 *
 */
class string extends base {

	private $_multi_byte = false;

	protected function construct( $arg ) {
		/**
		 * Require 'model' argument
		 */
		if ( !array_key_exists( 'model', $arg ) ) {
			return false; // throw error
		}

		if ( array_key_exists( 'multibyte', $arg ) && true === $arg['multibyte'] ) {
			$this -> _multi_byte = true;
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
