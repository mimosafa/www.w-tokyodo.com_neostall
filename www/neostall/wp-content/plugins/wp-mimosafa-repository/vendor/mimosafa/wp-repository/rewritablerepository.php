<?php
namespace mimosafa\WP\Repository;

/**
 * Abstract rewritable repository class.
 *
 * @author Toshimichi Mimoto <mimosafa@gmail.com>
 *
 * @uses mimosafa\WP\Repository\Repository
 */
abstract class RewritableRepository {

	/**
	 * Repository's name.
	 * Use as {post type|taxonomy} rerite slug.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Repository's real name in WordPress.
	 *
	 * @var string
	 */
	protected $alias;

	/**
	 * Arguments for repository registration.
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * Instances of rewritable repositories.
	 *
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * Rewritable repositories `alias` <=> `name` map.
	 *
	 * @var array
	 */
	protected static $aliases = [];

	/**
	 * Repository default arguments.
	 *
	 * @var array
	 */
	protected static $defaults = [];

	/**
	 * Post types arguments
	 *
	 * @var array { @type array ${name} }
	 */
	protected static $post_types = [];

	/**
	 * Taxonomies arguments
	 *
	 * @var array { @type array ${name} }
	 */
	protected static $taxonomies = [];

	/**
	 * Abstract method: Regulate arguments for registration.
	 *
	 * @access public
	 */
	abstract public function regulation();

	/**
	 * Constructor.
	 *
	 * @access protected
	 *
	 * @uses  mimosafa\WP\Repository\Repository::__construct()
	 *
	 * @param  string $name
	 * @param  string $alias
	 * @param  array  $args
	 * @return void
	 */
	public function __construct( $name, $args = [], Factory $factory ) {
		if ( ! static::validateName( $name ) ) {
			throw new \Exception( 'Invalid.' );
		}
		$args = wp_parse_args( $args, static::$defaults );
		if ( ! static::validateAlias( $alias = isset( $args['alias'] ) ? $args['alias'] : $factory->prefix . $name ) ) {
			throw new \Exception( 'Invalid.' );
		}
		if ( ! static::isConfricted( $name, $alias ) ) {
			$this->name  = $name;
			$this->alias = $alias;
			$this->args  = $args;
			static::$aliases[$name] = $alias;
			static::$instances[$name] = $this;
			add_action( 'init', [ &$this, 'regulation' ], 0 );
			/**
			 * Flag for action at only once.
			 *
			 * @var boolean
			 */
			static $done = false;
			if ( ! $done ) {
				add_action( 'init', [ &$this, 'register_taxonomies' ], 1 );
				add_action( 'init', [ &$this, 'register_post_types' ], 1 );
				$done = true;
			}
		}
	}

	public function __get( $name ) {
		if ( in_array( $name, [ 'name', 'alias' ], true ) ) {
			return $this->$name;
		}
	}

	/**
	 * Parameter setter.
	 *
	 * @access public
	 */
	public function __set( $name, $value ) {
		$this->args[$name] = $value;
	}

	public static function getInstance( $var ) {
		if ( ! is_object( $var ) ) {
			if ( is_array( $var ) ) {
				return;
			}
			if ( isset( self::$instances[$var] ) ) {
				$instance = self::$instances[$var];
			}
			else if ( in_array( $var, self::$aliases, true ) ) {
				$index = array_search( $var, self::$aliases, true );
				$instance = self::$instances[$index];
			}
			else {
				return;
			}
		}
		else {
			$instance = $var;
		}
		$class = get_called_class();
		return $instance instanceof $class ? $instance : null;
	}

	/**
	 * Register taxonomy components.
	 *
	 * @access public
	 */
	public function register_taxonomies() {
		if ( self::$taxonomies ) {
			foreach ( self::$taxonomies as $tx ) {
				/**
				 * @var string $taxonomy
				 * @var array  $object_type
				 * @var array  $args
				 */
				extract( $tx, EXTR_OVERWRITE );

				register_taxonomy( $taxonomy, $object_type, $args );
				/**
				 * Built-in object types
				 */
				if ( $object_type ) {
					foreach ( (array) $object_type as $object ) {
						if ( post_type_exists( $object ) ) {
							register_taxonomy_for_object_type( $taxonomy, $object );
						}
					}
				}
			}
		}
	}

	/**
	 * Register post type components.
	 *
	 * @access public
	 */
	public function register_post_types() {
		if ( self::$post_types ) {
			/**
			 * Theme support: post-thumbnails
			 *
			 * @var boolean
			 */
			static $thumbnail_supported;
			if ( ! isset( $thumbnail_supported ) ) {
				$thumbnail_supported = current_theme_supports( 'post-thumbnails' );
			}
			/**
			 * Theme support: post-formats
			 *
			 * @var boolean
			 */
			static $post_formats_supported;
			if ( ! isset( $post_formats_supported ) ) {
				$post_formats_supported = current_theme_supports( 'post-formats' );
			}
			foreach ( self::$post_types as $pt ) {
				/**
				 * @var string $post_type
				 * @var array  $args
				 */
				extract( $pt, EXTR_OVERWRITE );
				/**
				 * Taxonomies
				 */
				if ( self::$taxonomies ) {
					$taxonomies = [];
					foreach ( self::$taxonomies as $tx ) {
						if ( in_array( $post_type, $tx['object_type'], true ) ) {
							$taxonomies[] = $tx['taxonomy'];
						}
					}
					if ( $taxonomies ) {
						if ( ! isset( $args['taxonomies'] ) || ! is_array( $args['taxonomies'] ) ) {
							$args['taxonomies'] = array_unique( array_merge( $args['taxonomies'], $taxonomies ) );
						}
					}
				}
				/**
				 * Theme supports.
				 */
				if ( ! $thumbnail_supported && isset( $args['supports'] ) && in_array( 'thumbnail', (array) $args['supports'], true ) ) {
					add_theme_support( 'post-thumbnails' );
					$thumbnail_supported = true;
				}
				if ( ! $post_formats_supported && isset( $args['supports'] ) && in_array( 'post-formats', (array) $args['supports'], true ) ) {
					add_theme_support( 'post-formats' );
					$post_formats_supported = true;
				}
				register_post_type( $post_type, $args );
			}
		}
	}

	/**
	 * Validate name string.
	 *
	 * @access protected
	 *
	 * @param  string $str
	 * @return boolean
	 */
	protected static function validateName( $str ) {
		return filter_var( $str ) && $str === sanitize_key( $str );
	}

	/**
	 * Validate alias string.
	 *
	 * @access protected
	 *
	 * @param  string $str
	 * @return boolean
	 */
	protected static function validateAlias( $str ) {
		return self::validateName( $str );
	}

	/**
	 * Confrict checker.
	 *
	 * @access protected
	 *
	 * @param  string $name
	 * @param  string $alias
	 * @return boolean false
	 * @throws Exception
	 */
	protected static function isConfricted( $name, $alias ) {
		if ( isset( static::$aliases[$name] ) ) {
			throw new \Exception( 'Existing.' );
		}
		if ( in_array( $alias, static::$aliases, true ) ) {
			throw new \Exception( 'Existing.' );
		}
		if ( in_array( $name, static::$aliases, true ) ) {
			throw new \Exception( 'Confricted.' );
		}
		if ( isset( static::$aliases[$alias] ) ) {
			throw new \Exception( 'Confricted.' );
		}
		return false;
	}

	/**
	 * Labelize
	 *
	 * @access protected
	 *
	 * @param  string $string
	 * @return string
	 */
	protected static function labelize( $string ) {
		return ucwords( str_replace( [ '-', '_' ], ' ', $string ) );
	}

}
