<?php

global $post;

$arg = array(
    'post_type' => 'attachment',
    'post_parent' => $post->ID,
    'post_mime_type' => 'image/jpeg',
    'posts_per_page' => -1,
    'post_status' => 'inherit'
);
$medias = get_posts( $arg );

if ( $medias ) :
    foreach ( $medias as $post ) {
        setup_postdata( $post );
        printf( '<h4>%s <small>ID: %d</small></h4>', get_the_title(), get_the_ID() );
        the_content();
        echo '<pre>';
        var_dump( get_post_custom() );
        echo '</pre>';
    }
    wp_reset_postdata();
endif;