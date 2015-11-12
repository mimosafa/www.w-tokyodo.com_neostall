<?php get_header(); ?>
<?php the_post(); ?>
<article id="<?php echo get_post_type() . '-' . get_the_ID(); ?>" <?php post_class(); ?>>
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php get_footer(); ?>