<?php

namespace space;

/**
 * @param array $query_args
 */
class query {
	use \module\query;

	/**
	 * Default query arguments.
	 * - 基本的に非ログインユーザーに向けた設定で。
	 *
	 * @see http://codex.wordpress.org/Class_Reference/WP_Query
	 */
	private $query_args = [

		'nopaging' => true,

		'order'   => 'ASC',
		'orderby' => 'meta_value_num',
		'meta_key'   => 'serial',

		'meta_query' => [
			[
				'key'     => 'publication',
				'compare' => '!=',
				'value'   => 0,
			], [
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
