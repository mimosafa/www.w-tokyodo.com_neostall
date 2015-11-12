<?php

mmsfScrollTop();

function mmsfScrollTop() {
	$st = new mmsf_scroll_top();
	$st -> init();
}

class mmsf_scroll_top {

	function init() {
		add_action( 'wp_head', array( $this, 'style' ) );
		add_action( 'wp_footer', array( $this, 'html' ), 0 );
		add_action( 'wp_footer', array( $this, 'script' ), 99 );
	}

	function html() { ?>
<div id="mmsf-scroll-top"><i class="fa fa-arrow-up"></i></div><?php
	}

	function style() { ?>
<style>
/* scroll top */
#mmsf-scroll-top {
  font-size: 75px;
  text-align: center;
  position: fixed;
  width: 100%;
  top: 0;
  cursor: pointer;
  display: none;
  background-color: rgba(245,245,245,0);
  transition: background-color .2s;
}
#mmsf-scroll-top:hover {
  background-color: rgba(245,245,245,.5);
}
#mmsf-scroll-top > i {
  color: #d3d3d3;
  opacity: .5;
  transition: all .2s;
}
#mmsf-scroll-top:hover > i {
  color: #fff;
  text-shadow: 0 0 4px #c0c0c0,
               0 0 16px #a9a9a9;
  opacity: .9;
}
</style>
<?php
	}

	function script() { ?>
<script>
(function($){
  /**
   * scroll to top
   */
  var scrltp = $( '#mmsf-scroll-top' );
  scrltp.click( function( e ) {
    e.preventDefault();
    $( 'html, body' ).animate( { scrollTop: 0 }, 400 );
  });
  $( window ).on( 'scroll resize', function() {
    var len = $( window ).scrollTop();
    if ( 250 < len ) scrltp.fadeIn( '200' );
    else scrltp.fadeOut( '800' );
  });
})(jQuery);
</script><?php
	}
}
