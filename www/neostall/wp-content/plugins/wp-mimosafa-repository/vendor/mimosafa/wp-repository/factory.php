<?php
namespace mimosafa\WP\Repository;

class Factory {

	private $prefix = '';

	public static function getInstance() {
		static $instance;
		return $instance ?: $instance = new self();
	}

	private function __construct() {}

	public function __set( $name, $var ) {
		if ( $name === 'prefix' ) {
			if ( is_string( $var ) && $var === sanitize_key( $var ) && strlen( $var ) < 17 ) {
				$this->prefix = $var;
			}
		}
	}

	public function __get( $name ) {
		if ( $name === 'prefix' ) { return $this->prefix; }
	}

	public function __unset( $name ) {
		if ( $name === 'prefix' ) { $this->prefix = ''; }
	}

	public function create_post_type( $name, $args ) {
		return new PostType( $name, $args, $this );
	}

	public function create_taxonomy( $name, $args ) {
		return new Taxonomy( $name, $args, $this );
	}

}
