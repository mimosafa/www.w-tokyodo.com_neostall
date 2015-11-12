<?php
/*********************************************
 * RGISTER & ENQUEUE SCRIPTS & STYLES
 *********************************************/
if ( ! is_admin() ) :

    // Scripts
    function core_register_scripts() {
        // replace jQuery CDN
        wp_deregister_script( 'jquery' );
        wp_register_script(
            'jquery',
            '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
            array(),
            '1.10.2'
        );
        // Google Maps API v3 & gmaps.js
        wp_register_script( // Google Maps API v3
            'google_maps_api',
            '//maps.google.com/maps/api/js?sensor=false'
        );
        // modernizr
        wp_register_script(
            'modernizr',
            get_template_directory_uri() . '/js/modernizr-2.6.2-respond-1.1.0.min.js',
            array(),
            '2.6.2'
        );
        wp_register_script(
            'lazyload',
            get_template_directory_uri() . '/js/jquery.lazyload.min.js',
            array( 'jquery' ),
            '1.8.5',
            true
        );
        wp_register_script(
            'hcaptions',
            get_template_directory_uri() . '/js/jquery.hcaptions.js',
            array( 'jquery' ),
            '',
            true
        );
        wp_register_script(
            'jquery_cookie',
            get_template_directory_uri() . '/js/jquery.cookie.js',
            array( 'jquery' ),
            '1.3.1',
            true
        );
        // my scripts
        wp_register_script(
            'mmsf',
            get_template_directory_uri() . '/js/jquery.mmsf.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_template_directory() . '/js/jquery.mmsf.js' ) ),
            true
        );
        wp_register_script(
            'neosystem_cookie',
            get_template_directory_uri() . '/js/neosystem.cookie.js',
            array( 'jquery_cookie' ),
            date( 'YmdHis', filemtime( get_template_directory() . '/js/neosystem.cookie.js' ) ),
            true
        );
    }
    function core_print_script() {
        core_register_scripts();
        // Common Scripts
        wp_enqueue_script( 'modernizr' );
        wp_enqueue_script( 'google_maps_api' );
        if ( is_user_logged_in() ) {
            //wp_enqueue_script( 'jquery_cookie' );
            //wp_enqueue_script( 'neosystem_cookie' );
        }
        wp_enqueue_script( 'neoyatai_core' );
    }
    add_action( 'wp_print_scripts', 'core_print_script', 49 );

    // Styles
    function core_register_styles() {
        wp_register_style(
            'mmsf_calendar',
            get_template_directory_uri() . '/css/calendar.css',
            array(),
            date( 'YmdHis', filemtime( get_template_directory() . '/css/calendar.css' ) )
        );
    }
    function core_print_styles() {
        core_register_styles();
    }
    add_action( 'wp_print_styles', 'core_print_styles', 49 );

endif;