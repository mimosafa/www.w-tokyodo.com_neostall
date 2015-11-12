<?php

namespace space;

class admin {
	use \module\admin;

	private $readonly = [ 'title', 'slug' ];

	private $meta_boxes = [
		[
			'property' => 'meta',
			'context'  => 'normal',
			//'fw' => 'vue',
		], [
			'property' => 'locationData',
		], [
			'property' => 'start',
			'context'  => 'side'
		], [
			'property' => 'end',
			'context'  => 'side'
		], [
			'property' => 'publication',
		]
	];

}
