<?php

/**
 * #0 - Include utility modules.
 * #1 - 開発用関数、クラスをロード
 * #2 - 自作の WordPress関数、クラスをロード
 * #3 - クラスのオートロードを登録
 * #4 - ドメイン初期化（カスタム投稿タイプとカスタムタクソノミーの設定、ヘルパー関数の読み込みなど）
 * #5 - Theme init
 */

#0
require_once __DIR__ . '/library/PhpFilesLoader.php';
require_once __DIR__ . '/library/ClassLoader.php';

// utility functions
require_once __DIR__ . '/library/mmsf/utilities.php';

/**
 * Register scripts
 */
wp_register_script(
	'vue',
	'//cdnjs.cloudflare.com/ajax/libs/vue/0.10.6/vue.min.js',
	[], '0.10.6', true
);

#1
PhpFilesLoader::init( __DIR__ . '/library/dev' );

#2
PhpFilesLoader::init( __DIR__ . '/library/wordpress' );

#3
ClassLoader::registerLoader( 'service', __DIR__ . '/application' );
ClassLoader::registerLoader( 'module', __DIR__ . '/application' );
ClassLoader::registerLoader( 'property', __DIR__ . '/application' );

// wordpress model
ClassLoader::registerLoader( 'wordpress\\model', __DIR__ . '/library' );

// namespace: 'mmsf'
ClassLoader::registerLoader( 'mmsf', __DIR__ . '/library' );

#4
new service\domain_init();

#
new service\router();



/**
 * TEST: Custom endpoint
 */
$cre = new wordpress\create_rewrite_endpoints();
$cre -> set( 'test' );
$cre -> set( 'mypage' );
$cre -> init();

add_action( 'template_redirect', function() {
	if ( wordpress\is_endpoint( 'mypage' ) ) {
		echo '<p>mai pe----ji</p>';
		exit;
	}
	if ( wordpress\is_endpoint( 'test' ) ) {
		echo '<p>teeeeest</p>';
		exit;
	}
	if ( wordpress\is_endpoint( 'api' ) ) {
		echo '<p>aaaappppiiiiiii</p>';
		exit;
	}
} );
/**
 * END, TEST
 */








#5
add_action( 'after_setup_theme', function() {

	/**
	 * hacks
	 */
	add_action( 'template_redirect', function() {
		if ( is_page() ) {
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		}
	} );

}, 1 );




class mmsf_admin_menu {

	private $_level;
	private $_parent;
	private $_page_title;
	private $_menu_title;
	private $_capability;
	private $_menu_slug;
	private $_icon_url;
	private $_position;

	public function init() {
		if ( !$this -> page_title )
			return;

		if ( 0 === $this -> _level ) {
			add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		}
		add_action( 'admin_menu', [ $this, 'add_submenu_page' ] );
	}

	public function add_menu_page() {
	}

}







add_action( 'admin_menu', function() {
	add_menu_page( 'page_title', 'menu_title', 'edit_themes', 'menu_slug', 'tteesstt', '', 3 );
} );
/*
add_action( 'admin_menu', function() {
	add_submenu_page( 'edit.php?post_type=activity', 'page_title', 'menu_title', 'edit_themes', 'menu_slug', 'tteesstt' );
} );
*/
//*
function tteesstt() { ?>
<div class="wrap">
<h2>tteesstt</h2>
<?php var_dump( '$tss' ); ?>
</div>
<?php
}
//*/

/*
function aaaaa( $_post ) {
	return $_post -> post_title . ' Done!';
}
add_filter( 'get_filtered_post', 'aaaaa' );

$_p = get_filtered_post( 18800 );

_var_dump( $_p );
//*/

/*
add_action( 'load-post.php', function() {

	$screen = get_current_screen();
	if ( 'event' === $screen -> post_type ) {

		$id = $_GET['post'];

		$props = new event\properties( $id );
		$data = $props -> eventsData;

		_var_dump( $data );

	}

} );
*/






