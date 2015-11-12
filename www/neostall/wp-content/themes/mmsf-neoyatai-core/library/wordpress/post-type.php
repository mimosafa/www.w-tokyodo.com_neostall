<?php

/**
 * $postのポストタイプが $post_typeに合致した場合は WP_Postを、合致しない場合は falseを返す。
 *
 * @param int|WP_Post $post
 * @param string $post_type
 * @return WP_Post|false
 */
if ( !function_exists( 'get_type_checked_post' ) ) {
	function get_type_checked_post( $post, $post_type ) {
		$post = get_post( $post );
		if ( post_type_exists( $post_type ) && $post_type === get_post_type( $post ) )
			return $post;
		return false;
	}
}

/**
 * $postのポストタイプが $post_typeに合致するか否か
 *
 * @param int|WP_Post $post
 * @param string $post_type
 * @return bool
 */
if ( !function_exists( 'check_post_type' ) ) {
	function check_post_type( $post, $post_type ) {
		$post = get_type_checked_post( $post, $post_type );
		return !!$post;
	}
}
