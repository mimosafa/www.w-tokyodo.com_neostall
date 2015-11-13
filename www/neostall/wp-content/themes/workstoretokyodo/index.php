<?php
/**
 * Workstore Tokyo Do Common Theme - index.php
 *
 * @since 0.0.0
 */
get_header();

/**
 * h tag number for Title
 *
 * @var string
 */
$hnum = is_singular() ? '2' : '3';

?><div class="container">
	<div class="row">
		<div class="col-md-8">

<?php
if ( have_posts() ) :
	/**
	 * Main Loop
	 */
	while ( have_posts() ) : the_post(); ?>

<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * Post Title
		 *
		 * @uses wstd_the_title()
		 */
		wstd_the_title( "<h{$hnum}>", "</h{$hnum}>" ); ?>

	<?php
		/**
		 * Post Content
		 */
		if ( is_singular() ) { the_content(); }
		else { the_excerpt(); } ?>

</section>

<?php
	endwhile;
endif; ?>

		</div>

		<div class="col-md-4">
<?php 

get_sidebar(); ?>

		</div>
	</div>
</div>

<?php

get_footer();
