<?php
/**
 * Utility Functions
 *
 * @since 0.0.0
 */

if ( ! function_exists( 'is_page_top' ) ) {
	/**
	 * Current Queried is Page & Top Page, OR Not
	 *
	 * @access public
	 *
	 * @since  0.0.0
	 * @return boolean
	 */
	function is_page_top() {
		if ( is_page() ) {
			$page = get_queried_object();
			return $page->post_parent === 0;
		}
		return false;
	}
}
