<?php

get_header();

$heading = '';
$attribute = '';
if ( is_post_type_archive() ) {
    $heading .= post_type_archive_title( '', false );
    $attribute .= sprintf( ' data-post_type="%s"', esc_attr( $post_type ) );
} elseif ( is_tax() ) {
    $heading = sprintf(
        '%s | %s',
        esc_html( get_taxonomy( $taxonomy )->labels->name ),
        esc_html( get_term_by( 'slug', $term, $taxonomy )->name )
    );
    $attribute .= sprintf( ' data-taxonomy="%s"', esc_attr( $taxonomy ) );
    $attribute .= sprintf( ' data-term="%s"', esc_attr( $term ) );
}

if ( !empty( $heading ) )
    echo '<header>';
    printf( '<h3>%s</h3>', $heading );
    echo '</header>';

printf( '<div id="archive-main"%s>', $attribute );

mmsf_archive_loop();

print( '</div>' );

previous_posts_link();
next_posts_link();

get_footer();