<?php

namespace wordpress;

/**
 *
 */
class register_customs {

	private $_post_types = [];
	private $_taxonomies = [];

	private static $_cpt_labels = [];
	private static $_ct_labels = [];

	public function init() {
		if ( !empty( $this -> _post_types ) ) {
			add_action( 'init', [ $this, 'register_post_type' ], 1 );
		}
		if ( !empty( $this -> _taxonomies ) ) {
			add_action( 'init', [ $this, 'register_taxonomy' ], 1 );
		}
	}

	public function add_post_type( $post_type, $label, $options = [] ) {
		$cpt = [
			'post_type' => $post_type,
			'label'     => $label,
			'options'   => $options
		];
		$this -> _post_types[] = $cpt;
	}

	public function add_taxonomy( $taxonomy, $label, $post_types = [], $options = [] ) {
		$ct = [
			'taxonomy'   => $taxonomy,
			'label'      => $label,
			'post_types' => $post_types,
			'options'    => $options
		];
		$this -> _taxonomies[] = $ct;
	}

	public function register_post_type() {
		foreach ( $this -> _post_types as $pt ) {
			$pt['options']['label'] = $pt['label'];
			if ( !empty( $this -> _taxonomies ) ) {
				$taxonomies = [];
				foreach ( $this -> _taxonomies as $tax ) {
					if ( in_array( $pt['post_type'], $tax['post_types'] ) )
						$taxonomies[] = $tax['taxonomy'];
				}
				if ( !empty( $taxonomies ) ) {
					$pt['options'] = array_merge(
						$pt['options'],
						[ 'taxonomies' => $taxonomies ]
					);
				}
			}
			register_post_type( $pt['post_type'], $pt['options'] );
		}
	}

	public function register_taxonomy() {
		foreach ( $this -> _taxonomies as $tx ) {
			$tx['options']['label'] = $tx['label'];
			register_taxonomy( $tx['taxonomy'], $tx['post_types'], $tx['options'] );
		}
	}

}
