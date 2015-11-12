<?php

/**
 *
 */
class MenuContent {

	/**
	 * @var string 'Active'|'Pending'|'Absence'
	 */
	public $status = '';

	public $activity;
	public $kitchencar;
	public $vendor;

	public $name = 'undefined';

	public $items = []; // WP_Post Objects
	public $genres = []; // Term Objects(genre)
	public $text = ''; // string
	public $images1 = []; // Kitchencar images(post id)
	public $images2 = []; // Menu Items images(post id)

	private static $meta_keys = [];
	private static $_weeks = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];

	public static function get_instance( $post ) {
		if ( !$post = get_post( $post ) )
			return false;
		$post_type = $post -> post_type;
		if ( !in_array( $post_type, [ 'activity', 'kitchencar' ] ) )
			return false;
		return new static( $post, $post_type );
	}

	protected function __construct( $post, $post_type ) {
		switch ( $post_type ) {
			case 'kitchencar' :
				$this -> _post( $post, 'kitchencar' );
				$this -> status = 'Pending';
				$this -> activity = null;
				break;
			case 'activity' :
				$this -> _post( $post, 'activity' );
				$phase = (int) get_post_meta( $post -> ID, 'phase', true );
				switch ( $phase ) {
					case 2 :
						$this -> status = 'Active';
						break;
					case 9 :
						$this -> status = 'Absence';
						break;
				}
				break;
		}
		$this -> _name( $post -> ID, $post_type );
	}

	public function contents( $param = '' ) {
		/*
		if ( 'Active' === $this -> status ) {
		}
		if (
			empty( $this -> items )
			|| empty( $this -> genres )
			|| empty( $this -> text )
			|| empty( $this -> images1 )
			|| empty( $this -> images2 )
		) {
			$this -> _get_kitchencar_content( $param );
		}
		*/
		if ( null !== $this -> kitchencar )
			$this -> _get_kitchencar_content( $param );
	}

	private function _post( $post, $post_type ) {
		$array = [];
		$array['ID'] = $post -> ID;
		if ( in_array( $post_type, [ 'kitchencar', 'vendor' ] ) ) {
			$array['post_title'] = get_the_title( $post );
			$array['name'] = (string) get_post_meta( $post -> ID, 'name', true );
			$array['url'] = get_permalink( $post );
		}
		$this -> $post_type = $array;
		if ( 'activity' === $post_type && $_id = (int) get_post_meta( $post -> ID, 'actOf', true ) ) {
			$_post = get_post( $_id );
			if ( $_post && 'kitchencar' === $_post -> post_type ) {
				$this -> _post( $_post, 'kitchencar' );
			} else {
				$this -> kitchencar = null;
				$this -> vendor = null;
			}
		} elseif ( 'kitchencar' === $post_type && $_id = $post -> post_parent ) {
			$_post = get_post( $_id );
			if ( $_post && 'vendor' === $_post -> post_type ) {
				$this -> _post( $_post, 'vendor' );
			} else {
				$this -> vendor = null;
			}
		}
	}

	private function _name( $id, $post_type ) {
		$name = '';
		if ( 'activity' === $post_type )
			$name = (string) get_post_meta( $id, 'name', true );
		if ( '' === $name && null !== $this -> kitchencar ) {
			$kitchencar = $this -> kitchencar;
			$name = $kitchencar['name'] ? $kitchencar['name'] : $kitchencar['post_title'];
		}
		if ( '' !== $name )
			$this -> name = $name;
	}

	private function _get_kitchencar_content( $param = '' ) {

		if ( '' === $param )
			$param = $this -> _return_param();

		$this -> _meta_keys( $param );

		$_id = $this -> kitchencar['ID'];
		$post_meta_keys = get_post_custom_keys( $_id );
		foreach ( self::$meta_keys as $meta_key ) {
			if ( !in_array( $meta_key, $post_meta_keys ) )
				continue;
			$_content = get_post_meta( $_id, $meta_key, true );
			break;
		}
		if ( !isset( $_content ) || empty( $_content ) )
			return;
		// items
		if ( empty( $this -> items ) && isset( $_content['item'] ) && !empty( $_content['item'] ) ) {
			foreach ( (array) $_content['item'] as $_item_id ) {
				$_item = get_post( $_item_id );
				if ( 'menu_item' === $_item -> post_type )
					$this -> items[] = $_item;
			}
		}
		// genres
		if ( empty( $this -> genres ) && isset( $_content['genre'] ) && !empty( $_content['genre'] ) ) {
			foreach ( (array) $_content['genre'] as $_genre_id ) {
				$_genre = get_term( $_genre_id, 'genre' );
				if ( null !== $_genre && !is_wp_error( $_genre ) )
					$this -> genres[] = $_genre;
			}
		}
		// text
		if ( empty( $this -> text ) && isset( $_content['text'] ) && !empty( $_content['text'] ) ) {
			$this -> text = $_content['text'];
		}
		// images
		$_sizes = [ 'thumbnail', 'medium', 'large', 'full' ];
		// images1 (Kitchencar images)
		if ( empty( $this -> images1 ) && isset( $_content['img1'] ) && !empty( $_content['img1'] ) ) {
			foreach ( (array) $_content['img1'] as $_i => $_img1_id ) {
				$_img1 = get_post( $_img1_id );
				if ( 'attachment' !== $_img1 -> post_type )
					continue;
				foreach ( $_sizes as $size ) {
					$this -> images1[$_i][$size] = wp_get_attachment_image_src( $_img1_id, $size );
				}
			}
		}
		// images2 (Menu Items images)
		if ( empty( $this -> images2 ) && isset( $_content['img2'] ) && !empty( $_content['img2'] ) ) {
			foreach ( (array) $_content['img2'] as $_i => $_img2_id ) {
				$_img2 = get_post( $_img2_id );
				if ( 'attachment' !== $_img2 -> post_type )
					continue;
				foreach ( $_sizes as $size ) {
					$this -> images2[$_i][$size] = wp_get_attachment_image_src( $_img2_id, $size );
				}
			}
		}
	}

	private function _return_param() {
		global $post;
		$param = '';
		if ( $post ) {
			$post_type = $post -> post_type;
			switch ( $post_type ) {
				case 'space' :
					$param = 'space';
					break;
				case 'event' :
					$series = get_the_single_term( $post, 'series' );
					if ( $series ) {
						$param = $series -> slug;
					} else {
						$param = 'event';
					}
					break;
			}
		}
		return $param;
	}

	private function _meta_keys( $param ) {
		$param = strtolower( (string) $param );
		$_meta_keys = [];
		if ( 'space' === $param || in_array( $param, self::$_weeks ) ) {
			if ( in_array( $param, self::$_weeks ) )
				$_meta_keys[] = 'weekly_' . $param;
			$_meta_keys[] = 'weekly_0';
		} elseif ( 'event' === $param ) {
			$_meta_keys[] = 'event_0';
		} elseif ( get_term_by( 'slug', $param, 'series' ) ) {
			$_meta_keys[] = 'event_' . $param;
			$_meta_keys[] = 'event_0';
		}
		$_meta_keys[] = 'default_0';
		self::$meta_keys = $_meta_keys;
	}

}

/**
 *
 */
class MenuContentConverter extends MenuContent {

	public $_return = [];

	private static $_fake_image1 = 'http://fakeimg.pl/360x270/?text=No%20Image';
	private static $_fake_image2 = 'http://fakeimg.pl/150x150/?text=No%20Image';
/*
	public static function get_instance( $post ) {
		if ( !$post = get_post( $post ) )
			return false;
		$post_type = $post -> post_type;
		if ( !in_array( $post_type, [ 'activity', 'kitchencar' ] ) )
			return false;
		return new static( $post, $post_type );
	}
*/
	public function convert() {
		$this -> _return['ID'] = $this -> activity['ID'] ? $this -> activity['ID'] : $this -> kitchencar['ID'];
		$this -> _return['status'] = esc_js( $this -> status );
		$this -> _return['name'] = esc_js( esc_html( $this -> name ) );
		$this -> _return['url'] = esc_js( esc_url( $this -> kitchencar['url'] ) );
		if (
			!empty( $this -> items )
			|| !empty( $this -> genres )
			|| !empty( $this -> text )
			|| !empty( $this -> images1 )
			|| !empty( $this -> images2 )
		) {
			$this -> _items();
			$this -> _genres();
			$this -> _return['text'] = esc_js( esc_html( $this -> text ) );
			$this -> _image1();
			$this -> _image2();
		}
		return $this -> _return;
	}

	private function _items() {
		$_items = [];
		if ( !empty( $this -> items ) )
			foreach ( $this -> items as $_item )
				$_items[] = esc_js( esc_html( $_item -> post_title ) );
		$this -> _return['items'] = $_items;
	}

	private function _genres() {
		$_genres = [];
		if ( !empty( $this -> genres ) )
			foreach ( $this -> genres as $_genre )
				$_genres[] = esc_js( esc_html( $_genre -> name ) );
		$this -> _return['genres'] = $_genres;
	}

	private function _image1() {
		$_image1 = [];
		if ( !empty( $this -> images1 ) ) {
			$_size = wp_is_mobile() ? 'medium' : 'large';
			$array = $this -> images1[0][$_size];
			$_image1['src'] = esc_js( esc_url( $array[0] ) );
			$_image1['aspect'] = $array[1] / $array[2];
			if ( 1.34 <= $_image1['aspect'] )
				$_image1['class'] = 'horizontally';
			elseif ( 1.33 >= $_image1['aspect'] )
				$_image1['class'] = 'vertically';
			else
				$_image1['class'] = 'fit';
		} else {
			$_image1 = [
				'src' => esc_js( esc_url( self::$_fake_image1 ) ),
				'aspect' => 0,
				'class' => 'fit'
			];
		}
		$this -> _return['image1'] = $_image1;
	}

	private function _image2() {
		$_image2 = [];
		if ( !empty( $this -> images2 ) ) {
			$_image2 = [
				'src' => esc_js( esc_url( $this -> images2[0]['thumbnail'][0] ) ),
				'url' => esc_js( esc_url( $this -> images2[0]['full'][0] ) )
			];
		} else {
			$_image2 = [
				'src' => esc_js( esc_url( self::$_fake_image2 ) )
			];
		}
		$this -> _return['image2'] = $_image2;
	}

}

function _menu_content_convert( $post, $content = false, $param = '' ) {
	if ( !$instance = MenuContentConverter::get_instance( $post ) )
		return null;
	if ( $content )
		$instance -> contents( $param );
	return $instance -> convert();
}
function json_neoyatai_menu_content() {
	if ( isset( $_POST['postid'] ) && !empty( $_POST['postid'] ) ) {
		if ( is_array( $_POST['postid'] ) )
			$_array = $_POST['postid'];
		elseif ( !$_post = absint( $_POST['postid'] ) )
			die();
	} else {
		die();
	}
	if ( isset( $_POST['content'] ) && true === (bool) $_POST['content'] ) {
		$content = true;
	} else {
		$content = false;
	}
	if ( isset( $_POST['param'] ) && !empty( $_POST['param'] ) ) {
		$param = (string) $_POST['param'];
	} else {
		$param = '';
	}
	$return = [];
	if ( isset( $_post ) ) {
		$return = _menu_content_convert( $_post, $content, $param );
	} elseif ( isset( $_array ) ) {
		foreach ( $_array as $_post )
			if ( absint( $_post ) )
				$return[] = _menu_content_convert( $_post, $content, $param );
	}
	if ( null === $return )
		die();
	$json = json_encode( $return );
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $json;
	die();
}
add_action( 'wp_ajax_json_neoyatai_menu_content', 'json_neoyatai_menu_content' );
add_action( 'wp_ajax_nopriv_json_neoyatai_menu_content', 'json_neoyatai_menu_content' );