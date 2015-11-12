<?php

namespace vendor;

class admin {
	use \module\admin;

	/**
	 *
	 */
	private $supports = [ 'editor' ];
	private $readonly = [ 'title', 'slug' ];

	private $meta_boxes = [
		[
			'property' => 'serial',
			'context'  => 'side',
		], [
			'property' => 'organization',
			'context'  => 'normal',
		], [
			'property' => 'kitchencars',
		]
	];

}
