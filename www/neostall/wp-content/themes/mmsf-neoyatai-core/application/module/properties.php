<?php

namespace module;

/**
 * This is 'Trait',
 * must be used in '\(domain)\properties' class.
 *
 * @uses \wordpress\model\(model type)
 */
trait properties {

	private $_post;

	/**
	 *
	 */
	private $_data = [];

	/**
	 *
	 */
	private static $models = [
		'metadata' => null,
		'taxonomy' => null,
	];

	/**
	 * Properties constructor.
	 *
	 * @param object|int WP_Post object or post_id
	 */
	public function __construct( $post = null ) {
		if ( !$post = get_post( $post ) )
			return null;
		$this -> _post = $post;
	}

	/**
	 * Get defined properties setting.
	 *
	 * @param  string|null property name, if null value, get all settings.
	 * @return array
	 */
	public function get_property_setting( $prop = null ) {
		if ( !isset( $this -> properties ) )
			return null;

		// All properties
		if ( null === $prop )
			return $this -> properties;

		return is_string( $prop ) && array_key_exists( $prop, $this -> properties )
			? $this -> properties[$prop]
			: null
		;
	}

	/**
	 * Overloading method, '__isset'.
	 * Check property definition, and set property instance, if defined.
	 * e.g. isset( $this -> var )
	 *
	 * @param  string $var, name of property.
	 * @return bool
	 */
	public function __isset( $var ) {

		if ( isset( $this -> _data[$var] ) ) {
			return true;
		}

		if ( !$propSetting = $this -> get_property_setting( $var ) ) {
			return false; // throw error: not exist
		}

		/**
		 * Conditional arguments ** underconstruction !! **
		 * - 表示する条件
		 */
		if ( array_key_exists( 'condition', $propSetting ) ) {
			/**
			 *
			 */
			foreach ( (array) $propSetting['condition'] as $arg ) {
				$condProp = $this -> $arg['property'];
				if ( !property_exists( $condProp, 'value' ) ) {
					return false;
					break;
				}
				if ( $arg['value'] !== $condProp -> value ) {
					return false;
					break;
				}
			}
		}

		if ( array_key_exists( 'model', $propSetting ) ) {

			/**
			 * Get model instance
			 *
			 * @uses \wordpress\model\~
			 */
			$propModel = $propSetting['model'];
			if ( !array_key_exists( $propModel, self::$models ) ) {
				return false; // throw error
			}
			// Get instance
			if ( null === self::$models[$propModel] ) {
				$modelStr = '\\wordpress\\model\\' . $propModel;
				self::$models[$propModel] = new $modelStr( $this -> _post );
			}
			$modelInstance =& self::$models[$propModel];

			/**
			 * Get property type instance
			 *
			 * @uses \properties\~
			 */

			// If key does not exist, set default type.
			if ( !array_key_exists( 'type', $propSetting ) ) {
				switch ( $propModel ) :
					case 'metadata' :
						$propSetting['type'] = 'string';
						break;
					//case 'taxonomy' :
				endswitch;
			}

			$typeClass = '\\property\\' . $propSetting['type'];
			if ( !class_exists( $typeClass ) ) {
				return false; // throw error
			}

			$typeInstance = new $typeClass( $var, $propSetting );

			/**
			 * Set value (get by model instance), to type instance
			 */
			$typeInstance -> val( $modelInstance -> $var );

			// ~

			$this -> _data[$var] = $typeInstance;

		} else if ( array_key_exists( 'type', $propSetting ) ) {

			if ( 'group' === $propSetting['type'] ) {

				/**
				 * @uses \mmsf\is_vector()
				 */
				if ( !array_key_exists( 'elements', $propSetting ) || !\mmsf\is_vector( $propSetting['elements'] ) ) {
					return false;
				}
				$instance = new \property\group( $var, $propSetting );

				foreach ( $propSetting['elements'] as $element ) {
					if ( $elementData = $this -> $element ) {
						$instance -> set_element( $element, $elementData );
					}
				}

				if ( empty( $instance -> properties ) ) {
					return false;
				}

				$this -> _data[$var] = $instance;

			}

		}

		return array_key_exists( $var, $this -> _data );

	}

	/**
	 * Overloading method, '__get'.
	 * Get property instance, if exists.
	 *
	 * @param  string $var property name
	 * @return null|object
	 */
	public function __get( $var ) {
		return isset( $this -> $var ) ? $this -> _data[$var] : null;
	}

	/**
	 * Overloading method, '__set'.
	 * Edit property value, using model instnce.
	 *
	 * @uses \mmsf\getDistalClassname()
	 *
	 * @param string $name, name of defined property.
	 * @param mixed $value, new value. if null value, delete property's value.
	 */
	public function __set( $name, $value ) {

		if ( !current_user_can( 'edit_post', $this -> _post -> ID ) ) {
			return;
		}

		$property = $this -> $name;

		if ( is_null( $property ) ) {
			return;
		}

		$type = \mmsf\getDistalClassname( $property );

		if ( in_array( $type, [ 'group', 'conponent' ] ) ) {

			if ( !is_array( $value ) )
				return;

			$elements = array_keys( $property -> properties );
			foreach ( $elements as $element ) {
				$newValue = array_key_exists( $element, $value )
					? $value[$element]
					: ''
				;
				$this -> $element = $newValue; // New value, not filtered
			}

		} else {

			$model = $property -> getModel();
			$modelInstance =& self::$models[$model];

			$newValue = $property -> filter( $value ); // New value, filtered

			if ( null === $newValue ) {

				unset( $modelInstance -> $name );

			} else if ( $newValue !== $property -> value ) {

				$modelInstance -> $name = $newValue;

			}

		}

	}

}
