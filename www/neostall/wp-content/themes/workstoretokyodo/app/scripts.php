<?php
namespace WSTD;
/**
 * Management Styles & JavaScripts Class
 *
 * @since 0.0.0
 */
class Scripts {

	/**
	 * This Theme Name
	 *
	 * @since 0.0.0
	 */
	const THEME = 'workstoretokyodo';

	/**
	 * @var string
	 *
	 * @since 0.0.0
	 */
	private $theme;

	/**
	 * CDNs
	 *
	 * @since 0.0.0
	 */
	private $cdns = [
		/**
		 * Twitter Bootstrap
		 *
		 * @since 0.0.0
		 * @see   http://getbootstrap.com/getting-started/
		 */
		'twitter-bootstrap' => [
			'3.3.5',
			'//maxcdn.bootstrapcdn.com/bootstrap/%s/css/bootstrap.min.css',
			'//maxcdn.bootstrapcdn.com/bootstrap/%s/js/bootstrap.min.js'
		],

		/**
		 * Font Awesome
		 *
		 * @since 0.0.0
		 * @see   http://fortawesome.github.io/Font-Awesome/get-started/
		 */
		'font-awesome' => [
			'4.4.0',
			'//maxcdn.bootstrapcdn.com/font-awesome/%s/css/font-awesome.min.css'
		],
	];

	/**
	 * @access public
	 *
	 * @since 0.0.0
	 */
	public static function init() {
		static $instance;
		$instance ?: $instance = new self();
	}

	/**
	 * @access private
	 *
	 * @since 0.0.0
	 */
	private function __construct() {
		$this->theme = get_stylesheet();
		$this->register();
		$this->enqueue();
	}

	/**
	 * @access private
	 *
	 * @since 0.0.0
	 */
	private function register() {
		$this->register_themes();
		$this->register_cdns();
	}

	/**
	 * @access private
	 *
	 * @since 0.0.0
	 */
	private function enqueue() {
		wp_enqueue_style( $this->theme );
	}

	/**
	 * @access private
	 *
	 * @since 0.0.0
	 */
	private function register_themes() {
		if ( $this->theme !== self::THEME ) {
			/**
			 * Child Theme Style
			 */
			wp_register_style(
				$this->theme,
				get_stylesheet_uri(),
				[ self::THEME ],
				wp_get_theme()->get( 'Version' )
			);
		}
		/**
		 * This Theme Style
		 */
		wp_register_style(
			self::THEME,
			$this->stylesheet_uri(),
			array_keys( $this->cdns ),
			WSTD_THEME_VER
		);
	}

	/**
	 * @access private
	 *
	 * @since 0.0.0
	 */
	private function register_cdns() {
		if ( $this->cdns ) {
			foreach ( $this->cdns as $id => $array ) {
				if ( ! $array || ! is_array( $array ) || count( $array ) < 2 ) {
					continue;
				}
				static $version;
				$version = null;
				foreach ( $array as $i => $var ) {
					if ( $i === 0 && ! isset( $version ) ) {
						$version = filter_var( $var );
						continue;
					}
					if ( substr( $var, -4 ) === '.css' ) {
						$fn = 'wp_register_style';
					}
					else if ( substr( $var, -3 ) === '.js' ) {
						$fn = 'wp_register_script';
					}
					if ( isset( $version ) && isset( $fn ) ) {
						$fn( $id, sprintf( $var, $version ), [], $version );
					}
				}
			}
		}
	}

	/**
	 * @access private
	 *
	 * @since 0.0.0
	 */
	private function stylesheet_uri() {
		return trailingslashit( get_template_directory_uri() ) . 'style.css';
	}

}
