<?php

/**
 * neostall theme setup
 */
function neostall_theme_setup() {
    // Custom Header
    add_theme_support( 'custom-header', array(
        'default-image' => get_stylesheet_directory_uri() . '/img/neostall-default-header.jpg',
    ) );
    // Post Thumbnails
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'neostall_theme_setup' );

/**
 * view, model, snippet などが定義されたファイルを読み込む。
 * 今回の場合、すべて functionsディレクトリーにまとめている。
 * 読み込みたくないファイルは削除をするか、ファイル名の最初に'_'を付けるかする。
 *
 * @param  string $path 読み込むディレクトリーのパス。要スラッシュ。
 * @return null
 *
 * 参考エントリー
 * @link http://dogmap.jp/2011/04/19/wordpress-managed-snippet/
 * @link http://kanamehackday.blog17.fc2.com/blog-entry-245.html
 *
 * ...開発が一段落したらちゃんと全部 require_once, または get_template_partする？
 */
class theme_functions_autoload {

    /**
     * Singleton
     *
     * @link http://stein2nd.wordpress.com/2013/10/04/wordpress_and_oop/
     * @link http://ja.phptherightway.com/pages/Design-Patterns.html
     */
    public static function get_instance() {
        static $instance = null;
        if ( null == $instance )
            $instance = new static();
        return $instance;
    }
    private function __construct() {}
    private function __clone() {}
    private function __waleup() {}

    /**
     * 読み込む phpファイル
     */
    private $php_files = array();

    /**
     * ディレクトリーに含まれる phpファイルを走査
     */
    private function read_dir( $path ) {
        if ( !is_dir( $path ) )
            return;
        $dir = array();
        $entries = scandir( $path );
        foreach ( $entries as $entry ) {
            if ( '.' == $entry || '..' == $entry || '_' == $entry[0] )
                continue;
            $result = $path . $entry;
            if ( is_dir( $result ) )
                $dir[] = $this->read_dir( $result . '/' );
            elseif ( '.php' === strtolower( substr( $result, -4 ) ) )
                $dir[] = $result;
        }
        /**
         * $dir は配列が入れ子になっていたりするので別関数で展開、$php_files に格納する
         */
        $this->set_php_files( $dir );
    }

    private function set_php_files( $dir ) {
        if ( !empty( $dir ) ) {
            foreach ( $dir as $var ) {
                if ( is_array( $var ) )
                    $this->set_php_files( $var );
                elseif ( is_file( $var ) ) // よく分からないが nullが混じっちゃうので…
                    $this->php_files[] = $var;
            }
        }
    }

    /**
     * 初期化
     */
    public function init( $path ) {
        $this->read_dir( $path );
        if ( !empty( $this->php_files ) ) {
            foreach ( $this->php_files as $file )
                require_once( $file );
        }
    }
}
add_action( 'after_setup_theme', function() {
    $tfa = theme_functions_autoload::get_instance();
    $tfa->init( trailingslashit( get_stylesheet_directory() ) . 'functions/' );
} );

/**
 * default scripts and styles enqueue
 * - bootstrap 3.1.1
 * - fontawesome 4.0.3
 * - modernizr 2.7.1
 * ...and theme style, script
 */
function neostall_default_scripts_styles() {
    if ( !is_admin() ) {
        // styles
        wp_enqueue_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.css', array(), '3.1.1' );
        wp_enqueue_style( 'font-awesone', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array( 'bootstrap' ), '4.0.3' );
        wp_enqueue_style( 'neostall', get_stylesheet_uri(), array(), date( 'YmdHis', filemtime( get_stylesheet_directory() . '/style.css' ) ) );
        // scripts
        wp_enqueue_script( 'neostall', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ), date( 'YmdHis', filemtime( get_stylesheet_directory() . '/js/script.js' ) ), true );
        wp_enqueue_script( 'modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js', array(), '2.7.1' );
        wp_enqueue_script( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', array( 'jquery' ), '3.1.1', true );
    }
}
add_action( 'wp_enqueue_scripts', 'neostall_default_scripts_styles' );

















function neoyatai_breadcrumb() {
    if ( is_admin() || is_home() )
        return;

    $array = array( array( home_url(), 'ネオ屋台村' ) );
    if ( is_post_type_archive() ) {
        $array[] = esc_html( get_post_type_object( get_post_type() )->label );
    } elseif ( is_singular( array( 'news', 'management', 'space', 'event', 'kitchencar', 'vendor' ) ) ) {
        $array[] = array(
            get_post_type_archive_link( get_post_type() ),
            esc_html( get_post_type_object( get_post_type() )->label )
        );
        $array[] = the_title( '', '', false );
    }

    if ( 1 === ( $i = count( $array ) ) )
        return;

    $breadcrumb  = '<div id="neoyatai-breadcrumb" class="container">';
    $breadcrumb .= '<ol class="breadcrumb">' . "\n";
    foreach ( $array as $var ) {
        $li = ( $i !== 1 ) ? '<li>' : '<li class="active">';
        if ( is_array( $var ) )
            $breadcrumb .= sprintf( $li . '<a href="%s">%s</a></li>', $var[0], $var[1] ) . "\n";
        else
            $breadcrumb .= sprintf( $li . '%s</li>', $var ) . "\n";
        $i--;
    }
    $breadcrumb .= "</ol>\n";
    $breadcrumb .= "</div>\n";

    echo $breadcrumb;
}

// BREADCRUMB
function mmsf_breadcrumb( $sep = ' &gt; ' ) {
    if ( is_admin() || is_home() )
        return;

    //global $post;
    $str = '';

    $str .= "\n" . '<div class="breadcrumb">' . "\n";
    $str .= sprintf( '<p><a href="%s">ネオ屋台村</a>%s', home_url(), $sep );
    if ( is_post_type_archive() ) {
        $str .= esc_html( get_post_type_object( get_post_type() )->label );
    } elseif ( is_tax( 'series' ) ) {
        $link = get_post_type_archive_link( 'event' );
        $str .= sprintf( '<a href="%s">イベント</a>%s', $link, $sep );
        $str .= single_term_title( '', false );
    } elseif ( is_singular( 'event' ) ) {
        $link = get_post_type_archive_link( 'event' );
        $str .= sprintf( '<a href="%s">イベント</a>%s', $link, $sep );
        $series = get_series_tax_obj( $post );
        $series_options = get_option( 'series_' . $series->slug );
        if ( $series_options['front_end_archive'] ) {
            $link = get_term_link( $series, 'series' );
            $label = esc_html( $series->name );
            $str .= sprintf( '<a href="%s">%s</a>%s', $link, $label, $sep );
        }
        $str .= $post->name ? esc_html( $post->name ) : get_the_title();
    } elseif ( is_singular( 'space' ) || is_singular( 'news' ) || is_singular( 'kitchencar' ) ) {
        $link = get_post_type_archive_link( $post->post_type );
        $label = esc_html( get_post_type_object( get_post_type() )->label );
        $str .= '<a href="' . $link . '">' . $label . '</a>' . $sep;
        $str .= $post->name ? esc_html( $post->name ) : get_the_title();
    } elseif ( is_category() ) {
        $cat = get_queried_object();
        if ( $cat->parent != 0 ) {
            $ancestors = array_reverse( get_ancestors( $cat->cat_ID, 'category' ) );
            foreach ( $ancestors as $ancestor ) {
                $link = get_category_link( $ancestor );
                $name = get_cat_name( $ancestor );
                $str .= '<a href="' . $link . '">' . $name . '</a>' . $sep;
            }
        }
        $str .= $cat->name;
    } elseif ( is_page() ) {
        if ( $post->post_parent != 0 ) {
            $ancestors = array_reverse( $post->ancestors );
            foreach ( $ancestors as $ancestor ) {
                $link = get_permalink( $ancestor );
                $name = get_the_title( $ancestor );
                $str .= '<a href="' . $link . '">' . $name . '</a>' . $sep;
            }
        }
        $str .= $post->post_title;
    } elseif ( is_single() ) {
        $categories = get_the_category( $post->ID );
        $cat = $categories[0];
        if ( $cat->parent != 0 ) {
            $ancestors = array_reverse( get_ancestors( $cat->cat_ID, 'category' ) );
            foreach ( $ancestors as $ancestor ) {
                $link = get_category_link( $ancestor );
                $name = get_cat_name( $ancestor );
                $str .= '<a href="' . $link . '">' . $name . '</a>' . $sep;
            }
        }
        $str .= '<a href="' . get_category_link( $cat->cat_ID ) . '">' . $cat->cat_name . '</a>' . $sep;
        $str .= $post->post_title;
    }
    $str .= '</p>' . "\n";
    $str .= '</div>';

    echo $str;
}

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
    register_post_type( 'space', array( 'labels' => $labels, 'public' => true, 'has_archive' => true ) );
}
function kitchencar_custom_post_type() {
    $labels = array(
        'name' => 'ネオ屋台のご紹介'
    );
    register_post_type( 'kitchencar', array( 'labels' => $labels, 'public' => true, 'has_archive' => true ) );
}

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

function neoyatai_home_query( $query ) {
    if ( $query->is_main_query() && $query->is_home() ) {
        $query->set( 'post_type', 'news' );
        $date_query = array(
            array(
                'after' => 'January 1st, 2014',
                'inclusive' => true
            )
        );
        $query->set( 'date_query', $date_query );
    }
    return;
}

if ( class_exists( 'MmsfCustoms' ) ) {

    $mmsf_customs = new MmsfCustoms();
    $mmsf_customs->custom_post_type( 'ニュース', 'news', array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 5 ) );
    $mmsf_customs->custom_post_type( 'アクティビティ', 'activity', array(), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( '管理情報', 'management', array( 'title', 'editor', 'excerpt', 'custom-fields' ), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( 'イベント情報', 'event', array(), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( '事業者', 'vendor', array( 'title', 'editor', 'excerpt', 'custom-fields' ), array( 'menu_position' => 15 ) );
    $mmsf_customs->custom_post_type( 'キッチンカー', 'kitchencar', array( 'title', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 15 ) );
    $mmsf_customs->custom_post_type( '提供商品', 'menu_item', array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 15, 'rewrite' => array( 'slug' => 'menu' ) ) );
    $mmsf_customs->custom_post_type( 'ランチスケジュール', 'space', array(), array( 'menu_position' => 10 ) );
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