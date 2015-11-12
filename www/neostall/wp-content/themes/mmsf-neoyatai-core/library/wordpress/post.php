<?php

/**
 *
 */
if ( !function_exists( 'get_filtered_post' ) ) {
	function get_filtered_post( $post, $filter_tag = '' ) {
		$_post = get_post( $post );
		$tag = $filter_tag && is_string( $filter_tag ) ? $filter_tag : 'get_filtered_post';
		return apply_filters( $tag, $_post );
	}
}