<?php

namespace vendor;

class query {
	use \module\query;

	private $query_args = [

		'nopaging' => true,
		'order'   => 'ASC',
		'orderby' => 'meta_value_num',
		'meta_key'   => 'serial',

	];

	private function init() {
		if ( is_admin() ) {
			$this -> query_args['nopaging'] = false;
		}
	}

}
