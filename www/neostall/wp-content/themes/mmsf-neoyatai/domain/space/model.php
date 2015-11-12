<?php

namespace neoyatai\space;

/**
 *
 */
class model {

	public $name = '';

	/**
	 * @var bool
	 */
	public $frontend = false;

	public $data = [
		'ID' => 0,
		'post_title' => '',
		'name' => '',
		'url' => '',
		'schedule_type' => '',
		'phase' => 0,
		'publication' => ''
	];

	public $region = [];

	public $location = [];

	public $open = [];

	public $kitchencars = [];

	private static $_weeks = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];
	private static $_calendar = [ 'week', 4 ];

	/**
	 * Initialize 'Space'
	 */
	public static function get_instance( $post = 0 ) {
		$post = get_post( $post );
		if ( !$post || !check_post_type( $post, 'space' ) )
			return false;
		return new self( $post );
	}

	private function __construct( $post ) {
		$this -> _post( $post );
		$this -> _status();
		/*
		if ( !$this -> frontend )
			return;
		*/
		$this -> _name();
		$this -> _region( $post );
		$this -> _location( $post );
		$this -> _open( $post );
		if ( 'flex' !== $this -> data['schedule_type'] )
			$this -> _kitchencars( $post );
	}

	private function _post( $post ) {
		$this -> data['ID'] = $post -> ID;
		$this -> data['post_title'] = get_the_title( $post );
		if ( $_name = get_post_meta( $post -> ID, 'name', true ) )
			$this -> data['name'] = $_name;
		$this -> data['url'] = get_permalink( $post );
		$this -> data['schedule_type'] = get_post_meta( $post -> ID, 'sche_type', true );
		$this -> data['phase'] = (int) get_post_meta( $post -> ID, 'phase', true );
	}

	/**
	 * @uses class \mmsf\date() /mmsf-neoyatai/libs/mmsf/date.php
	 */
	private function _status() {
		$_publication = get_post_meta( $this -> data['ID'], 'publication', true );
		if ( !$_publication )
			return;
		if ( 1 !== absint( $_publication ) ) {
			$pattern = '/\A([0-9]{4})\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])\z/';
			if ( !preg_match( $pattern, $_publication ) ) {
				return;
			} elseif ( time() > \mmsf\date::strtotime( $_publication ) ) {
				return;
			}
		}
		/*
		$_phase = $this -> data['phase'];
		$_schedule_type = $this -> data['schedule_type'];
		*/
		$this -> frontend = true;
	}

	private function _name() {
		$this -> name = '' !== $this -> data['name'] ? $this -> data['name'] : $this -> data['post_title'];
	}

	private function _region( $post ) {
		$this -> region = get_the_region_objects( $post );
	}

	/**
	 *
	 */
	private function _location( $post ) {
		foreach ( [ 'address', 'site', 'areaDetail', 'latlng' ] as $str ) {
			$this -> location[$str] = get_post_meta( $post -> ID, $str, true );
		}
	}

	/**
	 *
	 */
	private function _open( $post ) {
		$this -> open['starting'][] = get_post_meta( $post -> ID, 'starting', true );
		if ( get_post_meta( $post -> ID, 'startingPending', true ) )
			$this -> open['starting']['pending'] = 1;
		$this -> open['ending'][] = get_post_meta( $post -> ID, 'ending', true );
		if ( get_post_meta( $post -> ID, 'endingPending', true ) )
			$this -> open['ending']['pending'] = 1;
	}

	/**
	 *
	 */
	private function _kitchencars( $post ) {
		$post_id = $post -> ID;
		foreach ( self::$_weeks as $key ) {
			if ( $_cars = get_post_meta( $post_id, $key, true ) )
				$this -> kitchencars[ucwords($key)] = $_cars;
		}
	}

	/**
	 * Set 'space' contents
	 *
	 * @uses class \mmsf\date() /mmsf-neoyatai/libs/mmsf/date.php
	 */
	public function set_contents() {
		$_d = new \mmsf\date(); // <- あたまの'\'、ハマった …
		call_user_func_array( [ $_d, 'init' ], self::$_calendar );
		$_days = $_d -> get_formatted_days( 'l', 'Ymd' );
		$_offset = (int) \mmsf\date::date( 'N' ) - 1; // offset 'Monday' start.
		$n = 0;
		foreach ( $_days as $i => $_date ) {
			$this -> contents[$i]['l'] = $_date['l'];
			$this -> contents[$i]['Ymd'] = $_date['Ymd'];
			$_posts = $this -> _get_activity( $_date['Ymd'], $_date['l'] );
			if ( $_offset <= $i && $n < 7 ) {
				if ( !empty( $_posts ) ) {
					$this -> active_day[] = $_date['l'];
					$this -> contents[$i]['contents'] = $_posts;
				}
				$n++;
			} else {
				if ( !empty( $_posts ) ) {
					$this -> contents[$i]['contents'] = $_posts;
				}
			}
		}
		$this -> date_offset = $_offset;
	}

	/**
	 *
	 */
	private function _get_activity( $time_Ymd = '', $time_l = '' ) {
		$array = [];
		if ( $activities = $this -> _get_posts( 'activity', $time_Ymd ) ) {
			$_activity = [];
			$_absence = [];
			foreach ( $activities as $activity ) {
				$phase = get_post_meta( $activity, 'phase', true );
				if ( 2 == $phase )
					$_activity[] = $activity;
				elseif ( 9 == $phase )
					$_absence[] = $activity;
			}
			if ( !empty( $_activity ) )
				$array['activity'] = $_activity;
			if ( !empty( $_absence ) )
				$array['absence'] = $_absence;
		} else {
			$dayname = $time_l;
			if ( isset( $this -> kitchencars[$dayname] ) && !empty( $this -> kitchencars[$dayname] ) )
				$array['pending'] = $this -> kitchencars[$dayname];
		}
		if ( $managements = $this -> _get_posts( 'management', $time_Ymd ) ) {
			foreach ( $managements as $management ) {
				$type = (string) get_post_meta( $management, 'type', true );
				if ( 'off' === $type )
					$array['off'][] = $management;
				else
					$array['management'][] = $management;
			}
		}
		return $array;
	}

	/**
	 *
	 */
	private function _get_posts( $post_type, $time_Ymd ) {
		$_posts = get_posts(
			[
				'post_type' => $post_type,
		        'posts_per_page' => -1,
		        'meta_query'  => [
		            [
		                'key'     => 'space_id',
		                'value'   => $this -> data['ID'],
		                'compare' => '='
		            ], [
		                'key'     => 'day',
		                'value'   => $time_Ymd,
		                'compare' => '='
		            ]
	            ]
			]
		);
		if ( !empty( $_posts ) ) {
			$_id = [];
			foreach ( $_posts as $_post )
				$_id[] = $_post -> ID;
			return $_id;
		}
		return null;
	}

}