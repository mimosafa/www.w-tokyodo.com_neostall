<?php
function edit_space_module() {
    global $post;
    if (
        is_singular( 'space' )
        && current_user_can( 'edit_page' )
        && isset( $_POST['nyt_edit_default_nonce'] )
        && wp_verify_nonce( $_POST['nyt_edit_default_nonce'], 'space_single' )
    ) {
        $post_id = $post->ID;

        // serial
        if ( isset( $_POST['serial'] ) && is_numeric( $_POST['serial'] ) ) {
            $serial = esc_html( $_POST['serial'] );
            if ( '' == get_post_meta( $post_id, 'serial' ) ) {
                add_post_meta( $post_id, 'serial', $serial );
            } elseif ( $serial != get_post_meta( $post_id, 'serial' ) ) {
                update_post_meta( $post_id, 'serial', $serial );
            } elseif ( '' == $serial ) {
                delete_post_meta( $post_id, 'serial' );
            }
        }

        // publication
        if ( isset( $_POST['publication'] ) ) {
            $pblc = esc_html( $_POST['publication'] );
            if ( '' == get_post_meta( $post_id, 'publication' ) ) {
                add_post_meta( $post_id, 'publication', $pblc );
            } elseif ( $pblc != get_post_meta( $post_id, 'publication' ) ) {
                update_post_meta( $post_id, 'publication', $pblc );
            } elseif ( '' == $pblc ) {
                delete_post_meta( $post_id, 'publication' );
            }
        }

        // sche_type
        if ( $_POST['sche_type'] ) {
            $stype = esc_html( $_POST['sche_type'] );
            if ( '' == get_post_meta( $post_id, 'sche_type' ) ) {
                add_post_meta( $post_id, 'sche_type', $stype );
            } elseif ( $stype != get_post_meta( $post_id, 'sche_type' ) ) {
                update_post_meta( $post_id, 'sche_type', $stype );
            } elseif ( '' == $stype ) {
                delete_post_meta( $post_id, 'sche_type' );
            }
        }

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

        header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
        die();

    }
}
add_action( 'template_redirect', 'edit_space_module' );