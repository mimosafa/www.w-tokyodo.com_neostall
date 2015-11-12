<?php

get_header();

?>

<div class="headerWrapper">
<div class="container">
<h1>ネオ屋台村たち</h1>
<p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
</div>
</div>

<div class="container">

<ul class="nav nav-pills">
  <li class="active"><a href="#">アジアン</a></li>
  <li><a href="#">カレー</a></li>
  <li><a href="#">洋食</a></li>
  <li><a href="#">スイーツ</a></li>
  <li><a href="#">ケバブ</a></li>
</ul>

<div class="row">

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $name = $post->name ? esc_html( $post->name ) : get_the_title();
        $img1_id = $post->_thumbnail_id;
        $img1 = mmsf_get_lazyload_image( $img1_id, array(370,278) );
?>

<div class="span4">
  <section class="kitchencarCell">
    <div class="nytName">
      <h4><?php echo $name; ?></h4>
    </div>
    <a href="#nytDesc-<?php echo $post->ID; ?>" class="nytImage" cap-effect="fade" cap-speed="200">
      <?php echo $img1; ?>
    </a>
    <div id="nytDesc-<?php echo $post->ID; ?>" class="nytDesc">
      <a href="#" class="nytImageThumb"><?php echo $img2; ?><i class="icon-resize-full icon-2x"></i></a>
      <p><span class="label"><?php echo esc_html( $genre ); ?></span> <?php echo esc_html( $items ); ?></p>
      <p><?php echo esc_html( $text ); ?></p>
    </div>
  </section>
</div>

<?php
    endwhile;
endif;
?>

</div><!-- /.row -->

</div><!-- /.contaniner -->

<?php get_footer(); ?>