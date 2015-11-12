<?php
/***
 * Queries
 * Added Action in 'neoyatai-core' theme
 ***/
if ( !is_admin() ) {

    // EVENT
    function neoyatai_event_query( $query ) {
        if ( $query->is_main_query() && $query->is_post_type_archive( 'event' ) ) {
            $query->set( 'posts_per_page', 50 );
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'day' );
        }
        // return;
    }

    // Vendor
    function neoyatai_vendor_query( $query ) {
        if ( $query->is_main_query() && $query->is_post_type_archive( 'vendor' ) ) {
            $query->set( 'posts_per_page', -1 );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'meta_key', 'serial' );
            $query->set( 'order', 'ASC' );
        }
        // return;
    }

    // Kitchencar
    function neoyatai_kitchencar_query( $query ) {
        if ( $query->is_main_query() && $query->is_post_type_archive( 'kitchencar' ) ) {
            $query->set( 'posts_per_page', -1 );
            $query->set( 'orderby', 'meta_value_num menu_order' );
            $query->set( 'order', 'ASC' );
            $query->set( 'meta_key', 'serial' );
        }
        // return; 要らない...よね？
    }

}