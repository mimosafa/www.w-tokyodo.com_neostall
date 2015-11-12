<?php
if ( !is_admin() ) :

    // VENDOR
    if ( !function_exists( 'neoyatai_vendor_query' ) ) {
        function neoyatai_vendor_query( $query ) {
            if ( $query->is_main_query() && $query->is_post_type_archive( 'vendor' ) ) {
                $query->set( 'posts_per_page', -1 );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'ASC' );
                $query->set( 'meta_key', 'serial' );
            }
            return; // 要らない...よね？
        }
    }
    add_action( 'pre_get_posts', 'neoyatai_vendor_query' );

    // KITCHENCAR
    if ( !function_exists( 'neoyatai_kitchencar_query' ) ) {
        function neoyatai_kitchencar_query( $query ) {
            if ( $query->is_main_query() && $query->is_post_type_archive( 'kitchencar' ) ) {
                $query->set( 'posts_per_page', -1 );
                $query->set( 'orderby', 'meta_value_num menu_order' );
                $query->set( 'order', 'ASC' );
                $query->set( 'meta_key', 'serial' );
                if ( !current_user_can( 'edit_posts' ) ) {
                    $meta_query = array(
                        array(
                            'key' => 'phase',
                            'value' => 1,
                            'compare' => '='
                        )
                    );
                    $query->set( 'meta_query', $meta_query );
                }
            }
            return; // 要らない...よね？
        }
    }
    add_action( 'pre_get_posts', 'neoyatai_kitchencar_query' );

    // SPACE
    if ( !function_exists( 'neoyatai_space_query' ) ) {
        function neoyatai_space_query( $query ) {
            if ( $query->is_main_query() && $query->is_post_type_archive( 'space' ) ) {
                $query->set( 'posts_per_page', -1 );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'ASC' );
                $query->set( 'meta_key', 'serial' );
                if ( !current_user_can( 'edit_posts' ) ) {
                    $meta_query = array(
                        array(
                            'key' => 'publication',
                            'value' => 0,
                            'compare' => '!='
                        )
                    );
                    $query->set( 'meta_query', $meta_query );
                }
            }
            return;
        }
    }
    add_action( 'pre_get_posts', 'neoyatai_space_query' );

    // EVENT
    if ( !function_exists( 'neoyatai_event_query' ) ) {
        function neoyatai_event_query( $query ) {
            if ( $query->is_main_query() && $query->is_post_type_archive( 'event' ) ) {
                $query->set( 'posts_per_page', -1 );
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'day' );
                $query->set( 'order', 'DESC' );
                if ( !current_user_can( 'edit_posts' ) ) {
                    $meta_query = array(
                        array(
                            'key' => 'publication',
                            'value' => date( 'Y-m-d H:i:s' ),
                            'compare' => '<=',
                            'type' => 'DATETIME'
                        )
                    );
                    $query->set( 'meta_query', $meta_query );
                }
            }
            return;
        }
    }
    add_action( 'pre_get_posts', 'neoyatai_event_query' );

    // TAXONOMY 'SERIES'
    if ( !function_exists( 'neoyatai_series_query' ) ) {
        function neoyatai_series_query( $query ) {
            if ( $query->is_main_query() && $query->is_tax( 'series' ) ) {
                $query->set( 'posts_per_page', 20 );
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'day' );
                if ( !current_user_can( 'edit_posts' ) ) {
                    $meta_query = array(
                        array(
                            'key' => 'publication',
                            'value' => date( 'Y-m-d H:i:s' ),
                            'compare' => '<=',
                            'type' => 'DATETIME'
                        )
                    );
                    $query->set( 'meta_query', $meta_query );
                }
            }
            return;
        }
    }
    add_action( 'pre_get_posts', 'neoyatai_series_query' );

    // ACTIVITY
    if ( !function_exists( 'neoyatai_activity_query' ) ) {
        function neoyatai_activity_query( $query ) {
            if ( $query->is_main_query() && $query->is_post_type_archive( 'activity' ) ) {
                $query->set( 'posts_per_page', 200 );
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'day' );
                $d_query = array();
                if ( isset( $_GET['date'] ) && preg_match( '/^\d{8}$/', $_GET['date'] ) ) {
                    $_Y = substr( $_GET['date'], 0, 4 );
                    $_m = substr( $_GET['date'], 4, 2 );
                    $_d = substr( $_GET['date'], -2 );
                    if ( checkdate( $_m, $_d, $_Y ) ) {
                        $d_query = array(
                            'key' => 'day',
                            'value' => $_GET['date'],
                            'compare' => '='
                        );
                    }
                }
                $sp_query = array();
                if ( $space_id = absint( $_GET['space_id'] ) and 'space' == get_post( $space_id )->post_type ) {
                    $sp_query = array(
                        'key' => 'space_id',
                        'value' => $_GET['space_id'],
                        'compare' => '='
                    );
                }
                $ev_query = array();
                if ( $event_id = absint( $_GET['event_id'] ) and 'event' == get_post( $event_id )->post_type ) {
                    $ev_query = array(
                        'key' => 'event_id',
                        'value' => $_GET['event_id'],
                        'compare' => '='
                    );
                }
                $k_query = array();
                if ( $k_id = absint( $_GET['k_id'] ) and 'kitchencar' == get_post( $k_id )->post_type ) {
                    $k_query = array(
                        'key' => 'actOf',
                        'value' => $_GET['k_id'],
                        'compare' => '='
                    );
                }
                $more_query = array();
                if ( isset( $_GET['key'] ) && isset( $_GET['val'] ) ) {
                    $more_query = array(
                        'key' => $_GET['key'],
                        'value' => $_GET['val']
                    );
                }
                $meta_query = array( $d_query, $sp_query, $ev_query, $k_query, $more_query );
                $query->set( 'meta_query', $meta_query );
            }
            return;
        }
    }
    add_action( 'pre_get_posts', 'neoyatai_activity_query' );

    // home.php
    if ( !function_exists( 'neoyatai_home_query' ) ) {
        function neoyatai_home_query( $query ) {
            if ( $query->is_main_query() && $query->is_home() ) {
                $query->set( 'post_type', 'news' );
            }
            return;
        }
    }
    add_action( 'pre_get_posts', 'neoyatai_home_query' );

endif;