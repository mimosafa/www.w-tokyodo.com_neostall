<?php

namespace property;

/**
 *
 */
class array_legacy extends base {

	private $_structure = [];

	protected function construct( $arg ) {

		return true;

	}

	public function filter( $value ) {

		return $value;

	}

}
