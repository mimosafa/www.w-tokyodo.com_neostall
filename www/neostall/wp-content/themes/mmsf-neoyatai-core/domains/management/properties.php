<?php

/*

Name: Management
Plural Name: Managements
Register As: Custom Post Type
Permalink Format: ID

*/

namespace management;

class properties {
	use \module\properties;

	private $properties = [

		'test' => [
			'type' => 'group',
			'elements' => [ 'type', 'day' ],
		],

		'type' => [
			'type' => 'select',
			'model' => 'metadata',
			'options' => [
				'off' => '休み'
			],
		],

		'day' => [
			'type' => 'date',
			'model' => 'metadata',
			'format' => 'Ymd',
		],

		'publication' => [
			'type' => 'datetime',
			'model' => 'metadata',
			'format' => 'Y-m-d H:i:s',
		],

	];
}