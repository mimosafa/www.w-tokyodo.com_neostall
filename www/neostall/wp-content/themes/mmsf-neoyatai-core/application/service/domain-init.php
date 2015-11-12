<?php

namespace service;

/**
 * ドメイン初期化クラス
 *
 * @uses \DirectoryIterator
 * @uses \wordpress\register_customs
 * @uses \wordpress\rewrite_slug_to_postid
 * @uses \wordpress\create_rewrite_endpoint
 * @uses \ClassLoader
 */
class domain_init {

	const API_SLUG = 'api';

	/**
	 * テーマディレクトリー配下のドメインフォルダーが格納されているするディレクトリー
	 */
	private $_domains_dir = 'domains';

	/**
	 * カスタム投稿タイプ、カスタムタクソノミー登録用インスタンス
	 *
	 * @var null | object \wordpress\register_customs
	 */
	private static $registerCustoms = null;

	/**
	 * カスタム投稿タイプのリライトルール変更用インスタンス (slugを post_idにする )
	 *
	 * @var null | object \wordpress\rewrite_slug_to_postid
	 */
	private static $rewriteSlugToPostid = null;

	/**
	 * 自前のエンドポイント作成用インスタンス
	 *
	 * @var null | object \wordpress\create_rewrite_endpoint
	 */
	private static $createRewriteEndpoints;

	/**
	 * /domains/(domain)/helper.php
	 *
	 * @var array file path to helper.php
	 */
	private $helper_files = [];

	/**
	 * 初期化された各ドメインのクラスローダー
	 *
	 * @var array
	 */
	private $classloaders = [];

	/**
	 * カスタム投稿タイプのデフォルト引数 (properties.phpの内容よりも優先される )
	 */
	private $_cpt_option = [
		'public'       => true,
		'has_archive'  => true,
		'hierarchical' => false,
		'rewrite'      => [ 'with_front' => false ],
		'supports'     => false,
	];

	/**
	 * カスタムタクソノミーのデフォルト引数 (properties.phpの内容よりも優先される )
	 */
	private $_ct_option = [
		'public'            => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'with_front' => false ],
	];

	/**
	 * #0 コンストラクター
	 */
	public function __construct() {
		/**
		 *
		 */
		self::$createRewriteEndpoints = new \wordpress\create_rewrite_endpoints();
		self::$createRewriteEndpoints -> set( self::API_SLUG );

		$this -> scan_dir();
		$this -> init();
	}

	/**
	 * #4
	 */
	private function init() {

		// Custom post types & Custom taxonomies
		if ( null !== self::$registerCustoms )
			self::$registerCustoms -> init();

		// Rewrite slug to post_id
		if ( null !== self::$rewriteSlugToPostid )
			self::$rewriteSlugToPostid -> init();

		// Customs endpoint
		self::$createRewriteEndpoints -> init();

		// ClassLoader
		if ( !empty( $this -> classloaders ) ) {
			foreach ( $this -> classloaders as $domain => $somePath ) {
				foreach ( $somePath as $path )
					\ClassLoader::registerLoader( $domain, $path );
			}
		}

		// Include helper.php
		if ( !empty( $this -> helper_files ) ) {
			foreach ( $this -> helper_files as $helper )
				require_once $helper;
		}

	}

	/**
	 * #1 親テーマディレクトリー、子テーマディレクトリーの順番でドメインディレクトリーを走査する
	 *
	 * @return bool
	 */
	private function scan_dir() {

		/**
		 * 走査するテーマディレクトリー
		 */
		$themeDirs = [
			TEMPLATEPATH,  // 親テーマ
			STYLESHEETPATH // 子テーマ (子テーマを使用していない場合は親テーマと同一になる )
		];

		// ドメインとして登録できる(properties.phpがある )ドメインと設定を格納する変数
		$domains = [];

		// 走査したテーマディレクトリーを格納 (子テーマが在るかどうかの確認用 )
		$done = '';

		foreach ( $themeDirs as $themeDir ) { // ...*1

			// TEMPLATEPATHと STYLESHEETが同じ場合 (子テーマが無い場合 )はループは一回で終了
			if ( $themeDir === $done )
				break;

			$path = realpath(
				trailingslashit( $themeDir ) . ltrim( $this -> _domains_dir, '/' )
			);
			if ( !is_readable( $path ) )
				continue;

			$dir = new \DirectoryIterator( $path );

			foreach ( $dir as $fileinfo ) {

				if ( $fileinfo -> isDot() || !$fileinfo -> isDir() )
					continue;

				$domain = $fileinfo -> getFilename();

				/**
				 * クラスのオートローダーとヘルパー関数が定義されたファイル (helper.php)
				 * - テーマディレクトリーループ (*1)で 2巡目のみを対象。(子テーマ )
				 * - 親テーマディレクトリーよりも前にしたいため、array_unshiftで配列の先頭に追加する。
				 */
				if ( isset( $this -> classloaders[$domain] ) ) {
					array_unshift( $this -> classloaders[$domain], $path );
					if ( $helper_path = self::returnReadableFilePath( $fileinfo, 'helper.php' ) )
						array_unshift( $this -> helper_files, $helper_path );
				}

				/**
				 * properties.phpのヘッダーコメントを読み込み
				 * ファイルが無ければ continue
				 *
				 * @see http://dogmap.jp/2014/09/10/post-3109/
				 */
				if ( !$property_file = self::returnReadableFilePath( $fileinfo, 'properties.php' ) ) {
					continue;
				}
				$property = get_file_data( $property_file, [
					'name'          => 'Name',
					'plural_name'   => 'Plural Name',
					'register'      => 'Register As',
					'post_type'     => 'Post Type Name',
					'taxonomy'      => 'Taxonomy Name',
					'taxonomy_type' => 'Taxonomy Type',
					'related_post_type' => 'Related Post Type',
					'rewrite'       => 'Permalink Format',
				] );
				$property = array_filter( $property );

				// 親テーマの場合はそのまま代入、子テーマの場合 (すでに $domainsに要素がある場合)はマージする。
				$domains[$domain] = is_null( $domains[$domain] )
					? $property
					: array_merge( $domains[$domain], $property )
				;

				/**
				 * クラスのオートローダーとヘルパー関数が定義されたファイル (helper.php)
				 * - テーマディレクトリーループ (*1)で 1巡目のみを対象。(親テーマ )
				 */
				if ( !isset( $this -> classloaders[$domain] ) ) {
					$this -> classloaders[$domain][] = $path;
					if ( $helper_path = self::returnReadableFilePath( $fileinfo, 'helper.php' ) )
						array_unshift( $this -> helper_files, $helper_path );
				}
			}
			$done = $themeDir;
		}

		if ( !empty( $domains ) ) {
			$this -> classfy_domains( $domains );
		}
	}

	/**
	 * @param  DirectoryIterator $fileinfo
	 *         string $file
	 * @return readable file path | false
	 */
	private static function returnReadableFilePath( $fileinfo, $file ) {
		if ( 'DirectoryIterator' !== get_class( $fileinfo ) )
			return false;
		$path = $fileinfo -> getPathname() . '/' . ltrim( $file, '/' );
		if ( is_readable( $path ) )
			return $path;
		return false;
	}

	/**
	 * #2
	 */
	private function classfy_domains( $domains ) {
		foreach ( $domains as $domain => $array ) {
			if ( 'Custom Post Type' === $array['register'] ) {
				$this -> cpt_setting( $domain, $array );
			} elseif ( 'Custom Taxonomy' === $array['register'] ) {
				$this -> ct_setting( $domain, $array );
			} elseif ( 'Custom Endpoint' === $array['register'] ) {
				$this -> ep_setting( $domain, $array ); // yet!
			}
		}
	}

	/**
	 * #3
	 */
	private function cpt_setting( $domain, $array ) {
		// Post type register name
		$post_type = ( array_key_exists( 'post_type', $array ) )
			? esc_html( $array['post_type'] )
			: esc_html( $domain )
		;
		// Label
		$label = ( array_key_exists( 'name', $array ) )
			? esc_html( $array['name'] )
			: ucwords( str_replace( '_', ' ', $post_type ) )
		;
		// Rewrite
		$opt = [ 'rewrite' => [ 'slug' => $domain ] ];
		/*
		// Capability
		if ( $plural_post_type = $array['capability'] ) {
			if ( $post_type !== $plural_post_type && is_string( $plural_post_type ) ) {
				$opt['capability_type'] = [ $post_type, $plural_post_type ];
				$opt['map_meta_cap'] = true;
			}
		}
		*/
		// ~
		$opt = self::array_merge( $opt, $this -> _cpt_option );

		/**
		 * Get instance \wordpress\register_customs, if not constructed.
		 */
		if ( null === self::$registerCustoms ) {
			self::$registerCustoms = new \wordpress\register_customs();
		}

		self::$registerCustoms -> add_post_type( $post_type, $label, $opt );

		if ( array_key_exists( 'rewrite', $array ) && 'ID' === $array['rewrite'] ) {
			/**
			 * Get instance \wordpress\rewrite_slug_to_postid, if not constructed.
			 */
			if ( null === self::$rewriteSlugToPostid ) {
				self::$rewriteSlugToPostid = new \wordpress\rewrite_slug_to_postid();
			}

			self::$rewriteSlugToPostid -> set( $post_type );
		}
	}

	/**
	 * #3
	 */
	private function ct_setting( $domain, $array ) {
		// Taxonomy register name
		$taxonomy = array_key_exists( 'taxonomy', $array )
			? esc_html( $array['taxonomy'] )
			: esc_html( $domain )
		;
		// Label
		$label = array_key_exists( 'name', $array )
			? esc_html( $array['name'] )
			: ucwords( str_replace( '_', ' ', $taxonomy ) )
		;
		// Post types
		$post_types = explode( ',', $array['related_post_type'] );
		$post_types = array_map( function( $string ) {
			return trim( $string );
		}, $post_types );
		// Rewrite
		$opt = [ 'rewrite' => [ 'slug' => $domain ] ];
		if ( array_key_exists( 'taxonomy_type', $array ) && 'Category' === $array['taxonomy_type'] ) {
			$opt['hierarchical'] = true;
			$opt['rewrite']['hierarchical'] = true;
		}
		// ~
		$opt = self::array_merge( $opt, $this -> _ct_option );

		/**
		 * Get instance \wordpress\register_customs, if not constructed.
		 */
		if ( null === self::$registerCustoms ) {
			self::$registerCustoms = new \wordpress\register_customs();
		}

		self::$registerCustoms -> add_taxonomy( $taxonomy, $label, $post_types, $opt );
	}

	/**
	 *
	 */
	private function ep_setting( $domain, $array ) {
		// ~ yet!!
		self::$createRewriteEndpoints -> set( $domain );
	}

	/**
	 * 多次元配列をマージ
	 *
	 * @param   Array $a
	 *          Array $b
	 * @return  Array
	 */
	private static function array_merge( array $a, array $b ) {
		foreach ( $a as $key => $val ) {
			if ( !isset( $b[$key] ) ) {
				$b[$key] = $val;
			} elseif ( is_array( $val ) ) {
				$b[$key] = self::array_merge( $val, $b[$key] );
			}
		}
		return $b;
	}

}
