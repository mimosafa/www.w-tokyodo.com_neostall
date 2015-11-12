<?php

namespace wordpress;

/**
 * Rewrite slug to post_id for custom post types.
 * - 設定 -> パーマリンク -> 更新 を忘れずに！
 */
class rewrite_slug_to_postid {

	/**
	 * Post types
	 *
	 * @var Array
	 */
	private $post_types = [];

	/**
	 * Set post types.
	 *
	 * @param string $post_type variable-length
	 */
	public function set() {
		if ( $args = func_get_args() ) {
			foreach ( $args as $post_type ) {
				if ( is_string( $post_type ) )
					$this -> post_types[] = $post_type;
			}
		}
	}

	/**
	 * init function.
	 */
	public function init() {
		if ( !empty( $this -> post_types ) ) {
			add_action( 'init', [ $this, 'set_rewrite' ] );
			add_filter( 'post_type_link', [ $this, 'set_permalink' ], 10, 2 );
		}
	}

	/**
	 * @link http://www.torounit.com/blog/2011/04/17/683/
	 * @link http://blog.ext.ne.jp/?p=1416
	 */
	# action_hook 'init'
	public function set_rewrite() {
		global $wp_rewrite;
		foreach ( $this -> post_types as $post_type ) {
			if ( post_type_exists( $post_type ) ) {
				$slug = get_post_type_object( $post_type ) -> rewrite['slug'];
				$wp_rewrite -> add_rewrite_tag( "%{$post_type}_id%", '([^/]+)', "post_type={$post_type}&p=" );
				$wp_rewrite -> add_permastruct( $post_type, "/{$slug}/%{$post_type}_id%", false );
			}
		}
	}
	# filter_hook 'post_type_link'
	public function set_permalink( $url, $post ) {
		global $wp_rewrite;
		$post = get_post( $post );
		$post_type = $post -> post_type;
		if ( in_array( $post_type, $this -> post_types ) ) {
			$_url = str_replace(
				"%{$post_type}_id%",
				$post -> ID,
				$wp_rewrite -> get_extra_permastruct( $post_type )
			);
			$url = home_url( user_trailingslashit( $_url ) );
		}
		return $url;
	}

}

/*
$rwrt = new rewrite_slug_to_postid();
$rwrt -> set( 'activity', 'management' );
$rwrt -> init();
*/
