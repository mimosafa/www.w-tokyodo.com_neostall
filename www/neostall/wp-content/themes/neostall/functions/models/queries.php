<?php

/**
 * customized queries
 */
function neostall_queries( $query ) {
    if ( is_admin() || !$query->is_main_query() )
        return;

    $meta_query = array();

    if ( $query->is_home() ) {
        $query->set( 'post_type', array( 'news', 'management' ) );
        $meta_query[] = array(
            'key' => 'publication',
            'value' => date_i18n( 'Y-m-d H:i:s' ),
            'compare' => '<=',
            'type' => 'DATETIME'
        );
    }

    if ( $query->is_post_type_archive( 'kitchencar' ) ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'orderby', 'meta_value_num menu_order' );
        $query->set( 'order', 'ASC' );
        $query->set( 'meta_key', 'serial' );
    }

    if ( $query->is_post_type_archive( 'space' ) ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'orderby', 'meta_value_num' );
        $query->set( 'order', 'ASC' );
        $query->set( 'meta_key', 'serial' );
    }

    if ( $query->is_post_type_archive( 'event' ) ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', 'day' );
        $query->set( 'order', 'DESC' );
    }

    if ( !empty( $meta_query ) )
        $query->set( 'meta_query', $meta_query );

}
add_action( 'pre_get_posts', 'neostall_queries' );