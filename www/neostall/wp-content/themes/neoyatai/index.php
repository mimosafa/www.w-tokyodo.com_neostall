<?php

get_header();

?>

<div class="container">

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>

<h1><?php
        if ( is_archive() ) {
            echo '<a href="';
            the_permalink();
            echo '">';
        }
        the_title();
        echo ($post->post_parent != '' ? ' | ' . get_the_title($post->post_parent) : '');
        if ( is_archive() ) {
            echo '</a>';
        }
?></h1>

<?php the_content(); ?>

<?php
    endwhile;
endif;
?>

</div><!-- /.contaniner -->

<?php get_footer(); ?>