<?php
/*
Plugin Name: Neostall CSV Exporter
Description: CSVエクスポート用
Author: mimosafa
Version: 1.1
Author URI: http://mimosafa.me
*/

if ( ! is_admin() )
	return;

add_action( 'plugins_loaded', function() {

	if ( ! class_exists( 'Settings_Page_Helper' ) ) {
		return;
	}
	$page = new Settings_Page_Helper();
	$page->init( 'edit.php?post_type=activity' );
	$page->init( 'activity-csv-exporter', 'CSV Exporter' );
	$page->done();

} );
