<?php

namespace wordpress\admin;

/**
 * @uses \wakisuke\Decoder
 */
class meta_box_inner {

	/**
	 *
	 */
	private $_post;

	/**
	 *
	 */
	private $_new = false;

	/**
	 * TEST Array
	 *
	 * - \wakisuke\Decoder Class
	 */
	private static $domArray = [
		[
			'element' => 'p',
			'text' => 'TTTTEEEEESSSSSTTTTTT!!!!!!!!!!'
		],
	];

	/**
	 *
	 */
	public function __construct( $post, $args ) {
		$this -> _post = get_post( $post );

		if ( null === $this -> _post ) {
			// ~ error
			return;
		}

		if ( 'auto-draft' === $post -> post_status ) {
			$this -> _new = true;
		}

		/**
		 * Include Decoder
		 */
		require_once TEMPLATEPATH . '/library/wakisuke/Decoder.php';

		//self::$domArray = [];


		$this -> output();
	}

	private function output() {
		$decoder = new \wakisuke\Decoder();
		echo $decoder -> getArrayToHtmlString( self::$domArray );
	}

}
