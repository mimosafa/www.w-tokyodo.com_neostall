<?php

if ( have_posts() ) :

	/**
	 *
	 */
	do_action( 'mmsf_before_loops' );

	while ( have_posts() ) :
		the_post();

		/**
		 *
		 */
		do_action( 'mmsf_before_contet', $post );

		/**
		 *
		 */
		do_action( 'mmsf_content', $post );

		/**
		 *
		 */
		do_action( 'mmsf_after_contet', $post );

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

endif;
