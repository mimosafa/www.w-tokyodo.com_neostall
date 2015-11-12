<?php get_header(); ?>
<div id="main">
<div class="topics">
<div class="topicsInner">
<p class="label"><img alt="topics" src="/_shared/images/neo_yatai/img_topics.png" width="18" height="51"></p>
<?php /*
<h2>
	<img src="http://www.w-tokyodo.com/wstd/wp-content/themes/workstoretokyodo/img/forum-spring.jpg" alt="ネオ屋台村 @東京国際フォーラム 地上広場" width="652" height="281" />
</h2>
*/ ?>
<div style="
	background-image: url(http://www.w-tokyodo.com/neostall/wp-content/themes/neostall-legacy/img/nsn-small.jpg);
	background-size: cover;
	background-position-y: 50%;
	height: 275px;
	position: relative;
	margin-bottom: 15px;
">
<h2 style="
	color: #fff;
	font-size: 18px;
	padding: 0 15px;
	position: absolute;
	bottom: 10px;
	right: 0;
	letter-spacing: .075em;
">11月9日 金曜日、ネオ屋台村ボジョレーナイト開催</h2>
</div>
</div>
</div>

<div class="clmn">
<h3 class="homeH3">Events</h3>
<ul class="homeUl">
<?php
$ev_args = array(
	'post_type' => 'event',
	'posts_per_page' => 10,
	'orderby' => 'meta_value',
	'order' => 'ASC',
	'meta_key' => 'day',
	'meta_query' => array(
		array(
			'key' => 'day',
			'value' => date( 'Ymd' ),
			//'value' => date( 'Ymd', strtotime( '-7 day' ) ),
			'compare' => '>=',
			'type' => 'DATE'
		),
		array(
			'key' => 'publication',
			'value' => date( 'Y-m-d H:i:s' ),
			'compare' => '<=',
			'type' => 'DATETIME'
		)
	)
);
$events = new WP_Query( $ev_args );
if ( $events->have_posts() ) :
	while ( $events->have_posts() ) : $events->the_post();
		$ts = strtotime( $post->day );
		$link = esc_url( get_permalink() );
		$series = get_series_tax_obj();
		$ttl = '';
		$series_slug = $series->slug;
		$series_options = get_option( 'series_' . $series_slug );
		if ( $series_options['front_end_archive'] ) {
			$series_link = get_term_link( $series_slug, 'series' );
			$series_name = esc_html( $series->name );
			$ttl .= sprintf( '[%s] %s', $series_name, esc_html( get_the_title() ) );
		} else {
			$ttl .= esc_html( get_the_title() );
		} ?>
<li>
  <a href="<?php echo $link; ?>">
    <span><?php echo date( 'n/j', $ts ); ?><small>(<?php _e( date( 'D', $ts ) ); ?>)</small></span>
    <?php echo $ttl; ?>
  </a>
</li>
<?php
	endwhile;
else : ?>
<li>現在予定されているイベントはありません</li><?php
endif; ?>
</ul><!-- /.homeUl -->
</div><!-- /.clmn -->

<div class="clmn">
<h3 class="homeH3">News</h3>
<ul class="homeUl">
<?php
$ns_args = array(
	'post_type' => array( 'news', 'management' ),
	'posts_per_page' => 10,
	'meta_query' => array(
		array(
            'key' => 'publication',
            'value' => date_i18n( 'Y-m-d H:i:s' ),
            'compare' => '<=',
            'type' => 'DATETIME'
        )
	)
);
$news = new WP_Query( $ns_args );
if ( $news->have_posts() ) :
	while ( $news->have_posts() ) : $news->the_post();
		$link = esc_url( get_permalink() ); ?>
<li>
  <a href="<?php echo $link; ?>">
    <?php the_title(); ?> &lt;更新: <?php the_time( 'n/j (D)'); ?>&gt;
  </a>
</li>
<?php
	endwhile;
endif;
?>
</ul><!-- /.homeUl -->
</div><!-- /.clmn -->
<?php /*
<div class="clmn">
<h3 class="homeH3">Twitter</h3>
<ul class="homeUl">
<li>読み込み中...</li>
</ul>
</div>
*/ ?>

<?php /* ?>
<div class="column">
<p class="label"><span class="orange">イベント</span></p>
<div class="columnInner">
<h2><a href="http://www.w-tokyodo.com/neostall/series/reds"><img class="alignleft size-full wp-image-1523" title="浦和レッズ ホームゲーム" alt="" src="http://www.w-tokyodo.com/neostall/wp-content/uploads/2010/07/reds.png" width="206" height="85"></a></h2>
<p>ネオ屋台村が出店する、「浦和レッズ ホームゲーム @埼玉スタジアム２００２」特設ページです。</p>
</div>
</div>

<div class="column">
<p class="label"><span class="orange">イベント</span></p>
<div class="columnInner">
<h2><a href="http://www.w-tokyodo.com/neostall/series/ardija"><img class="alignleft size-full wp-image-1523" title="大宮アルディージャ ホームゲーム" alt="" src="http://www.w-tokyodo.com/neostall/wp-content/uploads/2010/07/ardija.png" width="206" height="85"></a></h2>
<p>ネオ屋台村が出店する、「大宮アルディージャ ホームゲーム @NACK5スタジアム大宮」特設ページです。</p>
</div>
</div>

<div class="column">
<p class="label"><span class="orange">お知らせ</span></p>
<div class="columnInner">
<h2><a onclick="javascript:pageTracker._trackPageview('/outgoing/twitter.com/neoyatai');" href="http://twitter.com/neoyatai"><img class="aligncenter size-full wp-image-1488" title="ネオ屋台村 ツイッター" alt="ネオ屋台村 ツイッター" src="http://www.w-tokyodo.com/neostall/wp-content/uploads/2010/07/twitterIcon1.png" width="206" height="48"></a></h2>
<p>ネオ屋台村® の公式ツイッターアカウントです。ネオ屋台村に関するツイートにはハッシュタグ”#neoyatai”をつけてつぶやいてください。<!-- ホームページ左側に表示されます(^^) --> どうぞフォローをお願いいたします！</p>
</div>
</div>

<!-- news -->
<div id="news">
<h2><img src="/_shared/images/neo_yatai/ttl_news.png" width="52" height="17" alt="News" /></h2>
<dl>
<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>
<dt><?php the_time('Y年 n月 j日'); ?></dt>
<dd><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dd>
<?php
    endwhile;
else :
?>
<dt><?php echo date_i18n( 'Y年 n月 j日' ); ?></dt>
<dd>現在、NEWS記事はありません</dd>
<?php
endif;
?>
</dl>
</div><!-- /news -->
<p class="linkMark">
  <a href="<?php echo get_post_type_archive_link( 'news' ); ?>">すべてのニュース</a>
</p>
<?php */ ?>
</div><!-- /main -->
<?php
get_sidebar();
get_footer();
?>