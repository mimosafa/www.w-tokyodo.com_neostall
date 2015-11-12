<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php wp_title( '|', true, 'right' ) ?>ネオ屋台村 | ワークストア・トウキョウドゥ</title>
  <meta name="viewport" content="maximum-scale=0.55">
  <?php wp_head(); ?>
  <!--[if lt IE 9]>
  <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
  <![endif]-->
  <script>window.jQuery || document.write(<?php echo "'<script src=" . '"' . get_template_directory_uri() . '/js/jquery-1.10.2.min.js"' . "><\/script>'" ?>)</script>
</head>

<body <?php body_class(); ?>>

<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<div id="wrap">

<div class="navbar navbar-fixed-top">
  <div class="wstdCompanyDiv">
    <div class="container">
      <div class="dropdown">
        <a id="wstd-id" class="brand dropdown-toggle" data-toggle="dropdown" href="#">Work Store Tokyo Do</a>
        <ul class="dropdown-menu">
          <li>
            <a href="//www.w-tokyodo.com">Workstore Tokyo Do</a>
          </li>
          <li class="divider"></li>
          <li class="nav-header">運営サービス</li>
          <li>
            <a href="//www.w-tokyodo.com/direct">DIRECT MANAGEMENT</a>
          </li>
          <li>
            <a href="//chibaramen.info">千葉らぁ麺</a>
          </li>
          <li>
            <a href="//www.w-tokyodo.com/sharyobu">車両部 - Maintenance Support</a>
          </li>
<?php
if ( is_user_logged_in() ) {
?>
          <li class="divider"></li>
          <li><a id="neosystem-switch-on" href="#"><i class="icon-signin"></i> NeoSystem</a></li>
<?php
}
?>
        </ul>
      </div>
      <ul class="nav pull-right">
        <li><a href="#"><i class="icon-building"></i> 会社案内</a></li>
        <li><a href="#"><i class="icon-envelope"></i> お問い合わせ</a></li>
      </ul>
    </div>
  </div>
  <div class="navbar-inner neoyataiNavbarInner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo home_url(); ?>">ネオ屋台村</a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li>
            <a href="<?php echo get_post_type_archive_link( 'space' ); ?>">
              <i class="icon-food"></i> ネオ屋台村のランチ
            </a>
          </li>
          <li>
            <a href="<?php echo get_post_type_archive_link( 'event' ); ?>">
              <i class="icon-calendar"></i> イベント情報
            </a>
          </li>
          <li>
            <a href="<?php echo get_post_type_archive_link( 'kitchencar' ); ?>">
              <i class="icon-truck"></i> ネオ屋台のご紹介
            </a>
          </li>
          <li>
            <a href="<?php echo get_permalink( get_page_by_path( 'about' )->ID ); ?>">
              <i class="icon-question"></i> ネオ屋台村とは
            </a>
          </li>
          <li class="dropdown wstdCompanyDropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">会社情報 <b class="icon-caret-down"></b></a>
            <ul class="dropdown-menu">
              <li><a href="//www.w-tokyodo.com">Workstore Tokyo Do</a></li>
              <li class="divider"></li>
              <li class="nav-header">運営サービス</li>
              <li><a href="//www.w-tokyodo.com/direct">DIRECT MANAGEMENT</a></li>
              <li><a href="//chibaramen.info">千葉らぁ麺</a></li>
              <li><a href="//www.w-tokyodo.com/sharyobu">車両部 - Maintenance Support</a></li>
            </ul>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div id="contents">

<?php // mmsf_breadcrumb_old(); ?>