<?php

namespace event;

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

		'order'    => 'ASC',
		'orderby'  => 'meta_value',
		'meta_key' => 'day',

		'meta_query' => [
			[
				'key'     => 'day',
				'compare' => '>=',
				// 'value' => date_i18n( 'Ymd' ), # init methodで追加
				'type'    => 'DATE'
			], [
				'key'     => 'publication',
				'compare' => '<=',
				// 'value' => date_i18n( 'Y-m-d H:i:s' ), # init methodで追加
				'type'    => 'DATETIME'
			]
		],

	];

	private function init() {
		$args =& $this -> query_args;

		if ( is_admin() ) {
			$args = [];
			return;
		}

		$args['meta_query'][0]['value'] = date_i18n( 'Ymd' );
		if ( isset( $_GET['past'] ) ) {
			$args['nopaging'] = false;
			$args['posts_per_page'] = 10;
			$args['order'] = 'DESC';
			$args['meta_query'][0]['compare'] = '<';
		}
		if ( !is_user_logged_in() ) {
			$args['meta_query'][1]['value'] = date_i18n( 'Y-m-d H:i:s' );
		} else {
			unset( $args['meta_query'][1] );
		}
	}

}
