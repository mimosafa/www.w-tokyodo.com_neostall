<?php

namespace neoyatai\space;

new controller();

class controller {

	const DOMAIN = 'space';

	public function __construct() {
		$this -> init();
	}

	private function init() {
		require_once __DIR__ . '/model.php';
		add_action( 'template_redirect', [ $this, 'template_redirect' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function template_redirect() {
		if ( is_singular( self::DOMAIN ) ) {
			$this -> single_init();
		} elseif ( is_post_type_archive( self::DOMAIN ) ) {
			//$this -> archive_init();
		}
	}

	private function single_init() {
		/*
		add_action( 'neoyatai_contents', function() {
			locate_template( [ 'domain/' . self::DOMAIN . '/template-single.php' ], true );
		} );
		//*/

		global $neoyatai;
		$neoyatai = model::get_instance();
		$neoyatai -> set_contents();
		add_action( 'wp_enqueue_scripts', function() {
			global $neoyatai;
			$js_path = '/js/single-space.js';
			wp_enqueue_script(
				'single-space',
				get_stylesheet_directory_uri() . $js_path,
				[ 'bootstrap', 'theme-script' ],
				date( 'YmdHis', filemtime( get_stylesheet_directory() . $js_path ) ),
				true
			);
			$scripts = [
				'_NEOYATAI_CONTENTS_ALL' => (array) $neoyatai,
				'_NEOYATAI_CONTENTS' => $neoyatai -> contents,
				'_AJAX_ENDPOINT' => admin_url( 'admin-ajax.php' ),
				'_latlng' => $neoyatai -> location['latlng']
			];
			foreach ( $scripts as $name => $data )
				wp_localize_script( 'single-space', $name, $data );
		} );
		//*
		require __DIR__ . '/view.php';
		$view = new view();
		$view -> _singular_init();
		//*/
	}

	private function archive_init() {
	}

	public function enqueue_scripts() {
		if ( is_single() ) {
			wp_enqueue_script( 'google-maps', '//maps.google.com/maps/api/js?sensor=false' );
			wp_enqueue_script( 'vue', '//cdnjs.cloudflare.com/ajax/libs/vue/0.10.5/vue.js' );
			$css_path = '/css/kitchencar-contents.css';
			wp_enqueue_style(
				'kitchencar-contents',
				get_stylesheet_directory_uri() . $css_path,
				array(),
				date( 'YmdHis', filemtime( get_stylesheet_directory() . $css_path ) )
			);
		}
	}

/*

	public $data = [];

	public function template_redirect() {
		global $neoyatai;
		if ( is_singular() ) {
			$neoyatai = model::get_instance();
			$neoyatai -> set_contents();
			add_action( 'wp_enqueue_scripts', function() {
				global $neoyatai;
				$js_path = '/js/single-space.js';
				wp_enqueue_script(
					'single-space',
					get_stylesheet_directory_uri() . $js_path,
					[ 'bootstrap', 'theme-script' ],
					date( 'YmdHis', filemtime( get_stylesheet_directory() . $js_path ) ),
					true
				);
				$scripts = [
					'_NEOYATAI_CONTENTS' => $neoyatai -> contents,
					'_AJAX_ENDPOINT' => admin_url( 'admin-ajax.php' ),
					'_latlng' => $neoyatai -> location['latlng']
				];
				foreach ( $scripts as $name => $data )
					wp_localize_script( 'single-space', $name, $data );
			} );
			require __DIR__ . '/view.php';
			$view = new view();
			$view -> _singular_init();
		}
	}

	function enqueue_scripts() {
		if ( is_single() ) {
			wp_enqueue_script( 'google-maps', '//maps.google.com/maps/api/js?sensor=false' );
			wp_enqueue_script( 'vue', '//cdnjs.cloudflare.com/ajax/libs/vue/0.10.5/vue.js' );
			$css_path = '/css/kitchencar-contents.css';
			wp_enqueue_style(
				'kitchencar-contents',
				get_stylesheet_directory_uri() . $css_path,
				array(),
				date( 'YmdHis', filemtime( get_stylesheet_directory() . $css_path ) )
			);
		}
	}

*/

}

