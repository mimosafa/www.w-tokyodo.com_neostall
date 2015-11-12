<?php

if ( !is_admin() ) {

    function neoyatai_space_query( $query ) {
        if ( $query->is_main_query() && $query->is_post_type_archive( 'space' ) ) {
            $query->set( 'posts_per_page', -1 );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', 'ASC' );
            $query->set( 'meta_key', 'serial' );
            $meta_query = array(
                array(
                    'key' => 'publication',
                    'value' => 1,
                    'compare' => '='
                )
            );
            $query->set( 'meta_query', $meta_query );
        }
        return;
    }

}