<?php
function edit_kitchencar_module() {
    global $post;
    if (
        is_singular( 'kitchencar' )
        && current_user_can( 'edit_page' )
        && isset( $_POST['nyt_edit_default_kitchencar_nonce'] )
        && wp_verify_nonce( $_POST['nyt_edit_default_kitchencar_nonce'], 'kitchencar_single' )
    ) {
        $post_id = $post->ID;

        // phase
        if ( isset( $_POST['phase'] ) && is_numeric( $_POST['phase'] ) ) {
            $phase = esc_html( $_POST['phase'] );
            if ( '' == get_post_meta( $post_id, 'phase' ) ) {
                add_post_meta( $post_id, 'phase', $phase );
            } elseif ( $phase != get_post_meta( $post_id, 'phase' ) ) {
                update_post_meta( $post_id, 'phase', $phase );
            } elseif ( '' == $phase ) {
                delete_post_meta( $post_id, 'phase' );
            }
        }

        // vin
        if ( isset( $_POST['vin'] ) ) {
            $vin = esc_html( $_POST['vin'] );
            if ( '' == get_post_meta( $post_id, 'vin' ) ) {
                add_post_meta( $post_id, 'vin', $vin );
            } elseif ( $vin != get_post_meta( $post_id, 'vin' ) ) {
                update_post_meta( $post_id, 'vin', $vin );
            } elseif ( '' == $vin ) {
                delete_post_meta( $post_id, 'vin' );
            }
        }

        // length
        if ( isset( $_POST['length'] ) && is_numeric( $_POST['length'] ) ) {
            $length = esc_html( $_POST['length'] );
            if ( '' == get_post_meta( $post_id, 'length' ) ) {
                add_post_meta( $post_id, 'length', $length );
            } elseif ( $length != get_post_meta( $post_id, 'length' ) ) {
                update_post_meta( $post_id, 'length', $length );
            } elseif ( '' == $length ) {
                delete_post_meta( $post_id, 'length' );
            }
        }

        // width
        if ( isset( $_POST['width'] ) && is_numeric( $_POST['width'] ) ) {
            $width = esc_html( $_POST['width'] );
            if ( '' == get_post_meta( $post_id, 'width' ) ) {
                add_post_meta( $post_id, 'width', $width );
            } elseif ( $width != get_post_meta( $post_id, 'width' ) ) {
                update_post_meta( $post_id, 'width', $width );
            } elseif ( '' == $width ) {
                delete_post_meta( $post_id, 'width' );
            }
        }

        // height
        if ( isset( $_POST['height'] ) && is_numeric( $_POST['height'] ) ) {
            $height = esc_html( $_POST['height'] );
            if ( '' == get_post_meta( $post_id, 'height' ) ) {
                add_post_meta( $post_id, 'height', $height );
            } elseif ( $height != get_post_meta( $post_id, 'height' ) ) {
                update_post_meta( $post_id, 'height', $height );
            } elseif ( '' == $height ) {
                delete_post_meta( $post_id, 'height' );
            }
        }

        header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] ); // '_wp_http_referer' ... set by wp_nonce_fields
        die();

    }
}
add_action( 'template_redirect', 'edit_kitchencar_module' );