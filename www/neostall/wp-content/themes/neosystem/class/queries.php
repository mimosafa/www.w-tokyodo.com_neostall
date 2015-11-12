<?php

/**
 * queries
 */
class MmsfQuery {

    private $query_rules = array(
        'post_type' => array(
            'vendor' => array(
                'posts_per_page' => -1,
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_key' => 'serial'
            ),
            'kitchencar' => array(
                'posts_per_page' => -1,
                'orderby' => 'meta_value_num menu_order',
                'order' => 'ASC',
                'meta_key' => 'serial'
            ),
            'space' => array(
                'posts_per_page' => -1,
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_key' => 'serial'
            ),
            'event' => array(
                'posts_per_page' => 20,
                'orderby' => 'meta_value',
                'order' => 'DESC',
                'meta_key' => 'day'
            ),
            'activity' => array(
                'posts_per_page' => 200,
                'orderby' => 'meta_value',
                'meta_key' => 'day'
            ),
        ),
        'taxonomy' => array(
            'series' => array(
                'posts_per_page' => 20,
                'orderby' => 'meta_value',
                'meta_key' => 'day'
            )
        ),
        'home' => array(
            'post_type' => 'news'
        )
    );

    function __construct() {
        add_action( 'pre_get_posts', array( $this, 'init' ) );
    }

    function set_query( $query, $array ) {
        foreach ( $array as $key => $val ) {
            $query->set( $key, $val );
        }
    }

    function init( $query ) {

        if ( is_admin() && !$query->is_main_query() )
            return;

        if ( $query->is_home() ) {
            $array = $this->query_rules['home'];
            if ( !empty( $array ) )
                $this->set_query( $query, $array );
            return;
        } elseif ( $query->is_post_type_archive() ) {
            $fn = 'is_post_type_archive';
            $arrays = $this->query_rules['post_type'];
        } elseif ( $query->is_tax() ) {
            $fn = 'is_tax';
            $arrays = $this->query_rules['taxonomy'];
        }

        if ( !isset( $arrays ) )
            return;

        foreach ( $arrays as $arg => $array ) {
            if ( $query->$fn( $arg ) ) {
                $this->set_query( $query, $array );
                return;
            }
        }

    }

}
new MmsfQuery();