<?php

namespace management;

class admin {
	use \module\admin;

	private $supports = [ 'title', 'editor', 'excerpt', 'custom-fields' ];

	private $meta_boxes = [

		[
			'property' => 'type',
			'context'  => 'side',
		], [
			'property' => 'day',
			'context'  => 'side',
		],

	];

}
