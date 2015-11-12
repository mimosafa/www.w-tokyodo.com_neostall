<?php
/*
Plugin Name: Mmsf Theme Switcher
Description: テーマ切替用プラグイン
Author: mimosafa
Version: 1.1
Author URI: http://mimosafa.me
*/

/**
 * 参考になったエントリー
 * - http://hijiriworld.com/web/wordpress-h-itheme/
 * - http://ja.forums.wordpress.org/topic/13483
 */

class MmsfThemeSwitch {

    //private $dir_uri = '/neostall/';
    /**
     * not logged in user
     */
    private $theme_0 = 'neostall-legacy';
    /**
     * logged in user
     */
    #private $theme_1 = 'neoyatai';
    private $theme_1 = 'neosystem-admin';
    /**
     * developing theme
     */
    //private $theme_2 = 'neostall';
    private $theme_2 = 'mmsf-neoyatai';
    //private $theme_2 = 'neosystem'; // frontend control panel
    //private $theme_2 = 'neoyatai';

    private $cookie_key = '__mmsf_theme_switch';
    private $cookie_val = '';

    function check_front_end() {
        /*
        $wp_admin = $this->dir_uri . 'wp_admin/';
        $admin_ajax_php = $wp_admin . 'admin-ajax.php';
        $image_php = $wp_admin . 'image.php';
        */
        if (
            ! preg_match( '/^\/neostall\/wp-admin\//', $_SERVER['REQUEST_URI'] )
            // for ajax action
            || preg_match( '/^\/neostall\/wp-admin\/admin-ajax.php/', $_SERVER['REQUEST_URI'] )
            // for front-end upload image
            || preg_match( '/^\/neostall\/wp-admin\/image.php/', $_SERVER['REQUEST_URI'] )
        ) {
            return true;
        }
        return false;
    }

    function check_logged_in() {
        $logged_in = false;
        if ( isset( $_COOKIE ) )
            foreach ( $_COOKIE as $key => $val )
                if ( preg_match( '/^wordpress_logged_in_/', $key ) ) {
                    $logged_in = true;
                    $this->cookie_val = str_replace( 'wordpress_logged_in_', '', $key );
                    break;
                }
        return $logged_in;
    }

    function script() {
        $jqc_url = plugins_url( 'js/jquery.cookie.js', __FILE__ );
        $jqc_ver = '1.4.0';
        wp_enqueue_script( 'jquery-cookie', $jqc_url, array( 'jquery' ), $jqc_ver, true );
        $script_url = plugins_url( 'js/script.js', __FILE__ );
        $script_ver = date( 'YmdHis', filemtime( plugin_dir_path( __FILE__ ) . 'js/script.js' ) );
        wp_enqueue_script( 'mmsf-theme-switch', $script_url, array( 'jquery-cookie' ), $script_ver, true );
        $params = array(
            'switch_key' => $this->cookie_key,
            'switch_val' => $this->cookie_val
        );
        wp_localize_script( 'mmsf-theme-switch', 'MMSF_THEME_SWITCH', $params );
    }

    function select_theme() {
        $theme = $this->theme_0;
        if ( $this->check_logged_in() ) {
            $key = $this->cookie_key;
            $val = $this->cookie_val;
            if ( !isset( $_COOKIE[$key] ) || ( $_COOKIE[$key] !== $val ) )
                $theme = $this->theme_1;
            else
                $theme = $this->theme_2;
        }
        return $theme;
    }

    function theme_stylesheet() {
        $selected_theme = wp_get_theme( $this->select_theme() );
        if ( $selected_theme->exists() )
            return $selected_theme['Stylesheet'];
    }

    function theme_template() {
        $selected_theme = wp_get_theme( $this->select_theme() );
        if ( $selected_theme->exists() )
            return $selected_theme['Template'];
    }

    function theme_switch() {
        if ( $this->check_front_end() ) {
            add_filter( 'stylesheet', array( $this, 'theme_stylesheet' ) );
            add_filter( 'template', array( $this, 'theme_template' ) );
        }
    }
/*
    static function theme_dir_name() {
        $dir_name = str_replace( WP_CONTENT_DIR . '/themes/', '', STYLESHEETPATH );
        return $dir_name;
    }

    function remove_cookie() {
        if ( !is_user_logged_in() ) {
            unset( $_COOKIE[$this->cookie_key] );
            header( 'Location: //' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
            die();
        }
    }

    function theme_init() {
        if ( ( $this->theme_2 !== $this->theme_0 ) && ( $this->theme_dir_name() === $this->theme_2 ) )
            add_action( 'template_redirect', array( $this, 'remove_cookie' ) );
    }
*/
    function __construct() {
        $this->theme_switch();
        //$this->theme_init();
        if ( $this->check_logged_in() )
            $this->script();
    }

}

new MmsfThemeSwitch();