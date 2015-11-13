<?php
namespace WSTD;
/**
 * Workstore Tokyo Do Division Class
 *
 * @since 0.0.0
 */
class Divisions {

	const WSTD_HOME = 'https://www.w-tokyodo.com';

	/**
	 * Current Division
	 *
	 * @var string|null
	 */
	private $current;

	/**
	 * Current Division Object
	 *
	 * @var Object
	 */
	private $division;

	/**
	 * Workstore Tokyo Do Division Model
	 *
	 * @var array
	 */
	private static $divisions = [];

	/**
	 * @access public
	 */
	public static function init() {
		static $instance;
		return $instance ?: $instance = new self();
	}

	/**
	 * @access private
	 */
	private function __construct() {
		/**
		 * @todo
		 */
		$this->prepare();

		if ( $this->current ) {
			$this->division = (object) self::$divisions[$this->current];
			$this->division->name = $this->current;
		}
	}

	/**
	 * Test
	 *
	 * @since 0.0.0
	 */
	private function prepare() {
		// Divisions
		self::$divisions['direct']   = [];
		self::$divisions['neostall'] = [];
		self::$divisions['neoponte'] = [];
		self::$divisions['sharyobu'] = [];

		// Public Name
		self::$divisions['direct']['public_name']   = 'Tokyo Do';
		self::$divisions['neostall']['public_name'] = 'ネオ屋台村';
		self::$divisions['neoponte']['public_name'] = 'ネオポンテ';
		self::$divisions['sharyobu']['public_name'] = '車両部';

		// (Division) Home URL
		self::$divisions['direct']['home_url']   = 'https://www.w-tokyodo.com/direct';
		self::$divisions['neostall']['home_url'] = home_url();
		self::$divisions['neoponte']['home_url'] = 'http://www.w-tokyodo.com/neoponte';
		self::$divisions['sharyobu']['home_url'] = 'https://www.w-tokyodo.com/sharyobu';

		// Current
		#$this->current = 'direct';
		$this->current = 'neostall';
		#$this->current = 'neoponte';
		#$this->current = 'sharyobu';
	}

	/**
	 * Cirrent Division Property Getter
	 *
	 * @since 0.0.0
	 */
	public function __get( $name ) {
		if ( isset( $this->division ) && isset( $this->division->$name ) ) {
			return $this->division->$name;
		}
	}

	/**
	 * @since 0.0.0
	 */
	public static function division_exists( $var ) {
		if ( $var = filter_var( $var ) ) {
			return isset( self::$divisions[$var] );
		}
		return false;
	}

	/**
	 * @since 0.0.0
	 */
	public static function home_url( $division = null ) {
		if ( ! $division || ! self::division_exists( $division ) ) {
			return self::WSTD_HOME;
		}
		return self::$divisions[$division]['home_url'];
	}

	/**
	 * @since 0.0.0
	 *
	 * @return object|null
	 */
	public function get_division() {
		return $this->division;
	}

	/**
	 * @since 0.0.0
	 *
	 * @return array
	 */
	public function get_divisions() {
		return self::$divisions;
	}

	/**
	 * @since 0.0.0
	 */
	public function is_division( $division = null ) {
		if ( ! $this->current ) {
			return false;
		}
		if ( ! $division ) {
			return true;
		}
		if ( ! self::division_exists( $division ) ) {
			return false;
		}
		return $division === $this->current;
	}

	/**
	 * @since 0.0.0
	 */
	public function is_division_home( $division = null ) {
		if ( $this->is_division( $division ) ) {
			/**
			 * @todo
			 */
			return is_home();
		}
		return false;
	}

}
