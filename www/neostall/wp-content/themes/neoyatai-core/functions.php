<?php

/****
 * SETTING DEFAULT TIMEZONE
 **/
date_default_timezone_set( 'Asia/Tokyo' );

/****
 * REQUIRE PHPs
 **/
require_once( TEMPLATEPATH . '/functions/customs.php' );
require_once( TEMPLATEPATH . '/functions/scripts-styles.php' );
require_once( TEMPLATEPATH . '/functions/queries.php' );
require_once( TEMPLATEPATH . '/functions/functions.php' );
require_once( TEMPLATEPATH . '/functions/edit-form-fields.php' );

/****
 * ADD_THEME_SUPPORT
 **/
add_theme_support( 'post-thumbnails' );

/****
 * redirect after login
 **/
function neoyatai_login_redirect() {
    return home_url();
}
//add_filter( 'login_redirect', 'neoyatai_login_redirect' );

/****
 * カスタム投稿タイプのリライトルール -> 参考: http://blog.ext.ne.jp/?p=1416
 **/
function myposttype_rewrite() {
    global $wp_rewrite;
    $post_types = array( 'news', 'management', 'activity' );
    foreach ( $post_types as $post_type ) {
        $queryarg = "post_type={$post_type}&p=";
        $wp_rewrite->add_rewrite_tag( "%{$post_type}_id%", '([^/]+)', $queryarg );
        $wp_rewrite->add_permastruct( $post_type, "/{$post_type}/%{$post_type}_id%", false );
    }
}
add_action( 'init', 'myposttype_rewrite' );

function myposttype_permalink( $post_link, $id = 0, $leavename ) {
    global $wp_rewrite;
    $post = get_post( $id );
    if ( is_wp_error( $post ) )
        return $post;
    $post_types = array( 'news', 'management', 'activity' );
    if ( !in_array( get_post_type( $post ), $post_types ) ) // 'news' のみに適用... 本当にこれで大丈夫？
        return $post_link;
    $post_type = $post->post_type;
    $newlink = $wp_rewrite->get_extra_permastruct( $post_type );
    $newlink = str_replace( "%{$post_type}_id%", $post->ID, $newlink );
    $newlink = home_url( user_trailingslashit( $newlink ) );
    return $newlink;
}
add_filter( 'post_type_link', 'myposttype_permalink', 1, 3 );


/****
 * dashboard: add column 'post_parent' in media.
 * -> http://daily.glocalism.jp/wordpress/wp-media-add-file-url-column/
 **/
function posts_columns_attachment_parent_post($defaults){
    $defaults['attachments_parent_post'] = __('post parent');
    return $defaults;
}
add_filter('manage_media_columns', 'posts_columns_attachment_parent_post', 1);
function posts_custom_columns_attachment_parent_post($column_name, $id){
    if($column_name === 'attachments_parent_post'){
        $parent = get_post( $id )->post_parent;
        $pt = get_post( $parent )->post_type;
        $postType = get_post_type_object( $pt )->label;
        $ttl = get_the_title( $parent );
        printf( '[%s] %s', $postType, $ttl );
    }
}
add_action('manage_media_custom_column', 'posts_custom_columns_attachment_parent_post', 1, 2);

/****
 *
 **/
if ( !function_exists( 'neoyatai_add_title' ) ) {
    function neoyatai_add_title( $title ) {

        if ( is_singular( 'event' ) ) {

            global $post;

            if ( $series = get_series_tax_obj( $post->ID ) ) {
                $title = esc_html( $series->name ) . ' | ' . $title;
            }
            if ( $day = strtotime( $post->day ) ) {
                $title .= sprintf( '%s (%s) | ', date( 'n月j日', $day ), mb_substr( __( date( 'D', $day ) ), 0, 1 ) );
            }

        }

        return $title;
    }
}
add_filter( 'wp_title', 'neoyatai_add_title' );