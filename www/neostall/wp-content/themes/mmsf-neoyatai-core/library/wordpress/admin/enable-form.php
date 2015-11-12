<?php

namespace wordpress\admin;

/**
 *
 */
class enable_form {

	/**
	 *
	 */
	private $_post_type;

	/**
	 *
	 */
	private $_forms = [];

	/**
	 *
	 */
	private $_fully_enable = []; //yet

	/**
	 *
	 */
	private static $_css = '';

	/**
	 *
	 */
	private static $_js = '';

	/**
	 *
	 */
	public function __construct() {
		if ( !is_admin() )
			return null;

		if ( $post_type = get_current_screen() -> post_type ) {
			$this -> _post_type = $post_type;
		}

		self::$_css = '';
		self::$_js  = '';
	}

	/**
	 *
	 */
	public function init() {
		if ( empty( $this -> _forms ) )
			return;

		foreach ( $this -> _forms as $var ) {
			$this -> $var();
			if ( in_array( $var, $this -> _fully_enable ) ) {
				// ~ fully enable
			}
		}

		if ( '' !== self::$_css ) {
			add_action( 'admin_head', function() {
				self::$_css .= '</style>';
				echo self::$_css;
			} );
		}

		if ( '' !== self::$_js ) {
			add_action( 'admin_footer', function() {
				self::$_js .= '</script>';
				echo self::$_js;
			} );
		}
	}

	/**
	 *
	 */
	public function set( $form, $full_enable = false ) {
		if ( !method_exists( $this, $form ) ) {
			return false;
		}
		$this -> _forms[] = $form;
		if ( true === $full_enable ) {
			$this -> _fully_enable[] = $form;
		}
	}

	/**
	 *
	 */
	private function slug() {
		/**
		 * @see http://ja.forums.wordpress.org/topic/21239
		 */
		add_filter( 'get_sample_permalink_html', [ $this, 'sample_permalink_html' ], 10, 2 );
		add_action( 'add_meta_boxes', function() {
			remove_meta_box( 'slugdiv', $this -> _post_type, 'normal' );
		} );
	}

	/**
	 * @see https://core.trac.wordpress.org/browser/trunk/src/wp-admin/includes/post.php#L1184
	 */
	public function sample_permalink_html( $return, $id ) {
		if ( 'publish' !== get_post_status( $id ) ) {
			return $return;
		}
		if ( current_user_can( 'read_post', $id ) ) {
			$ptype = get_post_type_object( $this -> _post_type );
			if( 'draft' == get_post_status( $id ) ) {
				$view_post = __( 'Preview' );
			} else {
				$view_post = $ptype -> labels -> view_item;
			}
		}
		$return  = '<strong>' . __('Permalink:') . "</strong>\n";
		$return .= '<span id="sample-permalink" tabindex="-1">' . get_permalink( $id ) . "</span>\n";
		$return .= "<span id='view-post-btn'><a href='" . get_permalink( $id ) . "' class='button button-small'>$view_post</a></span>\n";

		return $return;
	}

	/**
	 *
	 */
	private function title() {
		self::init_css();
		$css =& self::$_css;
		$css .= "#titlediv #title { background-color: #eee; }\n";
		$css .= "#title:focus { border-color: #ddd; box-shadow: none; }\n";

		self::init_js();
		self::$_js .= "jQuery('#title').attr('readonly','readonly').removeAttr('name');\n";
	}

	/**
	 *
	 */
	private static function init_css() {
		if ( '' === self::$_css )
			self::$_css .= '<style type="text/css" id="enable-form-style">' . "\n";
	}

	/**
	 *
	 */
	private static function init_js() {
		if ( '' === self::$_js )
			self::$_js .= '<script type="text/javascript" id="enable-form-script">' . "\n";
	}

}
