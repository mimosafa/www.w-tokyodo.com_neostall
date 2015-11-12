<div id="navi">
<ul id="naviMain">
<li>
  <a href="<?php echo home_url( '/' ); ?>">
    <img src="/_shared/images/neo_yatai/btn_home.png" width="256" height="53" alt="HOME" />
  </a>
</li><!--
--><li>
  <a href="<?php echo get_permalink( get_page_by_path( 'about' )->ID ); ?>">
    <img src="/_shared/images/neo_yatai/btn_about.png" width="256" height="51" alt="ネオ屋台村とは？" />
  </a>
</li><!--
--><li>
  <a href="<?php echo get_post_type_archive_link( 'event' ); ?>">
    <img src="/_shared/images/neo_yatai/btn_event.png" width="256" height="51" alt="今月のイベント情報" />
  </a>
</li><!--
--><li>
  <a href="<?php echo get_post_type_archive_link( 'space' ); ?>">
    <img src="/_shared/images/neo_yatai/btn_schedule.png" width="256" height="51" alt="ランチスケジュール" />
  </a>
</li><!--
--><li>
  <a href="<?php echo get_post_type_archive_link( 'kitchencar' ); ?>">
    <img src="/_shared/images/neo_yatai/btn_intro.png" width="256" height="51" alt="ネオ屋台のご紹介" />
  </a>
</li><!--
--><li>
  <a href="http://www.w-tokyodo.com/contact">
    <img src="/_shared/images/neo_yatai/btn_contact.png" width="256" height="51" alt="お問い合わせ" />
  </a>
</li>
</ul>
<?php
/*
?>
<ul id="naviMain" class="mmsf-naviMain">
  <li>
    <a href="http://www.w-tokyodo.com/neostall/">HOME<span></span></a>
  </li>
  <li>
    <a href="http://www.w-tokyodo.com/neostall/about">ネオ屋台村とは？<span></span></a>
  </li>
  <li>
    <a href="http://www.w-tokyodo.com/neostall/event">今月のイベント情報<span></span></a>
  </li>
  <li>
    <a href="http://www.w-tokyodo.com/neostall/space">ランチスケジュール<span></span></a>
  </li>
  <li>
    <a href="http://www.w-tokyodo.com/neostall/kitchencar">ネオ屋台のご紹介<span></span></a>
  </li>
  <li>
    <a href="http://www.w-tokyodo.com/contact">お問い合わせ<span></span></a>
  </li>
</ul>
<?php
*/
?>

<aside id="nsn">
<a class="specialEvent" href="<?php echo home_url(); ?>/series/nsn">
<h3><span>&nbsp;ネオ屋台村スーパーナイト&nbsp;</span></h3>
<p>自然の木々に囲まれた都会の癒しの空間に出現するビアガーデン「ネオ屋台村スーパーナイト」。毎年4月から11月まで、月1回開催しています。</p>
</a>
</aside>

<aside id="reds">
<a class="specialEvent" href="<?php echo home_url(); ?>/series/reds">
<h3><span>&nbsp;浦和レッズ ホームゲーム&nbsp;</span></h3>
<p>埼玉スタジアム２００２で開催される浦和レッズホームゲーム。ネオ屋台村は2004年から毎シーズン出店しております。</p>
</a>
</aside>

<aside id="ardija">
<a class="specialEvent" href="<?php echo home_url(); ?>/series/ardija">
<h3><span>&nbsp;大宮アルディージャ ホームゲーム&nbsp;</span></h3>
<p>NACK5スタジアム大宮で開催される大宮アルディージャホームゲーム。ハーフタイム終了までGate1横で営業しております。</p>
</a>
</aside>

<?php /* ?>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/shohyo.png" width="245" height="150" />
<?php */ ?>
</div>