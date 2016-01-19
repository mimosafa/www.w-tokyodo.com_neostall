<?php
namespace mimosafa\WP;

class Common {

	public static function init() {
		static $instance;
		$instance ?: new self();
	}

	private function __construct() {
		#require_once __DIR__ . '/compat.php';
		require_once __DIR__ . '/classloader.php';
	}

}
