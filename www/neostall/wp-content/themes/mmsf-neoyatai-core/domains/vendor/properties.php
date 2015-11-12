<?php

/*

Name: Vendor
Plural Name: Vendors
Register As: Custom Post Type

*/

namespace vendor;

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
			'model'     => 'metadata',
			'type'      => 'integer',
			'allow_0'   => true,
			'increment' => true, // yet
			'multiple'  => false,
			'readonly'  => true, // yet
			'label'     => 'ネオ屋台ID',
			'description' => 'ネオ屋台村の登録通し番号です。',
		],

		/**
		 * 会社名，組織名
		 */
		'organization' => [
			'model'     => 'metadata',
			'type'      => 'string',
		],

		/**
		 *
		 */
		'genre' => [
			'model'    => 'term',
			'taxonomy' => 'genre',
			'multiple' => true,
		],

		/**
		 *
		 */
		'kitchencars' => [
			'model'     => 'children',
			'post_type' => 'kitchencar',
			'orderby'   => 'menu_order',
			'order'     => 'ASC',
			'multiple'  => true,
		],

	];

}
