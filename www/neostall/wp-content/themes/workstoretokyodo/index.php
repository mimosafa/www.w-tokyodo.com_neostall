<?php
/**
 * Workstore Tokyo Do Common Theme - index.php
 *
 * @since 0.0.0
 */
get_header();

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>

<section <?php post_class(); ?>>
	<h3><?php the_title(); ?></h3>
	<?php the_content(); ?>
</section>

<?php
	endwhile;
endif;

get_sidebar();

get_footer();
