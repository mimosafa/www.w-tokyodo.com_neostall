<?php

namespace kitchencar;

class admin {
	use \module\admin;

	private $supports = [ 'title', 'thumbnail', 'custom-fields' ];
	private $readonly = [ 'slug' ];

	private $meta_boxes = [
		[
			'property' => 'vin',
			'context'  => 'normal',
		]
	];

}
