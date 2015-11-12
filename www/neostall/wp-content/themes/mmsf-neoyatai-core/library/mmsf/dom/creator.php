<?php

namespace mmsf\dom;

/**
 *
 */
require_once TEMPLATEPATH . '/library/wakisuke/Decoder.php';

/**
 *
 */
class creator extends \wakisuke\Decoder {

	/**
	 * @var array
	 */
	private static $domArray = [];

	/**
	 * @var array for cache
	 */
	private static $domCache = [];


	private function init() {
		self::$domArray = self::$domCache = [];
		return $this;
	}


	private function __set( $name, $value ) {

		if ( 'tag' !== $name && empty( self::$domCache ) ) {
			return; // throw error
		}

		static $singleValAttr = [
			'id', 'type', 'value', 'name'
		];

		if ( 'tag' === $name && !array_key_exists( 'element', self::$domCache ) ) {

			self::$domCache['element'] = $value;

		} else if ( in_array( $name, $singleValAttr ) && !array_key_exists( $name, self::$domCache['attribute'] ) ) {

			self::$domCache['attribute'][$name] = $val;

		}

	}















	/**
	 *
	 */
	public function __construct() {
		self::$domArray = self::$domCache = [];
	}

	/**
	 *
	 */
	public function create( $tag ) {
		if ( !empty( self::$domCache ) ) {
			return $this; // throw error
		}
		self::$domCache['element'] = $tag;
		return $this;
	}

	public function cache() {
		if ( empty( self::$domCache ) ) {
			return $this; // throw error
		}
		self::$domArray[$this -> index] = self::$domCache;
		self::$domCache = [];
		$this -> index++;
	}

	public function add() {
		if ( empty( self::$domCache ) ) {
			return $this; // throw error
		}
	}

	/**
	 * Overload method __set
	 *
	 * @access private
	 * @param  string $name
	 * @param  string|array $value
	 */
	private function __set( $name, $value ) {

		if ( empty( self::$domCache ) ) {
			return false; // throw error
		}

		$attr =& self::$domCache['attribute'];

		static $singleVal = [
			'id', 'type', 'name', 'value'
		];
		if ( in_array( $name, $singleVal ) && array_key_exists( $name, $attr ) ) {
			return;
		}

		$attr[$name] = esc_attr( $value );
	}

	/**
	 *
	 */
	public function attr( $name, $value ) {
		$this -> $name = $value;
		return $this;
	}

	public function text( $text ) {
		if ( empty( self::$domCache ) ) {
			return false; // throw error
		}
		self::$domCache['text'] = $text;
		return $this;
	}

	/**
	 *
	 */
	public function init() {
		if ( !empty( self::$domCache ) ) {
			self::$domArray[] = self::$domCache;
		}
		self::$domCache = [];
		return $this;
	}

	/**
	 *
	 */
	public function __toString() {
		echo $this -> decode();
	}

	/**
	 * @uses \wakisuke\Decoder
	 */
	public function decode() {
		if ( empty( self::$domArray ) ) {
			return '';
		}
		$decoder = new \wakisuke\Decoder();
		return $decoder -> getArrayToHtmlString( self::$domArray );
	}

}
