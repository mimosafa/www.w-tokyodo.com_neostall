<?php

function mmsf_frontend_editing_module() {

    if ( ! isset( $_POST['_neosystem_admin_nonce'] ) )
        return;

    if ( ! current_user_can( 'publish_posts' ) ) //
        return;

    global $error_array;
    $error_array = array();

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * single-vendor.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_singular( 'vendor' ) ) :

        // single menu item
        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_vendor_s_menu_items' ) ) {

            global $post;

            if ( ! isset( $_POST['title'] ) || empty( $_POST['title'] ) )
                $error_array[] = "'NAME' is Required !";

            if ( ! isset( $_POST['genre'] ) || empty( $_POST['genre'] ) )
                $error_array[] = "'GENRE' is Required !";

            if ( ( 'modify' == $_POST['editType'] ) && ( ! isset( $_POST['postid'] ) || empty( $_POST['postid'] ) ) )
                $error_array[] = 'Invalid operation !';

            if ( empty( $error_array ) ) {

                $exists = get_children(
                    array(
                        'post_type' => 'menu_item',
                        'post_parent' => $post->ID,
                        'numberposts' => -1,
                        'post_status' => 'publish',
                        'order' => 'ASC',
                        'orderby' => 'menu_order'
                    )
                );
                $count = count( $exists );

                if ( 'modify' == $_POST['editType'] ) {

                    $item = array();

                    if ( $_POST['title'] != get_the_title( $_POST['postid'] ) )
                        $item['post_title'] = $_POST['title'];

                    if ( $_POST['content'] != get_the_content( $_POST['postid'] ) )
                        $item['post_content'] = $_POST['content'];

                    $now_order = get_post( $_POST['postid'] )->menu_order;
                    if ( $_POST['order'] != $now_order ) {

                        $item['menu_order'] = $_POST['order'];

                        if ( $now_order < $item['menu_order'] ) {
                            $i = $now_order;
                            $j = $item['menu_order'];
                            $calc = -1;
                        } else {
                            $i = $item['menu_order'];
                            $j = $now_order;
                            $calc = 1;
                        }

                    }

                    if ( ! empty( $item ) ) {

                        $item['ID'] = $_POST['postid'];

                        $item_id = wp_update_post( $item, true );

                    } else {

                        $item_id = $_POST['postid'];

                    }

                    if ( ! is_wp_error( $item_id ) && isset( $item['menu_order'] ) ) {

                        foreach ( $exists as $existing ) {

                            $mod_item = array();

                            $order = $existing->menu_order;

                            if ( $existing->ID == $_POST['postid'] )
                                continue;
                            if ( $order < $i )
                                continue;
                            if ( $order > $j )
                                continue;

                            $mod_item['ID'] = $existing->ID;
                            $mod_item['menu_order'] = $order + $calc;

                            wp_update_post( $mod_item );

                        }

                    }

                } elseif ( 'add' == $_POST['editType'] ) {

                    $item_id = wp_insert_post(
                        array(
                            'post_status' => 'publish',
                            'post_type' => 'menu_item',
                            'post_author' => get_current_user_id(),
                            'post_title' => $_POST['title'],
                            'post_content' => $_POST['content'],
                            'post_parent' => $post->ID,
                            'menu_order' => $_POST['order']
                        ), true
                    );

                    if ( ! is_wp_error( $item_id ) ) {

                        if ( $count != $_POST['order'] ) {

                            foreach ( $exists as $existing ) {
                                $mod_item = array();
                                $order = $existing->menu_order;
                                if ( $order < $_POST['order'] ) {
                                    continue;
                                } else {
                                    $mod_item['ID'] = $existing->ID;
                                    $mod_item['menu_order'] = $order + 1;
                                    wp_update_post( $mod_item );
                                }
                            }

                        }

                        update_post_meta( $post->ID, '_num_menuitems', $count + 1 );

                    }

                }

                if ( ! is_wp_error( $item_id ) ) {

                    if ( isset( $_POST['thumb'] ) && ( $_POST['thumb'] != get_post_thumbnail_id( $item_id ) ) ) {

                        update_post_meta( $item_id, '_thumbnail_id', $_POST['thumb'] );

                    }

                    $genre = explode( ',', $_POST['genre'] );
                    wp_set_object_terms( $item_id, $genre, 'genre', false );

                    $existsNow = get_children(
                        array(
                            'post_type' => 'menu_item',
                            'post_parent' => $post->ID,
                            'numberposts' => -1,
                            'post_status' => 'publish',
                            'order' => 'ASC',
                            'orderby' => 'menu_order'
                        )
                    );
                    $vendorGenre = array();
                    foreach ( $existsNow as $anItem ) {
                        $vendorGenre = array_merge( $vendorGenre, wp_get_post_terms( $anItem->ID, 'genre', array( 'fields' => 'slugs' ) ) );
                    }
                    $vendorGenre = array_unique( $vendorGenre );

                    wp_set_object_terms( $post->ID, $vendorGenre, 'genre', false );

                    header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                    die();

                } else {

                    $error_array[] = 'Error occured. ' . $item_id->get_error_message();

                }

            }

        }

        // add 'kitchencar, on vendor
        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_vendor_s_kitchencars' ) ) {

            global $post;

            if ( ! isset( $_POST['title'] ) || empty( $_POST['title'] ) )
                $error_array[] = "Kitchencar's NAME(Japanese) is Required !";

            if ( ! isset( $_POST['slug'] ) || empty( $_POST['slug'] ) )
                $error_array[] = "Kitchencar's NAME(ID) is Required !";

            if ( empty( $error_array ) ) {

                $parent = $post->ID;
                $serial = (int) $post->serial;

                $exists = get_children(
                    array(
                        'post_type' => 'kitchencar',
                        'post_parent' => $parent,
                        'numberposts' => -1,
                        'post_status' => 'publish',
                        'order' => 'ASC',
                        'orderby' => 'menu_order'
                    )
                );
                $count = count( $exists );

                $id = wp_insert_post(
                    array(
                        'post_status' => 'publish',
                        'post_type' => 'kitchencar',
                        'post_author' => get_current_user_id(),
                        'post_title' => $_POST['title'],
                        'post_name' => $_POST['slug'],
                        'post_parent' => $parent,
                        'menu_order' => $_POST['order']
                    ), true
                );

                if ( ! is_wp_error( $id ) ) {

                    // serial
                    update_post_meta( $id, 'serial', $serial );

                    // phase
                    if ( isset( $_POST['phase'] ) && is_numeric( $_POST['phase'] ) ) {

                        update_post_meta( $id, 'phase', $_POST['phase'] );

                    }

                    // vin
                    if ( isset( $_POST['vin'] ) ) {

                        update_post_meta( $id, 'vin', $_POST['vin'] );

                    }

                    // length
                    if ( isset( $_POST['len'] ) && is_numeric( $_POST['len'] ) ) {

                        update_post_meta( $id, 'length', $_POST['len'] );

                    }

                    // width
                    if ( isset( $_POST['wid'] ) && is_numeric( $_POST['wid'] ) ) {

                        update_post_meta( $id, 'width', $_POST['wid'] );

                    }

                    // height
                    if ( isset( $_POST['hei'] ) && is_numeric( $_POST['hei'] ) ) {

                        update_post_meta( $id, 'height', $_POST['hei'] );

                    }

                    if ( isset( $_POST['thumb'] ) && is_numeric( $_POST['thumb'] ) ) {

                        update_post_meta( $id, '_thumbnail_id', $_POST['thumb'] );

                        wp_update_post(
                            array(
                                'ID' => $_POST['thumb'],
                                'post_parent' => $id
                            )
                        );

                    }

                    if ( $count != $_POST['order'] ) {

                        foreach ( $exists as $existing ) {
                            $mod_item = array();
                            $order = $existing->menu_order;
                            if ( $order < $_POST['order'] ) {
                                continue;
                            } else {
                                $mod_item['ID'] = $existing->ID;
                                $mod_item['menu_order'] = $order + 1;
                                wp_update_post( $mod_item );
                            }
                        }

                    }

                    update_post_meta( $parent, '_num_kitchencars', $count + 1 );

                    header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                    die();

                } else {

                    $error_array[] = 'Error occured. ' . $id->get_error_message();

                }

            }

        }

        // Vendor's Default
        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'vendor_default' ) ) {

            global $post;

            if ( !isset( $_POST['post-title'] ) || empty( $_POST['post-title'] ) )
                $error_array[] = '「事業者名」は必須です。';

            if ( empty( $error_array ) ) {

                wp_update_post( array( 'ID' => $post->ID, 'post_title' => esc_html( $_POST['post-title'] ) ) );

                if ( isset( $_POST['organization'] ) && !empty( $_POST['organization'] ) ) {
                    update_post_meta( $post->ID, 'organization', esc_html( $_POST['organization']) );
                }

                header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                die();

            }

        }

        //

    endif; // if ( is_singular( 'vendor' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * single-kitchencar.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_singular( 'kitchencar') ) :

        // menu set (custom field array)
        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_kitchencar_s_menu_set' ) ) {

            if ( 'remove' != $_POST['editType'] ) {

                global $post;

                if ( ! isset( $_POST['cat1'] ) || empty( $_POST['cat1'] ) )
                    $error_array[] = "'CATEGORY 1' is Required !";

                if ( ! isset( $_POST['items'] ) || empty( $_POST['items'] ) )
                    $error_array[] = "'ITEMS' is Required !";

                if ( ! isset( $_POST['genres'] ) || empty( $_POST['genres'] ) )
                    $error_array[] = "'GENRE' is Required !";

                if ( ! isset( $_POST['text'] ) || empty( $_POST['text'] ) )
                    $error_array[] = "'TEXT' is Required !";
/* 車両イメージ、メニューイメージがない場合あり...
                if ( ! isset( $_POST['img1'] ) || empty( $_POST['img1'] ) )
                    $error_array[] = "'Kitchencar Image' is Required !";

                if ( ! isset( $_POST['img2'] ) || empty( $_POST['img2'] ) )
                    $error_array[] = "'Menu Item Image' is Required !";
*/
                if ( empty( $error_array ) ) {

                    $post_id = $post->ID;

                    if ( 'modify' == $_POST['editType'] ) {

                        $cfkey = $_POST['cfkey'];

                    } elseif ( 'add' == $_POST['editType'] ) {

                        $keys = get_post_custom_keys( $post_id );

                        if ( 'default' == $_POST['cat1'] ) {

                            $n = 0;
                            foreach ( $keys as $key ) {
                                if ( preg_match( '/^default_/', $key ) )
                                    $n++;
                            }
                            $cfkey = 'default_' . $n;

                        } elseif ( 'weekly' == $_POST['cat1'] ) {

                            if ( empty( $_POST['cat2'] ) ) {

                                $n = 0;
                                foreach ( $keys as $key ) {
                                    if ( preg_match( '/^weekly_\d+$/', $key ) )
                                        $n++;
                                }
                                $cfkey = 'weekly_' . $n;

                            } else {

                                $array = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );

                                if ( in_array( $_POST['cat2'], $array ) ) {

                                    $cfkey = 'weekly_' . $_POST['cat2'];

                                }

                            }

                        } elseif ( 'event' == $_POST['cat1'] ) {

                            if ( empty( $_POST['cat2'] ) ) {

                                $n = 0;
                                foreach ( $keys as $key ) {
                                    if ( preg_match( '/^event_\d+$/', $key ) )
                                        $n++;
                                }
                                $cfkey = 'event_' . $n;

                            } else {

                                $array = array();

                                $terms = get_terms( 'series', 'hide_empty=false' );
                                foreach ( $terms as $term ) {
                                    $array[] = $term->slug;
                                }

                                if ( in_array( $_POST['cat2'], $array ) ) {

                                    $cfkey = 'event_' . $_POST['cat2'];

                                }

                            }

                        }

                    }

                    if ( isset( $cfkey ) ) {

                        $cfval = array();

                        $cfval['item']  = explode( ',', $_POST['items'] );
                        $cfval['genre'] = explode( ',', $_POST['genres'] );
                        $cfval['text']  = esc_html( $_POST['text'] );
                        $cfval['img1']  = esc_html( $_POST['img1'] );
                        $cfval['img2']  = esc_html( $_POST['img2'] );

                        update_post_meta( $post_id, $cfkey, $cfval );

                        header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                        die();

                    } else {

                        $error_array[] = 'Invalid Operation.';

                    }

                }

            } else {

                if ( ! isset( $_POST['cfkey'] ) || empty( $_POST['cfkey'] ) )
                    $error_array[] = 'Invalid operation. ( Cannot define delete Menu Set. )';

                if ( ! isset( $_POST['kitchencar_id'] ) || empty( $_POST['kitchencar_id'] ) )
                    $error_array[] = 'Invalid operation. ( Cannot define Kitchencar. )';

                if ( empty( $error_array ) ) {

                    $post_id = $_POST['kitchencar_id'];
                    $cfkey = explode( '_', $_POST['cfkey'] );

                    delete_post_meta( $post_id, $_POST['cfkey'] );

                    if ( is_numeric( $cfkey[1] ) ) {

                        $existsKeys = get_post_custom_keys( $post_id );
                        $pattern = '/^' . $cfkey[0] . '_(\d+$)/';
                        $modifyCF = array();
                        $removeCF = array();

                        foreach ( $existsKeys as $oldKey ) {

                            if ( preg_match( $pattern, $oldKey, $match ) ) {

                                $n = (int) $match[1];

                                if ( $n > $cfkey[1] ) {

                                    $value = get_post_meta( $post_id, $oldKey, true );
                                    $newKey = $cfkey[0] . '_' . ( $n - 1 );

                                    $modifyCF[$newKey] = $value;
                                    $removeCF[] = $oldKey;

                                }

                            }

                        }

                        if ( ! empty( $removeCF ) && ! empty( $modifyCF ) ) {

                            foreach ( $removeCF as $remove ) {
                                delete_post_meta( $post_id, $remove );
                            }

                            foreach ( $modifyCF as $newKey => $value ) {
                                update_post_meta( $post_id, $newKey, $value );
                            }

                        }

                    }

                    header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                    die();

                }

            }

        }

        //

    endif; // if ( is_singular( 'kitchencar') )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * single-space.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_singular( 'space' ) ) :

        // edit kitchencars list - defined by 'Advanced Custom Fields plugin'
        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_acf_list_kitchencars' ) ) {

            if ( ! isset( $_POST['acf'] ) || empty( $_POST['acf'] ) )
                $error_array[] = 'Fail.';

            if ( empty( $error_array ) ) {

                global $post;
                $post_id = $post->ID;

                foreach ( $_POST['acf'] as $key => $string ) {
                    $val = explode( ',', $string );
                    break;
                }

                update_post_meta( $post_id, $key, $val );

                header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                die();

            }

        }

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'space_add_activities_from_calendar' ) ) {

            if ( isset( $_POST['addActivities'] ) && !empty( $_POST['addActivities'] ) ) {

                global $post;
                $space_id = $post->ID;
                $ttl_space = get_the_title( $space_id );

                foreach ( $_POST['addActivities'] as $day => $kitchencars ) {

                    $ttl_day = date( 'Y/n/j D.', strtotime( $day ) );
                    $slg_day = date( 'Y-m-d', strtotime( $day ) );

                    foreach ( $kitchencars as $kitchencar ) {

                        $ttl_kitchencar = get_the_title( $kitchencar );

                        $ttl = sprintf( '%s %s %s', $ttl_day, $ttl_space, $ttl_kitchencar );
                        $slg = sprintf( '%s-%d-%d', $slg_day, $space_id, $kitchencar );

                        $id = wp_insert_post(
                            array(
                                'post_status' => 'publish',
                                'post_type' => 'activity',
                                'post_author' => get_current_user_id(),
                                'post_name' => $slg,
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

            if ( isset( $_POST['remove-activity'] ) && !empty( $_POST['remove-activity'] ) ) {

                $remove_array = explode( ',' , $_POST['remove-activity'] );
                foreach ( $remove_array as $remove ) {

                    if ( 'activity' == get_post( $remove )->post_type ) {

                        wp_delete_post( $remove, true );

                    }

                }

            }

            header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] ); // '_wp_http_referer' ... set by wp_nonce_fields
            die();

        }

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'space_management' ) ) {

            global $post;

            if ( isset( $_POST['active_weeks'] ) && !empty( $_POST['active_weeks'] ) ) {

                $active_weeks = (array) $_POST['active_weeks'];
                $exists_keys = get_post_custom_keys( $post->ID );
                $weeks = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
                $del = array();
                foreach ( $weeks as $week ) {
                    if ( in_array( $week, $active_weeks ) ) {
                        add_post_meta( $post->ID, $week, array(), true ); // true ... すでに存在していれば追加されない
                    } elseif ( in_array( $week, $exists_keys ) ) {
                        $del[] = $week;
                    }
                }
                if ( !empty( $del ) ) {
                    foreach ( $del as $week ) {
                        $kitchencars = (array) get_post_meta( $post->ID, $week, true );
                        if ( !empty( $kitchencars ) ) {
                            foreach ( $kitchencars as $kitchencar_id ) {
                                delete_post_meta( $kitchencar_id, $week, "$post->ID" );
                            }
                        }
                        delete_post_meta( $post->ID, $week );
                    }
                }
                header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                die();

            }

            if ( isset( $_POST['kitchencars'] ) && !empty( $_POST['kitchencars'] ) ) {

                $array = array();
                foreach ( $_POST['kitchencars'] as $week => $arg ) {
                    if ( !is_array( $arg ) )
                        $array[$week] = explode( ',', $arg );
                    else
                        $array[$week] = $arg;
                }
                $weeks = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );

                $space_id = array();
                $space_id[] = $post->ID;
                // if Rotation
                if ( $pair_id = absint( $post->rotation_pair ) ) {
                    if ( 'space' == get_post_type( $pair_id ) )
                        $space_id[] = $pair_id;
                }

                // foreach ... for Rotation
                foreach ( $space_id as $_sid ) {
                    $del = array();
                    foreach ( $array as $week => $kitchencars ) {
                        $exists = array();
                        if ( in_array( $week, $weeks ) ) {
                            foreach ( $kitchencars as $key => $kitchencar_id ) {
                                if ( 'kitchencar' != get_post_type( $kitchencar_id ) ) {
                                    $error_array[] = 'ERROR: ' . '"ID: ' . $kitchencar_id . '" はキッチンカーではありません';
                                    unset( $kitchencars[$key] );
                                } else {
                                    $space_id = get_post_meta( $kitchencar_id, $week );
                                    if ( !in_array( $_sid, $space_id ) )
                                        add_post_meta( $kitchencar_id, $week, $_sid );
                                }
                            }
                            $exists = (array) get_post_meta( $_sid, $week, true );
                            foreach ( $exists as $_id ) {
                                if ( !in_array( $_id, $kitchencars ) )
                                    $del[$week][] = $_id;
                            }
                            update_post_meta( $_sid, $week, (array) $kitchencars );
                        } else {
                            $error_array[] = 'ERROR: ' . '"kye: ' . $week . '" は不正な処理です';
                        }
                    }
                    if ( !empty( $del ) ) {
                        foreach ( $del as $week => $kitchencars ) {
                            foreach ( $kitchencars as $_id ) {
                                delete_post_meta( $_id, $week, "$_sid" );
                            }
                        }
                    }
                }
                header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                die();

            }

        }

    endif; // if ( is_singular( 'space' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * taxonomy-series.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_tax( 'series' ) ) :

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'series_location_data_management' ) ) {

            global $term;

            if ( isset( $_POST['locationData'] ) && !empty( $_POST['locationData'] ) ) {

                $series_meta = get_option( "series_{$term}" );
                foreach ( $_POST['locationData'] as $key => $val ) {
                    $series_meta['locationData'][$key] = $val;
                }
                update_option( "series_{$term}", $series_meta );

                header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                die();

            }

        }

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'series_options' ) ) {

            global $term;

            if ( isset( $_POST['options'] ) && !empty( $_POST['options'] ) ) {

                $series_meta = get_option( "series_{$term}" );
                foreach ( $_POST['options'] as $key => $val ) {
                    if ( is_numeric( $val ) )
                        $val = intval( $val );
                    $series_meta[$key] = $val;
                }
                update_option( "series_{$term}", $series_meta );

                header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                die();

            }

        }

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'add_new_event' ) ) {

            global $term;

            if ( ! isset( $_POST['post_title'] ) || empty( $_POST['post_title'] ) )
                $error_array[] = "イベント名が入力されていません";

            if ( ! isset( $_POST['event-day'] ) || empty( $_POST['event-day'] ) ) {
                $error_array[] = "イベント開催日が入力されていません";
            } else {
                $day = str_replace( '-', '', $_POST['event-day'] );
                if ( !checkdate_Ymd( $day ) )
                    $error_array[] = "イベント開催日の書式が不正です";
            }

            if ( empty( $error_array ) ) {

                $event_id = wp_insert_post(
                    array(
                        'post_status' => 'publish',
                        'post_type' => 'event',
                        'post_author' => get_current_user_id(),
                        'post_title' => $_POST['post_title'],
                        'post_name' => $term . $day
                    ), true
                );

                if ( ! is_wp_error( $event_id ) ) {

                    update_post_meta( $event_id, 'day', $day );

                    if ( ( '1' == $_POST['multiarea'] ) && ( 1 < count( $_POST['area'] ) ) )
                        update_post_meta( $event_id, 'multiarea', $_POST['multiarea'] );

                    $starting = array();
                    $area_array = array();
                    foreach ( $_POST['area'] as $key => $row ) {
                        $row['starting'] = str_replace( 'T', ' ', $row['starting'] ) . ':00';
                        $row['ending']   = str_replace( 'T', ' ', $row['ending'] ) . ':00';
                        $starting[$key] = $row['starting'];
                        $area_array[$key] = $row;
                    }
                    array_multisort( $starting, SORT_ASC, $area_array );
                    update_post_meta( $event_id, 'eventsData', $area_array );

                    $region = absint( array_shift( $area_array )['region'] );

                    wp_set_object_terms( $event_id, $region, 'region' );
                    wp_set_object_terms( $event_id, $term, 'series' );

                    header( 'Location: ' . get_permalink( $event_id ) );
                    die();

                } else {

                    $error_array[] = 'Something Wrong !';

                }

            }

        }

    endif; // if ( is_tax( 'series' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * single-event.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_singular( 'event' ) ) :

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'event_add_activities' ) ) {

            global $post;

            if ( isset( $_POST['eventsData'] ) && !empty( $_POST['eventsData'] ) ) {

                $eventsData = $post->eventsData;
                if ( $post->multiarea ) {
                    foreach ( $_POST['eventsData'] as $key => $val ) {
                        $area = $eventsData[$key]['areaDetail'];
                        break;
                    }
                }

                $eventName = '';
                if ( $series = get_series_tax_obj( $post ) )
                    $eventName .= esc_html( $series->name ) . ' ';
                $eventName .= esc_html( get_the_title( $post ) );
                if ( isset( $area ) )
                    $eventName .= sprintf( ' [%s]', esc_html( $area ) );
                $ttlpre = sprintf( '%s %s', date( 'Y/n/j D.', strtotime( $post->day ) ), $eventName );
                $slgpre = sprintf( '%s-%d', date( 'Y-m-d', strtotime( $post->day ) ), $post->ID );

                foreach ( $_POST['eventsData'] as $key => $data ) {

                    $kitchencars = explode( ',', $data['activities'] );

                    foreach ( $kitchencars as $n => $kitchencar_id ) {

                        if ( $kitchencar = get_post( $kitchencar_id ) and 'kitchencar' == $kitchencar->post_type ) {

                            $activity_id = wp_insert_post(
                                array(
                                    'menu_order' => $n,
                                    'post_author' => get_current_user_id(),
                                    'post_name' => sprintf( '%s-%d', $slgpre, $kitchencar_id ),
                                    'post_status' => 'publish',
                                    'post_title' => sprintf( '%s %s', $ttlpre, $kitchencar->post_title ),
                                    'post_type' => 'activity'
                                ), true
                            );

                            if ( !is_wp_error( $activity_id ) ) {

                                update_post_meta( $activity_id, 'actOf', $kitchencar_id );
                                update_post_meta( $activity_id, 'day', $post->day );
                                update_post_meta( $activity_id, 'event_id', $post->ID );
                                update_post_meta( $activity_id, 'phase', 2 );

                                $activities[] = $activity_id;

                            }

                        }

                    }

                    $eventsData[$key]['activities'] = $activities;

                }

                update_post_meta( $post->ID, 'eventsData', $eventsData );

                header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                die();

            }

        }

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_event_options' ) ) {

            global $post;

            if ( isset( $_POST['publication'] ) && !empty( $_POST['publication'] ) ) {

                if ( 'open' == $_POST['publication'] ) {

                    $publication = date( 'Y-m-d H:i:s' );
                    update_post_meta( $post->ID, 'publication', $publication );

                } elseif ( 'close' == $_POST['publication'] ) {

                    delete_post_meta( $post->ID, 'publication' );

                }

            }

            header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
            die();

        }

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_eventdata' ) ) {

            global $post;

            if ( isset( $_POST['eventsData'] ) && !empty( $_POST['eventsData'] ) ) {

                $eventsData = $post->eventsData;
                foreach ( $_POST['eventsData'] as $n => $array ) {
                    foreach ( $array as $key => $val ) {
                        if ( ( 'starting' || 'ending' ) == $key )
                            $val = date( 'Y-m-d G:i', strtotime( $val ) );
                        if ( $val != $eventsData[$n][$key] ) {
                            $eventsData[$n][$key] = $val;
                        }
                    }
                    if ( 1 !== absint( $array['startingPending'] ) )
                        unset( $eventsData[$n]['startingPending'] );
                    if ( 1 !== absint( $array['endingPending'] ) )
                        unset( $eventsData[$n]['endingPending'] );
                }

                update_post_meta( $post->ID, 'eventsData', $eventsData );

            }

            header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
            die();

        }

    endif; // if ( is_singular( 'event' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * archive-vendor.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_post_type_archive( 'vendor' ) ) :

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'add_vendor' ) ) {

            if ( !isset( $_POST['serial'] ) || empty( $_POST['serial'] ) ) {
                $error_array[] = 'ネオ屋台村IDが入力されていません';
            } else {
                if ( !$serial = absint( $_POST['serial'] ) ) {
                    $error_array[] = 'ネオ屋台村IDに不正な値が入力されています';
                }
                // 登録済みの serial でないかの検証も
            }

            if ( !isset( $_POST['vendor-name'] ) || empty( $_POST['vendor-name'] ) )
                $error_array[] = '屋号の入力は必須です';

            if ( !isset( $_POST['vendor-slug'] ) || empty( $_POST['vendor-slug'] ) )
                $error_array[] = 'アカウントの入力は必須です';
                // 半角英数字の正規表現確認も

            if ( empty( $error_array ) ) {

                $vendor_id = wp_insert_post(
                    array(
                        'post_status' => 'publish',
                        'post_type' => 'vendor',
                        'post_author' => get_current_user_id(),
                        'post_title' => $_POST['vendor-name'],
                        'post_name' => $_POST['vendor-slug']
                    ), true
                );

                if ( !is_wp_error( $vendor_id ) ) {

                    update_post_meta( $vendor_id, 'serial', $serial );

                    header( 'Location: ' . get_permalink( $vendor_id ) );
                    die();

                } else {

                    $error_array[] = '事業者の追加を正常に完了することができませんでした';

                }

            }

        }

    endif; // if ( is_post_type_archive( 'vendor' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * single-activity.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_singular( 'activity' ) ) :

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_activity' ) ) {

            global $post;
            $id = $post->ID;

            if ( isset( $_POST['phase'] ) ) {

                if ( 'remove' == $_POST['phase'] ) {

                    if ( !$place_id = $post->space_id ) {

                        $place_id = $post->event_id;
                        $eventsData = get_post_meta( $place_id, 'eventsData', true );
                        foreach ( $eventsData as $key0 => $data ) {

                            if ( !$activities = $data['activities'] )
                                continue;

                            foreach ( $activities as $key1 => $activity_id ) {
                                if ( $id == $activity_id ) {
                                    unset( $eventsData[$key0]['activities'][$key1] );
                                    $eventsData[$key0]['activities'] = array_values( $eventsData[$key0]['activities'] );
                                }
                            }

                        }

                        update_post_meta( $place_id, 'eventsData', $eventsData );

                    }

                    if ( wp_delete_post( $id, true ) ) {

                        header( 'Location: ' . get_permalink( $place_id ) );
                        die();

                    }

                } else {

                    update_post_meta( $id, 'phase', $_POST['phase'] );

                    header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
                    die();

                }

            }

        }

    endif; // if ( is_singular( 'activity' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * single-management.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_singular( 'management' ) ) :

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'edit_management' ) ) {

            global $post;

            // .....

            if ( isset( $_POST['target-space'] ) && !empty( $_POST['target-space'] ) ) {

                $space_arr = explode( ',', $_POST['target-space'] );
                $exists_spaces = (array) get_post_meta( $post->ID, 'space_id' ); // array

                // update_post_meta( $post->ID, 'target_posts', $space_arr ); // delete

                $remove_space = array_diff( $exists_spaces, $space_arr );
                $add_space = array_diff( $space_arr, $exists_spaces );

                foreach ( $remove_space as $r_space ) {
                    delete_post_meta( $post->ID, 'space_id', $r_space );
                }
                foreach ( $add_space as $a_space ) {
                    if ( 'space' == get_post_type( $a_space ) )
                        add_post_meta( $post->ID, 'space_id', $a_space );
                }

            }

            header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_POST['_wp_http_referer'] );
            die();

        }

    endif; // if ( is_singular( 'management' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * archive-event.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_post_type_archive( 'event' ) ) :

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'add_new_series' ) ) {

            if ( !isset( $_POST['series_name'] ) || empty( $_POST['series_name'] ) ) {
                $error_array[] = 'シリーズ名が入力されていません';
            } else {
                if ( term_exists( $_POST['series_name'], 'series' ) ) {
                    $error_array[] = esc_html( $_POST['series_name'] ) . ' というシリーズ名はすでに存在しています';
                }
                $series = trim( $_POST['series_name'] );
                $series = str_replace( '　', ' ', $series ); // 全角スペースを半角スペースに
            }

            if ( !isset( $_POST['series_slug'] ) || empty( $_POST['series_slug'] ) ) {
                $error_array[] = 'シリーズIDが入力されていません';
            } else {
                if ( term_exists( $_POST['series_slug'], 'series' ) ) {
                    $error_array[] = esc_html( $_POST['series_slug'] ) . ' というシリーズIDはすでに存在しています';
                }
                $slug = trim( $_POST['series_slug'] );
            }

            if ( empty( $error_array ) ) {

                $array = wp_insert_term( $series, 'series', array( 'slug' => $slug ) );

                if ( !is_wp_error( $array ) ) {

                    $options['front_end_archive'] = ( isset( $_POST['front_end_archive'] ) && !empty( $_POST['front_end_archive'] ) ) ? 1 : 0;
                    update_option( 'series_' . $slug, $options );

                    header( 'Location: ' . get_term_link( $array['term_id'], 'series' ) );
                    die();

                } else {

                    $error_array[] = 'エラーが発生しました ' . $array->get_error_message();

                }

            }

        }

    endif; // if ( is_post_type_archive( 'event' ) )

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * * * archive-space.php
    * * * *
     * * *
    * * * *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if ( is_post_type_archive( 'space' ) ) :

        if ( wp_verify_nonce( $_POST['_neosystem_admin_nonce'], 'add_new_space' ) ) {

            if ( !isset( $_POST['space-name'] ) || empty( $_POST['space-name'] ) )
                $error_array[] = 'スペース名は入力必須です';

            if ( !isset( $_POST['space-slug'] ) || empty( $_POST['space-slug'] ) )
                $error_array[] = 'スペースIDは入力必須です';

            if ( !isset( $_POST['serial'] ) || empty( $_POST['serial'] ) )
                $error_array[] = '管理番号は入力必須です';

            if ( !isset( $_POST['space-region'] ) || empty( $_POST['space-region'] ) )
                $error_array[] = '所在地（市区郡）が選択されていません';

            if ( !isset( $_POST['address'] ) || empty( $_POST['address'] ) )
                $error_array[] = '所在地（補記住所）は入力必須です';

            if ( empty( $error_array ) ) {

                $space_id = wp_insert_post(
                    array(
                        'post_status' => 'publish',
                        'post_type' => 'space',
                        'post_author' => get_current_user_id(),
                        'post_title' => $_POST['space-name'],
                        'post_name' => $_POST['space-slug']
                    ), true
                );

                if ( !is_wp_error( $space_id ) ) {

                    $region_id = absint( $_POST['space-region'] );
                    wp_set_object_terms( $space_id, $region_id, 'region' );

                    if ( isset( $_POST['dayname'] ) && !empty( $_POST['dayname'] ) ) {

                        $daynames = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
                        $n = 0;
                        $acf_list = array();
                        foreach ( $daynames as $dayname ) {
                            if ( in_array( $dayname, $_POST['dayname'] ) ) {
                                add_post_meta( $space_id, 'list_' . $n . '_dayname', $dayname );
                                add_post_meta( $space_id, '_list_' . $n . '_dayname', 'field_42' ); // for ACF plugin
                                $n++;
                            }
                        }
                        add_post_meta( $space_id, 'list', $n );
                        add_post_meta( $space_id, '_list', 'field_41' ); // for ACF plugin

                    }

                    add_post_meta( $space_id, 'serial', $_POST['serial'] );
                    add_post_meta( $space_id, 'phase', $_POST['phase'] );
                    add_post_meta( $space_id, 'sche_type', $_POST['sche_type'] );
                    add_post_meta( $space_id, 'address', $_POST['address'] );
                    if ( isset( $_POST['site'] ) && !empty( $_POST['site'] ) )
                        add_post_meta( $space_id, 'site', $_POST['site'] );
                    if ( isset( $_POST['areaDetail'] ) && !empty( $_POST['areaDetail'] ) )
                        add_post_meta( $space_id, 'areaDetail', $_POST['areaDetail'] );
                    if ( isset( $_POST['latlng'] ) && !empty( $_POST['latlng'] ) )
                        add_post_meta( $space_id, 'latlng', $_POST['latlng'] );
                    if ( isset( $_POST['starting'] ) && !empty( $_POST['starting'] ) )
                        add_post_meta( $space_id, 'starting', $_POST['starting'] );
                    if ( isset( $_POST['startingPending'] ) && !empty( $_POST['startingPending'] ) )
                        add_post_meta( $space_id, 'startingPending', '1' );
                    if ( isset( $_POST['ending'] ) && !empty( $_POST['ending'] ) )
                        add_post_meta( $space_id, 'ending', $_POST['ending'] );
                    if ( isset( $_POST['endingPending'] ) && !empty( $_POST['endingPending'] ) )
                        add_post_meta( $space_id, 'endingPending', '1' );

                    header( 'Location: ' . get_permalink( $space_id ) );
                    die();

                } else {

                    $error_array[] = 'エラーが発生しました ' . $space_id->get_error_message();

                }

            }

        }

    endif; // if ( is_post_type_archive( 'space' ) )

}
add_action( 'template_redirect', 'mmsf_frontend_editing_module' );