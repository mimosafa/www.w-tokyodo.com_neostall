<?php

namespace service;

/**
 *
 */
class api_init {

	const VERSION = '0.0';

	/**
	 *
	 */
	public function __construct( $args ) {
		if ( !empty( $args ) ) {
			$this -> args = $args;
			add_action( 'template_redirect', [ $this, 'template_redirect' ] );
		}
	}

	public function template_redirect() {
		echo '<h1>API <small>ver. ' . self::VERSION . '</small></h1>';
		echo '<pre>';
		var_dump( $this -> args );
		echo '</pre>';
		exit();
	}

}
