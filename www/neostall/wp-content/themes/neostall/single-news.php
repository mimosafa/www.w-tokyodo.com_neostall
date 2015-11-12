<?php get_header();
the_post();
?>
<div id="ttl" class="news">
<h1><?php the_title(); ?></h1>
</div>
<div id="newsBox" <?php post_class(); ?>>
<?php the_content(); ?>
</div>
<p class="linkMark">
  <a href="<?php echo get_post_type_archive_link( 'news' ) ?>">一覧に戻る</a>
</p>
<?php get_footer(); ?>