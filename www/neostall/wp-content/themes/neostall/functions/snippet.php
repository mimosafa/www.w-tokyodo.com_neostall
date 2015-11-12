<?php

/**
 * admin bar を消す
 */
/*
function kill_admin_bar() {
    add_filter( 'show_admin_bar', '__return_false', 1000 );
    if ( class_exists( 'crazy_bone' ) ) {
        function deregister() {
            wp_deregister_script( 'wp-pointer' );
            wp_deregister_style( 'wp-pointer' );
        }
        add_action( 'wp_enqueue_scripts', 'deregister', 9999 );
    }
}
add_action( 'init', 'kill_admin_bar' );
*/