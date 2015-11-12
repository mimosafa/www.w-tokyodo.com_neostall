<?php get_header(); ?>

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>

<section <?php post_class(); ?>>
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<?php the_content(); ?>
</section>

<?php
    endwhile;
else :
?>

<p class="well">No Posts</p>

<?php
endif;
?>

<pre>
<?php
?>
</pre>

<?php get_footer(); ?>