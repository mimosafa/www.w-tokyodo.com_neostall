<?php

if ( class_exists( 'MmsfCustoms' ) ) {

    $mmsf_customs = new MmsfCustoms();
    $mmsf_customs->custom_post_type( 'ニュース', 'news', array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ), array( 'menu_position' => 5 ) );
    $mmsf_customs->custom_post_type( 'アクティビティ', 'activity', array(), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( '管理情報', 'management', array( 'title', 'editor', 'excerpt', 'custom-fields' ), array( 'menu_position' => 10 ) );
    $mmsf_customs->custom_post_type( 'イベント情報', 'event', array(), array( 'menu_position' => 10 ) );
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