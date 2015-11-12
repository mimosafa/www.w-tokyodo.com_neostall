<?php get_header(); ?>
<div id="ttl" class="news">
  <h1>ニュース</h1>
</div>

<div id="news">
<dl>
<?php
    while (have_posts()):
        the_post();
?>
<dt><?php the_time('Y年 n月 j日'); ?></dt>
<dd><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dd>
<?php
    endwhile;
?>
</dl>
</div>

<!-- ページング -->
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<div id="nav-below" class="navigation clearfix">
  <p class="nav-previous"><?php next_posts_link('過去の記事へ'); ?></p>
  <p class="nav-next"><?php previous_posts_link('新しい記事へ'); ?></p>
</div><!-- #nav-below -->
<?php endif; ?>

<?php get_footer(); ?>