<?php

/**
 * Domain init
 * - 各ドメインの設定、情報を /domainディレクトリー配下のディレクトリーごとにまとめている。
 * - e.g. //www.w-tokyodo.com/neostall/space -> /domain/space
 */

namespace neoyatai;

/**
 * Class domain
 */
class domain {

	/**
	 * @var string
	 */
	private $dir = __DIR__;

	/**
	 * @var array
	 */
	private $configs = array(); // conf.php files

	/**
	 * @var array
	 */
	private $init_array = array(); // args for domain init

	private $post_types = array();

	private $var; // TEST Var

	public function __construct() {
		$this -> scandir();
		if ( !empty( $this -> configs ) )
			$this -> read_config();
		$this -> init();
	}

	public function init() {
		if ( !empty( $this -> init_array ) )
			add_action( 'init', array( $this, 'domain_init' ), 1 );
	}

	/**
	 * 配下のディレクトリー（ドメイン）の phpファイルを読み込む
	 * - conf.php ポストタイプ、タクソノミーの登録など。 $configsに格納する。
	 * - フォルダ名が'_'で始まる場合は除外
	 */
	private function scandir() {
		$entries = scandir( $this -> dir );
		foreach ( $entries as $entry ) {
			if ( '.' == $entry || '..' == $entry || '_' == $entry[0] )
				continue;
			if ( !is_dir( $result = $this -> dir . '/' . $entry ) )
				continue;
			if ( is_readable( $result . '/conf.php' ) )
				$this -> configs[] = $result . '/conf.php';
		}
	}

	/**
	 * $configsに格納された各ドメインの conf.phpを読み込み、初期化の準備をする
	 */
	private function read_config() {
		foreach ( $this -> configs as $config ) {
			$conf_args = require_once $config; // config.phpは、arrayを返すだけ。
			if ( self::is( $conf_args['init'] ) )
				$this -> init_array[] = (array) $conf_args['init'];
		}
	}

	/**
	 * $init_arrayに格納された、ドメインごとの初期化処理を実行
	 */
	public function domain_init() {
		foreach ( $this -> init_array as $array ) {
			foreach ( $array as $init ) {
				$args = self::is( $init['args'] ) ? (array) $init['args'] : array();
				if ( isset( $init['func'] ) && function_exists( (string) $init['func'] ) ) {
					$func = (string) $init['func'];
					call_user_func_array( $func, $args );
				}
			}
		}
	}

	/**
	 * array(
	 *	'register' => 'post_type',
	 *	'name' = false,
	 *	'label' = false,
	 *	'has_archive' => true,
	 *	'public' => true
	 * )
	 */
	/*
	private function post_type_config( $args ) {
		$array = array();
		if ( false === $args['name'] )
	}
	*/

	/**
	 *
	 */
	static function is( $var ) {
		return isset( $var ) && !empty( $var );
	}

}

new domain();
