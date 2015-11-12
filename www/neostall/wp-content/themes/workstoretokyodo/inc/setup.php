<?php
/**
 * Theme Setup
 *
 * @since 0.0.0
 */

/**
 * Register Auto Class Loader
 *
 * @since 0.0.0
 */
spl_autoload_register( 'wstd_classloader' );

/**
 * Theme Supports
 *
 * @since 0.0.0
 */
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );

/**
 * Register & Enqueue Scripts
 *
 * @since 0.0.0
 *
 * @uses  WSTD\Scripts::init()
 * @see   inc/scripts.php
 */
add_action( 'wp_enqueue_scripts', 'WSTD\\Scripts::init' );

/**
 * Class Loader
 *
 * @since 0.0.0
 *
 * @param  string $class # Class Name
 * @return void
 */
function wstd_classloader( $class ) {
	/**
	 * Workstore Tokyo Do Theme Applications Directory Path
	 *
	 * @var string
	 */
	static $app;
	if ( ! isset( $app ) ) {
		$app = TEMPLATEPATH . '/app';
	}
	$strings = explode( '\\', $class );
	if ( $n = count( $strings ) - 1 ) {
		if ( $strings[0] === 'WSTD' ) {
			$path = $app;
			for ( $i = 1; $i <= $n; $i++ ) {
				$path .= '/' . $strings[$i];
			}
			$path .= '.php';
			if ( is_readable( $path ) ) {
				/**
				 * File Load
				 */
				require_once $path;
			}
		}
	}
}
