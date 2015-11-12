<?php

/*

Name: Kitchencar
Plural Name: Kitchencars
Register As: Custom Post Type

*/

namespace kitchencar;

/**
 *
 */
class properties {
	use \module\properties;

	/**
	 *
	 */
	private $properties = [

		/**
		 * ネオ屋台 ID
		 */
		'serial' => [
			'model'     => 'sync', // yet
			'sync_with' => 'vendor',
			'label'     => 'ネオ屋台ID',
		],

		'vendor' => [
			'model'     => 'parent',
			'post_type' => 'vendor',
		],

		/**
		 * 車両番号
		 */
		'vin' => [
			'model'  => 'metadata',
			'type'   => 'string',
			'label'  => '車両番号',
		],

		/**
		 * 車体サイズ (長さ )
		 */
		'length' => [
			'model' => 'metadata',
			'type'  => 'integer',
			'suffix' => 'mm',
		],

		/**
		 * 車体サイズ (幅 )
		 */
		'width' => [
			'model' => 'metadata',
			'type'  => 'integer',
			'suffix' => 'mm',
		],

		/**
		 * 車体サイズ (高さ )
		 */
		'height' => [
			'model' => 'metadata',
			'type'  => 'integer',
			'suffix' => 'mm',
		],

	];

}
