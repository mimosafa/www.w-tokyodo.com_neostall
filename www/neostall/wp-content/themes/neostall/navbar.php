<?php
do_action( 'neostall_before_navbar' );
?>
<nav id="neoyatai-navbar" class="navbar navbar-default" role="navigation">
  <div class="container">
    <div class="navbar-header visible-xs visible-sm">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#neoyatai-global-nav">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url(); ?>">ネオ屋台村</a>
    </div>
    <div id="neoyatai-global-nav" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li<?php if ( is_post_type_archive( 'space' ) || is_singular( 'space' ) ) echo ' class="active"'; ?>>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'space' ) ); ?>"><i class="fa fa-map-marker"></i> ランチ<span class="hidden-sm">スケジュール</span></a>
        </li>
        <li<?php if ( is_post_type_archive( 'event' ) || is_singular( 'event' ) || is_tax( 'series' ) ) echo ' class="active"'; ?>>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>"><i class="fa fa-calendar"></i> イベント<span class="hidden-sm">スケジュール</span></a>
        </li>
        <li<?php if ( is_post_type_archive( 'kitchencar' ) || is_singular( 'kitchencar' ) ) echo ' class="active"'; ?>>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'kitchencar' ) ); ?>"><i class="fa fa-truck"></i> ネオ屋台<span class="hidden-sm">のご紹介</span></a>
        </li>
        <li<?php if ( is_page( 'about' ) ) echo ' class="active"'; ?>>
          <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' )->ID ) ); ?>"><i class="fa fa-question"></i> ネオ屋台村とは？</a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown visible-xs visible-sm">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-building-o"></i> 会社案内</a>
          <ul class="dropdown-menu">
            <li><a href="/">Workstore Tokyo Do</a></li>
            <li class="divider"></li>
            <li><a href="/terms/">ご利用にあたって</a></li>
            <li><a href="/company/">会社概要</a></li>
            <li><a href="/contact/">お問い合せ</a></li>
            <li class="divider"></li>
            <li class="dropdown-header">その他事業部</li>
            <li><a href="/direct">DIRECT MANAGEMENT</a></li>
            <li><a href="/neoponte">ネオポンテ</a></li>
            <li><a href="/sharyobu">車両部</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php
do_action( 'neostall_after_navbar' );