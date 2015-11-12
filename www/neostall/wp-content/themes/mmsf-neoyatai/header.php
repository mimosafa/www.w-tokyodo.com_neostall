<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=768, initial-scale=.4">
<meta name="format-detection" content="telephone=no">
<title><?php wp_title( '|', 1, 'right' ); ?>ネオ屋台村 | ワークストア・トウキョウドゥ</title>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
/**
 * header navigation, legacy --------------------------------------------------
 */ ?>
<div id="areaSiteHeader">
<div class="container">
<div id="wstdLogo">
<a href="/">Workstore Tokyo Do</a>
</div>
<ul>
<li><a id="wstdCompany" href="/#company">会社案内</a></li>
<li><a id="wstdContact" href="/contact">お問い合わせ</a></li>
</ul>
</div>
</div>
<div id="header-legacy">
<div class="container">
<div id="siteid"><?php
if ( !is_home() ) { ?>
<a href="<?php echo home_url(); ?>">ネオ屋台村</a><?php
} else { ?>
<h1>ネオ屋台村</h1><?php
} ?>
</div>
<ul>
<li id="btn-tokyodo"><a href="/direct">Tokyo Do</a></li>
<li id="btn-neostall" class="now-on-display">ネオ屋台村</li>
<li id="btn-neoponte"><a href="/neoponte">ネオポンテ</a></li>
<li id="btn-sharyobu"><a href="/sharyobu">車両部</a></li>
</ul>
</div>
</div>
<?php
/**
 * END: header navigation, legacy ---------------------------------------------
 */ ?>

<nav class="navbar navbar-default" role="navigation">
<div class="container">

<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#neoyatai-gnav">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
</div>

<div class="collapse navbar-collapse" id="neoyatai-gnav">
<ul class="nav navbar-nav">
<li><a href="<?php echo get_post_type_archive_link( 'space' ); ?>"><i class="fa fa-map-marker"></i> ランチスペース</a></li>
<li><a href="<?php echo get_post_type_archive_link( 'event' ); ?>"><i class="fa fa-calendar"></i> イベントスケジュール</a></li>
<li><a href="<?php echo get_post_type_archive_link( 'kitchencar' ); ?>"><i class="fa fa-truck"></i> ネオ屋台のご紹介</a></li>
</ul>
<?php /*
<form class="navbar-form navbar-right" role="search">
<div class="form-group">
<input type="text" class="form-control" placeholder="Search">
</div>
<button type="submit" class="btn btn-default">Submit</button>
</form>
*/ ?>
</div><!-- /.navbar-collapse -->
</div><!-- /.container -->
</nav>

<?php do_action( 'neoyatai_before_contents' ); ?>

