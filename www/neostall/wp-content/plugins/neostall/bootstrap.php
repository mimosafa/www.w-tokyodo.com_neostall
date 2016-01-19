<?php
namespace Neostall;
use mimosafa\WP;

if ( class_exists( 'mimosafa\\WP\\Repository\\Factory' ) ) {
	/*
	new WP\Repository\PostType( 'kitchencar', 'kitchencar', 'public=yes&has_archive=yes' );
	new WP\Repository\PostType( 'space',      'space',      'public=yes&has_archive=yes' );
	new WP\Repository\PostType( 'event',      'event',      'public=yes&has_archive=yes' );
	new WP\Repository\PostType( 'management', 'management', 'public=yes&has_archive=yes' );
	*/
	$fctr = WP\Repository\Factory::getInstance();
	$a = $fctr->create_post_type( 'kitchencar', 'public=yes&has_archive=yes' );
	$b = $fctr->create_post_type( 'space', 'public=yes&has_archive=yes' );
	$c = $fctr->create_taxonomy( 'region', 'public=yes' );
	$c->label = '地域';
	$c->hierarchical = true;

	$c->bind( $b );
	$a->bind( $c );
	$c->unbind( $a );
}
