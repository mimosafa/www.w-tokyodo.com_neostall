<?php

namespace service;

/**
 *
 */
class admin_init {

	private $domain;

	/**
	 *
	 */
	public function __construct() {
		$this -> registerLoader();
		self::add_theme_support();
	}

	/**
	 * Register autoloader for admin
	 */
	private function registerLoader() {
			\ClassLoader::registerLoader( 'admin', TEMPLATEPATH . '/application' );
			\ClassLoader::registerLoader( 'wordpress\admin', TEMPLATEPATH . '/library' );
	}

	/**
	 * Theme supports for admin
	 */
	private static function add_theme_support() {
		add_theme_support( 'post-thumbnails' );
	}

}
