<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<?php
$description = get_bloginfo( 'description', 'Display' );
if ( is_home() ) {
?>
<meta name="description" content="首都圏各所で展開中のネオ屋台村。ランチ、イベントなどの出店情報を発信しています。">
<meta name="keywords" content="ネオ屋台村,ネオ屋台,屋台村,屋台,キッチンカー,移動販売車,移動販売,ランチ,イベント,東京,サンケイビル,国際フォーラム">
<?php
}
?>
<title><?php wp_title( '|', true, 'right' ) ?>ネオ屋台村 | ワークストア・トウキョウドゥ</title>
<link rel="stylesheet" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
<!--[if lte IE 6]>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/DD_belatedPNG_0.0.8a-min.js">
</script>
<script type="text/javascript">  /* EXAMPLE */  DD_belatedPNG.fix('.toNews,.linkMark,.linkList,.list li a,.listPlace li a,#naviSub li,.nav-next a,.nav-previous a,#naviSub li');</script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="areaSiteHeader" class="clearfix">
  <div id="areaSiteHeaderInner">
    <p>
      <a href="/">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ttl_wtd.png" width="232" height="22" alt="Workstore Tokyo Do" />
      </a>
    </p>
    <ul>
      <li>
        <a href="/#company">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/btn_about_company.png" width="68" height="14" alt="会社案内" />
        </a>
      </li>
      <li>
        <a href="/contact#lunch">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/btn_contact.png" width="100" height="14" alt="お問い合わせ" />
        </a>
      </li>
    </ul>
  </div>
</div>

<div id="header" class="clearfix">
  <div id="headerInner">
    <div id="siteid">
<?php
$ttl_neoyatai = '<img src="' . get_stylesheet_directory_uri() . '/images/neo_yatai/ttl_neo_yatai.png" width="241" height="37" alt="ネオ屋台村" />';
if ( !is_home() ) {
    printf( '<p><a href="%s">%s</a></p>', home_url(), $ttl_neoyatai );
} else {
    printf( '<h1>%s</h1>', $ttl_neoyatai );
}
?>
    </div>
    <ul>
    <li><a href="/direct"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/btn_direct.png" alt="DIRECT MANAGEMENT" width="120" height="69" /></a></li><!--
    --><li><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/btn_neo_yatai_on.png" width="120" height="69" alt="ネオ屋台村" /></li><!--
    --><li><a href="/neoponte"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/btn_neo_ponte.png" width="120" height="69" alt="ネオポンテ" /></a></li><!--
    --><li><a href="/sharyobu"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/btn_shoryo.png" width="120" height="69" alt="車両部" /></a></li>
    </ul>
  </div><!-- /headerInner -->
</div><!-- /header -->

<?php mmsf_breadcrumb(); ?>

<div id="wrap" class="clearfix">