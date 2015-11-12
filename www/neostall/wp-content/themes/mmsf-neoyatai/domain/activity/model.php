<?php

namespace neoyatai\activity;

/**
 *
 */
class model {

	public $activity = [
		'ID' => 0,
		'status' => ''
	];

	public $day = '';

	public $kitchencar;
	// public $space OR public $event

	/**
	 * keys
	 * - name string
	 * - items array WP_Post Objects
	 * - genres array Term Objects(genre)
	 * - text string
	 * - images1 array int attachment ids // Kitchencar images
	 * - images2 array int attachment ids // Menu items images
	 */
	public $property = [];
	public $reserved_property = [];

	private static $_list_phase = [
		2 => 'Active',
		9 => 'Absence'
	];

	public function get_instance( $post = 0 ) {
		$post = get_post( $post );
		if ( !post || 'activity' !== get_post_type( $post ) )
			return false;
		return new static( $post );
	}

	private function __construct( $post ) {
		$this -> _post( $post );
	}

	/**
	 * @uses check_post_type
	 * @uses get_type_checked_post
	 */
	private function _post( $post ) {
		$id = $this -> activity['ID'] = $post -> ID;
		if ( $phase = (int) get_post_meta( $id, 'phase', true ) ) {
			if ( isset( self::$_list_phase[$phase] ) )
				$this -> activity['status'] = self::$_list_phase[$phase];
		}
		if ( $day = get_post_meta( $id, 'day', true ) ) {
			if ( self::checkdate_Ymd( $day ) )
				$this -> day = $day;
		}
		if ( $_id = (int) get_post_meta( $id, 'actOf', true ) ) {
			$this -> kitchencar = get_type_checked_post( $_id, 'kitchencar' );
		}
		foreach ( [ 'event', 'space' ] as $string ) {
			if ( $_id = (int) get_post_meta( $id, "{$string}_id", true ) ) {
				if ( check_post_type( $_id, $string ) )
					$this -> $string = get_post( $_id );
			}
		}
	}

	/**
	 * 与えられた文字列が日付形式 (Ymd)にマッチするか、また正しい日付か
	 *
	 * @param string $string
	 * @return bool
	 */
	public static function checkdate_Ymd( $string ) {
		if ( !preg_match( '/^\d{8}$/', $string ) )
			return false;
		return checkdate(
			substr( $string, 4, 2 ), // month
			substr( $string, -2 ), // day
			substr( $string, 0, 4 ) // year
		);
	}

}