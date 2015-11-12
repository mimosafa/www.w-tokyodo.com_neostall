<?php

namespace property;

/**
 *
 */
class date extends base {

	private $_format;

	protected function construct( $arg ) {
		/**
		 * Require 'model' argument
		 */
		if ( !array_key_exists( 'model', $arg ) ) {
			return false; // throw error
		}

		$this -> _format = array_key_exists( 'format', $arg ) && is_string( $arg['format'] )
			? $arg['format']
			: 'Y-m-d'
		;

		// ~
		return true;
	}

	/**
	 *
	 */
	public function filter( $value ) {
		if ( !$value ) {
			$value = null;
		} else {
			$value = date( $this -> _format, strtotime( $value ) );
		}
		
		return $value;
	}

}
