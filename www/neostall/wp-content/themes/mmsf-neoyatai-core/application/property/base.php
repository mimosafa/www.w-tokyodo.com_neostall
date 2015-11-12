<?php

namespace property;

/**
 *
 */
abstract class base {

	public $name;

	public $label;

	protected $_type;

	protected $_model;

	protected $_required = false;

	protected $_multiple = false;

	protected $_readonly = false;

	protected $_unique   = false;

	/**
	 * @param  array $arg
	 * @return bool
	 */
	abstract protected function construct( $arg );

	/**
	 * @param  mixed
	 * @return mixed
	 */
	abstract public function filter( $value );

	/**
	 * @uses \mmsf\getDistalClassname()
	 *
	 * @param string $var
	 * @param array $arg
	 */
	public function __construct( $var, $arg ) {

		if ( !is_string( $var ) ) {
			return null; // throw error
		}

		/**
		 * type
		 */
		if ( !array_key_exists( 'type', $arg ) ) {
			return null; // throw error
		}

		/**
		 * Object constructer
		 */
		if ( !$this -> construct( $arg ) ) {
			return null; // throw error
		}

		$this -> name = $var;

		/**
		 * type
		 */
		$this -> _type = \mmsf\getDistalClassname( $this );

		/**
		 * model
		 */
		if ( array_key_exists( 'model', $arg ) ) {
			$this -> _model = $arg['model'];
		}

		if ( array_key_exists( 'required', $arg ) && true === $arg['required'] ) {
			$this -> _required = true;
		}

		if ( array_key_exists( 'multiple', $arg ) && true === $arg['multiple'] ) {
			$this -> _multiple = true;
		}

		if ( array_key_exists( 'readonly', $arg ) && true === $arg['readonly'] ) {
			$this -> _readonly = true;
		}

		if ( array_key_exists( 'unique', $arg ) && true === $arg['unique'] ) {
			$this -> _unique = true;
		}

		/**
		 * Property label
		 */
		$this -> label = array_key_exists( 'label', $arg ) && is_string( $arg['label'] )
			? $arg['label']
			: ucwords( str_replace( [ '_', '-' ], ' ', trim( $var ) ) );
		;

		/**
		 * Description
		 */
		if ( array_key_exists( 'description', $arg ) ) {
			$this -> description = $arg['description'];
		}
	}

	/**
	 *
	 */
	public function val( $value ) {
		$this -> value = $this -> filter( $value );
	}

	/**
	 *
	 */
	public function getModel() {
		return $this -> _model;
	}

	public function getArray() {
		return get_object_vars( $this );
	}

}
