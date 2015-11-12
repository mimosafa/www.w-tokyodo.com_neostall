<?php

if ( ! is_admin() ) :

    // Scripts
    function neosystem_admin_register_scripts() {
        // bootstrap 2.3.2
        wp_register_script(
            'bootstrap_2',
            get_stylesheet_directory_uri() . '/js/bootstrap.js',
            array( 'jquery' ),
            '2.3.2',
            true
        );
        // select2 3.4.2
        wp_register_script(
            'select2',
            get_stylesheet_directory_uri() . '/js/select2.min.js',
            array( 'jquery-ui-sortable' ),
            '3.4.2',
            true
        );
        // dropzone.js 3.7.1
        wp_register_script(
            'dropzone',
            get_stylesheet_directory_uri() . '/js/dropzone.min.js',
            array(),
            '3.7.1',
            true
        );
        // my scripts
        wp_register_script(
            'neosystem_admin_main',
            get_stylesheet_directory_uri() . '/js/main.js',
            array( 'jquery_cookie', 'jquery-ui-sortable', 'bootstrap_2' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/main.js' ) ),
            true
        );
        wp_register_script(
            'space',
            get_stylesheet_directory_uri() . '/js/space.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/space.js' ) ),
            true
        );
        wp_register_script(
            'kitchencar',
            get_stylesheet_directory_uri() . '/js/kitchencar.js',
            array( 'bootstrap_2', 'jquery-ui-selectable', 'jquery-ui-sortable' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/kitchencar.js' ) ),
            true
        );
        wp_register_script(
            'vendor',
            get_stylesheet_directory_uri() . '/js/vendor.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/vendor.js' ) ),
            true
        );
        wp_register_script(
            'series',
            get_stylesheet_directory_uri() . '/js/series.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/series.js' ) ),
            true
        );
        wp_register_script(
            'event',
            get_stylesheet_directory_uri() . '/js/event.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/event.js' ) ),
            true
        );
        wp_register_script(
            'archive-vendor',
            get_stylesheet_directory_uri() . '/js/archive-vendor.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/archive-vendor.js' ) ),
            true
        );
        wp_register_script(
            'archive-activity',
            get_stylesheet_directory_uri() . '/js/archive-activity.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/archive-activity.js' ) ),
            true
        );
        wp_register_script(
            'activity',
            get_stylesheet_directory_uri() . '/js/activity.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/activity.js' ) ),
            true
        );
        wp_register_script(
            'management',
            get_stylesheet_directory_uri() . '/js/management.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/management.js' ) ),
            true
        );
        wp_register_script(
            'archive-event',
            get_stylesheet_directory_uri() . '/js/archive-event.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/archive-event.js' ) ),
            true
        );
        wp_register_script(
            'archive-space',
            get_stylesheet_directory_uri() . '/js/archive-space.js',
            array( 'jquery' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/archive-space.js' ) ),
            true
        );
    }
    function neosystem_admin_print_script() {
        neosystem_admin_register_scripts();
        // Enqueue Scripts
        wp_enqueue_script( 'bootstrap_2' );
        wp_enqueue_script( 'select2' );
        wp_enqueue_script( 'dropzone' );
        // My Scripts
        wp_enqueue_script( 'mmsf' ); // MMSF jQuery Plugin !!!
        wp_enqueue_script( 'neosystem_admin_main' );
        if ( is_singular( 'space' ) )
            wp_enqueue_script( 'space' );
        if ( is_singular( 'kitchencar' ) ) {
            wp_enqueue_script( 'kitchencar' );
        }
        if ( is_singular( 'vendor' ) ) {
            wp_enqueue_script( 'vendor' );
        }
        if ( is_tax( 'series' ) ) {
            wp_enqueue_script( 'series' );
        }
        if ( is_singular( 'event' ) ) {
            wp_enqueue_script( 'event' );
        }
        if ( is_post_type_archive( 'vendor' ) ) {
            wp_enqueue_script( 'archive-vendor' );
        }
        if ( is_post_type_archive( 'activity' ) ) {
            wp_enqueue_script( 'archive-activity' );
        }
        if ( is_singular( 'activity' ) ) {
            wp_enqueue_script( 'activity' );
        }
        if ( is_singular( 'management' ) ) {
            wp_enqueue_script( 'management' );
        }
        if ( is_post_type_archive( 'event' ) ) {
            wp_enqueue_script( 'archive-event' );
        }
        if ( is_post_type_archive( 'space' ) ) {
            wp_enqueue_script( 'archive-space' );
        }
    }
    add_action( 'wp_print_scripts', 'neosystem_admin_print_script', 52 );

    // Styles
    function neosystem_admin_register_styles() {
        // style.css
        wp_register_style(
            'neosystem',
            get_stylesheet_uri(),
            array( 'bootstrap_2' ),
            date( 'YmdHis', filemtime( get_stylesheet_directory() . '/style.css' ) )
        );
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
        // select2 3.4.2
        wp_register_style(
            'select2',
            get_stylesheet_directory_uri() . '/css/select2.css',
            array(),
            '3.4.2'
        );
    }
    function neosystem_admin_print_styles() {
        neosystem_admin_register_styles();
        // Enqueue styles
        wp_enqueue_style( 'neosystem' );
        wp_enqueue_style( 'bootstrap_2' );
        wp_enqueue_style( 'font_awesome' );
        wp_enqueue_style( 'font_awesome_ie7' );
        wp_enqueue_style( 'select2' );
        $GLOBALS['wp_styles']->add_data( 'font_awesome_ie7', 'conditional', 'IE 7' );
        if ( is_singular( 'space' ) || is_singular( 'kitchencar' ) )
            wp_enqueue_style( 'mmsf_calendar' );
    }
    add_action( 'wp_print_styles', 'neosystem_admin_print_styles', 52 );

endif;