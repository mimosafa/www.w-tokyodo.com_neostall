<?php

/**
 * カスタム投稿タイプを簡単に利用できるようにする便利コード
 * -> http://2inc.org/blog/2012/03/16/1322/
 *
 * @since 0.0.0
 *
 * @return
 */
class MmsfCustoms {
    private $custom_post_type = array();
    private $custom_taxonomy = array();
    private $post_types = array();

    /**
     * 実行
     */
    public function init() {
        if ( !empty( $this->custom_post_type ) ) {
            add_action( 'init', array( $this, 'register_post_type' ) );
        }
        if ( !empty( $this->custom_taxonomy ) ) {
            add_action( 'init', array( $this, 'register_taxonomy' ) );
        }
    }

    /**
     * パーマリンクを post_id にする
     * -> http://www.torounit.com/blog/2011/04/17/683/
     */
    public function int_permalink( $post_types ) {
        $this->post_types['int_permalink'] = (array) $post_types;
        add_action( 'init', array( $this, 'set_rewrite' ) );
        add_filter( 'post_type_link', array( $this, 'set_permalink' ), 1, 2 );
    }

    /**
     * カスタム投稿タイプの登録
     * -> http://codex.wordpress.org/Function_Reference/register_post_type
     * @param String  表示名
     *        String  スラッグ（登録名）
     *        Array   サポートタイプ
     *        Array   オプション項目
     */
    public function custom_post_type( $name, $slug, $supports = array(), $options = array() ) {
        $custom_post_type = array(
            'name' => $name,
            'slug' => $slug,
            'supports' => $supports,
            'options' => $options
        );
        $this->custom_post_type[] = $custom_post_type;
    }

    /**
     * 多次元配列をマージ
     * @param   Array   $a
     *          Array   $b
     * @return  Array
     */
    protected function array_merge( Array $a, Array $b ) {
        foreach ( $a as $key => $val ) {
            if ( !isset( $b[$key] ) ) {
                $b[$key] = $val;
            } elseif ( is_array( $val ) ) {
                $b[$key] = $this->array_merge( $val, $b[$key] );
            }
        }
        return $b;
    }

    /**
     * カスタム登録タイプの登録を実行
     */
    public function register_post_type() {
        foreach ( $this->custom_post_type as $cpt ) {
            if ( empty( $cpt['supports'] ) ) {
                $cpt['supports'] = array( 'title', 'editor', 'custom-fields' );
            }
            $labels = array(
                'name' => $cpt['name'],
                'singular_name' => $cpt['name'],
                'add_new_item' => $cpt['name'] . 'を追加',
                'add_new' => '新規追加',
                'new_item' => '新規追加',
                'edit_item' => $cpt['name'] . 'を編集',
                'view_item' => $cpt['name'] . 'を表示',
                'not_found' => $cpt['name'] . 'は見つかりませんでした',
                'not_found_in_trash' => 'ゴミ箱に' . $cpt['name'] . 'はありません。',
                'search_items' => $cpt['name'] . 'を検索',
            );
            $default_options = array(
                'public' => true,
                'has_archive' => true,
                'hierarchical' => false,
                'labels' => $labels,
                'menu_position' => 20,
                'supports' => $cpt['supports'],
                'rewrite' => array(
                    'slug' => $cpt['slug'],
                    'with_front' => false
                )
            );
            $args = $this->array_merge( $default_options, $cpt['options'] );

            $_taxonomies = array();
            foreach ( $this->cusom_taxonomy as $custom_taxonomy ) {
                if ( in_array( $cpt['slug'], $custom_taxonomy['post_type'] ) ) {
                    $_taxonomies[] = $custom_taxonomy['slug'];
                }
            }
            if ( !empty( $_taxonomies ) ) {
                $args_taxonomies = array(
                    'taxonomies' => $_taxonomies
                );
                $args = array_merge( $args, $args_taxonomies );
            }
            register_post_type( $cpt['slug'], $args );
        }
    }

    /**
     * カスタムタクソノミーの登録
     * http://codex.wordpress.org/Function_Reference/register_taxonomy
     * @param   String  表示名
     *        String  スラッグ（登録名）
     *        Array   ポストタイプ
     *        Array   オプション項目
     */
    public function custom_taxonomy( $name, $slug, $post_type = array(), $options = array() ) {
        $custom_taxonomy = array(
            'name' => $name,
            'slug' => $slug,
            'post_type' => $post_type,
            'options' => $options
        );
        $this->custom_taxonomy[] = $custom_taxonomy;
    }

    /**
     * カスタムタクソノミーの登録を実行
     */
    public function register_taxonomy() {
        foreach ( $this->custom_taxonomy as $ct ) {
            $default_options = array(
                'hierarchical' => true,
                'rewrite' => array(
                    'with_front' => false
                )
            );
            $ct['options'] = array_merge( $default_options, $ct['options'] );
            register_taxonomy(
                $ct['slug'],
                $ct['post_type'],
                array(
                    'hierarchical' => $ct['options']['hierarchical'],
                    'label' => $ct['name'],
                    'query_var' => true,
                    'rewrite' => array(
                        'with_front' => $ct['options']['rewrite']['with_front']
                    )
                )
            );
        }
    }

    /**
     * カスタム投稿タイプのリライトルール
     * -> http://blog.ext.ne.jp/?p=1416
     */
    public function set_rewrite() {
        global $wp_rewrite;
        $post_types = $this->post_types['int_permalink'];
        foreach ( $post_types as $post_type ) {
            $queryarg = "post_type={$post_type}&p=";
            $wp_rewrite->add_rewrite_tag( "%{$post_type}_id%", '([^/]+)', $queryarg );
            $wp_rewrite->add_permastruct( $post_type, "/{$post_type}/%{$post_type}_id%", false );
        }
    }
    public function set_permalink( $post_link, $id = 0 ) {
        global $wp_rewrite;
        $post = get_post( $id );
        if ( is_wp_error( $post ) )
            return $post;
        $post_types = $this->post_types['int_permalink'];
        if ( !in_array( get_post_type( $post ), $post_types ) )
            return $post_link;
        $post_type = $post->post_type;
        $newlink = $wp_rewrite->get_extra_permastruct( $post_type );
        $newlink = str_replace( "%{$post_type}_id%", $post->ID, $newlink );
        $newlink = home_url( user_trailingslashit( $newlink ) );
        return $newlink;
    }
}

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