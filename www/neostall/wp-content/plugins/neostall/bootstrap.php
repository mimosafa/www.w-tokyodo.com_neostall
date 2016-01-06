<?php
namespace Neostall;
use mimosafa\WP;
class Bootstrap {
	public static function init() {
		static $ins;
		$ins ?: $ins = new self();
	}
	private function __construct() {
		$this->init_repositories();
	}
	private function init_repositories() {
		$repositories = json_decode(  file_get_contents( __DIR__ . '/models/repositories.json' ), true );
		foreach ( $repositories as $name => $repo ) {
			if ( ! isset( $repo['repository'] ) ) {
				continue;
			}
			$id = isset( $repo['alias'] ) && filter_var( $repo['alias'] ) ? $repo['alias'] : $name;
			$args = isset( $repo['arguments'] ) ? $repo['arguments'] : [];
			if ( $repo['repository'] === 'post_type' ) {
				WP\Repository\PostType::generate( $name, $id, $args );
			}
			else if ( $repo['repository'] === 'taxonomy' ) {
				WP\Repository\Taxonomy::generate( $name, $id, $args );
			}
		}
	}
}
