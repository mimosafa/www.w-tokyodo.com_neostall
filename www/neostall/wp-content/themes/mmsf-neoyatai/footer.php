<?php

if ( is_user_logged_in() )
	do_action( 'mmsf_var_dump' );

?>


<?php
/**
 *
 */
do_action( 'mmsf_after_contents' ); ?>
<footer id="wstd-footer" class="container-fluid">
<div class="row">
<div class="col-sm-8 col-sm-offset-2" id="wstd-footer-inner">
<div class="row" id="wstd-marks">
<div class="col-sm-3"><a href="/direct"><i class="icon-direct"></i></a></div>
<div class="col-sm-3"><a href="/neostall"><i class="icon-neoyatai"></i></a></div>
<div class="col-sm-3"><a href="/neoponte"><i class="icon-neoponte"></i></a></div>
<div class="col-sm-3"><a href="/sharyobu"><i class="icon-sharyobu"></i></a></div>
</div>
<nav>
<ul class="list-inline">
<li><a href="/">トップページ</a></li>
<li><a href="/terms">ご利用にあたって</a></li>
<li><a href="/#company">会社概要</a></li>
<li><a href="/contact">お問い合せ</a></li><?php
if ( is_user_logged_in() ) { ?>
<li><a id="mmsf-theme-switch" href="#">NeoSystem</a></li><?php
} ?>
</ul>
</nav>
<p>©<?php echo date( 'Y' ); ?>&nbsp;Workstore&nbsp;Tokyo&nbsp;Do&nbsp;ALL&nbsp;Rights&nbsp;Reserved.</p>
</div>
</div>
</footer>
<?php wp_footer(); ?>
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</body>
</html>