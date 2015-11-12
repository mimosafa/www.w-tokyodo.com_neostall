<?php get_header(); ?>
<div id="main">
  <div id="ttl" class="shopcar">
    <h1>ネオ屋台のご紹介</h1>
  </div>
  <div class="clearfix">
<?php
while ( have_posts() ) :
    the_post();
    $name = get_the_display_name();
    $thumb_id = absint( $post->_thumbnail_id );
    $thumb = mmsf_get_lazyload_image( $thumb_id, array( 106, 106 ) );
?>
    <dl style="float:left; margin:0 7px 7px 0; height:106px; width:106px; overflow:hidden;">
      <dt style="display:none;"><?php echo $name; ?></dt>
      <dd><a href="<?php the_permalink(); ?>" title="<?php echo $name; ?>"><?php echo $thumb; ?></a></dd>
    </dl>
<?php
endwhile;
?>
  </div>
  <p class="linkMark"><a href="<?php home_url(); ?>">トップページに戻る</a></p>
</div><!-- /#main -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>