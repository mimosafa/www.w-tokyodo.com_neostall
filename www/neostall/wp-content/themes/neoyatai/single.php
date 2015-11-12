<?php

get_header();

the_post();

?>

<div class="headerWrapper">
  <div class="container">
    <h2>
      <?php the_title(); ?>
    </h2>
  </div>
</div>

<div class="container">

<?php the_content(); ?>

</div><!-- /.contaniner -->

<?php get_footer(); ?>