<?php
/**
 * Workstore Tokyo Do Specific Functions & Processing
 *
 * @since 0.0.0
 */
if ( ! defined( 'WSTD_ONE_PHRASE' ) ) {
	define( 'WSTD_ONE_PHRASE', '食を通じて「賑わい」と「笑顔」と「思い出」を作るプロフェッショナル!!' );
}

/**
 * Workstore Tokyo Do One Phrase
 *
 * @since 0.0.0
 *
 * @uses WSTD_ONE_PHRASE
 *
 * @param  boolean $echo
 * @return string|void
 */
function wstd_one_phrase() {
	return esc_html( WSTD_ONE_PHRASE );
}

/**
 * Functions for Workstore Tokyo Do Divisions
 *
 * @since 0.0.0
 * @uses  WSTD\Divisions
 */

function wstd_home_url( $division = null ) {
	return WSTD\Divisions::home_url( $division );
}

function wstd_current_home_url() {
	return current_wstd_division( 'home_url' ) ?: wstd_home_url();
}

function current_wstd_division( $field = null ) {
	if ( ! filter_var( $field ) ) {
		return WSTD\Divisions::init()->get_division();
	}
	return WSTD\Divisions::init()->$field;
}

function is_wstd_division( $division = null ) {
	return WSTD\Divisions::init()->is_division( $division );
}

function is_wstd_division_home( $division = null ) {
	return WSTD\Divisions::init()->is_division_home( $division );
}

function is_wstd_home() {
	/**
	 * @todo
	 */
	return false;
}

function wstd_has_header_image() {
	/**
	 * @todo
	 */
	return is_wstd_division_home();
}

function wstd_has_global_nav() {
	/**
	 * @todo
	 */
	return true;
	#return false;
}
