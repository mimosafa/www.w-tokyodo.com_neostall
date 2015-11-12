<?php

namespace mmsf;

class DOMLine extends \DOMDocument {

	private static $dom;

	private static $domCache = null;

	public function __construct() {
		self::$dom = null;
		$this -> init();
	}

	private function init() {
		if ( null == self::$dom ) {
			self::$dom = parent::__construct();
		}
		self::$domCache = null;
	}

	private function __set( $name, $value ) {

		static $_this =& self::$domCache;
		static $attr = null;
		static $text = null;

		if ( 'element' === $name ) {

			if ( null == $_this ) {

				$_this = $this -> createElement( $value );

			} else {
				/*
				if ( !is_null( $attr ) )
					$_this -> appendChild( $attr );
				self::$dom -> appendChild( $_this );
				$this -> init();
				*/
			}

		} else if ( 'attribute' === $name && !is_null( self::$domCache ) ) {

			$attr = $this -> createAttribute( $value );

		} else if ( 'text' === $name && !is_null( self::$domCache ) ) {}

	}



}