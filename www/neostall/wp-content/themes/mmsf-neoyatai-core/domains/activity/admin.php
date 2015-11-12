<?php

namespace activity;

class admin {
	use \module\admin;

	private $supports = [ 'custom-fields' ];
	private $readonly = [ 'title' ];

	private $meta_boxes = [
		[
			'property' => 'phase',
			'context'  => 'side',
		],
	];

}
