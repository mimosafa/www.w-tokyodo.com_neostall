<?php get_header(); ?>
<div id="ttl" class="events">
  <h1><?php single_cat_title(); ?></h1>
</div>

<div id="nav-below" class="navigation clearfix" style="margin-top:0;padding-bottom:15px;">
  <p class="nav-next">
    <a href="<?php echo get_post_type_archive_link( 'event' ); ?>">
      最新のイベント情報はこちら
    </a>
  </p>
</div>
<div class="eventInfo">
<?php
    while (have_posts()):
        the_post();
        $d = explode('-', post_custom('開催日時（システム用）'));
        if ($month != $d[1]):
            $month = $d[1] * 1;	// 頭の 0を取り消す
?>
<h2 class="inner-ttl"><?php echo $month; ?>月</h2>
<?php
        endif;
?>
<div class="eventBox">
<dl class="clearfix">
<dt>イベント名</dt>
<dd><a href="<?php the_permalink(); ?>"><?php echo nl2br(post_custom('イベント名')); ?></a></dd>
<dt>開催日時</dt>
<dd><?php echo post_custom('開催日時（表示用）'); ?></dd>
<dt>開催場所</dt>
<dd><?php echo post_custom('開催場所'); ?></dd>
</dl>
</div>
<?php
    endwhile;
?>
</div>

<!-- ページング -->
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<div id="nav-below" class="navigation clearfix">
  <p class="nav-previous"><?php next_posts_link('過去の記事へ'); ?></p>
  <p class="nav-next"><?php previous_posts_link('新しい記事へ'); ?></p>
</div><!-- #nav-below -->
<?php endif; ?>

<?php get_footer(); ?>