<?php

/*

Name: Activity
Plural Name: Activities
Register As: Custom Post Type
Permalink Format: ID

*/

namespace activity;

class properties {
	use \module\properties;

	private $properties = [

		'day' => [
			'model' => 'metadata',
			'type'  => 'date',
			'format' => 'Ymd',
		],

		'actOf' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
		],

		'space_id' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'space',
		],

		'event_id' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'event',
		],

		'phase' => [
			'model' => 'metadata',
			'type'  => 'select',
			'options' => [
				1 => 'Active',
				//8 => 'Cancel',
				9 => 'Absence',
			],
		],

	];

}