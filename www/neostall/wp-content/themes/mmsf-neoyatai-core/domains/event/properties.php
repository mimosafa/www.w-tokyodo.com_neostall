<?php

/*

Name: Event
Plural Name: Events
Register As: Custom Post Type

*/

namespace event;

class properties {
	use \module\properties;

	private $properties = [

		'series' => [
			'model' => 'term',
		],

		'day' => [
			'model' => 'metadata',
			'type'  => 'date',
			'format' => 'Ymd',
		],

		'publication' => [
			'model' => 'metadata',
			'type'  => 'datetime',
			'format' => 'Y-m-d H:i:s',
		],

		'eventsData' => [
			'model' => 'metadata',
			'type'  => 'array_legacy',
			'structure' => [
				'region' => [
					'model' => 'term',
					'set_field' => 'term_id',
					//'set_field' => 'slug',
					'get_field' => 'name',
				],
				'address' => [
					'type' => 'string',
				],
				'latlng' => [
					'type' => 'latlng',
				],
				'site' => [
					'type' => 'string',
				],
				'areaDetail' => [
					'type' => 'string',
				],
				'starting' => [
					'type' => 'datetime',
				],
				'startingPending' => [
					'type' => 'bool',
				],
				'ending' => [
					'type' => 'datetime',
				],
				'endingPending' => [
					'type' => 'bool',
				],
				'activities' => [
					'type' => 'related_post',
					'post_type' => 'activity',
					'multiple' => true,
					'ordered' => true,
					'set_field' => 'post_id',
					'get_field' => '',
				],
			],
		],

	];
}
