<?php

namespace Neoyatai\Admin;

class meta_box {

}



add_action( 'add_meta_boxes_space', 'add_meta_box_space_init' );
function add_meta_box_space_init() {
	if ( is_readable( __DIR__ . '/domain/space/meta-boxes.php' ) )
		$boxes = require __DIR__ . '/domain/space/meta-boxes.php';
	if ( isset( $boxes ) && !empty( $boxes ) ) {
		foreach ( $boxes as $key => $args ) {
			$title = isset( $args['title'] ) && !empty( $args['title'] ) ? esc_html( $args['title'] ) : $key;
			$context = 'normal';
			$priority = 'default';
			add_meta_box(
				"space-{$key}",
				$title,
				'meta_box_space',
				'space',
				$context,
				$priority,
				array( 'contents' => $args['contents'] )
			);
		}
	}
}
function meta_box_space( $post, $metabox ) {
	$post_id = $post -> ID;
	$keys = $metabox['args']['contents'];
	$props = require __DIR__ . '/domain/space/prop.php'; // 要・存在確認
	$contents = array();
	foreach ( $keys as $key ) {
		$prop = $props[$key];
		create_form_parts( $post_id, $key, $prop );
	}
}
function create_form_parts( $post_id, $key, $prop ) {
	$field = $prop['field'];
	$label = $prop['label'];
	$var = '';
	switch ( $field ) {
		case 'taxonomy' :
			$fnc = "get_the_{$key}_objects";
			$var = $fnc( $post_id );
			break;
		case 'meta' :
			$var = get_post_meta( $post_id, $key, true );
			break;
	}
	var_dump( $var );
}


/*
function add_meta_box_space_init() {
	global $post;
	$keys = (array) get_post_custom_keys( $post -> ID );
	foreach ( $keys as $key ) {
		if ( '_' !== $key[0] ) {
			add_meta_box(
				"space-{$key}",
				ucwords( str_replace( '_', ' ', $key ) ),
				'meta_box_space',
				'space',
				'normal',
				'default',
				array( 'meta_key' => $key )
			);
		}
	}
}
function meta_box_space( $post, $metabox ) {
	$post_id = $post -> ID;
	$key = $metabox['args']['meta_key'];
	$value = (array) get_post_meta( $post_id, $key );
	if ( 1 === count( $value ) )
		$value = array_pop( $value );
	echo '<pre>';
	var_dump( $key );
	var_dump( $value );
	echo '</pre>';
}
*/