<?php

namespace activity;

/**
 * @param array $query_args
 */
class query {
	use \module\query;

	/**
	 * フロントエンドで未ログインユーザー向けに公開するか否か
	 */
	private $private_in_frontend = true;

	/**
	 * Default query arguments.
	 * - 基本的に非ログインユーザーに向けた設定で。
	 *
	 * @see http://codex.wordpress.org/Class_Reference/WP_Query
	 */
	private $query_args = [];

	private function init() {
		$args =& $this -> query_args;
		if ( is_user_logged_in() ) {
			$args['posts_per_page'] = 100;
			$args['order']    = 'DESC';
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = 'day';
		}
	}

}
