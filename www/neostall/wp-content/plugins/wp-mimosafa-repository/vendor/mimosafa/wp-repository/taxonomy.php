<?php
namespace mimosafa\WP\Repository;

/**
 * Taxonomy repository builder.
 *
 * @author Toshimichi Mimoto <mimosafa@gmail.com>
 *
 * @uses mimosafa\WP\Repository\RewritableRepository
 */
class Taxonomy extends RewritableRepository implements Repository {

	/**
	 * Object types
	 *
	 * @var array
	 */
	protected $object_type = [];

	/**
	 * WordPress built-in taxonomies.
	 *
	 * @var array
	 */
	protected static $builtins = [
		'category' => 'category',
		'tag'      => 'post_tag',
		'type'     => 'post_format',
	];

	/**
	 * Taxonomy default arguments.
	 *
	 * @var array
	 */
	protected static $defaults = [
		'labels'                => [],
		'description'           => '',
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => null,
		'show_in_menu'          => null,
		'show_in_nav_menus'     => null,
		'show_tagcloud'         => null,
		'show_in_quick_edit'    => null,
		'show_admin_column'     => false,
		'meta_box_cb'           => null,
		'capabilities'          => [],
		'rewrite'               => true,
		'query_var'             => true,
		'update_count_callback' => '',
		/**
		 * Additional arguments.
		 *
		 * @todo
		 */
		'singular' => '',
		'plural'   => '',
	];

	/**
	 * Rewrites default arguments.
	 *
	 * @var array
	 */
	private static $rewrite_defaults = [
		'slug'         => '',
		'with_front'   => true,
		'hierarchical' => false,
		'ep_mask'      => EP_NONE
	];

	/**
	 * Label formats.
	 *
	 * @var array
	 */
	protected static $label_formats = [
		// Common
		'name'          => null,
		'singular_name' => null,
		'search_items'  => [ 'plural',   'Search %s' ],
		'all_items'     => [ 'plural',   'All %s' ],
		'edit_item'     => [ 'singular', 'Edit %s' ],
		'view_item'     => [ 'singular', 'View %s' ],
		'update_item'   => [ 'singular', 'Update %s' ],
		'add_new_item'  => [ 'singular', 'Add New %s' ],
		'new_item_name' => [ 'singular', 'New %s Name' ],
		'not_found'     => [ 'plural',   'No %s found.' ],
		'no_terms'      => [ 'plural',   'No %s' ],
		// No-hierarchical
		'popular_items'              => [ 'singular', 'Popular %s' ],
		'separate_items_with_commas' => [ 'plural',   'Separate %s with commas' ],
		'add_or_remove_items'        => [ 'plural',   'Add or remove %s' ],
		'choose_from_most_used'      => [ 'plural',   'Choose from the most used %s' ],
		// Hierarchical
		'parent_item'       => [ 'singular', 'Parent %s' ],
		'parent_item_colon' => [ 'singular', 'Parent %s:' ],
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @param  string  $name
	 * @param  string  $alias
	 * @param  array   $args
	 * @param  boolean $builtin
	 */
	public function __construct( $name, $args = [], Factory $factory ) {
		parent::__construct( $name, $args, $factory );
		if ( isset( $this->args['object_type'] ) ) {
			if ( is_string( $this->args['object_type'] ) ) {
				$this->args['object_type'] = preg_split( '/[\s,]+/', $this->args['object_type'] );
			}
			if ( $this->args['object_type'] && is_array( $this->args['object_type'] ) ) {
				$this->object_type = array_values( $this->args['object_type'] );
			}
			unset( $this->args['object_type'] );
			$this->object_type = array_unique( $this->object_type );
		}
	}

	/**
	 * Parameter setter.
	 *
	 * @access public
	 */
	public function __set( $name, $value ) {
		if ( preg_match( '/^rewrite_([a-z_]+)/', $name, $m ) ) {
			/**
			 * Post type rewrite arguments.
			 */
			$key = $m[1];
			if ( array_key_exists( $key, self::$rewrite_defaults ) ) {
				if ( ! is_array( $this->args['rewrite'] ) ) {
					$this->args['rewrite'] = [];
				}
				$this->args['rewrite'][$key] = $value;
			}
		}
		else if ( preg_match( '/^label_([a-z_]+)/', $name, $m ) ) {
			/**
			 * Post type labels.
			 */
			$key = $m[1];
			if ( array_key_exists( $key, self::$label_formats ) && filter_var( $value ) ) {
				if ( ! is_array( $this->args['labels'] ) ) {
					$this->args['labels'] = [];
				}
				$this->args['labels'][$key] = esc_html( $value );
			}
		}
		else {
			parent::__set( $name, $value );
		}
	}

	/**
	 * Bind other repository.
	 *
	 * @access public
	 *
	 * @param  Repository|string $repository
	 */
	public function bind( $repository ) {
		if ( $repository = PostType::getInstance( $repository ) ) {
			return $this->add_object_type( $repository->alias );
		}
	}

	/**
	 * Unbind repository.
	 *
	 * @access public
	 *
	 * @param  Repository|string $repository
	 */
	public function unbind( $repository ) {
		if ( $repository = PostType::getInstance( $repository ) ) {
			return $this->remove_object_type( $repository->alias );
		}
	}

	public function add_object_type( $name ) {
		if ( filter_var( $name ) ) {
			if ( ! in_array( $name, $this->object_type, true ) ) {
				$this->object_type[] = $name;
				return true;
			}
		}
		return false;
	}

	public function remove_object_type( $name ) {
		if ( filter_var( $name ) ) {
			if ( in_array( $name, $this->object_type, true ) ) {
				$index = array_search( $name, $this->object_type, true );
				unset( $this->object_type[$index] );
				return true;
			}
		}
		return false;
	}

	/**
	 * Regulate arguments for registration.
	 *
	 * @access public
	 */
	public function regulation() {
		if ( taxonomy_exists( $this->alias ) ) {
			return;
		}
		/**
		 * @var array          &$labels
		 * @var string         &$description
		 * @var boolean        &$public
		 * @var boolean        &$hierarchical
		 * @var boolean        &$show_ui
		 * @var boolean        &$show_in_menu
		 * @var boolean        &$show_in_nav_menus
		 * @var boolean        &$show_tagcloud
		 * @var boolean        &$show_in_quick_edit
		 * @var boolean        &$show_admin_column
		 * @var callable       &$meta_box_cb
		  @var array          &$capabilities
		 * @var boolean|array  &$rewrite
		 * @var boolean|string &$query_var
		 * @var callable       &$update_count_callback
		 * @var array|string   &$object_type
		 */
		extract( $this->args, \EXTR_REFS );

		/**
		 * Regulate arguments.
		 */
		$public            = filter_var( $public,            \FILTER_VALIDATE_BOOLEAN );
		$hierarchical      = filter_var( $hierarchical,      \FILTER_VALIDATE_BOOLEAN );
		$show_admin_column = filter_var( $show_admin_column, \FILTER_VALIDATE_BOOLEAN );
		if ( isset( $show_ui ) ) {
			$show_ui = filter_var( $show_ui, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE );
		}
		if ( isset( $show_in_menu ) ) {
			$show_in_menu = filter_var( $show_in_menu, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE );
		}
		if ( isset( $show_in_nav_menus ) ) {
			$show_in_nav_menus = filter_var( $show_in_nav_menus, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE );
		}
		if ( isset( $show_tagcloud ) ) {
			$show_tagcloud = filter_var( $show_tagcloud, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE );
		}
		if ( isset( $show_in_quick_edit ) ) {
			$show_in_quick_edit = filter_var( $show_in_quick_edit, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE );
		}
		if ( is_array( $description ) || is_object( $description ) ) {
			$description = '';
		}
		if ( isset( $meta_box_cb ) ) {
			if ( ! is_string( $meta_box_cb ) || ! preg_match( '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $meta_box_cb ) ) {
				$meta_box_cb = null;
			}
		}
		if ( $update_count_callback ) {
			if ( $update_count_callback !== '_update_post_term_count' || $update_count_callback !== '_update_generic_term_count' ) {
				$update_count_callback = '';
			}
		}
		if ( $public ) {
			if ( filter_var( $rewrite, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE ) !== false ) {
				$rewrite = wp_parse_args( is_array( $rewrite ) ? $rewrite : [], self::$rewrite_defaults );
				if ( ! $rewrite['slug'] || ! is_string( $rewrite['slug'] ) ) {
					$rewrite['slug'] = $this->name;
				}
				$rewrite['with_front']   = filter_var( $rewrite['with_front'],   \FILTER_VALIDATE_BOOLEAN );
				$rewrite['hierarchical'] = filter_var( $rewrite['hierarchical'], \FILTER_VALIDATE_BOOLEAN );
				$rewrite['ep_mask'] = filter_var( $rewrite['ep_mask'], \FILTER_VALIDATE_INT, [ 'options' => [ 'default' => EP_NONE ] ] );
			}
			if ( filter_var( $query_var, \FILTER_VALIDATE_BOOLEAN ) !== false ) {
				$query_var = $this->alias;
			} else {
				$query_var = false;
			}
		} else {
			$rewrite = $query_var = false;
		}
		if ( ! is_array( $labels ) ) {
			$labels = [];
		}
		if ( ! isset( $labels['name'] ) || ! filter_var( $labels['name'] ) ) {
			$labels['name'] = isset( $label ) && filter_var( $label ) ? $label : self::labelize( $this->name );
		}
		if ( ! isset( $labels['singular_name'] ) || ! filter_var( $labels['singular_name'] ) ) {
			$labels['singular_name'] = $labels['name'];
		}
		self::generateLabels( $labels, $hierarchical );

		if ( $this->object_type = array_filter( $this->object_type ) ) {
			$this->object_type = array_unique( $this->object_type, \SORT_REGULAR );
			$this->object_type_regulation();
		}

		/**
		 * Cache for registration.
		 */
		self::$taxonomies[] = [ 'taxonomy' => $this->alias, 'object_type' => $this->object_type, 'args' => $this->args ];
	}

	/**
	 * Create taxonomy labels.
	 *
	 * @access private
	 *
	 * @param  array &$labels
	 */
	private static function generateLabels( &$labels ) {
		$singular = $labels['singular_name'];
		$plural   = $labels['name'];
		foreach ( self::$label_formats as $key => $format ) {
			if ( ! isset( $labels[$key] ) || ! filter_var( $labels[$key] ) ) {
				if ( is_array( $format ) && ( $string = ${$format[0]} ) ) {
					$labels[$key] = esc_html( sprintf( __( $format[1], 'wp-mimosafa-libs' ), $string ) );
				}
			}
		}
	}

	/**
	 * Regulate object types.
	 *
	 * @access private
	 */
	private function object_type_regulation() {
		foreach ( $this->object_type as $i => $type ) {
			if ( in_array( $type, self::$aliases, true ) ) {
				continue;
			}
			if ( isset( self::$aliases[$type] ) ) {
				$this->object_type[$i] = self::$aliases[$type];
				continue;
			}
			unset( $this->object_type[$i] );
		}
	}

	/**
	 * Validate $alias string for taxonomy.
	 *
	 * @access protected
	 *
	 * @param  string $str
	 * @return string|null
	 */
	protected static function validateAlias( $str ) {
		if ( parent::validateAlias( $str ) ) {
			/**
			 * Taxonomy name regulation.
			 *
			 * @see http://codex.wordpress.org/Function_Reference/register_taxonomy#Parameters
			 */
			if ( strlen( $str ) < 33 && ! @preg_match( '/[0-9\-]/', $str ) ) {
				return true;
			}
		}
		return false;
	}

}
