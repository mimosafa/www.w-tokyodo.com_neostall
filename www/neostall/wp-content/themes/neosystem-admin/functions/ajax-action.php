<?php

/**
 *
 */
function mmsf_json_get() {

    $result = array();

    if ( isset( $_POST['target'] ) && !empty( $_POST['target'] ) ) {

        switch ( $_POST['target'] ) {
            case 'posts' :
                $objs = get_posts( (array) $_POST['query'] );
                break;
            case 'theTerms' :
                $objs = wp_get_post_terms( absint( $_POST['postid'] ), $_POST['taxonomy'] );
                break;
        }

        if ( isset( $objs ) ) {

            $keys = array();
            if ( isset( $_POST['key'] ) && !empty( $_POST['key'] ) )
                $keys = (array) $_POST['key'];

            $res = (array) $_POST['response'];
            $c = count( $res );
            foreach ( $objs as $obj ) {
                $array = array();
                for ( $i = 0; $i < $c; $i++ ) {
                    $key = $keys[$i] ? $keys[$i] : $i;
                    $val = esc_js( $obj->$res[$i] );
                    $array[$key] = $val;
                }
                $result[] = $array;
            }

        }

    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_mmsf_json_get', 'mmsf_json_get' );
// add_action( 'wp_ajax_nopriv_mmsf_json_get', 'mmsf_json_get' );

/**
 *
 * 'action': 'mmsf_json_get_posts',
 *
 */
function mmsf_json_get_posts() {

    $result = array();

    if ( $posts = get_posts( (array) $_POST['query'] ) ) {

        $keys = array();
        if ( isset( $_POST['key'] ) && !empty( $_POST['key'] ) )
            $keys = (array) $_POST['key'];

        $res = (array) $_POST['response'];
        $c = count( $res );
        foreach ( $posts as $post ) {
            $array = array();
            for ( $i = 0; $i < $c; $i++ ) {
                $key = $keys[$i] ? $keys[$i] : $i;
                $val = $post->$res[$i];
                $array[$key] = $val;
            }
            $result[] = $array;
        }

    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_mmsf_json_get_posts', 'mmsf_json_get_posts' );
// add_action( 'wp_ajax_nopriv_mmsf_json_get_posts', 'mmsf_json_get_posts' );

/**
 *
 * 'action': 'mmsf_json_get_the_terms',
 *
 */
function mmsf_json_get_the_terms() {

    $result = array();

    if ( $terms = get_the_terms( absint( $_POST['postid'] ), $_POST['taxonomy'] ) ) {

        $keys = array();
        if ( isset( $_POST['key'] ) && !empty( $_POST['key'] ) )
            $keys = (array) $_POST['key'];

        $res = (array) $_POST['response'];
        $c = count( $res );
        foreach ( $terms as $term ) {
            $array = array();
            for ( $i = 0; $i < $c; $i++ ) {
                $key = $keys[$i] ? $keys[$i] : $i;
                $val = $term->$res[$i];
                $array[$key] = $val;
            }
            $result[] = $array;
        }

    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_mmsf_json_get_the_terms', 'mmsf_json_get_the_terms' );
// add_action( 'wp_ajax_nopriv_mmsf_json_get_the_terms', 'mmsf_json_get_the_terms' );

/**
 * VENDOR
 */

/**
 * KITCHENCAR
 */
function json_get_kitchencar_s_menu_set_data() {

    $result = array(
        'menu_items' => array(),
        'genres'     => array(),
        'texts'      => array(),
        'images1'    => array(),
        'images2'    => array(),
        'exists'     => array()
    );

    $id = $_POST['kitchencar_id'];
    $vendor = get_post( $id )->post_parent;

    if ( $v_content = get_post( $vendor )->post_content ) {
        $v_name = esc_attr( get_the_title( $vendor ) );
        $texts[$v_name] = esc_attr( $v_content );
    }

    $thumb1_id = get_post_thumbnail_id( $id );
    $thumb1_html = wp_get_attachment_image( $thumb1_id, 'thumbnail' );
    $result['images1'][$thumb1_id] = $thumb1_html;

    $arg = array(
        'post_type' => 'menu_item',
        'posts_per_page' => -1,
        'post_parent' => $vendor,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    $menu_item_posts = get_posts( $arg );

    $array = array();
    foreach ( $menu_item_posts as $menu_item_post ) {

        $item_id = $menu_item_post->ID;
        $item_name = get_the_title( $item_id );
        $result['menu_items'][] = array( 'id' => $item_id, 'text' => $item_name );

        if ( ! empty( $menu_item_post->post_content ) )
            $texts[esc_attr( $item_name )] = esc_attr( $menu_item_post->post_content );

        $genres = get_the_terms( $item_id, 'genre' );
        foreach ( $genres as $genre ) {
            $term_id = $genre->term_id;
            $term_name = $genre->name;
            $array[$term_id] = $term_name;
        }

        if ( has_post_thumbnail( $item_id ) ) {
            $thumb2_id = get_post_thumbnail_id( $item_id );
            $thumb2_html = wp_get_attachment_image( $thumb2_id, 'thumbnail' );
            $result['images2'][$thumb2_id] = $thumb2_html;
        }

    }

    if ( ! empty( $texts ) ) {
        foreach( $texts as $textID => $textContent ) {
            $result['texts'][] = array( 'id' => $textContent, 'text' => '<<' . $textID . '>> ' . $textContent );
        }
    }

    if ( ! empty( $array ) ) {
        foreach ( $array as $i => $str ) {
            $result['genres'][] = array( 'id' => $i, 'text' => $str );
        }
    }

    if ( ( 'add' != $_POST['editType'] ) && ( ! empty( $_POST['cfkey'] ) ) ) {

        $result['exists'] = get_post_meta( $id, $_POST['cfkey'], true );

    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_json_get_kitchencar_s_menu_set_data', 'json_get_kitchencar_s_menu_set_data' );
// add_action( 'wp_ajax_nopriv_json_get_kitchencar_s_menu_set_data', 'json_get_kitchencar_s_menu_set_data' );

/**
 * thumbnails data (json)
 *
 * 'action': 'json_get_thumbnails_data',
 * 'vendor_id'
 *
 */
function json_get_thumbnails_data() {

    $result = array();

    $args = array(
        'post_type' => 'attachment',
        'post_parent' => $_POST['vendor_id'],
        'post_mime_type' => 'image/jpeg',
        'posts_per_page' => -1,
        'post_status' => 'inherit'
    );
    $children = get_posts( $args );
    foreach ( $children as $child ) {
        $thumb_id = $child->ID;
        $src = wp_get_attachment_image( $child->ID, 'thumbnail' );
        $result[$thumb_id] = $src;
    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_json_get_thumbnails_data', 'json_get_thumbnails_data' );
// add_action( 'wp_ajax_nopriv_json_get_thumbnails_data', 'json_get_thumbnails_data' );

/**
 * term data (json)
 *
 * 'action': 'json_get_terms_data',
 * 'responce': 'keyval', 'val'
 * 'keyFormat': (string),
 * 'valFormat': (string),
 * 'keyProp': 'term_id', 'name', 'slug',
 * 'valProp': 'term_id', 'name', 'slug',
 * 'taxonomy': (string) taxonomy slug
 *
 */
function json_get_terms_data() {

    $result = array();

    // terms to get, result format...
    $responce = $_POST['responce'] ? $_POST['responce'] : 'keyval'; // keyval, val... default 'keyval'
    $taxonomy = $_POST['taxonomy'];
    // format
    $key_format = $_POST['keyFormat'] ? $_POST['keyFormat'] : 'id'; // default 'id'
    $val_format = $_POST['valFormat'] ? $_POST['valFormat'] : 'text'; // default 'text'
    // property
    $key_prop = $_POST['keyProp']; // term_id, name, slug
    $val_prop = $_POST['valProp']; // term_id, name, slug

    $arg = array(
        'hide_empty' => false
    );
    $terms = get_terms( $taxonomy, $arg );

    foreach ( $terms as $term ) {

        // val
        if ( 'term_id' == $val_prop ) {
            $val = esc_attr( $term->term_id );
        } elseif ( 'name' == $val_prop ) {
            $val = esc_attr( $term->name );
        } elseif ( 'slug' == $val_prop ) {
            $val = esc_attr( $term->slug );
        }

        if ( 'keyval' == $responce ) {

            // key
            if ( 'term_id' == $key_prop ) {
                $key = esc_attr( $term->term_id );
            } elseif ( 'name' == $key_prop ) {
                $key = esc_attr( $term->name );
            } elseif ( 'slug' == $key_prop ) {
                $key = esc_attr( $term->slug );
            }
            $result[] = array( $key_format => $key, $val_format => $val );

        } elseif ( 'val' == $responce ) {

            $key = esc_attr( $term->slug );
            $result[] = $val;

        }

    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_json_get_terms_data', 'json_get_terms_data' );
// add_action( 'wp_ajax_nopriv_json_get_terms_data', 'json_get_terms_data' );




function file_upload_from_dropzone() {

    if ( $file = $_FILES['file'] ) :

        if ( $file['error'] !== UPLOAD_ERR_OK )
            __return_false();

        $result = array();

        $post_id = $_POST['parent'];

        if ( $post_id ) {

            $mimetype = $file['type'];

            if ( $mimetype == 'image/jpeg' OR $mimetype == 'image/gif' OR $mimetype == 'image/png' ) {

                $upload_dir_var = wp_upload_dir();
                $upload_dir = $upload_dir_var['path'];

                $filename = basename( $file['name'] );
                $filename = trim( $filename );

                $extention = end( explode( '.', $filename ) );
                $title = substr( $filename, 0, ( ( strlen( $extention ) + 1 ) * -1 ) );

                if ( ! preg_match( '/^[a-zA-Z0-9]+$/', $filename ) )
                    $filename = sanitize_file_name( $filename );

                $slug = substr( $filename, 0, ( ( strlen( $extention ) + 1 ) * -1 ) );

                $upload_file = $upload_dir . '/' . $filename;

                if ( file_exists( $upload_file ) ) {
                    $n = 1;
                    while ( file_exists( $upload_file ) ) {
                        $upload_file = $upload_dir . '/' . $slug . '-' . $n . '.' . $extention;
                        $n++;
                    }
                }

                if ( move_uploaded_file( $file['tmp_name'], $upload_file ) ) {

                    $attachment = array(
                        'guid' => $upload_dir_var['url'] . '/' . $filename,
                        'post_mime_type' => $mimetype,
                        'post_title' => $title,
                        'post_name' => $slug,
                        'post_status' => 'inherit'
                    );
                    $attachment_id = wp_insert_attachment( $attachment, $upload_file, $post_id );

                    require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file );
                    wp_update_attachment_metadata( $attachment_id,  $attachment_data );

                }

            }

        }

        if ( isset( $attachment_id ) && ! empty( $attachment_id ) ) {

            $src = wp_get_attachment_image( $attachment_id, 'thumbnail' );

            $result[$attachment_id] = $src;

            header( 'Content-Type: application/json; charset=utf-8' );
            echo json_encode( $result );
            die();

        } else {

            __return_false();

        }

    endif;

}
add_action( 'wp_ajax_file_upload_from_dropzone', 'file_upload_from_dropzone' );
// add_action( 'wp_ajax_nopriv_file_upload_from_dropzone', 'file_upload_from_dropzone' );


function json_get_kitchencars_for_select2() {

    $result = array();

    $exclude = '';
    if ( $exists = $_POST['exists'] ) {
        $exclude = implode( ', ', $exists );
    }

    $objs = get_posts(
        array(
            'post_type' => 'kitchencar',
            'numberposts' => -1,
            'orderby' => 'meta_value_num',
            'meta_key' => 'serial',
            'order' => 'ASC',
            'exclude' => $exclude
        )
    );
    foreach ( $objs as $obj ) {
        $result[] = array( 'id' => $obj->ID, 'text' => get_the_title( $obj->ID ) );
    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_json_get_kitchencars_for_select2', 'json_get_kitchencars_for_select2' );
// add_action( 'wp_ajax_nopriv_json_get_kitchencars_for_select2', 'json_get_kitchencars_for_select2' );


function json_get_spaces_for_select2() {

    $result = array();

    $objs = get_posts(
        array(
            'post_type' => 'space',
            'numberposts' => -1,
            'orderby' => 'meta_value_num',
            'meta_key' => 'serial',
            'order' => 'ASC'
        )
    );
    foreach ( $objs as $obj ) {
        $result[] = array( 'id' => $obj->ID, 'text' => get_the_title( $obj->ID ) );
    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $result );
    die();

}
add_action( 'wp_ajax_json_get_spaces_for_select2', 'json_get_spaces_for_select2' );
// add_action( 'wp_ajax_nopriv_json_get_spaces_for_select2', 'json_get_spaces_for_select2' );