<?php

namespace mmsf\date;

/**
 *
 */
class calendar {

	const TIMEZONE = 'Asia/Tokyo';
/*
	const DEFAULT_CALENDAR = 'weekly';
	const DEFAULT_NUMBER_OF_WEEKS = 3;
	const DEFAULT_OFFSET_OF_WEEKS = 0;
	const DEFAULT_FIRST_DAY = 1; // Monday
	const DEFAULT_MONTH_OFFSET_FILL = false;
*/
	/**
	 * Type of calendar
	 *
	 * 'weekly' or 'monthly'
	 *
	 * @var string
	 */
	public $calendar = 'weekly';

	/**
	 * Days of the week, sorted according the setting.
	 *
	 * @var array
	 */
	public $order_of_daynames = [];

	/**
	 *
	 */
	public $number_of_days = 0;

	/**
	 * Calendar contents
	 *
	 * @var array
	 */
	public $contents = [];

	/**
	 * Index of target day (default is today) in $contents
	 *
	 * @var int
	 */
	public $index_of_date = 0;

	/**
	 * Index of prev month end & next month start.
	 * - Monthly calendar && fill margin, only
	 *
	 * @var int|null
	 */
	public $index_of_prev_month_end;
	public $index_of_next_month_start;

	private static $_daynames = [
		'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
	];

	private static $_first_day = 1; // = Monday or 0 = Sunday

	// private static $_number_of_days = 21;

	private static $_number_of_weeks = 3;
	private static $_offset_of_weeks = 0;

	private static $_month_offset_fill = false;

	private static $_date_formats = [ 'Y', 'n', 'j', 'l' ];

	private static $_callback = '';

	/**
	 * Setting of calendar
	 *
	 * @param mixed array of key & value set | string key & value
	 * - keys
	 *   -    'calendar' ... Type of calendar 'weekly'|'monthly'
	 *   -      'period' ... Days period (except 'monthly')
	 *   -      'offset' ...
	 *   -   'first_day' ...
	 *   - 'fill_margin' ...
	 */
	public function set( $arg ) {
		if ( $args = func_get_args() ) {
			if ( 2 === count( $args ) && is_string( $args[0] ) ) {
				$this -> _set_property( $args[0], $args[1] );
			} elseif ( 1 === count( $args ) && is_array( $args[0] ) ) {
				foreach ( $args[0] as $key => $val ) {
					if ( is_string( $key ) )
						$this -> _set_property( $key, $val );
				}
			}
		}
	}

	/**
	 * Acceptable args
	 * - $timestamp
	 * - $index
	 * - $index_of_date
	 */
	public function filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		if ( !is_callable( $function_to_add ) )
			return;
		self::$_callback = $tag;
		add_filter( "mmsf_calendar_{$tag}", $function_to_add, $priority, $accepted_args );
	}

	private function _set_property( $key, $val ) {
		switch ( $key ) {
			case 'calendar' :
				if ( in_array( $val, [ 'weekly', 'monthly'] ) )
					$this -> calendar = $val;
				break;
			case 'period' :
				if ( !$period = absint( $val ) )
					break;
				if ( 'weekly' === $this -> calendar )
					self::$_number_of_weeks = $period;
				break;
			case 'offset' :
				if ( !$offset = absint( $val ) )
					break;
				if ( 'weekly' === $this -> calendar ) {
					if ( $offset >= self::$_number_of_weeks )
						break;
					self::$_offset_of_weeks = $offset;
				}
				break;
			case 'first_day' :
				$w = absint( $val );
				if ( 0 <= $w && $w < 6 )
					self::$_first_day = $w;
				break;
			case 'fill_margin' :
				if ( 'monthly' !== $this -> calendar || !is_bool( $val ) )
					break;
				self::$_month_offset_fill = $val;
				break;
		}
	}

	/**
	 * @param unknown $date - 'strtotime' で解釈できるもの
	 */
	public function init( $date = null ) {
		$default_tz = date_default_timezone_get();

		// Set timezone.
		if ( $default_tz !== self::TIMEZONE )
			date_default_timezone_set( self::TIMEZONE );

		$this -> _timestamps( $date );

		// Reset timezone.
		if ( $default_tz !== self::TIMEZONE )
			date_default_timezone_set( $default_tz );

		$this -> number_of_days = count( $this -> contents );

		$n = self::$_first_day;
		$daynames = self::$_daynames;
		$array = [];
		for ( $i = 0; $i < 7 - self::$_first_day; $i++ ) {
			$array[$i] = $daynames[$n];
			$n++;
		}
		if ( 0 !== self::$_first_day ) {
			for ( $j = 0; $j < 7 - $i; $j++ ) {
				$array[$i + $j] = $daynames[$j];
			}
		}
		$this -> order_of_daynames = $array;
	}

	/**
	 * @param unknown $date
	 * @return array
	 */
	private function _timestamps( $date ) {
		$u = strtotime( $date ) ? strtotime( $date ) : time();
		$offset_pre  = 0;
		$offset_post = 0;
		switch ( $this -> calendar ) {
			case 'weekly' :
				$offset = $this -> _offset( $u ) + self::$_offset_of_weeks * 7;
				if ( 0 !== $offset ) {
					$ts = strtotime( $offset * -1 . ' days', $u );
				} else {
					$ts = $u;
				}
				$this -> index_of_date = $offset;
				$period = self::$_number_of_weeks * 7;
				break;
			case 'monthly' :
				$ts = strtotime( date( 'Y/n/1', $u ) );
				$_ts_end = strtotime( date( 'Y/n/t', $u ) );
				$period = intval( date( 't', $u ) );
				$_prev_len = $this -> _offset( $ts );
				if ( 6 !== $int = $this -> _offset( $_ts_end ) )
					$_next_len = 6 - $int;
				else
					$_next_len = 0;
				$this -> index_of_date = intval( date( 'j', $u ) ) - 1 + $_prev_len;
				if ( false === self::$_month_offset_fill ) {
					$offset_pre = $_prev_len;
					$offset_post = $_next_len;
				} elseif ( true === self::$_month_offset_fill ) {
					if ( 0 !== $_prev_len )
						$ts = strtotime( $_prev_len * -1 . ' days', $ts );
					$period = $period + $_prev_len + $_next_len;
					$this -> index_of_prev_month_end = $_prev_len;
					$this -> index_of_next_month_start = $period - $_next_len;
				}
				break;
		}
		$return = [];
		if ( $offset_pre ) {
			for ( $i = 0; $i < $offset_pre; $i++ )
				$return[] = null;
		}
		for ( $i = 0; $i < $period; $i++ ) {
			if ( '' !== $cb = self::$_callback ) {
				$index = $offset_pre + $i;
				$return[] = apply_filters( "mmsf_calendar_{$cb}", $ts, $index, $this -> index_of_date );
			} else {
				$return[] = $timestamp;
			}
			$ts = $ts + 86400;
		}
		if ( $offset_post ) {
			for ( $i = 0; $i < $offset_post; $i++ )
				$return[] = null;
		}
		$this -> contents = $return;
	}

	/**
	 *
	 */
	private function _offset( $timestamp ) {
		$offset = 0;
		$w = intval( date( 'w', $timestamp ) );
		if ( $w !== self::$_first_day ) {
			switch ( self::$_first_day ) {
				case 0 :
					$offset = $w;
					break;
				default :
					if ( 0 === $w )
						$offset = 7 - self::$_first_day;
					elseif ( self::$_first_day < $w )
						$offset = $w - self::$_first_day;
					else
						$offset = 7 - ( self::$_first_day - $w );
					break;
			}
		}
		return $offset;
	}

}
