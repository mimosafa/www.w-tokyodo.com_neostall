<?php

namespace module;

/**
 *
 */
trait query {

	public function __construct() {

		if ( method_exists( $this, 'init' ) )
			$this -> init();

		if (
			isset( $this -> private_in_frontend )
			&& true === $this -> private_in_frontend
			&& !is_admin()
		)
			$this -> forbidden();

		if ( $this -> query_args && is_array( $this -> query_args ) )
			$this -> pre_get_posts();

	}

	/**
	 *
	 */
	private function pre_get_posts() {
		add_action( 'pre_get_posts', [ $this, 'main_query' ] );
	}

	/**
	 *
	 */
	public function main_query( $query ) {
		if ( !$query -> is_main_query() || $query -> is_singular() )
			return;
		foreach ( $this -> query_args as $key => $val ) {
			$query -> set( $key, $val );
		}
	}

	/**
	 * Force 403 forbidden for not permitted user.
	 */
	private function forbidden() {
		//status_header( 403 );
		header( 'HTTP/1.1 403 Forbidden' );
		echo '<h1>403 Forbidden</h1>';
		die();
	}

}
