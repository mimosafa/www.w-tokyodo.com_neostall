<?php

namespace admin;

/**
 * @uses \admin\nonce (*important)
 * @uses \wakisuke\Decoder
 * @uses \property\(property type)
 */
class meta_box_inner_vuejs {

	/**
	 * @var string
	 */
	private $post_type;

	/**
	 * @var bool
	 */
	private static $_post_new = null;

	/**
	 * @var string
	 */
	private $_form_id_prefix = 'custom-form-';

	/**
	 * @var object \admin\nonce
	 */
	private static $nonceInstance;

	/**
	 *
	 */
	public function __construct( $post_type ) {
		if ( !post_type_exists( $post_type ) ) {
			return false; // throw error
		}
		$this -> post_type = $post_type;

		if ( null === self::$_post_new ) {
			self::$_post_new = ( 'add' === get_current_screen() -> action )
				? true
				: false
			;
		}

		self::$nonceInstance = new nonce( $post_type );

		if ( !wp_script_is( 'vie' ) ) {
			wp_enqueue_script( 'vue' );
		}
	}

	/**
	 * @uses \mmsf\getDistalClassname()
	 */
	public function init( $post, $metabox ) {

		$instance = $metabox['args']['instance']; // object \property\(property type)
		$type = \mmsf\getDistalClassname( $instance );

		$json = $instance -> getArray();

		echo '<pre>';
		var_dump( $json );
		echo '</pre>';

	}

	private function html( $name ) {
		$id = $this -> _form_id_prefix . $name;
	}

}
