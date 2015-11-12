<?php get_header(); ?>


<h2>ネオ屋台村の最新情報</h2>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section id="<?php echo get_post_type() . '-' . get_the_ID(); ?>" <?php post_class( 'panel panel-default' ); ?>>
<header class="panel-heading">
<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</header>
<div class="panel-body">
<?php the_excerpt(); ?>
</div>
<footer class="panel-footer">
<?php the_time('Y年 n月 j日'); ?>
</footer>
</section>
<?php endwhile; endif; ?>
<p class="linkMark">
  <a href="<?php echo get_post_type_archive_link( 'news' ); ?>">すべてのニュース</a>
</p>
<?php get_footer(); ?>