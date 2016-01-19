<?php
namespace mimosafa\WP\Repository;

interface Repository {

	public function __construct( $name, $args, Factory $factory );

	public function __get( $name );

	public function __set( $name, $var );

	public function bind( $repository );

	public static function getInstance( $var );

}
