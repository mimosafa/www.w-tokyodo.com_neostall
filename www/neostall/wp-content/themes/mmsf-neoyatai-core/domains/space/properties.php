<?php

/*

Name: Space
Plural Name: Spaces
Register As: Custom Post Type

*/

namespace space;

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
		 * Groups
		 */

		'meta' => [
			'type' => 'group',
			'elements' => [ 'serial', 'phase', 'sche_type', 'rotation_pair' ],
			'label' => '管理情報',
			'description' => 'スペース管理情報です',
		],

		'locationData' => [
			'type' => 'group',
			'elements' => [ 'address', 'site', 'areaDetail', 'latlng' ],
			'label' => '所在地情報',
		],

		'open' => [
			'type' => 'group',
			'elements' => [ 'start', 'end' ],
		],

		'start' => [
			'type' => 'group',
			'elements' => [ 'starting', 'startingPending' ],
		],

		'starting' => [
			'model' => 'metadata',
			'type'  => 'time',
		],

		'startingPending' => [
			'model' => 'metadata',
			'type'  => 'boolean', // yet
			'options' => [
				0 => '',
				1 => 'Pending'
			],
			'label' => '予定',
		],

		'ending' => [
			'model' => 'metadata',
			'type'  => 'time', // yet
		],

		'endingPending' => [
			'model' => 'metadata',
			'type'  => 'boolean', // yet
			'options' => [
				0 => '',
				1 => 'Pending'
			],
			'label' => '予定',
		],

		'end' => [
			'type' => 'group',
			'elements' => [ 'ending', 'endingPending' ],
		],

		/**
		 *
		 */
		'serial' => [
			'model' => 'metadata',
			'type'  => 'integer',
			'min'   => 1,
			'prefix' => '#', // yet
			'label'  => 'スペース通し番号',
			'description' => 'ネオ屋台村のランチスペース開村順の通し番号です。',
			'required' => true,
			'readonly' => true,
		],

		'region' => [
			'model'    => 'term', // yet
			'taxonomy' => 'region',
		],

		'address' => [
			'model' => 'metadata',
			'type'  => 'string',
			'label' => '補記住所',
			'multibyte' => true,
			'required' => true,
			'readonly' => true,
		],

		'site' => [
			'model' => 'metadata',
			'type'  => 'string',
			'label' => '施設',
			'multibyte' => true,
		],

		'areaDetail' => [
			'model' => 'metadata',
			'type'  => 'string',
			'label' => '詳細エリア',
			'multibyte' => true,
		],

		'latlng' => [
			'model' => 'metadata',
			'type'  => 'latlng', // yet
			'label' => '緯度経度',
			'readonly' => true,
		],

		'phase' => [
			'model' => 'metadata',
			'type'  => 'select', // yet
			'options' => [
				0 => '開村見込み', //'Before start',
				1 => '営業中', //'Active',
				8 => '休止中', //'Suspention',
				9 => '閉村済み', //'End',
			],
			'required' => true,
			'readonly' => true
		],

		'publication' => [
			'model' => 'metadata',
			'type' => 'boolean',
		],

		'sche_type' => [
			'model' => 'metadata',
			'type'  => 'select',
			'options' => [
				'fixed'  => '固定スケジュール', //'Fixed Schedule',
				'rotate' => 'ローテーション', //'Rotation Schedule',
				'flex'   => '変動スケジュール / 不定期' //'Flex Schedule',
			],
			'default' => 'fixed',
		],

		/**
		 *
		 */
		'rotation_pair' => [
			'condition' => [
				[
					'property' => 'sche_type',
					'value'    => 'rotate',
					'compare'  => '=',
				],
			],
			'model' => 'metadata',
			'type'  => 'related_post', // yet
			'multiple' => true,
			'query' => [
				'post_type' => 'space',
				'meta_query' => [
					[
						'key' => 'sche_type',
						'value' => 'rotate',
						'compare' => '=',
					],
				],
				'posts_per_page' => -1,
			],
			'disabled' => true,
		],

		'sunday' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
			'multiple'  => true,
			'ordered'   => true,
		],

		'monday' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
			'multiple'  => true,
			'ordered'   => true,
		],

		'tuesday' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
			'multiple'  => true,
			'ordered'   => true,
		],

		'wednesday' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
			'multiple'  => true,
			'ordered'   => true,
		],

		'thursday' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
			'multiple'  => true,
			'ordered'   => true,
		],

		'friday' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
			'multiple'  => true,
			'ordered'   => true,
		],

		'saturday' => [
			'model' => 'metadata',
			'type'  => 'related_post',
			'post_type' => 'kitchencar',
			'multiple'  => true,
			'ordered'   => true,
		],

		'kitchencars' => [
			'model' => 'custom', // yet
			'class' => '\\space\\kitchencars',
		],

	];

}
