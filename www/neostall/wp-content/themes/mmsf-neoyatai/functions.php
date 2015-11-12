<?php

/**
 * Include library
 */
require_once __DIR__ . '/libs/mmsf/date.php';

/**
 * Run neoyatai controller
 */
require_once __DIR__ . '/controllers/neoyatai.php';


add_action( 'after_setup_theme', function() {
	PhpFilesLoader::init( __DIR__ . '/models/neoyatai' );
	PhpFilesLoader::init( __DIR__ . '/views/elements' );
} );


/**
 * default scripts and styles enqueue
 * - bootstrap 3.1.1
 * - fontawesome 4.0.3
 * - modernizr 2.7.1
 * ...and theme style, script
 */
add_action( 'wp_enqueue_scripts', function() {
	if ( !is_admin() ) {
		// styles
		wp_enqueue_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.css', array(), '3.1.1' );
		wp_enqueue_style( 'font-awesone', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array( 'bootstrap' ), '4.0.3' );
		wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), date( 'YmdHis', filemtime( get_stylesheet_directory() . '/style.css' ) ) );
		// wstd icon font
		$path = '/css/tokyodo2014.css';
		wp_enqueue_style( 'tokyodo2014', get_stylesheet_directory_uri() . $path, array(), date( 'YmdHis', filemtime( get_stylesheet_directory() . $path ) ) );
		// scripts
		wp_enqueue_script( 'theme-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ), date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/script.js' ) ), true );
		# wp_enqueue_script( 'modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js', array(), '2.7.1' );
		wp_enqueue_script( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', array( 'jquery' ), '3.1.1', true );
	}
} );