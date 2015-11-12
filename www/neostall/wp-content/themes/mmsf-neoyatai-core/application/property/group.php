<?php

namespace property;

/**
 *
 */
class group {

	public $name;
	public $label;
	public $properties = [];

	protected $_type = 'group';

	public function __construct( $var, $arg ) {

		$this -> name = $var;

		$this -> label = array_key_exists( 'label', $arg ) && is_string( $arg['label'] )
			? $arg['label']
			: ucwords( str_replace( [ '_', '-' ], ' ', trim( $var ) ) );
		;

		if ( array_key_exists( 'description', $arg ) ) {
			$this -> description = $arg['description'];
		}

	}

	/**
	 * Set elements' property.
	 * ~ This method will be used in class '\(domain)\properties'.
	 *
	 * @param string $name
	 * @param object $value \property\(type)
	 */
	public function set_element( $name, $value ) {
		if ( !$value || !is_object( $value ) )
			return;
		$this -> properties[$name] = $value;
	}

	public function getJson() {
		return json_encode( $this -> getArray() );
	}

	public function getArray() {
		$name = $this -> name;
		$label = $this -> label;
		if ( property_exists( $this, 'description' ) ) {
			$description = $this -> description;
		}
		$_type = $this -> _type;
		$_properties = [];
		foreach ( $this -> properties as $obj ) {
			$_properties[] = $obj -> getArray();
		}
		return compact( 'name', 'label', 'description', '_type', '_properties' );
	}

}
