<?php

namespace kitchencar;

/**
 * @param array $query_args
 */
class query {
	use \module\query;

	/**
	 * Query arguments
	 *
	 * @see http://codex.wordpress.org/Class_Reference/WP_Query
	 */
	private $query_args = [

		// ページ送り引数
		'nopaging' => true,

		// 順序＆順序ベースパラメータ
		'order'   => 'ASC',
		'orderby' => 'meta_value_num menu_order',

		// カスタムフィールドパラメータ
		'meta_key'   => 'serial',
		'meta_query' => [
			[
				'key'     => 'phase',
				'compare' => '=',
				'value'   => 1,
			],
		],

	];

	private function init() {
		if ( is_admin() ) {
			$this -> query_args['nopaging'] = false;
			unset( $this -> query_args['meta_query'] );
		}
	}

}
