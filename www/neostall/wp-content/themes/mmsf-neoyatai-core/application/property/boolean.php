<?php

namespace property;

/**
 *
 */
class boolean extends base {

	private $_true_value = 1;
	private $_false_value = null;

	protected function construct( $arg ) {
		/**
		 * Require 'model' argument
		 */
		if ( !array_key_exists( 'model', $arg ) ) {
			return false; // throw error
		}

		// true val format
		if ( array_key_exists( 'true_value', $arg ) && $arg['true_value'] ) {
			$this -> _true_value = $arg['true_value'];
		}
		// false val format
		if ( array_key_exists( 'false_value', $arg ) ) {
			if ( '' === $arg['false_value'] || 0 === $arg['false_value'] ) {
				$this -> _false_value = $arg['false_value'];
			}
		}
		// ~
		return true;
	}

	/**
	 *
	 */
	public function filter( $value ) {
		if ( !$value ) {
			$value = $this -> _false_value;
		}
		// ~
		return $value;
	}

}
