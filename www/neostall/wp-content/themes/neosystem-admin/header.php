<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php wp_title( '|', true, 'right' ) ?>NeoSystem</title>
<meta name="viewport" content="width=device-width, initial-scale=0.75, user-scalable=no">
<?php wp_head(); ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
<![endif]-->
<script>window.jQuery || document.write(<?php echo "'<script src=" . '"' . get_template_directory_uri() . '/js/jquery-1.10.2.min.js"' . "><\/script>'" ?>)</script>
</head>
<body <?php body_class(); ?>>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner neoyataiNavbarInner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo home_url(); ?>">NeoSystem</a>
      <div class="nav-collapse collapse pull-right">
        <ul class="nav">
          <li><a href="<?php echo get_post_type_archive_link( 'activity' ); ?>"><i class="icon icon-exclamation"></i> アクティビティー</a></li>
          <li><a href="<?php echo get_post_type_archive_link( 'management' ); ?>"><i class="icon icon-wrench"></i> 管理情報</a></li>
          <li><a href="<?php echo get_post_type_archive_link( 'event' ); ?>"><i class="icon icon-calendar"></i> イベント</a></li>
          <li><a href="<?php echo get_post_type_archive_link( 'space' ); ?>"><i class="icon icon-map-marker"></i> スペース</a></li>
          <li><a href="<?php echo get_post_type_archive_link( 'vendor' ); ?>"><i class="icon icon-building"></i> 事業者</a></li>
          <li><a href="<?php echo get_post_type_archive_link( 'kitchencar' ); ?>"><i class="icon icon-truck"></i> ネオ屋台</a></li>
          <li><a id="mmsf-theme-switch" href="#"><i class="icon-signout"></i> DEV</a></li>
        </ul>
      </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
  </div><!-- /.navbar-inner -->
</div><!-- /.navbar -->

<div class="container">
<?php show_error_message(); ?>