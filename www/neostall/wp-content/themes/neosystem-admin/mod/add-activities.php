<?php
function add_activity_module() {
    if (
        is_singular( 'space' )
        && current_user_can( 'edit_page' )
        && isset( $_POST['nyt_add_activity_nonce'] )
        && wp_verify_nonce( $_POST['nyt_add_activity_nonce'], 'space_single' )
    ) {
        if ( $_POST['addActivities'] ) {
            foreach ( $_POST['addActivities'] as $space_id => $days ) {
                foreach ( $days as $day => $kitchencars ) {
                    foreach ( $kitchencars as $kitchencar ) {
                        $ttl = date( 'Y/n/j D.', strtotime( $day ) ) . ' ' . get_the_title( $space_id ) . ' ' . get_the_title( $kitchencar );
                        $id = wp_insert_post(
                            array(
                                'post_status' => 'publish',
                                'post_type' => 'activity',
                                'post_author' => get_current_user_id(),
                                'post_name' => "{$space_id}-{$day}-{$kitchencar}",
                                'post_title' => $ttl
                            ), true
                        );
                        if ( ! is_wp_error( $id ) ) {
                            update_post_meta( $id, 'space_id', $space_id );
                            update_post_meta( $id, 'day', $day );
                            update_post_meta( $id, 'actOf', $kitchencar );
                            update_post_meta( $id, 'phase', 2 );
                        }
                    }
                }
            }
            header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] ); // '_wp_http_referer' ... set by wp_nonce_fields
            die();
        }
    }
}
add_action( 'template_redirect', 'add_activity_module' );