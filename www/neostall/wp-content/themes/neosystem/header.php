<!DOCTYPE html>
<html>
<head>
<title><?php wp_title( '|', true, 'right' ) ?>NeoSystem</title>
<meta name="viewport" content="width=device-width, initial-scale=0.75, user-scalable=no">
<?php wp_head(); ?>
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
</head>
<body <?php body_class(); ?>>
<div id="wrap">
<div id="neosystem-navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url(); ?>">NeoSystem β</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <?php neosystem_nav_menus(); ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a href="#">Separated link</a></li>
            <li><a id="mmsf-theme-switch" href="#">Neostall</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>

<div class="container">
<?php neosystem_container_top(); ?>