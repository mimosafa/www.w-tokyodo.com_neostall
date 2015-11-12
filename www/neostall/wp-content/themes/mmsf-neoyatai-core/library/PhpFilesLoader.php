<?php

/**
 *
 */
class PhpFilesLoader {

	private $_dir;

	private function __construct( $path ) {
		$this -> _dir = new DirectoryIterator( $path );
	}

	private function requirePhpFiles() {
		foreach ( $this -> _dir as $fileinfo ) {
			if ( $fileinfo -> isDot() )
				continue;
			if ( 'php' === $fileinfo -> getExtension() && $fileinfo -> isReadable() )
				require_once $fileinfo -> getPathname();
		}
	}

	public static function init( $path ) {
		$fl = new self( $path );
		$fl -> requirePhpFiles();
	}

}