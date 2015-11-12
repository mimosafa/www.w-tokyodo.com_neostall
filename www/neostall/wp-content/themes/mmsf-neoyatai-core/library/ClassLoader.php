<?php

/**
 * @see http://sotarok.hatenablog.com/entry/20101208/1291739722
 * @see https://gist.github.com/sotarok/732010
 */
class ClassLoader {

	private static $_loaders = [];
	private $_namespace;
	private $_includePath;
	private $_namespace_separator = '\\';

	private $_classNameUnderbar_to_fileNameHyphen = true;
	private $_fileNameLower = true;

	private function __construct( $ns = null, $path = null, $_toHyphen = 1, $filenameLower = 1 ) {
		$this -> _namespace = $ns;
		$this -> _includePath = realpath( $path );
		if ( false === !!$_toHyphen )
			$this -> _classNameUnderbar_to_fileNameHyphen = false;
		if ( false === !!$filenameLower )
			$this -> _fileNameLower = false;
	}

	private function register() {
		spl_autoload_register( [ $this, 'loadClass' ] );
	}

	private function unregister() {
		spl_autoload_unregister( [ $this, 'loadClass' ] );
	}

	public function loadClass( $className ) {
		$sep = $this -> _namespace_separator;
		if (
			null === $this -> _namespace
			|| $this -> _namespace . $sep === substr( $className, 0, strlen( $this -> _namespace . $sep ) )
		) {
			$fileName = '';
			$namespace = '';
			if ( false !== ( $lastNsPos = strripos( $className, $sep ) ) ) {
				$namespace = substr( $className, 0, $lastNsPos );
				$className = substr( $className, $lastNsPos + 1 );
				if ( true === $this -> _fileNameLower ) {
					$className = strtolower( $className );
				}
				$fileName = str_replace( $sep, '/', $namespace ) . '/';
			}
			$replace = true === $this -> _classNameUnderbar_to_fileNameHyphen
				? '-'
				: '/'
			;
			$fileName .= str_replace( '_', $replace, $className ) . '.php';
			$filePath = $this -> _includePath . '/' . $fileName;
			if ( file_exists( $filePath ) ) {
				require $filePath;
			}
		}
	}

	public static function registerLoader( $ns, $path, $_toHyphen = 1, $filenameLower = 1 ) {
		$cl = new self( $ns, $path, $_toHyphen, $filenameLower );
		$cl -> register();
		$key = $ns . $path . intval( !!$_toHyphen ) . intval( !!$filenameLower );
		self::$_loaders[ $key ] = $cl;
	}

	public static function unregisterLoader( $ns, $path, $_toHyphen = 1, $filenameLower = 1 ) {
		$key = $ns . $path . intval( !!$_toHyphen ) . intval( !!$filenameLower );
		if ( isset( $key ) ) {
			self::$_loaders[ $key ] -> unregister();
		}
	}

}
