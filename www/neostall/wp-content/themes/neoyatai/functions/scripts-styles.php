<?php

if ( !is_admin() ) :

    // Scripts
    function neoyatai_register_scripts() {
        // bootstrap 2.3.2
        wp_register_script(
            'bootstrap_2',
            get_stylesheet_directory_uri() . '/js/bootstrap.js',
            array( 'jquery' ),
            '2.3.2',
            true
        );
        // my scripts
        wp_register_script(
            'neoyatai_main',
            get_stylesheet_directory_uri() . '/js/main.js',
            array( 'jquery', 'lazyload', 'hcaptions' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/main.js' ) ),
            true
        );
        wp_register_script(
            'neoyatai_single_space',
            get_stylesheet_directory_uri() . '/js/single-space.js',
            array( 'jquery', 'bootstrap_2' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/single-space.js' ) ),
            true
        );
        wp_register_script(
            'neoyatai_kitchencar',
            get_stylesheet_directory_uri() . '/js/kitchencar.js',
            array( 'jquery', 'bootstrap_2', 'hcaptions' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/kitchencar.js' ) ),
            true
        );
    }
    function neoyatai_print_script() {
        neoyatai_register_scripts();
        // Enqueue scripts
        wp_enqueue_script( 'bootstrap_2' );
        wp_enqueue_script( 'lazyload' );
        wp_enqueue_script( 'hcaptions' );
        // My Scripts
        wp_enqueue_script( 'neoyatai_main' );
        if ( is_singular( 'space' ) )
            wp_enqueue_script( 'neoyatai_single_space' );
        if (
            is_singular( 'space' )
            || is_singular( 'event' )
            || is_singular( 'season' )
            || is_post_type_archive( 'kitchencar' )
        )
            wp_enqueue_script( 'neoyatai_kitchencar' );
    }
    add_action( 'wp_print_scripts', 'neoyatai_print_script', 51 );

    // Styles
    function neoyatai_register_styles() {
        // bootstrap 2.3.2
        wp_register_style(
            'bootstrap_2',
            get_stylesheet_directory_uri() . '/css/bootstrap.css',
            array(),
            '2.3.2'
        );
        // fontawesome 3.2.1
        wp_register_style(
            'font_awesome',
            get_stylesheet_directory_uri() . '/css/font-awesome.min.css',
            array(),
            '3.2.1'
        );
        wp_register_style(
            'font_awesome_ie7',
            get_stylesheet_directory_uri() . '/css/font-awesome-ie7.min.css',
            array(),
            '3.2.1'
        );
        // my styles
        wp_register_style(
            'neoyatai_main',
            get_stylesheet_directory_uri() . '/css/main.css',
            array(),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/css/main.css' ) )
        );
        wp_register_style(
            'neoyatai_kitchencar',
            get_stylesheet_directory_uri() . '/css/kitchencar.css',
            array(),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/css/kitchencar.css' ) )
        );
    }
    function neoyatai_print_styles() {
        neoyatai_register_styles();
        // Enqueue styles
        wp_enqueue_style( 'bootstrap_2' );
        wp_enqueue_style( 'font_awesome' );
        wp_enqueue_style( 'font_awesome_ie7' );
        $GLOBALS['wp_styles']->add_data( 'font_awesome_ie7', 'conditional', 'IE 7' );
        //
        wp_enqueue_style( 'neoyatai_main' );
        if ( is_singular( 'space' ) )
            wp_enqueue_style( 'mmsf_calendar' );
        if (
            is_singular( 'space' )
            || is_singular( 'event' )
            || is_singular( 'season' )
            || is_post_type_archive( 'kitchencar' )
        )
            wp_enqueue_style( 'neoyatai_kitchencar' );
    }
    add_action( 'wp_print_styles', 'neoyatai_print_styles', 51 );

endif;