<?php

namespace neoyatai\kitchencar;

/**
 *
 */
class model {

	public $name = '';
	public $frontend = false;

	public $data = [
		'ID' => 0,
		'serial' => null,
		'post_title' => '',
		'name' => '',
		'url' => '',
		'status' => ''
	];

	//public $spec = [];
	//public $items = [];

	private static $_list_phase = [
		1 => 'Active',
		9 => 'Not Active'
	];

	public static function get_instance( $post = 0 ) {
		$post = get_post( $post );
		if ( !check_post_type( $post, 'kitchencar' ) )
			return false;
		return new static( $post );
	}

	private function __construct( $post ) {
		$this -> _post( $post );
		$this -> _name();
		$this -> _frontend();
	}

	private function _post( $post ) {
		$id = $this -> data['ID'] = $post -> ID;
		$this -> data['serial'] = (int) get_post_meta( $id, 'serial', true );
		$this -> data['post_title'] = get_the_title( $post );
		if ( $name = get_post_meta( $id, 'name', true ) )
			$this -> data['name'] = $name;
		$this -> data['url'] = get_permalink( $post );
		if ( $phase = (int) get_post_meta( $id, 'phase', true ) ) {
			if ( isset( self::$_list_phase[$phase] ) )
				$this -> data['status'] = self::$_list_phase[$phase];
		}
	}

	private function _name() {
		$this -> name = '' !== $this -> data['name'] ? $this -> data['name'] : $this -> data['post_title'];
	}

	private function _frontend() {
		if ( 'Active' !== $this -> data['status'] )
			return;
		$this -> frontend = true;
	}

	/**
	 *
	 */
	public function set_item( $param = null ) {
	}

	public function set_spec() {
		if ( !$id = $this -> data['ID'] )
			return;
		$spec = [];
		$spec['vin'] = get_post_meta( $id, 'vin', true );
		$size = [];
		foreach ( [ 'length', 'width', 'height' ] as $string ) {
			$mm = (int) get_post_meta( $id, $string, true );
			$size[$string] = $mm ? $mm : false;
		}
		$spec['size'] = $size;
		$this -> spec = $spec;
	}

	public function set_vendor() {
		if ( !$id = $this -> data['ID'] )
			return;
		$_id = get_post( $id ) -> post_parent;
		if ( !$_id || !check_post_type( $_id, 'vendor' ) )
			return;
		// require
		locate_template( [ 'domain/vendor/model.php' ], true );
		$this -> vendor = \neoyatai\vendor\model::get_instance( $_id );
	}

}

