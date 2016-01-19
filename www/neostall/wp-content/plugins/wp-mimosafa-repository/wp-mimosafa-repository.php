<?php
/**
 * Plugin Name: WordPress Repository (Test)
 * Version: 0.1-alpha
 * Description: 
 * Author: Toshimichi Mimoto <mimosafa@gmail.com>
 * Author URI: http://mimosafa.me
 * Plugin URI: 
 * Text Domain: wp-mimosafa-repository
 * Domain Path: /languages
 * @package Wp-mimosafa-repository
 */
namespace mimosafa;

if ( ! class_exists( 'mimosafa\WP\Common' ) ) {
	require_once __DIR__ . '/vendor/mimosafa/wp-common/common.php';
	WP\Common::init();
}
ClassLoader::register( 'mimosafa\WP\Repository', __DIR__ . '/vendor/mimosafa/wp-repository' );
