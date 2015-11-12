<?php

/*
Plugin Name: MMSF Degug Var
Plugin URI: 
Description: 
Author: mmsf
Version: 1.1
Author URI: http://mimosafa.me/
*/

/**
 *
 */
if ( !function_exists( '_var_dump' ) ) {
	function _var_dump( $var, $back_to = 1 ) {
		MMSF_DEBUG_VAR::var_dump( $var, $back_to );
	}
}

if ( defined( 'WP_DEBUG' ) && true === \WP_DEBUG ) {
	MMSF_DEBUG_VAR::init();
}

/**
 *
 */
class MMSF_DEBUG_VAR {

	/**
	 * @var array
	 */
	private static $vars = [];

	public static function init() {
		if ( is_admin() ) {
			$hook = 'admin_notices';
			add_action( 'admin_head', 'MMSF_DEBUG_VAR::stylesheet' );
		} else {
			$hook = 'wp_footer';
			add_action( 'wp_head', 'MMSF_DEBUG_VAR::stylesheet' );
		}
		add_action( $hook, 'MMSF_DEBUG_VAR::display_vars' );
	}

	/**
	 *
	 */
	public static function var_dump( $var, $back_to = 1 ) {
		if ( !absint( $back_to ) || 1 > $back_to ) {
			return;
		}
		$vars = array();
		$vars['var'] = $var;
		$debug_backtrace = debug_backtrace( false );
		$vars['backtrace'] = array();
		foreach ( $debug_backtrace as $arg ) {
			if ( $arg['file'] === __FILE__ ) {
				continue;
			}
			$vars['backtrace'][] = array(
				'file' => $arg['file'],
				'line' => $arg['line']
			);
			$back_to--;
			if ( !$back_to ) {
				break;
			}
		}
		MMSF_DEBUG_VAR::$vars[] = $vars;
	}

	/**
	 *
	 */
	public static function display_vars() {
		if ( is_super_admin() && MMSF_DEBUG_VAR::$vars ) {
			foreach ( MMSF_DEBUG_VAR::$vars as $vars ) {
				$i = 0;
				?>
    <div class="message mmsf-debug-var-message">
      <p><b>Variable</b></p>
      <pre><?php var_dump( $vars['var'] ); ?></pre>
      <?php foreach ( $vars['backtrace'] as $array ) { ?>
      <hr>
      <dl>
        <dt>#</dt>
        <dd><?php echo ++$i; ?></dd>
        <dt>File</dt>
        <dd><?php echo $array['file']; ?></dd>
        <dt>Line</dt>
        <dd><?php echo $array['line']; ?></dd>
      </dl>
      <?php } ?>
    </div>
				<?php
			}
		}
	}

	public static function stylesheet() {
		if ( !is_super_admin() || !MMSF_DEBUG_VAR::$vars ) {
			return;
		}
		echo <<<EOF
<style type="text/css">
  .mmsf-debug-var-message {
  	background: #fff;
  	border-left: 4px solid #ccc;
  	-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
  	        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
  	padding: 1px 12px;
  	margin: 5px 0 15px;
  }
  .mmsf-debug-var-message dt {
  	clear: left;
  	float: left;
  	width: 5em;
  	font-weight: bold;
  }
  .mmsf-debug-var-message dd {
  	margin-left: 5em;
  }
</style>
EOF;
	}

}
