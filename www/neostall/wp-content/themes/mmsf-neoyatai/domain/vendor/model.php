<?php

namespace neoyatai\vendor;

/**
 *
 */
class model {

	public $name = '';
	public $data = [
		'ID' => 0,
		'serial' => null,
		'post_title' => '',
		'name' => '',
		'url' => ''
	];

	public static function get_instance( $post = 0 ) {
		$post = get_post( $post );
		if ( !$post || !check_post_type( $post, 'vendor' ) )
			return false;
		return new static( $post );
	}

	private function __construct( $post ) {
		$this -> _post( $post );
		$this -> _name();
	}

	private function _post( $post ) {
		$id = $this -> data['ID'] = $post -> ID;
		$this -> data['serial'] = (int) get_post_meta( $id, 'serial', true );
		$this -> data['post_title'] = get_the_title( $post );
		if ( $_name = get_post_meta( $id, 'name', true ) )
			$this -> data['name'] = $_name;
		$this -> data['url'] = get_permalink( $post );
	}

	private function _name() {
		$this -> name = '' !== $this -> data['name'] ? $this -> data['name'] : $this -> data['post_title'];
	}

}