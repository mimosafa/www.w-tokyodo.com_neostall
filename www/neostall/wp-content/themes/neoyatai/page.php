<?php

get_header();

the_post();

?>

<div class="headerWrapper">
<div class="container">
<h1><?php the_title(); ?><?php echo ($post->post_parent != '' ? ' | ' . get_the_title($post->post_parent) : ''); ?></h1>
<p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
</div>
</div>

<div class="container">
<div class="row">
<div class="span12">

<?php the_content(); ?>

</div>
</div><!-- /.row -->
</div><!-- /.contaniner -->

<?php get_footer(); ?>