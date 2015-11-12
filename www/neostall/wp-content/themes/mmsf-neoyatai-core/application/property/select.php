<?php

namespace property;

class select extends base {

	/**
	 * @var array
	 */
	public $options = [];

	protected function construct( $arg ) {
		/**
		 * Require 'model' argument
		 */
		if ( !array_key_exists( 'model', $arg ) ) {
			return false; // throw error
		}

		if ( !array_key_exists( 'options', $arg ) || !is_array( $arg['options'] ) ) {
			return false; // throw error
		}
		$this -> options = $arg['options'];

		return true;
	}

	public function filter( $value ) {
		if ( preg_match( '/\d/', $value ) ) {
			$value = absint( $value );
		}

		if ( false === $value || !array_key_exists( $value, $this -> options ) ) {
			return null; // throw error
		}

		return $value;
	}

}
