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
	<div class="container">
		<div id="wstdLogo"><?php wstd_very_first_logo(); ?></div>
		<ul class="list-inline" id="wstdCompanyNav">
			<li><a href="/recruit"><i class="fa fa-child"></i> 採用情報</a></li>
			<li><a href="/#company"><i class="fa fa-building-o"></i> 会社案内</a></li>
			<li><a href=""><i class="fa fa-envelope"></i> お問い合わせ</a></li>
		</ul>
	</div>
</div>
<?php
/**
 * Header Navigation
 *
 * @since 0.0.0
 */
?><div id="header-legacy">
	<div class="container">
		<div id="siteid"><?php // wstd_header_site_id(); ?></div>
		<ul>
			<?php // wstd_header_division_nav_lists(); ?>
		</ul>
	</div>
</div>
