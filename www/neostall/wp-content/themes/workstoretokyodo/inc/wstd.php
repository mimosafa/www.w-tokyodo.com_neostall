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
function wstd_one_phrase( $echo = true ) {
	$phrase = WSTD_ONE_PHRASE;
	if ( ! $echo ) {
		return esc_html( $phrase );
	}
	echo esc_html( $phrase );
}
