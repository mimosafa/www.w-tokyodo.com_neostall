<?php

/**
 *
 */
class mmsf_var_dump {

	public $_var;
	private $frontend_fook = 'mmsf_var_dump';

	function __construct( $var ) {
		$this -> _var = $var;
		$hook = !is_admin() ? $this -> frontend_fook : 'admin_notices';
		add_action( $hook, function() {
			echo '<pre>';
			var_dump( $this -> _var );
			echo '</pre>';
		} );
	}
}

/**
 *
 */
if ( !function_exists( '_var_dump' ) ) {
	function _var_dump( $var ) {
		new mmsf_var_dump( $var );
	}
}