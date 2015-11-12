<?php

namespace neoyatai\space\model;

class location {

	public $ID;
	public $region;
	public $address;
	public $site;
	public $areaDetail;
	public $latlng;

	public function __construct( $post = 0 ) {
		if ( !$post = get_type_checked_post( $post, 'space' ) )
			return false;
		// ID
		$this -> ID = $post -> ID;
		// Region
		if ( $region = get_the_single_term( $post, 'region' ) )
			$this -> region = $region -> slug;
		else
			$this -> region = false;
		// post metas
		$vars = get_class_vars( __CLASS__ );
		foreach ( $vars as $key => $var ) {
			if ( !in_array( $key, [ 'ID', 'region' ] ) ) {
				$val = get_post_meta( $post -> ID, $key, true );
				$this -> $key = $val ? $val : false;
			}
		}
	}

	public function get_data() {
		$data = [];
		if ( $this -> region ) {
			$terms = get_the_region_objects( $this -> ID );
			foreach ( $terms as $n => $term ) {
				$data[$n]['name'] = $term -> name;
				$data[$n]['url']  = get_term_link( $term, 'region' );
			}
		}
		$vars = [ 'address', 'site', 'areaDetail', 'latlng' ];
		foreach ( $vars as $var ) {
			$data[] = $this -> $var;
		}
		return $data;
	}

}