<?php
/***********************
 * Define Post Types.
 ***********************/

// Define "ACTIVITY"
if ( ! function_exists( 'activity_custom_post_type') ) {
    function activity_custom_post_type() {
        $labels = array(
            'name' => 'アクティビティ',
            'singular_name' => 'アクティビティ',
            'add_new' => 'アクティビティを追加',
            'add_new_item' => '新しいアクティビティを追加',
            'edit_item' => 'アクティビティ情報を編集',
            'new_item' => '新しいアクティビティ',
            'view_item' => 'アクティビティを見る',
            'search_items' => 'アクティビティを検索する',
            'not_found' => 'アクティビティ情報はありません',
            'not_found_in_trash' => 'ゴミ箱にアクティビティ情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => array(
                // 'editor',
                'custom-fields',
                'author'
            ),
            // 'register_meta_box_cb' => 'mmsf_meta_box_activity'
        );
        register_post_type( 'activity', $args );
    }
}

// Define "MANAGEMENT"
if ( ! function_exists( 'management_custom_post_type' ) ) {
    function management_custom_post_type() {
        $labels = array(
            'name' => '管理情報',
            'singular_name' => '管理情報',
            'add_new' => '管理情報を追加',
            'add_new_item' => '新しい管理情報を追加',
            'edit_item' => '管理情報を編集',
            'new_item' => '新しい管理情報',
            'view_item' => '管理情報を見る',
            'search_items' => '管理情報を検索する',
            'not_found' => '管理情報はありません',
            'not_found_in_trash' => 'ゴミ箱に管理情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'custom-fields',
                'author'
            )
        );
        register_post_type( 'management', $args );
    }
}

// Define "EVENT"
if ( ! function_exists( 'event_custom_post_type' ) ) {
    function event_custom_post_type() {
        $labels = array(
            'name' => 'イベント情報',
            'singular_name' => 'イベント情報',
            'add_new' => 'イベント情報を追加',
            'add_new_item' => '新しいイベント情報を追加',
            'edit_item' => 'イベント情報を編集',
            'new_item' => '新しいイベント情報',
            'view_item' => 'イベント情報を見る',
            'search_items' => 'イベント情報を検索する',
            'not_found' => 'イベント情報はありません',
            'not_found_in_trash' => 'ゴミ箱にイベント情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => array(
                'title',
                //'editor',
                'custom-fields',
                'author'
            ),
            // 'register_meta_box_cb' => 'mmsf_meta_box_event'
        );
        register_post_type( 'event', $args );
    }
}

// Define "VENDOR"
if ( ! function_exists( 'vendor_custom_post_type' ) ) {
    function vendor_custom_post_type() {
        $labels = array(
            'name' => '事業者',
            'singular_name' => '事業者',
            'add_new' => '事業者を追加',
            'add_new_item' => '新しい事業者を追加',
            'edit_item' => '事業者情報を編集',
            'new_item' => '新しい事業者',
            'view_item' => '事業者を見る',
            'search_items' => '事業者を検索する',
            'not_found' => '事業者情報はありません',
            'not_found_in_trash' => 'ゴミ箱に事業者情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'custom-fields',
                'author'
            ),
            // 'register_meta_box_cb' => 'mmsf_meta_box_vendor'
        );
        register_post_type( 'vendor', $args );
    }
}

// Define "KITCHENCAR"
if ( ! function_exists( 'kitchencar_custom_post_type' ) ) {
    function kitchencar_custom_post_type() {
        $labels = array(
            'name' => 'キッチンカー',
            'singular_name' => 'キッチンカー',
            'add_new' => 'キッチンカーを追加',
            'add_new_item' => '新しいキッチンカーを追加',
            'edit_item' => 'キッチンカー情報を編集',
            'new_item' => '新しいキッチンカー',
            'view_item' => 'キッチンカーを見る',
            'search_items' => 'キッチンカーを検索する',
            'not_found' => 'キッチンカー情報はありません',
            'not_found_in_trash' => 'ゴミ箱にキッチンカー情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => array(
                'title',
                //'editor',
                'thumbnail',
                'custom-fields',
                'author'
            ),
            // 'register_meta_box_cb' => 'mmsf_meta_box_kitchencar'
        );
        register_post_type( 'kitchencar', $args );
    }
}

// Define "MENU_ITEM"
if ( ! function_exists( 'menu_item_custom_post_type' ) ) {
    function menu_item_custom_post_type() {
        $labels = array(
            'name' => '提供メニュー',
            'singular_name' => '提供メニュー',
            'add_new' => '提供メニューを追加',
            'add_new_item' => '新しい提供メニューを追加',
            'edit_item' => '提供メニュー情報を編集',
            'new_item' => '新しい提供メニュー',
            'view_item' => '提供メニューを見る',
            'search_items' => '提供メニューを検索する',
            'not_found' => '提供メニュー情報はありません',
            'not_found_in_trash' => 'ゴミ箱に提供メニュー情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true, //...'事業者'編集画面からしか追加させたくない
            'query_var' => true,
            'rewrite' => array( 'slug' => 'menu' ),
            'capability_type' => 'post',
            'hierarchical' => false,
            //'menu_position' => 5,
            'has_archive' => false,
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'custom-fields',
                'author'
            ),
            // 'register_meta_box_cb' => 'mmsf_meta_box_menu_item'
        );
        register_post_type( 'menu_item', $args );
    }
}

// Define "SPACE"
if ( ! function_exists( 'space_custom_post_type' ) ) {
    function space_custom_post_type() {
        $labels = array(
            'name' => 'スペース',
            'singular_name' => 'スペース',
            'add_new' => 'スペースを追加',
            'add_new_item' => '新しいスペースを追加',
            'edit_item' => 'スペース情報を編集',
            'new_item' => '新しいスペース',
            'view_item' => 'スペースを見る',
            'search_items' => 'スペースを検索する',
            'not_found' => 'スペース情報はありません',
            'not_found_in_trash' => 'ゴミ箱にスペース情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => array(
                'title',
                'author',
                'custom-fields'
            ),
            // 'register_meta_box_cb' => 'mmsf_meta_box_space'
        );
        register_post_type( 'space', $args );
    }
}

// Define "NEWS"
if ( ! function_exists( 'news_custom_post_type' ) ) {
    function news_custom_post_type() {
        $labels = array(
            'name' => 'ニュース',
            'singular_name' => 'ニュース',
            'add_new' => 'ニュースを追加',
            'add_new_item' => '新しいニュースを追加',
            'edit_item' => 'ニュース情報を編集',
            'new_item' => '新しいニュース',
            'view_item' => 'ニュースを見る',
            'search_items' => 'ニュースを検索する',
            'not_found' => 'ニュース情報はありません',
            'not_found_in_trash' => 'ゴミ箱にニュース情報はありません',
            'parent_item_colon' => ''
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'custom-fields'
            ),
            // 'register_meta_box_cb' => 'mmsf_meta_box_news'
        );
        register_post_type( 'news', $args );
    }
}

/***************************
 * REGISTER POST TYPES
 ***************************/
/*
add_action( 'init', 'news_custom_post_type' );
add_action( 'init', 'vendor_custom_post_type' );
add_action( 'init', 'kitchencar_custom_post_type' );
add_action( 'init', 'menu_item_custom_post_type' );
add_action( 'init', 'event_custom_post_type' );
add_action( 'init', 'space_custom_post_type' );
add_action( 'init', 'activity_custom_post_type' );
add_action( 'init', 'management_custom_post_type' );
*/

/***********************
 * Define TAXONOMIES
 ***********************/

// Define "SERIES"
if ( ! function_exists( 'series_custom_taxonomy' ) ) {
    function series_custom_taxonomy() {
        $args = array(
            'label' => 'シリーズ',
            'public' => true,
            'hierarchical' => true
        );
        register_taxonomy( 'series', array( 'event' ), $args );
    }
}

// Define "REGION"
if ( ! function_exists( 'region_custom_taxonomy' ) ) {
    function region_custom_taxonomy() {
        $args = array(
            'label' => '地域',
            'public' => true,
            'hierarchical' => true
        );
        register_taxonomy( 'region', array( 'space', 'event' ), $args );
    }
}

// Define "MENU_GENRE"
if ( ! function_exists( 'menu_genre_custom_taxonomy' ) ) {
    function menu_genre_custom_taxonomy() {
        $args = array(
            'label' => 'ジャンル',
            'public' => true
        );
        register_taxonomy( 'genre', array( 'menu_item', 'vendor' ), $args );
    }
}

/***************************
 * REGISTER TAXONOMIES
 ***************************/
/*
add_action( 'init', 'series_custom_taxonomy' );
add_action( 'init', 'region_custom_taxonomy' );
add_action( 'init', 'menu_genre_custom_taxonomy' );
*/
