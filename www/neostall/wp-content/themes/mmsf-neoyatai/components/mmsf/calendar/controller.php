<?php

namespace mmsf\calendar;

/**
 *
 */
class controller {

	const TIMEZONE = 'Asia/Tokyo';

	const DEFAULT_CALENDAR = 'weekly';

	public static $instance;

	public static function get_instance() {
		if ( !isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
