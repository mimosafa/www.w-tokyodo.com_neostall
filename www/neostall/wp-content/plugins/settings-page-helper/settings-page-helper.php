<?php
/*
Plugin Name: Settings Page Helper
Description: Helper class for settings pages
Author: mimosafa
Version: 0.0
Author URI: http://mimosafa.me
*/

/**
 * WordPress Settings API wrapper class
 *
 * Usage:
 * - Get instance <code>$instance = new Settings_Page_Helper();</code>
 * - Initialize with page sulug <code>$instance->init( 'my-plugin' );</code>
 *   - You can also set page title & menu title by this method
 * ...
 *
 * **CAUTION** This class methods print basically non-escape text
 *
 * @access private
 *
 * @package WordPress
 * @subpackage WP_Domain_Work
 *
 * @author mimosafa <mimosafa@gmail.com>
 */
class Settings_Page_Helper {

	/**
	 * Top level page
	 *
	 * @var string
	 */
	private $toplevel;

	/**
	 * Pages structure argument
	 *
	 * @var array
	 */
	private $pages = [];

	/**
	 * Argument caches
	 *
	 * @var array
	 */
	private static $page;
	private static $section;
	private static $field;

	/**
	 * Arguments of 'add_settings' method 
	 * 
	 * @var array
	 */
	private $sections = [];
	private $fields   = [];
	private $settings = [];

	/**
	 * Arguments of callback functions
	 *
	 * @var array
	 */
	private static $callback_args = [];

	private static $falseVal = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize instance
	 *
	 * @access public
	 *
	 * @return object self
	 */
	public function init( $page = null, $page_title = null, $menu_title = null ) {
		$this->init_page();
		if ( $page !== null ) {
			$this->page( $page, $page_title, $menu_title );
		}
		return $this;
	}

	/**
	 * Output settings page
	 *
	 * @access public
	 */
	public function done() {
		$this->init();
		if ( $this->pages ) {
			add_action( 'admin_menu', [ $this, 'add_pages' ] );
			add_action( 'admin_init', [ $this, 'add_settings' ] );
		}
	}

	/**
	 * Initialize static cache $page
	 *
	 * @access private
	 */
	private function init_page() {
		$this->init_section();
		if ( ! empty( self::$page ) ) {
			$this->pages[] = self::$page;
		}
		self::$page = [];
	}

	/**
	 * Initialize static cache $section
	 *
	 * @access private
	 */
	private function init_section() {
		$this->init_field();
		if ( ! empty( self::$section ) ) {
			if ( self::$page ) {
				if ( ! array_key_exists( 'sections', self::$page ) ) {
					self::$page['sections'] = [];
				}
				self::$page['sections'][] = self::$section;
			}
		}
		self::$section = [];
	}

	/**
	 * Initialize static cache $field
	 *
	 * @access private
	 */
	private function init_field() {
		if ( ! empty( self::$field ) ) {
			if ( self::$section ) {
				if ( ! array_key_exists( 'fields', self::$section ) ) {
					self::$section['fields'] = [];
				}
				self::$section['fields'][] = self::$field;
			} else if ( self::$page ) {
				if ( ! array_key_exists( 'fields', self::$page ) ) {
					self::$page['fields'] = [];
				}
				self::$page['fields'][] = self::$field;
			}
		}
		self::$field = [];
	}

	/**
	 * Add pages (run in action hook 'admin_menu')
	 *
	 * @access private
	 */
	public function add_pages() {
		if ( ! doing_action( 'admin_menu' ) || ! $this->pages ) {
			return;
		}
		foreach ( $this->pages as $page ) {
			$this->add_page( $page, $this->toplevel );
		}
	}

	/**
	 * Add page
	 *
	 * @access private
	 *
	 * @param  array $page_arg
	 * @param  string $toplevel
	 * @return void
	 */
	private function add_page( $page_arg, $toplevel ) {
		if ( ! array_key_exists( 'page', $page_arg ) ) {
			return;
		}
		global $admin_page_hooks;
		extract( $page_arg ); // $page must be generated.

		/**
		 * Avoid duplicate page body display
		 */
		if ( array_key_exists( $page, $admin_page_hooks ) ) {
			return;
		}

		if ( ! isset( $title ) ) {
			$title = ucwords( trim( str_replace( [ '-', '_', '/', '.php' ], ' ', $page ) ) );
			$page_arg['title'] = $title;
		}
		if ( ! isset( $menu_title ) ) {
			$menu_title = $title;
			$page_arg['menu_title'] = $menu_title;
		}
		if ( ! isset( $capability ) ) {
			$capability = 'manage_options';
			$page_arg['capability'] = $capability;
		}
		if ( ! isset( $callback ) ) {
			if ( ( isset( $sections ) && $sections ) || ( isset( $fields ) && $fields ) ) {
				$callback = [ $this, 'page_body' ];
			} else {
				$callback = [ $this, 'page_plane_body' ];
			}
		} else {
			unset( $page_arg['callback'] ); // Optimize vars
		}

		if ( $page === $toplevel && ! array_key_exists( $page, $admin_page_hooks ) ) {

			if ( ! isset( $icon_url ) ) {
				$icon_url = '';
			}
			if ( ! isset( $position ) ) {
				$position = null;
			}

			/**
			 * Add as top level page
			 */
			add_menu_page( $title, $menu_title, $capability, $page, $callback, $icon_url, $position );

		} else {
			/**
			 * Add as sub page
			 */
			add_submenu_page( $toplevel, $title, $menu_title, $capability, $page, $callback );
		}

		/**
		 * Sections
		 */
		if ( isset( $sections ) && $sections ) {
			foreach ( $sections as $section ) {
				$this->add_section( $section, $page );
			}
			unset( $page_arg['sections'] ); // Optimize vars
		}

		/**
		 * fields
		 */
		if ( isset( $fields ) && $fields ) {
			foreach ( $fields as $field ) {
				$this->add_field( $field, $page );
			}
			unset( $page_arg['fields'] ); // Optimize vars
		}

		/**
		 * Cache argument for callback method
		 */
		self::$callback_args[$page] = $page_arg;
	}

	/**
	 * Add section
	 *
	 * @access private
	 *
	 * @param  array $section
	 * @param  string $menu_slug
	 * @return void
	 */
	private function add_section( $section, $menu_slug ) {
		if ( ! array_key_exists( 'id', $section ) ) {
			return;
		}
		extract( $section ); // $id must be generated

		if ( ! isset( $title ) ) {
			$title = null;
		}
		if ( ! isset( $callback ) ) {
			$callback = [ $this, 'section_body' ];
		} else {
			unset( $section['callback'] ); // Optimize vars
		}
		$this->sections[] = [ $id, $title, $callback, $menu_slug ];

		/**
		 * fields
		 */
		if ( isset( $fields ) && $fields ) {
			foreach ( $fields as $field ) {
				$this->add_field( $field, $menu_slug, $id );
			}
			unset( $section['fields'] ); // Optimize vars
		}

		/**
		 * Cache argument for callback method
		 */
		self::$callback_args[$id] = $section;
	}

	/**
	 * Add & set field
	 *
	 * @access private
	 *
	 * @param  array $field
	 * @param  string $menu_slug
	 * @param  string $section_id (optional)
	 * @return void
	 */
	private function add_field( $field, $menu_slug, $section_id = '' ) {
		if ( ! array_key_exists( 'id', $field ) ) {
			return;
		}
		extract( $field ); // $id must be generated

		if ( ! isset( $title ) ) {
			$title = ucwords( str_replace( [ '-', '_' ], ' ', $id ) );
			$field['title'] = $title;
		}
		if ( ! isset( $callback ) ) {
			$callback = [ $this, 'field_body' ];
		} else {
			unset( $field['callback'] ); // Optimize vars
		}
		if ( isset( $option_name ) ) {
			$option_group = 'group_' . $menu_slug;
			if ( ! isset( $sanitize ) || ( ! method_exists( $this, $sanitize ) && ! is_callable( $sanitize ) ) ) {
				$sanitize = '';
			} else if ( isset( $sanitize ) ) {
				unset( $field['sanitize'] ); // Optimize vars
			}
			$this->settings[] = [ $option_group, $field['option_name'], $sanitize ];
		}

		$this->fields[] = [ $id, $title, $callback, $menu_slug, $section_id, $field ]; // $field is argument for callback method
	}

	/**
	 * Setting sections & fields method (run in action hook 'admin_init')
	 *
	 * @access private
	 */
	public function add_settings() {
		if ( ! doing_action( 'admin_init' ) || ! $this->pages ) {
			return;
		}
		foreach ( $this->sections as $section_arg ) {
			call_user_func_array( 'add_settings_section', $section_arg );
		}
		foreach ( $this->fields as $field_arg ) {
			call_user_func_array( 'add_settings_field', $field_arg );
		}
		foreach ( $this->settings as $setting_arg ) {
			call_user_func_array( 'register_setting', $setting_arg );
		}
	}

	/**
	 * Set page
	 *
	 * @access private
	 *
	 * @param  string $page (optional) if empty 'options.php' set
	 * @param  string $page_title (optional)
	 * @param  string $menu_title (optional)
	 * @return object self
	 */
	private function page( $page = null, $page_title = null, $menu_title = null ) {
		if ( ! $page ) {
			$page = 'options.php';
		}
		if ( ! is_string( $page ) ) {
			return; // error
		}
		self::$page['page'] = $page;
		if ( ! $this->toplevel ) {
			$this->toplevel = $page;
		}
		if ( $page_title ) {
			$this->title( $page_title );
		}
		if ( $menu_title ) {
			$this->menu_title( $menu_title );
		}
		return $this;
	}

	/**
	 * Set section
	 *
	 * @access public
	 *
	 * @param  string $section_id (required)
	 * @param  string $section_title (optional) if blank, string made from section_id. if want to hide set empty string ''.
	 * @return object self
	 */
	public function section( $section_id, $section_title = null ) {
		$this->init_section();

		if ( ! $section_id || ! is_string( $section_id ) ) {
			return;
		}
		self::$section['id'] = $section_id;
		if ( null !== $section_title && is_string( $section_title ) ) {
			self::$section['title'] = $section_title;
		}
		return $this;
	}

	/**
	 * Set field (for only field)
	 *
	 * @access public
	 *
	 * @return object self
	 */
	public function field( $field_id, $field_title = null ) {
		$this->init_field();

		if ( ! $field_id || ! is_string( $field_id ) ) {
			return;
		}
		self::$field['id'] = $field_id;
		if ( $field_title && is_string( $field_title ) ) {
			self::$field['title'] = $field_title;
		}
		return $this;
	}

	/**
	 * Set field's option & callback (for only field)
	 *
	 * @access public
	 *
	 * @return object self
	 */
	public function option_name( $option_name, $callback, $callback_args = [] ) {
		if ( ! self::$field || ! $option_name || ! is_string( $option_name ) ) {
			return; // error
		}
		if ( ! $this->callback( $callback, $callback_args ) ) {
			return;
		}
		self::$field['option_name'] = $option_name;
		return $this;
	}

	/**
	 * Set title (for page, section, field)
	 *
	 * @access public
	 *
	 * @param  string $title
	 * @return object self
	 */
	public function title( $title ) {
		if ( ! $title || ! is_string( $title ) ) {
			return;
		}
		if ( ! $cache =& $this->get_cache() ) {
			return;
		}
		$cache['title'] = $title;
		return $this;
	}

	/**
	 * Set menu title (for page only)
	 *
	 * @access public
	 *
	 * @param  string $title
	 * @return object self
	 */
	public function menu_title( $menu_title ) {
		if ( $menu_title && is_string( $menu_title ) ) {
			self::$page['menu_title'] = $menu_title;
		}
		return $this;
	}

	/**
	 * Set capability
	 *
	 * @access public
	 *
	 * @param  string $capability
	 * @return object self
	 */
	public function capability( $capability ) {
		if ( $capability && is_string( $capability ) ) {
			self::$page['capability'] = $capability;
		}
		return $this;
	}

	/**
	 * Set icon_url (for only top level page)
	 *
	 * @param  string $icon_url
	 * @return object self
	 */
	public function icon_url( $icon_url ) {
		if ( $icon_url && is_string( $icon_url ) ) {
			self::$page['icon_url'] = $icon_url;
		}
		return $this;
	}

	/**
	 * Set position in admin menu (for only top level page)
	 *
	 * @param  integer $position
	 * @return object self
	 */
	public function position( $position ) {
		if ( $position && $int = absint( $position ) ) {
			self::$page['position'] = $int;
		}
		return $this;
	}

	/**
	 * Set description text
	 * Description will be contained initialized cache(field, section, page) before calling this method
	 *
	 * @access public
	 *
	 * @param  string $text
	 * @param  bool   $wrap_p
	 * @return object self|(void)
	 */
	public function description( $text ) {
		if ( ! $text || ! is_string( $text ) ) {
			return;
		}
		if ( ! $cache =& $this->get_cache() ) {
			return;
		}
		$format = '<p class="description">%s</p>';
		if ( ! array_key_exists( 'description', $cache ) ) {
			$cache['description'] = sprintf( $format, $text );
		} else {
			$format = "\n{$format}";
			$cache['description'] .= sprintf( $format, $text );
		}
		return $this;
	}

	/**
	 * Set html contents
	 *
	 * @access public
	 *
	 * @param  string $html
	 * @param  boolean $wrap_div (optional) if true wrap $html by 'div'
	 * @return object self|(void)
	 */
	public function html( $html, $wrap_div = false ) {
		if ( ! $cache =& $this->get_cache() ) {
			return;
		}
		$format = ! $wrap_div ? '%s' : '<div>%s</div>';
		if ( ! array_key_exists( 'html', $cache ) ) {
			$cache['html'] = sprintf( $format, $html );
		} else {
			$format = "\n{$format}";
			$cache['html'] .= sprintf( $format, $html );
		}
		return $this;
	}

	/**
	 * Set callback function
	 * Callback will be contained initialized cache(field, section, page) before calling this method
	 *
	 * @access public
	 *
	 * @param  string $callback
	 * @return object self|(void)
	 */
	public function callback( $callback, $callback_args = [] ) {
		if (
			( is_array( $callback ) && ( count( $callback ) !== 2 || ! is_callable( $callback ) ) )
			&& ( ! method_exists( $this, $callback ) && ! is_callable( $callback ) )
		) {
			return;
		}

		if ( ! $cache =& $this->get_cache() ) {
			return;
		}
		$cache['callback'] = is_callable( $callback ) ? $callback : [ $this, $callback ];
		if ( $callback_args ) {
			foreach ( $callback_args as $key => $val ) {
				if ( ! array_key_exists( $key, $cache ) ) {
					$cache[$key] = $val;
				}
			}
		}
		return $this;
	}

	/**
	 * Set submit button ---- yet !!
	 *
	 * @access public
	 *
	 * @param  string $text
	 * @return object self
	 */
	public function submit_button( $text ) {
		//
		return $this;
	}

	/**
	 * Return var references cache
	 *
	 * @return references
	 */
	private function &get_cache() {
		if ( self::$field ) {
			return self::$field;
		} else if ( self::$section ) {
			return self::$section;
		} else if ( self::$page ) {
			return self::$page;
		} else {
			return self::$falseVal;
		}
	}

	/**
	 * Drow default page html (if has form)
	 * 
	 * @return (void)
	 */
	public function page_body() {
		$menu_slug = $_GET['page'];
		$arg = self::$callback_args[$menu_slug];
		$option_group = 'group_' . $menu_slug;
		/**
		 * @see http://wpcj.net/354
		 */
		global $parent_file;
		if ( $parent_file !== 'options-general.php' ) {
			require ABSPATH . 'wp-admin/options-head.php';
		}
?>
<div class="wrap">
  <h2><?= $arg['title'] ?></h2>
  <?php if ( array_key_exists( 'description', $arg ) ) { ?>
  <?= $arg['description'] ?>
  <?php } ?>
  <?php if ( array_key_exists( 'html', $arg ) ) { ?>
  <?= $arg['html'] ?>
  <?php } ?>
  <form method="post" action="options.php">
    <?php settings_fields( $option_group ); ?>
    <?php do_settings_fields( $menu_slug, '' ); ?>
    <?php do_settings_sections( $menu_slug ); ?>
    <?php submit_button(); ?>
  </form>
</div>
<?php
	}

	/**
	 * Drow default page html (if not has form)
	 * 
	 * @return (void)
	 */
	public function page_plane_body() {
		$menu_slug = $_GET['page'];
		$arg = self::$callback_args[$menu_slug];
?>
<div class="wrap">
  <h2><?= $arg['title'] ?></h2>
  <?php if ( array_key_exists( 'description', $arg ) ) { ?>
  <?= $arg['description'] ?>
  <?php } ?>
  <?php if ( array_key_exists( 'html', $arg ) ) { ?>
  <?= $arg['html'] ?>
  <?php } ?>
</div>
<?php
	}

	/**
	 * @param  array $array
	 */
	public function section_body( $array ) {
		$arg = self::$callback_args[$array['id']];
		if ( array_key_exists( 'description', $arg ) ) {
?>
      <?= $arg['description'] ?>
<?php
		}
		if ( array_key_exists( 'html', $arg ) ) {
?>
      <?= $arg['html'] ?>
<?php
		}
	}

	/**
	 * 
	 */
	public function field_body( $arg ) {
		if ( array_key_exists( 'description', $arg ) ) {
?>
        <?= $arg['description'] ?>
<?php
		}
		if ( array_key_exists( 'html', $arg ) ) {
?>
        <?= $arg['html'] ?>
<?php
		}
	}

	/**
	 *
	 */
	public function checkbox( $arg ) {
		if ( ! array_key_exists( 'option_name', $arg ) ) {
			return; // error
		}
		$option = $arg['option_name'];
		$checked = \get_option( $option ) ? 'checked="checked" ' : '';
		$label = array_key_exists( 'label', $arg ) ? esc_html( $arg['label'] ) : '';
?>
        <fieldset>
          <label for="<?= $option ?>">
            <input type="checkbox" name="<?= $option ?>" id="<?= $option ?>" value="1" <?= $checked ?>/>
            <?= $label ?>
          </label>
          <?php if ( array_key_exists( 'description', $arg ) ) { ?>
          <?= $arg['description'] ?>
          <?php } ?>
        </fieldset>
<?php
	}

}
