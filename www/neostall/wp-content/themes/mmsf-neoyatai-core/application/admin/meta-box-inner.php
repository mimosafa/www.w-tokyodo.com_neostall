<?php

namespace admin;

/**
 * @uses \admin\nonce (*important)
 * @uses \wakisuke\Decoder
 * @uses \property\(property type)
 */
class meta_box_inner {

	/**
	 * @var string
	 */
	private $post_type;

	/**
	 * @var bool
	 */
	private static $_post_new;

	/**
	 * @var string
	 */
	private $_form_id_prefix = 'custom-form-';

	/**
	 * @var object \admin\nonce
	 */
	private static $nonceInstance;

	/**
	 * @var object \wakisuke\Decoder
	 */
	private static $decoder;

	/**
	 *
	 */
	public function __construct( $post_type ) {
		if ( !post_type_exists( $post_type ) ) {
			return false; // throw error
		}
		$this -> post_type = $post_type;

		self::$_post_new = ( 'add' === get_current_screen() -> action )
			? true
			: false
		;

		self::$nonceInstance = new nonce( $post_type );

		/**
		 * DOM creater from php array (, and json).
		 */
		require_once TEMPLATEPATH . '/library/wakisuke/Decoder.php';
		self::$decoder = new \wakisuke\Decoder();

		$this -> form_style();
	}

	/**
	 *
	 */
	public function init( $post, $metabox ) {

		$instance = $metabox['args']['instance']; // object \property\(property type)
		$args = $instance -> getArray();
		$dom_array = $this -> generate_dom_array( $args );

		if ( empty( $dom_array ) ) {
			return;
		}

		$html  = self::$decoder -> getArrayToHtmlString( $dom_array );
		$html .= self::$nonceInstance -> nonce_field( $instance -> name );

		echo $html;

	}

	/**
	 *
	 */
	private function generate_dom_array( $args ) {

		static $name  = '';
		static $id    = '';
		static $label = '';

		static $wrapper = [];

		$type = $args['_type'];

		$return = [];

		if ( 'group' === $type ) {

			/**
			 *
			 */

			$wrapper = [
				'element' => 'table',
				'attribute' => [
					'class' => 'form-table',
				],
				'children' => [
					[
						'element' => 'tbody',
						'children' => []
					],
				],
			];

			if ( empty( $args['_properties'] ) ) {
				return []; // throw error
			}

			$inner = [];

			foreach ( $args['_properties'] as $arg ) {
				$name  .= $args['name'] . '[' . $arg['name'] . ']';
				$id    .= $this -> _form_id_prefix . $args['name'] . '-' . $arg['name'];
				$label .= $arg['label'];

				$_id = $id;

				if ( $form  = $this -> generate_dom_array( $arg ) ) {

					$inner[] = [
						'element' => 'tr',
						'children' => [
							[
								'element' => 'th',
								'children' => [
									[
										'element' => 'label',
										'attribute' => [ 'for' => esc_attr( $_id ) ],
										'text' => esc_html( $label )
									]
								]
							], [
								'element' => 'td',
								'children' => $form
							]
						]
					];

				}

				$name = $id = $label = '';
			}

			if ( !empty( $inner ) ) {
				$wrapper['children'][0]['children'] = $inner;
				$return[] = $wrapper;
			}

			$wrapper = [];

		} else {

			/**
			 * ** CAUTION! **
			 * 
			 * 
			 */

			$required = true === $args['_required'] ? true : false;
			$multiple = true === $args['_multiple'] ? true : false;
			$readonly = !self::$_post_new && true === $args['_readonly'] ? true : false;
			$unique   = true === $args['_unique'] ? true : false;

			if ( '' === $name ) {
				$name .= $args['name'];
			}
			
			if ( '' === $id ) {
				$id .= $this -> _form_id_prefix . $name;
			}

			/**
			 *
			 */
			$dom = [
				'element'   => '',
				'attribute' => []
			];
			$attr =& $dom['attribute'];

			if (
				'string'  === $type ||
				'integer' === $type ||
				'date'    === $type ||
				'time'    === $type
			) {

				/**
				 *
				 */

				$dom['element'] = 'input';
				$attr['id']   = esc_attr( $id );
				$attr['name'] = esc_attr( $name );

				if ( $required ) {
					$attr['required'] = 'required';
				}

				if ( $readonly ) {
					$attr['readonly'] = 'readonly';
				}

				if (
					'string'  === $type ||
					'integer' === $type
				) {

					if ( array_key_exists( 'value', $args ) ) {
						$attr['value'] = esc_attr( $args['value'] );
					}

					if ( 'string' === $type ) {

						$attr['type'] = 'text';
						$attr['class'] = 'regular-text';

					} else if ( 'integer' === $type ) {

						$attr['type'] = 'number';
						$attr['class'] = 'small-text';

					}

				} else if ( 'date' === $type ) {

					if ( array_key_exists( 'value', $args ) ) {
						$attr['value'] = esc_attr( date( 'Y-m-d', strtotime( $args['value'] ) ) );
					}

					$attr['type'] = 'date';

				} else if ( 'time' === $type ) {

					if ( array_key_exists( 'value', $args ) ) {
						$attr['value'] = esc_attr( date( 'H:i', strtotime( $args['value'] ) ) );
					}

					$attr['type'] = 'time';

				}

			} else if ( 'select' === $type ) {

				/**
				 *
				 */

				$dom['element'] = 'select';
				$attr['id'] = esc_attr( $id );
				$attr['name'] = esc_attr( $name );

				if ( $required ) {
					$attr['required'] = 'required';
				}

				if ( $readonly ) {
					$attr['style'] = 'background-color:#eee;';
				}

				$dom['children'] = [];
				if ( !$required ) {
					$dom['children'][] = [
						'element' => 'option',
						'text' => '-',
						'attribute' => [
							'value' => '',
						],
					];
				}
				foreach ( $args['options'] as $key => $val ) {
					$child = [
						'element' => 'option',
						'attribute' => [
							'value' => esc_attr( $key ),
						],
						'text' => esc_html( $val ),
					];
					if ( array_key_exists( 'value', $args ) ) {
						if ( $key == $args['value'] ) {
							$child['attribute']['selected'] = 'selected';
						} else if ( $readonly ) {
							$child['attribute']['disabled'] = 'disabled';
						}
					}
					$dom['children'][] = $child;
				}

			} else if ( 'boolean' === $type ) {

				/**
				 *
				 */

				$_dom = [
					'element' => 'input',
					'attribute' => [
						'type' => 'checkbox',
						'id' => esc_attr( $id ),
						'name' => esc_attr( $name ),
						'value' => 1, // $args['_true_value'],
					],
				];
				if ( array_key_exists( 'value', $args ) && $args['value'] ) {
					$_dom['attribute']['checked'] = 'checked';
				}

				if ( '' !== $label ) {
					$dom = $_dom;
				} else {
					$dom = [
						'element'   => 'label',
						'attribute' => [ 'for' => esc_attr( $id ) ],
						'text'      => esc_html( $args['label'] ),
						'children'  => [ $_dom ]
					];
				}

			} else if ( 'latlng' === $type ) {

				/**
				 *
				 */

				$dom['element'] = 'input';
				$attr['type'] = 'text'; //'hidden';
				$attr['id'] = esc_attr( $id );
				$attr['name'] = esc_attr( $name );
				if ( array_key_exists( 'value', $args ) ) {
					$attr['value'] = esc_attr( $args['value'] );
				}


			} else {

				$dom = [
					'element' => 'span',
					'text' => (string) $args['value']
				];

			}

			if ( !in_array( $type, [ 'group', 'component'] ) ) {
				/**
				 * Initialize static property $name, $id.
				 */
				$name = $id = '';
			}

			if ( !empty( $wrapper ) ) {

				$return[] = $dom;

			} else {

				$return[] = [
					'element' => 'p',
					'children' => [ $dom ],
				];

			}

		}

		/**
		 *
		 */
		if ( array_key_exists( 'description', $args ) && !empty( $args['description'] ) ) {
			$desc = [
				'element' => 'p',
				'text' => esc_html( $args['description'] ),
			];
			if ( 'group' === $type ) {
				array_unshift( $return, $desc );
			} else {
				$desc['attribute'] = [ 'class' => 'description' ];
				$return[] = $desc;
			}
		}

		return $return;
	}

	/**
	 *
	 */
	private function form_style() {
		add_action( 'admin_head', function() {
			$id_prefix = $this -> _form_id_prefix;
			echo <<<EOF
<style>
[id^="{$id_prefix}"] { max-width: 100%; }
</style>
EOF;
		} );
	}

}
