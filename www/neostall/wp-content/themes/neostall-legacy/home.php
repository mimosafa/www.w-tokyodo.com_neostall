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
	wp_reset_postdata();
else : ?>
<li>現在予定されているイベントはありません</li><?php
endif; ?>
</ul><!-- /.homeUl -->
</div><!-- /.clmn -->

<div class="clmn">
<h3 class="homeH3">News</h3>
<ul class="homeUl">
<?php

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
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
</div><!-- /main -->
<?php
get_sidebar();
get_footer();
?>