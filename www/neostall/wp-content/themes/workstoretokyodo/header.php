<?php
/**
 * Workstore Tokyo Do Common Theme - header.php
 *
 * @since 0.0.0
 */
?><!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=1240, initial-scale=.25">
<meta name="format-detection" content="telephone=no">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
/**
 * Area Site Header
 *
 * @since 0.0.0
 */
?><div id="areaSiteHeader">
	<nav class="container">
		<div class="pull-left" id="wstdLogo"><?php wstd_very_first_logo(); ?></div>
		<ul class="pull-right list-inline" id="wstdCompanyNav">
			<li><a href="/recruit"><i class="fa fa-child"></i> 採用情報</a></li>
			<li><a href="/#company"><i class="fa fa-building-o"></i> 会社案内</a></li>
			<li><a href=""><i class="fa fa-envelope"></i> お問い合わせ</a></li>
		</ul>
	</nav>
</div>
<?php
/**
 * Header Navigation
 *
 * @since 0.0.0
 */
?><div id="divisionNavBar">
	<nav class="container">
		<div id="siteid"><?php wstd_site_id(); ?></div>
		<ul>
			<?php wstd_division_links( 'header' ); ?>
		</ul>
	</nav>
</div>
<?php
/**
 * Show Header Image, If Exists
 *
 * @since 0.0.0
 */
if ( wstd_has_header_image() ) {
	/**
	 * @todo
	 */
?>
<div id="wstd-header-image"></div>
<?php
}
/**
 * Show Global Navigation, If Exists
 *
 * @since 0.0.0
 */
if ( wstd_has_global_nav() ) {
	/**
	 * @todo
	 */
?><nav class="navbar navbar-default" id="wstd-global-nav">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php wstd_global_nav_links(); ?>
			</ul>
			<?php wstd_global_nav_form(); ?>
		</div>
	</div>
</nav><?php
}
