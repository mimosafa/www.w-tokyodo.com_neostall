<?php

/**
 * Get the single term object.
 *
 * @uses get_the_terms
 *
 * @param int|object $post Post ID or object.
 * @param string $taxonomy Taxonomy name.
 * @return object|bool Single term object on success, false on failure.
 */
if ( !function_exists( 'get_the_single_term' ) ) {
	function get_the_single_term( $post, $taxonomy ) {
		$terms = get_the_terms( $post, $taxonomy );
		if ( !$terms || !is_wp_error( $terms ) )
			return array_pop( $terms );
		return false;
	}
}
