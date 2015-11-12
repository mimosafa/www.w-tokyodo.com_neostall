<?php

/****
 * REQUIRE PHPs
 **/
require_once( STYLESHEETPATH . '/functions/customs.php' );
require_once( STYLESHEETPATH . '/functions/scripts-styles.php' );
require_once( STYLESHEETPATH . '/functions/queries.php' );
require_once( STYLESHEETPATH . '/functions/functions.php' );

/****
 * remove admin-bar
 **/
function kill_admin_bar() {
    return false;
}
add_filter( 'show_admin_bar', 'kill_admin_bar', 1000 );

/****
 *
 **/
function neoyatai_theme_scripts_styles() {
    wp_enqueue_style( 'neoyatai-theme', get_stylesheet_uri(), array(), date( 'YmdHis', filemtime( get_stylesheet_uri() ) ) );
}
add_action( 'wp_enqueue_scripts', 'neoyatai_theme_scripts_styles' );