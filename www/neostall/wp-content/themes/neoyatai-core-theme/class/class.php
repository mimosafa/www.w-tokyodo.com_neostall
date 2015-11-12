<?php

/**
 * scripts and styles
 */
class MmsfScriptsStyles {

    public $styles = array(
        'NEOSYSTEM' => array(
            array(
                'handle' => 'bootstrap',
                'src'    => '//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css',
                'ver'    => '3.0.3'
            ),
            array(
                'handle' => 'font-awesome',
                'src'    => '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css',
                'deps'   => array( 'bootstrap' ),
                'ver'    => '4.0.3'
            ),
            array(
                'handle' => 'select2',
                'src'    => '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2.min.css',
                'ver'    => '3.4.5'
            ),
            array(
                'handle' => 'select2-bootstrap',
                'src'    => '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2-bootstrap.css',
                'deps'   => array( 'select2', 'bootstrap' ),
                'ver'    => '3.4.5'
            )
        )
    );
    public $scripts = array(
        '_common' => array(
            array(
                'handle'    => 'jquery',
                'src'       => '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
                'ver'       => '1.10.2',
                'in_footer' => true
            ),
            array(
                'handle'    => 'modernizr',
                'src'       => '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js',
                'ver'       => '2.7.1'
            )
        ),
        'NEOSYSTEM' => array(
            array(
                'handle'    => 'bootstrap',
                'src'       => '//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js',
                'deps'      => array( 'jquery' ),
                'ver'       => '3.0.3',
                'in_footer' => true
            ),
            array(
                'handle'    => 'select2',
                'src'       => '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2.min.js',
                'deps'      => array( 'jquery' ),
                'ver'       => '3.4.5',
                'in_footer' => true
            )
        )
    );

    function register() {

        $current_theme = wp_get_theme()->get( 'Name' );

        $styles = array();
        if ( isset( $this->styles['_common'] ) )
            $styles = array_merge( $styles, $this->styles['_common'] );
        if ( isset( $this->styles[$current_theme] ) )
            $styles = array_merge( $styles, $this->styles[$current_theme] );
        if ( !empty( $styles ) )
            $this->styles_fnc( $styles, true );

        $scripts = array();
        if ( isset( $this->scripts['_common'] ) )
            $scripts = array_merge( $scripts, $this->scripts['_common'] );
        if ( isset( $this->scripts[$current_theme] ) )
            $scripts = array_merge( $scripts, $this->scripts[$current_theme] );
        if ( !empty( $scripts ) )
            $this->scripts_fnc( $scripts, true );

    }

    function styles_fnc( $array, $enqueue = false ) {

        if ( empty( $array ) || !is_array( $array) )
            return;

        $default = array(
                'handle' => '',
                'src'    => '',
                'deps'   => array(),
                'ver'    => false,
                'media'  => 'all'
        );
        $string = $enqueue ? 'enqueue' : 'register';
        $behavior = "wp_{$string}_style";
        foreach ( $array as $key => $style ) {
            $style = array_merge( $default, $style );
            if ( !$style['handle'] || !$style['src'] )
                continue;
            if ( wp_style_is( $style['handle'], 'registered' ) )
                wp_deregister_style( $style['handle'] );
            $behavior(
                $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']
            );
        }

    }

    function scripts_fnc( $array, $enqueue = false ) {

        if ( empty( $array ) || !is_array( $array) )
            return;

        $default = array(
                'handle'    => '',
                'src'       => '',
                'deps'      => array(),
                'ver'       => '',
                'in_footer' => false
        );
        $string = $enqueue ? 'enqueue' : 'register';
        $behavior = "wp_{$string}_script";
        foreach ( $array as $script ) {
            $script = array_merge( $default, $script );
            if ( !$script['handle'] || !$script['src'] )
                continue;
            if ( wp_script_is( $script['handle'], 'registered' ) )
                wp_deregister_script( $script['handle'] );
            $behavior(
                $script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer']
            );
        }

    }

    function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'register' ), 0 );
    }

    function __construct() {
        $this->init();
    }

}
new MmsfScriptsStyles();

/**
 * queries
 */
class MmsfQuery {

    private $query_rules = array(
        'post_type' => array(
            'vendor' => array(
                'posts_per_page' => -1,
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_key' => 'serial'
            ),
            'kitchencar' => array(
                'posts_per_page' => -1,
                'orderby' => 'meta_value_num menu_order',
                'order' => 'ASC',
                'meta_key' => 'serial'
            ),
            'space' => array(
                'posts_per_page' => -1,
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_key' => 'serial'
            ),
            'event' => array(
                'posts_per_page' => 20,
                'orderby' => 'meta_value',
                'order' => 'DESC',
                'meta_key' => 'day'
            ),
            'activity' => array(
                'posts_per_page' => 200,
                'orderby' => 'meta_value',
                'meta_key' => 'day'
            ),
        ),
        'taxonomy' => array(
            'series' => array(
                'posts_per_page' => 20,
                'orderby' => 'meta_value',
                'meta_key' => 'day'
            )
        ),
        'home' => array(
            'post_type' => 'news'
        )
    );

    function __construct() {
        add_action( 'pre_get_posts', array( $this, 'init' ) );
    }

    function set_query( $query, $array ) {
        foreach ( $array as $key => $val ) {
            $query->set( $key, $val );
        }
    }

    function init( $query ) {

        if ( is_admin() && !$query->is_main_query() )
            return;

        if ( $query->is_home() ) {
            $array = $this->query_rules['home'];
            if ( !empty( $array ) )
                $this->set_query( $query, $array );
            return;
        } elseif ( $query->is_post_type_archive() ) {
            $fn = 'is_post_type_archive';
            $arrays = $this->query_rules['post_type'];
        } elseif ( $query->is_tax() ) {
            $fn = 'is_tax';
            $arrays = $this->query_rules['taxonomy'];
        }

        if ( !isset( $arrays ) )
            return;

        foreach ( $arrays as $arg => $array ) {
            if ( $query->$fn( $arg ) ) {
                $this->set_query( $query, $array );
                return;
            }
        }

    }

}
new MmsfQuery();