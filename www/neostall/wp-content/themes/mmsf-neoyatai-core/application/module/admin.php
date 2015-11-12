<?php

namespace module;

/**
 * This is 'Trait',
 * must be used in '\(domain)\admin' class.
 *
 * @uses \wordpress\admin\enable_form
 * @uses \(domain)\properties
 */
trait admin {

	/**
	 * @var string Dmain's name.
	 */
	private $domain;

	/**
	 * @var string post_type|taxonomy|(endpoint)
	 */
	private $registered;

	/**
	 * @var string Registered name of domain on system.
	 */
	private $registeredName;

	/**
	 * @var string
	 */
	private $_box_id_prefix = 'custom-meta-box-';

	/**
	 * @var null|object \(domain)\properties
	 */
	private static $properties = null;

	/**
	 * @var null|object \admin\meta_box_inner
	 */
	private static $metaBoxInner = null;

	/**
	 *
	 */
	private static $_supports = [
		'title', 'editor', 'author', 'thumbnail', 'excerpt',
		'trackbacks', 'custom-fields', 'comments', 'revisions',
		'page-attributes', 'post-formats',
	];

	/**
	 * @uses \mmsf\getObjectNamespace()
	 */
	public function __construct() {

		/**
		 * Define domain name.
		 */
		$this -> domain = \mmsf\getObjectNamespace( $this );

		/**
		 *
		 */
		if ( !$this -> _domain_data() ) {
			return;
		}

		if ( 'post_type' === $this -> registered ) {
			/**
			 * post type supports
			 */
			$this -> post_type_supports();
		} else if ( 'taxonomy' === $this -> registered ) {
			// yet!
		}

		/**
		 *
		 */
		if ( isset( $this -> meta_boxes ) && !empty( $this -> meta_boxes ) ) {

			$box_method = $this -> registered . '_meta_box';
			add_action( 'add_meta_boxes', [ $this, $box_method ] );

			if ( 'post_type' === $this -> registered ) {
				new \admin\save_post( $this -> registeredName );
			}

		}

	}

	/**
	 *
	 */
	private function _domain_data() {
		$property_file  = trailingslashit( TEMPLATEPATH ) . 'domains/';
		$property_file .= $this -> domain . '/properties.php';

		if ( !is_readable( $property_file ) ) {
			return false; // throw error
		}

		/**
		 * properties.phpのヘッダーコメントを読み込み
		 *
		 * @see http://dogmap.jp/2014/09/10/post-3109/
		 */
		$property = get_file_data( $property_file, [
			'register'  => 'Register As',
			'post_type' => 'Post Type Name',
			'taxonomy'  => 'Taxonomy Name',
			'rewrite'   => 'Permalink Format'
		] );
		$property = array_filter( $property );

		if ( !array_key_exists( 'register', $property ) ) {
			return false; // throw error
		}

		if ( 'Custom Post Type' === $property['register'] ) {

			$this -> registered = 'post_type';

			/**
			 * readonly slug, if 'Permalink' set 'ID'.
			 */
			if ( array_key_exists( 'rewrite', $property ) && 'ID' === $property['rewrite'] ) {
				if ( !property_exists( $this, 'readonly' ) )
					$this -> readonly = [ 'slug' ];
				else if ( !in_array( 'slug', $this -> readonly ) )
					$this -> readonly[] = 'slug';
			}

		} else if ( 'Custom Taxonomy' === $property['register'] ) {

			$this -> registered = 'taxonomy';

		}

		$this -> registeredName = !isset( $property[$this -> registered] )
			? $this -> registeredName = $this -> domain
			: $this -> registeredName = $property[$this -> registered]
		;

		return true;
	}

	/**
	 *
	 */
	private function post_type_supports() {
		add_action( 'load-post-new.php', [ $this, 'add_post_type_supports' ] ); // post new
		add_action( 'admin_action_edit', [ $this, 'add_post_type_supports' ] ); // edit post

		if ( isset( $this -> readonly ) && !empty( $this -> readonly ) ) {
			add_action( 'admin_action_edit', [ $this, 'set_support_readonly' ] ); // edit post
		}
	}

	/**
	 *
	 */
	public function add_post_type_supports() {
		$array = [];
		if ( property_exists( $this, 'supports' ) ) {
			$array = array_merge( $array, (array) $this -> supports );
		}
		if ( property_exists( $this, 'readonly' ) ) {
			$array = array_unique(
				array_merge( $array, (array) $this -> readonly )
			);
		}
		if ( empty( $array ) ) {
			return;
		}

		$supports = array_filter( $array, function( $var ) {
			return in_array( $var, self::$_supports );
		} );

		if ( $supports ) {
			add_post_type_support( $this -> registeredName, $supports );
		}
	}

	/**
	 * @uses \wordpress\admin\enable_form
	 */
	public function set_support_readonly() {
		$enableForm = new \wordpress\admin\enable_form();
		foreach ( (array) $this -> readonly as $support ) {
			$enableForm -> set( $support );
		}
		$enableForm -> init();
	}

	/**
	 *
	 */
	public function post_type_meta_box() {

		foreach ( (array) $this -> meta_boxes as $arg ) {

			/**
			 * @uses $this -> _properties()
			 */
			if ( array_key_exists( 'property', $arg ) && true === $this -> _properties() ) {

				$propStr = $arg['property'];

				if ( !$property = self::$properties -> $propStr ) {
					return false; // throw error
				}

				/**
				 * 以下、add_meta_boxの引数を準備
				 */

				$id = esc_attr( $this -> _box_id_prefix . $this -> domain . '-' . $propStr );
				$title = esc_html( $property -> label );
				$post_type = $this -> registeredName;

				static $_contexts = [ 'normal', 'advanced', 'side' ];
				$context = array_key_exists( 'context', $arg ) && in_array( $arg['context'], $_contexts )
					? $arg['context']
					: 'advanced'
				;

				static $_priorities = [ 'high', 'core', 'default', 'low' ];
				$priority = array_key_exists( 'priority', $arg ) && in_array( $arg['priority'], $_priorities )
					? $arg['priority']
					: 'default'
				;

				/**
				 * Meta box callback function
				 */
				if ( array_key_exists( 'fw', $arg ) && 'vue' === $arg['fw'] ) {
					// vue test
					$vueModel = new \admin\meta_box_inner_vuejs( $this -> registeredName );
					$callback = [ $vueModel, 'init' ];
				} else {

				if ( array_key_exists( 'callback', $arg ) && is_callable( $arg['callback'] ) ) {
					$callback = $arg['callback'];
				} else {
					if ( null === self::$metaBoxInner ) {
						self::$metaBoxInner = new \admin\meta_box_inner( $this -> registeredName );
					}
					$metaBoxInner = self::$metaBoxInner;
					$callback = [ $metaBoxInner, 'init' ];
				}

				} // vue test end

				/**
				 * $callback_args
				 */
				$callback_args = [ 'instance' => $property ];

				$meta_box = compact(
					'id', 'title', 'callback', 'post_type',
					'context', 'priority', 'callback_args'
				);

				call_user_func_array( 'add_meta_box', $meta_box );
			}

		}

	}

	/**
	 *
	 */
	public function taxonomy_meta_box() {
		// ~
	}

	/**
	 * ドメインのプロパティーが 'domains/(domain)/properties.php'で定義されているか確認。
	 * まだインスタンスが作成されていない場合は作成。
	 *
	 * @uses \(domain)\properties
	 * @return bool
	 */
	private function _properties() {
		if ( null !== self::$properties ) {
			return true;
		}
		$className = '\\' . $this -> domain . '\\properties';
		if ( class_exists( $className ) ) {
			self::$properties = new $className();
			return true;
		}
		return false;
	}

}
