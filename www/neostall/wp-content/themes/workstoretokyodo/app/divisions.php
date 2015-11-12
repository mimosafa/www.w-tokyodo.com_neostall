<?php
namespace WSTD;
/**
 * Workstore Tokyo Do Division Class
 *
 * @since 0.0.0
 */
class Divisions {

	/**
	 * Workstore Tokyo Do Division Model
	 *
	 * @var array
	 */
	private static $divisions = [
		/**
		 * Direct: Tokyo Do
		 *
		 * @since 0.0.0
		 */
		'direct' => [
			//
		],

		/**
		 * Neostall: Neoyataimura
		 *
		 * @since 0.0.0
		 */
		'neostall' => [
			//
		],

		/**
		 * Neoponte: Chibaramen
		 *
		 * @since 0.0.0
		 */
		'neoponte' => [
			//
		],

		/**
		 * Sharyobu
		 *
		 * @since 0.0.0
		 */
		'sharyobu' => [
			//
		],
	];

	/**
	 * @access public
	 */
	public static function init() {
		static $instance;
		$instance ?: $instance = new self();
	}

	/**
	 * @access private
	 */
	private function __construct() {
		//
	}

}
