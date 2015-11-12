<?php

/**
 * view, model, snippet などが定義されたファイルを読み込む。
 * 今回の場合、すべて functionsディレクトリーにまとめている。
 * 読み込みたくないファイルは削除をするか、ファイル名の最初に'_'を付けるかする。
 *
 * @param  string $path 読み込むディレクトリーのパス。要スラッシュ。
 * @return void
 *
 * 参考エントリー
 * @link http://dogmap.jp/2011/04/19/wordpress-managed-snippet/
 * @link http://kanamehackday.blog17.fc2.com/blog-entry-245.html
 *
 * ...開発が一段落したらちゃんと全部 require_once, または get_template_partする？
 */
class NeoyataiFiles {
	/**
	 * Singleton
	 *
	 * @link http://stein2nd.wordpress.com/2013/10/04/wordpress_and_oop/
	 * @link http://ja.phptherightway.com/pages/Design-Patterns.html
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null == $instance )
			$instance = new static();
		return $instance;
	}
	private function __construct() {}
	private function __clone() {}
	private function __wakeup() {}

	/**
	 * 読み込む phpファイル
	 */
	private $php_files = array();

	/**
	 * ディレクトリーに含まれる phpファイルを走査
	 */
	private function read_dir( $path ) {
		if ( !is_dir( $path ) )
			return;
		$dir = array();
		$entries = scandir( $path );
		foreach ( $entries as $entry ) {
			if ( '.' == $entry || '..' == $entry || '_' == $entry[0] )
				continue;
			$result = $path . $entry;
			if ( is_dir( $result ) )
				$dir[] = $this->read_dir( $result . '/' );
			elseif ( '.php' === strtolower( substr( $result, -4 ) ) )
				$dir[] = $result;
		}
		/**
		 * $dir は配列が入れ子になっていたりするので別関数で展開、$php_files に格納する
		 */
		$this->set_php_files( $dir );
	}

	private function set_php_files( $dir ) {
		if ( !empty( $dir ) ) {
			foreach ( $dir as $var ) {
				if ( is_array( $var ) )
					$this->set_php_files( $var );
				elseif ( is_readable( $var ) )
					$this->php_files[] = $var;
			}
		}
	}

	/**
	 * 初期化 - $php_filesに格納された phpファイルを require_onceする
	 */
	public function init( $path ) {
		$this->read_dir( $path );
		if ( !empty( $this->php_files ) ) {
			foreach ( $this->php_files as $file )
				require_once( $file );
		}
	}
}