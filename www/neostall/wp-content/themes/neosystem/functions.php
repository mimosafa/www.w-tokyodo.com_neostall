<?php

get_template_part( 'class/customs' );
get_template_part( 'class/scripts-styles' );
get_template_part( 'class/queries' );
get_template_part( 'class/class' );
get_template_part( 'functions/helper' );

/**
 * Enqueues scripts and styles for front end.
 *
 * @since 0.0.0
 *
 * @return void
 */

function mmsf_default_scripts_styles() {
    if ( !is_admin() ) {
    	// styles
        wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array( 'bootstrap' ), date( 'YmdHis', filemtime( get_stylesheet_directory() . '/style.css' ) ) );
        // scripts
        wp_enqueue_script( 'theme-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ), date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/script.js' ) ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'mmsf_default_scripts_styles' );


/**
 * Sets up theme
 *
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since 0.0.0
 *
 * @return void
 */
function neosystem_setup() {
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'neosystem_setup' );


/**
 * Snippet
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