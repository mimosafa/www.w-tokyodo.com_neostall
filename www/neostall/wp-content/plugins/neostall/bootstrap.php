<?php
namespace Neostall;
use mimosafa\ClassLoader, mimosafa\WP\Repository\Repository;
class Bootstrap {
	public static function init() {
		static $ins;
		$ins ?: $ins = new self();
	}
	private function __construct() {
		Repository::parseJSON( file_get_contents( __DIR__ . '/models/repositories.json' ) );
		ClassLoader::register( 'Neostall\\Model', __DIR__ . '/models' );
		$yml = \Spyc::YAMLLoad( __DIR__ . '/entities/kitchencar.entity' );
		var_dump( $yml );
	}
}
