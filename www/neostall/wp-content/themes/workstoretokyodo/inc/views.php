<?php
/**
 * Workstore Tokyo Do Theme Views
 *
 * @since 0.0.0
 */

/**
 * Very First Logo
 *
 * @since 0.0.0
 */
function wstd_very_first_logo() {
	if ( is_home() ) {
		wstd_one_phrase();
	}
	else {
		echo '<a href="' . home_url() . '"">Workstore Tokyo Do</a>';
	}
}
