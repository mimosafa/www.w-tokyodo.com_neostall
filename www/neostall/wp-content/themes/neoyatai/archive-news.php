<?php

get_header();

?>

<div class="headerWrapper">
  <div class="container">
    <h1>News</h1>
  </div>
</div>

<div class="container">

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>

<h3>
  <a href="<?php  the_permalink(); ?>"><?php the_title(); ?></a>
</h3>

<?php the_content(); ?>

<?php
    endwhile;
endif;
?>

</div><!-- /.contaniner -->

<?php get_footer(); ?>