<?php

namespace module;

/**
 *
 */
trait template {

	public function __construct() {
		// ~
		add_action( 'template_redirect', [ $this, 'init' ] );
	}

	public function init() {
		//
	}

	private function _query_args() {
		if ( $args = $_SERVER['QUERY_STRING'] ) {
			//
		}
	}

}
