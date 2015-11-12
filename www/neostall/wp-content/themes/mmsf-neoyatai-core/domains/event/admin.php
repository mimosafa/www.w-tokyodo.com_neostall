<?php

namespace event;

class admin {
	use \module\admin;

	private $supports = [ 'title', 'editor' ];

	private $meta_boxes = [

		/*
		[
			'custom' => true,
			'id' => 'test_id',
			'title' => 'Test Title',
			'callback' => '\event\meta_box_test',
		],
		//*/

		[
			'property' => 'eventsData',
			'callback' => '\event\meta_box_test',
		],

	];

}

function meta_box_test( $post, $metabox ) {
	echo '<pre>';
	var_dump( $metabox );
	echo '</pre>';
}
