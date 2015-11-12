<?php

/**
 * hide adminbar
 */
/*
add_filter( 'show_admin_bar', '__return_false', 1000 );
//*/

/**
 * リモートサーバー情報で条件分岐
 *
 * @param $addr REMOTE_ADDR: default is W.S.T.D. Office in Higashi-yaguchi, Kamata-ku.
 */
if ( !function_exists( 'remote_addr_check' ) ) {
	function remote_addr_check( $addr = '211.3.110.183' ) {
		if ( $_SERVER['REMOTE_ADDR'] === $addr )
			return true;
		return false;
	}
}
