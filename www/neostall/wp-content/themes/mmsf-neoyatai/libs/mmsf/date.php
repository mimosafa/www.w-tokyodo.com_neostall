<?php

namespace mmsf;

/**
 *
 */
class date {

	const TIMEZONE = 'Asia/Tokyo';

	private $date_unit = 'day'; // 'day' | 'week' | 'month'
	private $days = 21;
	private $weeks = 3;
	private $first_day = 1; // 0 (Sunday) | 1 (Monday)

	/**
	 * 取得する日付情報の初期化
	 *
	 * @param string $unit day|week|month
	 * @param int $length
	 * @param int $first_day 0|1
	 */
	public function init( $unit = 'day', $length = 0, $first_day = 1 ) {
		/**
		 * 取得する日付情報の区切り。デフォルトは区切りなし（日）。
		 */
		if ( in_array( $unit, [ 'week', 'month' ] ) )
			$this -> date_unit = $unit;
		/**
		 * 取得する期間。 $unitが 'week' or 'day'の場合のみ
		 */
		$length = abs( intval( $length ) );
		if ( 'month' !== $this -> date_unit && 0 !== $length ) {
			$_unit = $this -> date_unit;
			switch ( $_unit ) {
				case 'week' :
					$this -> weeks = $length;
					break;
				case 'day' :
					$this -> days = $length;
					break;
			}
		}
		/**
		 * カレンダーの開始曜日（日曜日(0)か月曜日(1)、デフォルトは月曜日
		 */
		if ( 0 === $first_day )
			$this -> first_day = $first_day;
	}

	/**
	 * 指定した期間のタイムスタンプを配列で取得
	 *
	 * @uses self::strtotime()
	 *
	 * @param string $start 有効な日付書式。デフォルトは今日。 Optional.
	 * @return array
	 */
	function get_timestamps( $start = '' ) {
		$timestamp = self::strtotime( $start );
		if ( false === $timestamp )
			$timestamp = time();
		$unit = $this -> date_unit;
		switch ( $unit ) {
			case 'day':
				$length = $this -> days;
				break;
			case 'week':
				if ( 0 !== $diff = $this -> diff( $timestamp ) )
					$timestamp = strtotime( $diff . ' days', $timestamp );
				$length = $this -> weeks * 7;
				break;
			case 'month':
				$timestamp = strtotime( date( 'Y/n/1', $timestamp ) );
				if ( 0 !== $diff = $this -> diff( $timestamp ) )
					$offset_prev = $diff * -1; // カレンダーの前月分（空）
				$period = strtotime( date( 'Y/n/t', $timestamp ) );
				if ( -6 !== $diff = $this -> diff( $period ) )
					$offset_next = 6 + $diff; // カレンダーの次月分（空）
				$length = ( $period - $timestamp ) / 86400 + 1;
				break;
		}
		$timestamps = array();
		if ( isset( $offset_prev ) )
			for ( $i = 0; $i < $offset_prev; $i++ )
				$timestamps[] = null;
		for ( $i = 0; $i < $length; $i++ ) {
			$timestamps[] = $timestamp;
			$timestamp = $timestamp + 86400;
		}
		if ( isset( $offset_next ) )
			for ( $i = 0; $i < $offset_next; $i++ )
				$timestamps[] = null;
		return $timestamps;
	}

	/**
	 * @param string $format 'date'関数で解釈できる日付フォーマットをいくつでも
	 */
	function get_formatted_days( $format ) {
		$return = array();
		$_formats = func_get_args();
		$_timestamps = $this -> get_timestamps();
		// Time Zone set
		$default_timezone = date_default_timezone_get();
		if ( $default_timezone !== self::TIMEZONE )
			date_default_timezone_set( self::TIMEZONE );
		// set $return
		foreach ( $_timestamps as $i => $timestamp ) {
			if ( null === $timestamp ) {
				$return[$i] = null;
				continue;
			}
			$return[$i] = array();
			foreach ( $_formats as $_format ) {
				$return[$i][$_format] = date( $_format, $timestamp );
			}
		}
		// Time Zone reset
		if ( $default_timezone !== self::TIMEZONE )
			date_default_timezone_set( $default_timezone );
		return $return;
	}

	/**
	 * 内部関数: 与えられた日付が
	 *
	 * @param int $timestamp
	 * @return int
	 */
	private function diff( $timestamp ) {
		$diff = 0;
		$w = intval( self::date( 'w', $timestamp ) );
		if ( $w !== $this -> first_day )
			if ( 0 === $this -> first_day ) // Sunday start
				$diff = $w * -1;
			else							// Monday start
				if ( 0 === $w )
					$diff = -6;
				else
					$diff = ( $w - 1 ) * -1;
		return $diff;
	}

	/**
	 * ローカルのタイゾーンで strtotime( $time )
	 *
	 * @param string $time
	 * @return int Unix タイムスタンプ
	 */
	public static function strtotime( $time ) {
		$default_timezone = date_default_timezone_get();
		if ( $default_timezone === self::TIMEZONE )
			return strtotime( $timestamp );
		date_default_timezone_set( self::TIMEZONE );
		$timestamp = strtotime( $time );
		date_default_timezone_set( $default_timezone );
		return $timestamp;
	}

	/**
	 * ローカルのタイゾーンで date()
	 *
	 * @param string $format
	 * @param $timestamp
	 * @return
	 */
	public static function date( $format, $timestamp = '' ) {
		$timestamp = '' !== $timestamp ? $timestamp : time();
		$default_timezone = date_default_timezone_get();
		if ( $default_timezone === self::TIMEZONE )
			return date( $format, $timestamp );
		date_default_timezone_set( self::TIMEZONE );
		$date = date( $format, $timestamp );
		date_default_timezone_set( $default_timezone );
		return $date;
	}

}