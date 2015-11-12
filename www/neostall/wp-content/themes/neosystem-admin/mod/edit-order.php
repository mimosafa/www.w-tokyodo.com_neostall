<?php
function edit_items_order_module() {
    if (
        current_user_can( 'edit_page' )
        && isset( $_POST['nyt_edit_items_order_nonce'] )
        && wp_verify_nonce( $_POST['nyt_edit_items_order_nonce'], 'edit_order' )
        && isset( $_POST['order'] )
    ) {
        foreach ( $_POST['order'] as $id => $order ) {
            $chngOrdr = array();
            if ( get_post( $id ) && is_numeric( $order ) ) {
                $chngOrdr['ID'] = $id;
                $chngOrdr['menu_order'] = $order;
                $done = wp_update_post( $chngOrdr );
            }
        }
        header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] ); // '_wp_http_referer' ... set by wp_nonce_fields
        die();
    }
}
add_action( 'template_redirect', 'edit_items_order_module' );