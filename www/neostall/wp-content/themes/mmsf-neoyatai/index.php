<?php

/**
 *
 */

get_header(); // has action hook 'mmsf_before_contents' ?>

<div class="container" id="wstd-contents">

<?php
if ( $obj = get_queried_object() )
	printf( '<h1>%s</h1>', esc_html( $obj -> label ) );

if ( have_posts() ) :

	/**
	 *
	 */
	do_action( 'mmsf_before_loops' );

	while ( have_posts() ) :



		the_post(); ?>
<header>
<?php
		if ( is_singular() ) { ?>
<h1><?php the_title(); ?></h1>
<?php
		} else { ?>
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<?php
		} ?>
</header>
<?php

	endwhile;

	/**
	 *
	 */
	do_action( 'mmsf_after_loops' );

else :

	/**
	 *
	 */
	do_action( 'mmsf_no_posts' );

endif; ?>

</div>

<?php
get_footer(); // has action hook 'mmsf_after_contents'