<?php

namespace wordpress;

/**
 * @see http://firegoby.jp/archives/5309
 */
class create_rewrite_endpoints {

	/**
	 *
	 */
	static $_reserved = [
		'attachment', 'attachment_id', 'author', 'author_name', 'calendar',
		'cat', 'category', 'category__and', 'category__in', 'category__not_in',
		'category_name', 'comments_per_page', 'comments_popup',
		'customize_messenger_channel', 'customized', 'cpage', 'day', 'debug',
		'error', 'exact', 'feed', 'hour', 'link_category', 'm', 'minute',
		'monthnum', 'more', 'name', 'nav_menu', 'nonce', 'nopaging', 'offset',
		'order', 'orderby', 'p', 'page', 'page_id', 'paged', 'pagename', 'pb',
		'perm', 'post', 'post__in', 'post__not_in', 'post_format', 'post_mime_type',
		'post_status', 'post_tag', 'post_type', 'posts', 'posts_per_archive_page',
		'posts_per_page', 'preview', 'robots', 's', 'search', 'second', 'sentence',
		'showposts', 'static', 'subpost', 'subpost_id', 'tag', 'tag__and', 'tag__in',
		'tag__not_in', 'tag_id', 'tag_slug__and', 'tag_slug__in', 'taxonomy', 'tb',
		'term', 'theme', 'type', 'w', 'withcomments', 'withoutcomments', 'year',
	];

	/**
	 *
	 */
	private $endpoints = [];

	/**
	 *
	 */
	public function set( $endpoint = null ) {
		if ( !$endpoint || !is_string( $endpoint ) ) {
			return;
		}
		if ( in_array( $endpoint, self::$_reserved ) ) {
			return;
		}
		$this -> endpoints[] = $endpoint;
	}

	/**
	 *
	 */
	public function init() {
		add_action( 'init', [ $this, 'endpoints_filter' ], 11 );
		add_action( 'init', [ $this, 'add_rewrite_endpoints' ], 12 );
		add_filter( 'query_vars', [ $this, 'add_query_vars' ] );
	}

	/**
	 *
	 */
	public function endpoints_filter() {
		$this -> endpoints = array_filter(
			$this -> endpoints,
			function( $var ) {
				return !( post_type_exists( $var ) || taxonomy_exists( $var ) );
			}
		);
	}

	/**
	 *
	 */
	public function add_rewrite_endpoints() {
		if ( empty( $this -> endpoints ) )
			return;

		/**
		 * rewrite rulesを取得
		 */
		global $wp_rewrite;
		$rules = $wp_rewrite -> wp_rewrite_rules();

		foreach ( $this -> endpoints as $endpoint ) {
			/**
			 * エンドポイントが既に DBのオプションテーブルに書き込まれていれば continue
			 * @see https://core.trac.wordpress.org/browser/tags/3.9.2/src/wp-includes/rewrite.php#L1274
			 */
			$search = $endpoint . '(/(.*))?/?$';
			if ( isset( $rules[$search] ) )
				continue;

			add_rewrite_endpoint( $endpoint, EP_ROOT );
			flush_rewrite_rules();
			_var_dump( 'flush! ' . $endpoint ); // test var
		}
	}

	/**
	 *
	 */
	public function add_query_vars( $vars ) {
		if ( !empty( $this -> endpoints ) ) {
			foreach ( $this -> endpoints as $endpoint ) {
				$vars[] = $endpoint;
			}
		}
		return $vars;
	}

	/**
	 *
	 */
	public static function is_endpoint( $endpoint ) {
		global $wp_query;
		return isset( $wp_query -> query[$endpoint] );
	}

}

/**
 * \wordpress\is_endpoint();
 *
 * @param  string Your custom endpoint.
 * @return bool
 */
if ( !function_exists( 'wordpress\is_endpoint' ) ) {
	function is_endpoint( $endpoint ) {
		return create_rewrite_endpoints::is_endpoint( $endpoint );
	}
}
