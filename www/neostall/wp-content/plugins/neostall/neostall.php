<?php
/**
 * Plugin Name: Neostall
 * Author: Toshimichi Mimoto
 */

add_action( 'plugins_loaded', '_init_neostall_plugin' );
function _init_neostall_plugin() {
	if ( class_exists( 'mimosafa\WP\Common' ) ) {
		require_once __DIR__ . '/bootstrap.php';
	}
}
