<?php

/**
 * Get the array region objects hierarchical ( array( $pref_obj, $city_obj ) ).
 * - 県オブジェクトと地域オブジェクトを配列で取得する
 *
 * @uses get_the_single_term (term.php)
 *
 * @param int|object $post Post ID or object.
 * @return array
 */
if ( !function_exists( 'get_the_region_objects' ) ) {
	function get_the_region_objects( $post = 0 ) {
		$regions = array();
		$term = get_the_single_term( $post, 'region' );
		if ( !$term )
			return false;
		$ancestors = get_ancestors( $term -> term_id, 'region' );
		if ( !empty( $ancestors ) ) {
			array_reverse( $ancestors );
			foreach ( $ancestors as $region_id ) {
				$regions[] = get_term( $region_id, 'region' );
			}
		}
		$regions[] = $term;
		return $regions;
	}
}
