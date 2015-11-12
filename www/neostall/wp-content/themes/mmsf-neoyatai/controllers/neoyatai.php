<?php

new Neoyatai();

class Neoyatai {

	private $domains = [];
	private static $level = 1; // WordPressのルートがサーバールート直下なら 0
	private static $admin_domain = 'wp-admin';

	function __construct() {
		$this -> get_domain();
		$this -> load_controller();
	}

	/**
	 * Dispatcher
	 */
	private function get_domain() {
		$array = explode( '?', $_SERVER['REQUEST_URI'] );
		$string = trim( $array[0], '/' );
		if ( !$string )
			return;
		$_domains = [];
		$params = explode( '/', $string );
		for ( $i = self::$level; $i < count( $params ); $i++ )
			$_domains[] = $params[$i];
		$this -> domains = $_domains;
	}

	private function load_controller() {
		$domains = $this -> domains;
		if ( !empty( $domains ) ) {
			$domain0 = $domains[0];
			if ( self::$admin_domain !== $domain0 ) {
				$controller = trailingslashit( get_stylesheet_directory() );
				$controller .= 'domain/' . $domain0 . '/controller.php';
				if ( is_readable( $controller ) )
					require_once( $controller );
			}
		}
	}

}
