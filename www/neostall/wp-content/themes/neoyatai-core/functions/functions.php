<?php

if ( ! function_exists( 'get_series_tax_obj' ) ) {
    function get_series_tax_obj( $post_id = 0 ) {
        $arr = get_the_terms( $post_id, 'series' );
        if ( ! $arr )
            return;
        $obj = array_pop( $arr );
        return $obj;
    }
}
/*
if ( ! function_exists( 'get_season_pt_obj' ) ) {
    function get_season_pt_obj( $slug = '' ) {
        global $post;
        if ( '' == $slug )
            if ( $term_obj = get_series_tax_obj( $post->ID ) )
                $slug = $term_obj->slug;
        if ( ! $slug )
            return;
        $arr = array(
            'post_status' => 'publish',
            'post_type' => 'season',
            'numberposts' => 1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'series',
                    'field' => 'slug',
                    'terms' => $slug
                )
            )
        );
        $obj = array_pop( get_posts( $arr ) );
        return $obj;
    }
}
*/
if ( ! function_exists( 'get_region_tax_obj' ) ) {
    function get_region_tax_obj( $post_id = 0 ) {
        if ( $site_id = get_post( $post_id )->site_id )
            $id = (int)$site_id;
        else
            $id = $post_id;
        $arr = get_the_terms( $id, 'region' );
        if ( ! $arr )
            return;
        $obj = array_pop( $arr );
        return $obj;
    }
}

/**
 * 引数で指定された文字列が 'Ymd'、または 'Ym'フォーマットの有効な日付かを判定
 *
 * @param string $days
 * @return bool
 *
 */
if ( ! function_exists( 'checkdate_Ymd' ) ) {
    function checkdate_Ymd ( $date ) {
        if ( preg_match( '/^\d{8}$/', $date ) ) {
            $_Y = substr( $date, 0, 4 );
            $_m = substr( $date, 4, 2 );
            $_d = substr( $date, -2 );
        } elseif ( preg_match( '/^\d{6}$/', $date ) ) {
            $_Y = substr( $date, 0, 4 );
            $_m = substr( $date, 4, 2 );
            $_d = 1;
        } else {
            return false;
        }
        return checkdate( $_m, $_d, $_Y );
    }
}

/**
 * 特定期間の日付情報を指定したフォーマット（複数指定可）の配列で返す。
 *
 * @param string $days 日数
 * @param string $date 始まりの日、有効なフォーマットであれば大丈夫 Default 本日。
 * @param string $format 返したいフォーマットを指定。複数指定可。
 * @return array( int => array( string, string... ) )
 *
 */
if ( ! function_exists( 'get_formatted_day_arrays' ) ) {
    function get_formatted_day_arrays( $days, $date = '', $format ) {

        if ( ! is_numeric( $days ) )
            return;

        if ( '' == $date ) {
            $ts = time();
        } else {
            if ( ! $ts = strtotime( $date ) )
                return;
        }

        $formats = func_get_args();
        unset( $formats[0], $formats[1] );

        $arr = array();
        for ( $i = 0; $i < $days; $i++ ) {

            $arr[$i] = array();
            foreach ( $formats as $format ) {
                $arr[$i][] = date( $format, $ts );
            }
            $ts = $ts + 86400;

        }
        return $arr;

    }
}

// Get cf name, else, post_title
// - original model function: get_the_title, located in wp-includes/post-template.php.
if ( ! function_exists( 'get_the_display_name' ) ) {
    function get_the_display_name( $post = 0 ) {
        $post = get_post( $post );
        $post_title = isset( $post->post_title ) ? $post->post_title : '';
        $title = $post->name ? $post->name : $post_title;
        $id = isset( $post->ID ) ? $post->ID : 0;
        return apply_filters( 'the_title', $title, $id );
    }
}

if ( ! function_exists( 'implode_post_title' ) ) {
    function implode_post_title( $glue = ', ' , $array = array(), $permalink = false ) {
        $string = '';
        foreach ( $array as $post_id ) {
            if ( $ttl = get_the_title( $post_id ) ) {
                if ( $permalink ) {
                    $prmlnk = get_permalink( $post_id );
                    $string .= sprintf( '<a href="%s">%s</a>', $prmlnk, $ttl );
                } else {
                    $string .= $ttl;
                }
                $string .= $glue;
            }
        }
        $trim = strlen( $glue ) * -1;
        $string = substr( $string, 0, $trim );
        return $string;
    }
}

if ( ! function_exists( 'implode_term_name' ) ) {
    function implode_term_name( $glue = ', ' , $array = array(), $taxonomy ) {
        $string = '';
        foreach ( $array as $term_id ) {
            if ( $term_name = get_term( $term_id, $taxonomy )->name ) {
                $string .= $term_name;
                $string .= $glue;
            }
        }
        $trim = strlen( $glue ) * -1;
        $string = substr( $string, 0, $trim );
        return $string;
    }
}

/****************************************************************************
 *
 ****************************************************************************/

if ( ! function_exists( 'get_kitchencar_menuitems' ) ) {

    function get_kitchencar_menuitems( $post_id, $post_type = '', $str = '' ) {

        if ( !$post_id = absint( $post_id ) )
            return;

        if ( 'kitchencar' != get_post( $post_id )->post_type )
            return;

        if ( '' === $post_type ) {
            global $post;
            $post_type = $post->post_type;
        }

        $keys = array();
        if ( 'space' == $post_type ) {
            if ( '' !== $str )
                $keys[] = 'weekly_' . $str;
            $keys[] = 'weekly_0';
        } elseif ( 'event' == $post_type ) {
            if ( '' !== $str )
                $keys[] = 'event_' . $str;
            $keys[] = 'event_0';
        }
        $keys[] = 'default_0';

        $array = array();
        foreach ( $keys as $key ) {
            if ( $array = get_post_meta( $post_id, $key, true ) ) {
                $array['key'] = $key;
                break;
            }
        }

        return $array;

    }

    if ( ! function_exists( 'get_the_menuitems' ) ) {

        function get_the_menuitems( $post_id, $post_type = '', $str = '' ) {

            if ( !$post_id = absint( $post_id ) )
                return;

            if ( 'activity' != get_post( $post_id )->post_type )
                return get_kitchencar_menuitems( $post_id, $post_type, $str );

            $default = array( 'item', 'genre', 'text', 'img1', 'img2' );
            $array = array();
            foreach ( $default as $i => $key ) {
                if ( $array[$key] = get_post_meta( $post_id, $key, true ) ) {
                    unset( $default[$i] );
                } else {
                    $array[$key] = '';
                }
            }
            if ( !empty( $default ) ) {
                $menuitems = get_kitchencar_menuitems( get_post( $post_id )->actOf, $post_type, $str );
                if ( !empty( $menuitems ) ) {
                    foreach ( $default as $key )
                        $array[$key] = $menuitems[$key];
                }
            }

            return $array;

        }

        if ( ! function_exists( 'legacy_get_the_menuitems') ) {

            function legacy_get_the_menuitems( $post_id, $post_type = '', $str = '' ) {

                $default = array( 'item', 'genre', 'text', 'img1', 'img2' );
                $array = (array) get_the_menuitems( $post_id, $post_type, $str );

                foreach ( $default as $i => $key ) {
                    if ( isset( $array[$key] ) && !empty( $array[$key] ) )
                        unset( $default[$i] );
                }

                if ( !empty( $default ) ) {

                    $obj = get_post( $post_id );
                    $kit_id = ( 'kitchencar' == $obj->post_type ) ? $post_id : $obj->actOf;

                    $acf_templates = (array) get_field( 'menu', $kit_id );
                    $un_case = ( 'space' == $post_type ) ? 2 : 1;

                    foreach ( $acf_templates as $n => $acf_array ) {
                        if ( $un_case != $acf_array['case'] ) {
                            $template = $acf_array;
                            break;
                        }
                    }
                    if ( isset( $template ) ) {
                        foreach ( $default as $key ) {
                            if ( 'img1' == $key ) {
                                $array['img1'] = get_post_thumbnail_id( $kit_id );
                            } elseif ( 'img2' == $key ) {
                                $array['img2'] = $template['img'];
                            } else {
                                $array[$key] = $template[$key];
                            }
                        }
                    }

                    $array['text'] .= ' ★★★LEGACY★★★';

                }
                return $array;

            }

        }

    }

}