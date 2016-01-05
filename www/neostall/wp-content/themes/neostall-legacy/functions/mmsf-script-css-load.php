<?php
/**********************************
 * SCRIPT LOAD
 **********************************/
if ( !is_admin() ) {
    function neostall_register_script() {
        wp_register_script(
            'lazyload',
            get_stylesheet_directory_uri() . '/js/vendor/jquery.lazyload.min.js',
            array( 'jquery' ),
            '1.8.4'
            // true
        );
        wp_register_script(
            'main',
            get_stylesheet_directory_uri() . '/js/main.js',
            array( 'jquery', 'lazyload' ),
            '',
            true
        );
        wp_register_script(
            'jqmodal_web_mugen',
            get_stylesheet_directory_uri() . '/js/jqmodal-web-mugen.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/jqmodal-web-mugen.js' ) ),
            true
        );
        wp_register_script(
            'event',
            get_stylesheet_directory_uri() . '/js/event.js',
            array( 'main' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/event.js' ) ),
            true
        );
        wp_register_script(
            'space',
            get_stylesheet_directory_uri() . '/js/space.js',
            array( 'main' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/space.js' ) ),
            true
        );
    }
    function neostall_enqueue_script() {
        neostall_register_script();
        wp_enqueue_script( 'lazyload' );
        wp_enqueue_script( 'main' );
        wp_enqueue_script( 'jqmodal_web_mugen' );
        if ( is_singular( 'event' ) || is_singular( 'season' ) ) {
            wp_enqueue_script( 'event' );
        }
        if ( is_singular( 'space' ) ) {
            wp_enqueue_script( 'space' );
        }
    }
    add_action( 'wp_enqueue_scripts', 'neostall_enqueue_script' );
}