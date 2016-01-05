<?php
/***************************
 * REQUIRE_ONCE FUNCTIONS
 ***************************/
require_once( STYLESHEETPATH . '/functions/mmsf-script-css-load.php' );

/**
 * views
 */
require_once( STYLESHEETPATH . '/functions/views/elements/breadcrumb.php' );


// Lazyload Image
function mmsf_get_lazyload_image( $attachment_id, $size = 'thumbnail', $str = 'lazy' ) {
    $html = '';
    $image = wp_get_attachment_image_src( $attachment_id, $size );
    $class   = esc_attr( $str );
    if ( $image ) {
        list( $src, $width, $height ) = $image;
        $hrstring = image_hwstring( $width, $height );
        $html = rtrim( "<img {$hrstring}" );
        $html .= ' src="' . get_stylesheet_directory_uri() . '/images/white.gif"';
        $html .= ' data-original="' . $src . '" class="' . $class . '" />' . "\n";
        $html .= '<noscript>' . "\n";
        $html .= rtrim( "<img {$hrstring}" );
        $html .= ' src="' . $src . '" />' . "\n";
        $html .= '</noscript>';
    } else { // if no image
        if ( is_array( $size ) ) {
            list( $width, $height ) = $size;
            $src = esc_url( sprintf( 'http://fakeimg.pl/%dx%d/?text=No Image', $width, $height ) );
        } elseif ( 'thumbnail' == $size ) {
            $width = 150;
            $height = 150;
            $src = get_template_directory_uri() . '/images/noimage-150x150.png';
        } elseif ( 'medium' == $size ) {
            $width = 300;
            $height = 225;
            $src = get_template_directory_uri() . '/images/noimage-300x225.png';
        }
        $hrstring = image_hwstring( $width, $height );
        $html = rtrim( "<img {$hrstring}" );
        $html .= ' src="' . get_stylesheet_directory_uri() . '/images/white.gif"';
        $html .= ' data-original="' . $src . '" class="' . $class . '" />' . "\n";
        $html .= '<noscript>' . "\n";
        $html .= rtrim( "<img {$hrstring}" );
        $html .= ' src="' . $src . '" />' . "\n";
        $html .= '</noscript>';
    }
    return $html;
}

// Location Str
function get_location_str( $post_id = 0 ) {
    $str = '';
    $region_obj = get_region_tax_obj( $post_id );
    if ( 0 != $region_obj->parent ) {
        $pref_obj = get_term( $region_obj->parent, 'region' );
        $str .= esc_html( $pref_obj->name );
    }
    $str .= esc_html( $region_obj->name );
    if ( $address = get_post( $post_id )->address )
        $str .= $address;
    if ( $site = get_post( $post_id )->site )
        $str .= ' ' . esc_html( $site );
    if ( $area = get_post( $post_id )->areaDetail )
        $str .= ' ' . esc_html( $area );
    return $str;
}

// Opening Time Str
function get_opening_time_str( $post_id = 0 ) {
    $post = get_post( $post_id );
    if ( $post->starting ) {
        $start = date( 'G:i', strtotime( esc_html( $post->starting ) ) );
        if ( $post->startingPending )
            $start .= '<small> (予定)</small>';
    }
    if ( $post->ending ) {
        $end = date( 'G:i', strtotime( esc_html( $post->ending ) ) );
        if ( $post->endingPending )
            $end .= '<small> (予定)</small>';
    }
    if ( $start || $end )
        $str = "{$start} ~ {$end}";
    return $str;
}


/****
 * custom posts (overwrite neoyatai-core)
 **/
function space_custom_post_type() {
    $labels = array(
        'name' => '所在地と出店スケジュール'
    );
    register_post_type( 'space', array( 'labels' => $labels, 'public' => true, 'has_archive' => true, 'supports' => array( 'title', 'custom-fields' ) ) );
}
function kitchencar_custom_post_type() {
    $labels = array(
        'name' => 'ネオ屋台のご紹介'
    );
    register_post_type( 'kitchencar', array( 'labels' => $labels, 'public' => true, 'has_archive' => true ) );
}

/****
 * Google Analyticr Code
 **/
function gae() {
    if ( !is_user_logged_in() ) {
?>
<script type="text/javascript">
  // w-tokyodo
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5722525-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  // neostall
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-44614414-1', 'w-tokyodo.com');
  ga('send', 'pageview');
</script>
<?php
    }
}
add_action( 'wp_head', 'gae' );

/**
 * hide (set no publication) single post
 */
function mmsf_not_to_display_post() {
    if ( current_user_can( 'edit_posts' ) )
        return;
    if ( is_singular( 'event' ) ) {
        global $post;
        if ( !$publication = $post->publication ) {
            $archive_link = get_post_type_archive_link( $post->post_type );
            wp_redirect( $archive_link );
        }
    }
    if ( is_singular( 'space' ) ) {
        global $post;
        if ( 1 !== absint( $post->phase ) ) {
            $archive_link = get_post_type_archive_link( $post->post_type );
            wp_redirect( $archive_link );
        }
    }
}
add_action( 'template_redirect', 'mmsf_not_to_display_post' );

/**
 * overwrite function (neoyatai-core)
 */
function neoyatai_add_title( $title ) {

    if ( is_singular( 'event' ) ) {

        global $post;

        $series = get_series_tax_obj( $post->ID );
        $series_options = get_option( 'series_' . $series->slug );
        if ( $series_options['front_end_archive'] ) {
            $title = esc_html( $series->name ) . ' | ' . $title;
        }
        if ( $day = strtotime( $post->day ) ) {
            $title .= sprintf( '%s (%s) | ', date( 'n月j日', $day ), mb_substr( __( date( 'D', $day ) ), 0, 1 ) );
        }

    }

    return $title;
}

/**
 * overwrite queries function (neoyatai-core)
 */
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
                ),
                array(
                    'key' => 'phase',
                    'value' => '1',
                    'compare' => '='
                )
            );
            $query->set( 'meta_query', $meta_query );
        }
    }
    return;
}

function neoyatai_event_query( $query ) {
    if ( $query->is_main_query() && $query->is_post_type_archive( 'event' ) ) {
        if ( isset( $_GET['past'] ) ) {
            $query->set( 'post_per_page ', 10 );
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'day' );
            $query->set( 'order', 'DESC' );
            $d_query = array(
                'key' => 'day',
                'value' => date( 'Ymd' ),
                'compare' => '<',
                'type' => 'DATE'
            );
        } else {
            $query->set( 'posts_per_page', -1 );
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'day' );
            $query->set( 'order', 'ASC' );
            $d_query = array(
                'key' => 'day',
                'value' => date( 'Ymd' ),
                'compare' => '>=',
                'type' => 'DATE'
            );
        }
        $pub_query = array();
        if ( !current_user_can( 'edit_posts' ) ) {
            $pub_query = array(
                'key' => 'publication',
                'value' => date( 'Y-m-d H:i:s' ),
                'compare' => '<=',
                'type' => 'DATETIME'
            );
        }
        $meta_query = array( $d_query, $pub_query );
        $query->set( 'meta_query', $meta_query );
    }
    return;
}

function neoyatai_series_query( $query ) {
    if ( $query->is_main_query() && $query->is_tax( 'series' ) ) {
        if ( isset( $_GET['past'] ) ) {
            $query->set( 'post_per_page ', 10 );
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'day' );
            $query->set( 'order', 'DESC' );
            $d_query = array(
                'key' => 'day',
                'value' => date( 'Ymd' ),
                'compare' => '<',
                'type' => 'DATE'
            );
        } else {
            $query->set( 'posts_per_page', -1 );
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_key', 'day' );
            $query->set( 'order', 'ASC' );
            $d_query = array(
                'key' => 'day',
                'value' => date( 'Ymd' ),
                'compare' => '>=',
                'type' => 'DATE'
            );
        }
        $pub_query = array();
        if ( !current_user_can( 'edit_posts' ) ) {
            $pub_query = array(
                'key' => 'publication',
                'value' => date( 'Y-m-d H:i:s' ),
                'compare' => '<=',
                'type' => 'DATETIME'
            );
        }
        $meta_query = array( $d_query, $pub_query );
        $query->set( 'meta_query', $meta_query );
    }
    return;
}

function neoyatai_home_query( $query ) {
    if ( $query->is_main_query() && $query->is_home() ) {
        $query->set( 'post_type', array( 'news', 'management' ) );
        /*
        $date_query = array(
            array(
                'after' => 'January 1st, 2014',
                'inclusive' => true
            )
        );
        $query->set( 'date_query', $date_query );
        */
        $meta_query = array(
            'key' => 'publication',
            'value' => date_i18n( 'Y-m-d H:i:s' ),
            'compare' => '<=',
            'type' => 'DATETIME'
        );
        $query->set( 'meta_query', array( $meta_query ) );
    }
    return;
}

if ( class_exists( 'MmsfCustoms' ) ) {

    $mmsf_customs = new MmsfCustoms();
    $mmsf_customs->custom_post_type( 'ニュース', 'news', array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 5 ) );
    $mmsf_customs->custom_post_type( 'アクティビティ', 'activity', array(), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( '管理情報', 'management', array( 'title', 'editor', 'excerpt', 'custom-fields' ), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( 'イベント情報', 'event', array( 'title', 'editor', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( '事業者', 'vendor', array( 'title', 'editor', 'excerpt', 'custom-fields' ), array( 'menu_position' => 15 ) );
    $mmsf_customs->custom_post_type( 'キッチンカー', 'kitchencar', array( 'title', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 15 ) );
    $mmsf_customs->custom_post_type( '提供商品', 'menu_item', array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 15, 'rewrite' => array( 'slug' => 'menu' ) ) );
    $mmsf_customs->custom_post_type( 'スペース', 'space', array(), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( 'クライアント', 'client', array(), array( 'menu_position' => 15 ) );
    $mmsf_customs->custom_taxonomy( 'シリーズ', 'series', array( 'event' ) );
    $mmsf_customs->custom_taxonomy( '地域', 'region', array( 'event', 'space' ) );
    $mmsf_customs->custom_taxonomy( 'ジャンル', 'genre', array( 'menu_item', 'vendor' ), array( 'hierarchical' => false ) );
    $mmsf_customs->init();
    $mmsf_customs->int_permalink( array( 'news', 'management', 'activity' ) );

}







if ( ! function_exists( 'get_series_tax_obj' ) ) {
    function get_series_tax_obj( $post_id = 0 ) {
        $arr = get_the_terms( $post_id, 'series' );
        if ( ! $arr )
            return;
        $obj = array_pop( $arr );
        return $obj;
    }
}
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

    }

}