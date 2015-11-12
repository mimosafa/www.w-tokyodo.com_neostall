<?php

/**
 * ヘッダーにスタイルを記述
 */
function mmsf_head() {
    $style = '';
    if ( is_admin_bar_showing() ) {
        $style .= ".navbar-fixed-top{top:32px !important;}\n";
    }

    // my plugin filter
    $style = apply_filters( 'mmsf_head', $style );

    if ( $style ) {
        $style = "<style>\n" . $style . "</style>\n";
        echo $style;
    }
}
add_action( 'wp_head', 'mmsf_head' );

/**
 * コンテナートップになにか表示する用
 * - ’neosystem_container_top'に add_action で関数をフックしてください
 */
function neosystem_container_top() {
    do_action( 'neosystem_container_top' );
}
/**
 * パンくずリスト
 */
function neosystem_breadcrumb() {
    if ( is_home() )
        return;

    global $post;
    $brdcrmb  = '<ol class="breadcrumb">';
    $brdcrmb .= '<li><a href="' . home_url() . '"><i class="fa fa-home"></i></a></li>';
    if ( is_post_type_archive() )
        $brdcrmb .= '<li class="active">' . esc_html( post_type_archive_title( '', false ) ) . '</li>';
    if ( is_singular( array( 'kitchencar', 'vendor', 'space', 'activity', 'management', 'news' ) ) ) {
        $brdcrmb .= '<li><a href="' . get_post_type_archive_link( $post->post_type ) . '">' . get_post_type_object( $post->post_type )->label . '</a></li>';
        $brdcrmb .= '<li class="active">' . esc_html( $post->post_title ) . '</li>';
    } elseif ( is_singular( 'event' ) || is_tax( 'series' ) ) {
        $series = get_the_series( $post );
        if ( is_singular() ) {
            $brdcrmb .= '<li><a href="' . get_post_type_archive_link( 'event' ) . '">' . get_post_type_object( 'event' )->label . '</a></li>';
            $brdcrmb .= '<li><a href="' . get_term_link( $series ) . '">' . get_the_series( $post )->name . '</a></li>';
            $brdcrmb .= '<li class="active">' . esc_html( $post->post_title ) . '</li>';
        } elseif ( is_tax() ) {
            $brdcrmb .= '<li><a href="' . get_post_type_archive_link( 'event' ) . '">' . get_post_type_object( 'event' )->label . '</a></li>';
            $brdcrmb .= '<li class="active">' . $series->name . '</li>';
        }
    }
    $brdcrmb .= '</ol>';

    echo $brdcrmb;
}
add_action( 'neosystem_container_top', 'neosystem_breadcrumb', 99 );

/**
 * グローバルナビゲーション表示
 *
 * @depends on Bootstrap 3.x, and font-awesome 4.x
 */
function neosystem_nav_menus( $array = null, $echo = true ) {
    global $wp_post_types;
    if ( !isset( $array ) ) {
        $array = array(
            array( 'post_type' => 'news', 'fa' => 'info' ),
            array(
                'title' => '管理',
                'children' => array(
                    array( 'post_type' => 'activity' ),
                    array( 'post_type' => 'management' )
                ),
                'fa' => 'wrench'
            ),
            array(
                'title' => 'スケジュール',
                'children' => array(
                    array( 'post_type' => 'event' ),
                    array( 'post_type' => 'space' )
                ),
                'fa' => 'calendar'
            ),
            array(
                'title' => 'ネオ屋台',
                'children' => array(
                    array( 'post_type' => 'vendor' ),
                    array( 'post_type' => 'kitchencar' )
                ),
                'fa' => 'truck'
            )
        );
    }
    $html = '';
    foreach ( $array as $args ) {
        if ( isset( $args['children'] ) ) {
            $html .= '<li class="dropdown">' . "\n";
            $html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
            if ( isset( $args['fa'] ) ) {
                $html .= sprintf( '<i class="fa fa-%s"></i> ', $args['fa'] );
            }
            $html .= $args['title'];
            $html .= ' <b class="caret"></b></a>' . "\n";
            $html .= '<ul class="dropdown-menu">' . "\n";
            $html .= neosystem_nav_menus( $args['children'], false );
            $html .= "</ul>\n";
            $html .= "</li>\n";
            continue;
        }
        $pt = $args['post_type'];
        $html .= '<li';
        if ( is_post_type_archive( $pt ) || is_singular( $pt ) )
            $html .= ' class="active"';
        $html .= '><a href="';
        $html .= get_post_type_archive_link( $pt );
        $html .= '">';
        if ( isset( $args['fa'] ) ) {
            $html .= sprintf( '<i class="fa fa-%s"></i> ', $args['fa'] );
        }
        $html .= $wp_post_types[$pt]->labels->singular_name;
        $html .= "</a></li>\n";
    }
    if ( $echo )
        echo $html;
    else
        return $html;
}






class MmsfOutput {

    protected function _id_( $post ) {
        return absint( $post->ID );
    }
    protected function _title_( $post ) {
        return get_the_title( $post );
    }
    protected function _post_date_( $post ) {
        return $post->post_date;
    }
    protected function _post_parent_( $post ) {
        return $post->post_parent;
    }
    protected function _term_( $post, $taxonomy ) {
        $array = get_the_terms( $post->ID, $taxonomy );
        $obj = array_pop( $array );
        return esc_html( $obj->name );
    }
    protected function _term_has_link_( $post, $taxonomy ) {
        $array = get_the_terms( $post->ID, $taxonomy );
        $obj = array_pop( $array );
        return sprintf( '<a href="%s">%s</a>', get_term_link( $obj ), esc_html( $obj->name ) );
    }
    protected function _multi_( $post, $arg ) {
        if ( is_array( $arg ) ) {
            foreach ( $arg as $key ) {
                if ( method_exists( $this, $key ) ) {
                    return $this->$key( $post );
                } else {
                    $return = $this->get_post_meta( $post, $key );
                    if ( !empty( $return ) )
                        return $return;
                    else
                        continue;
                }
            }
        }
        return '';
    }

    protected function get_post_meta( $post, $key ) {
        $id = $post->ID;
        return get_post_meta( $id, $key, true );
    }

    /**
     * format function
     */
    protected function date( $str, $var ) {
        return date( $str, strtotime( $var ) );
    }
    protected function string( $str, $var ) {
        return sprintf( $str, $var );
    }
    protected function title_by_id( $var ) {
        if ( !$id = absint( $var ) )
            return '';
        $post = get_post( $id );
        return isset( $post->post_title ) ? $post->post_title : '';
    }

}

/**
 * Archive
 */
class MmsfArchiveListItems extends MmsfOutput {

    protected $arg;
    protected $items = array(
        'space' => array(
            '#' => array(
                'key' => 'serial'
            ),
            'スペース' => array(
                'key' => '_title_',
                'href' => 'permalink'
            ),
            '形態' => array(
                'key' => 'sche_type',
                'value_list' => array(
                    'fixed'  => '',
                    'rotate' => 'ローテーション',
                    'flex'   => '変動スケジュール'
                )
            ),
            '状態' => array(
                'key' => 'phase',
                'value_list' => array(
                    1 => '',
                    8 => '休止中',
                    9 => '終了'
                )
            ),
            '地域' => array(
                'key' => '_term_has_link_',
                'key_args' => 'region'
            )
        ),
        'kitchencar' => array(
            '#' => array(
                'key' => 'serial'
            ),
            'キッチンカー名 <small>(WEB表示)</small>' => array(
                'key' => '_title_'
            ),
            '事業者名' => array(
                'key' => '_post_parent_',
                'format' => 'title_by_id',
                'href' => 'this'
            ),
            '車両No.' => array(
                'key' => 'vin'
            ),
            '長さ' => array(
                'key' => 'length',
                'format' => array( 'string', '%d <small>mm</small>' )
            ),
            '幅' => array(
                'key' => 'width',
                'format' => array( 'string', '%d <small>mm</small>' )
            ),
            '高さ' => array(
                'key' => 'height',
                'format' => array( 'string', '%d <small>mm</small>' )
            ),
            '状態' => array(
                'key' => 'phase',
                'value_list' => array(
                    0 => '見込',
                    1 => '',
                    7 => '非稼働',
                    8 => '譲渡',
                    9 => '廃車'
                )
            ),
        ),
        'management' => array(
            'Post Date' => array(
                'key' => '_post_date_',
                'format' => array( 'date', 'n/j D.' )
            ),
            '種別' => array(
                'key' => 'type',
                'value_list' => array(
                    'off' => '休み'
                )
            ),
            'タイトル' => array(
                'key' => '_title_',
                'href' => 'permalink'
            )
        ),
        'vendor' => array(
            '#' => array(
                'key' => 'serial'
            ),
            '事業者名' => array(
                'key' => '_title_',
                'href' => 'permalink'
            ),
            '会社名' => array(
                'key' => 'organization'
            ),
            'cars' => array(
                'key' => '_num_kitchencars',
                'value_list' => array( 0 => '' )
            ),
            'menus' => array(
                'key' => '_num_menuitems',
                'value_list' => array( 0 => '' )
            )
        ),
        'event' => array(
            '開催日' => array(
                'key' => 'day',
                'format' => array( 'date', 'n/j D.' )
            ),
            'シリーズ' => array(
                'key' => '_term_has_link_',
                'key_args' => 'series'
            ),
            'イベント名' => array(
                'key' => '_title_',
                'href' => 'permalink'
            ),
            '地域' => array(
                'key' => '_term_',
                'key_args' => 'region'
            )
        ),
        'activity' => array(
            '出店日' => array(
                'key' => 'day',
                'format' => array( 'date', 'n/j D.' )
            ),
            'キッチンカー' => array(
                'key' => 'actOf',
                'format' => 'title_by_id'
            ),
            '場所' => array(
                'key' => '_multi_',
                'key_args' => array(
                    'space_id',
                    'event_id'
                ),
                'format' => 'title_by_id'
            ),
            'ID' => array(
                'key' => '_id_',
                'href' => 'permalink'
            )
        ),
        '__default' => array(
            'Date' => array(
                'key' => '_multi_',
                'key_args' => array(
                    'day',
                    '_post_date_'
                ),
                'format' => array( 'date', 'Y/n/j D.' )
            ),
            'Post Title' => array(
                'key' => '_title_',
                'href' => 'permalink'
            ),
            'ID' => array(
                'key' => '_id_'
            )
        )
    );
    public $labels = array();

    function __construct() {
        if ( is_post_type_archive() ) {
            global $post_type;
            $this->arg = $post_type;
        }
        $this->set_labels( $this->arg );
    }

    function set_labels( $arg ) {
        $array = isset( $this->items[$arg] ) ? $this->items[$arg] : $this->items['__default'];
        $labels = array();
        foreach ( $array as $key => $args ) {
            $labels[] = $key;
        }
        $this->labels = $labels;
    }

    function return_items_array() {
        global $post;
        $array = array();
        $keys = isset( $this->items[$this->arg] ) ? $this->items[$this->arg] : $this->items['__default'];
        foreach ( $keys as $args ) {
            $var = '';
            $href = '';
            if ( isset( $args['key'] ) && !empty( $args['key'] ) ) {
                $method = $args['key'];
                if ( !method_exists( 'MmsfOutput', $method ) ) {
                    $var .= $this->get_post_meta( $post, $method );
                } else {
                    $method_arg = isset( $args['key_args'] ) ? $args['key_args'] : null;
                    $var .= $this->$method( $post, $method_arg );
                }
                if ( isset( $args['value_list'] ) && !empty( $args['value_list'] ) ) {
                    $var = isset( $args['value_list'][$var] ) ? $args['value_list'][$var] : $var;
                }
                if ( !empty( $var ) ) {
                    if ( isset( $args['href'] ) && !empty( $args['href'] ) ) {
                        if ( 'permalink' === $args['href'] ) {
                            $href = esc_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) );
                        } elseif ( 'this' === $args['href'] ) {
                            $href = esc_url( apply_filters( 'the_permalink', get_permalink( $var ) ) );
                        }
                    }
                    if ( isset( $args['format'] ) && !empty( $args['format'] ) ) {
                        if ( is_array( $args['format'] ) ) {
                            $format_fnc = $args['format'][0];
                            $var = $this->$format_fnc( $args['format'][1], $var );
                        } else {
                            $format_fnc = $args['format'];
                            $var = $this->$format_fnc( $var );
                        }
                    }
                    if ( !empty( $href ) ) {
                        $var = sprintf( '<a href="%s">%s</a>', $href, $var );
                    }
                }
            }
            $array[] = $var;
        }
        return $array;
    }

}


/**
 * loop for archive
 */
function mmsf_archive_loop() {

    global $wp_query;

    if ( have_posts() ) :

        $data = array(
            'label' => array(),
            'items' => array()
        );
        $class = new MmsfArchiveListItems();
        $data['label'] = $class->labels;

        while ( have_posts() ) : the_post();

            $args = array();
            $args = $class->return_items_array();
            $data['items'][] = $args;

            //do_action( 'mmsf_loop_action', $_title, $_permalink );

        endwhile;

        wp_localize_script( 'theme-script', 'NEOYATAI_POSTS', $data );

    endif;

}

/**
 * Single
 */
class NeosystemSingular {

    /**
     * シングルページの構造設計
     */
    public static $setting = array(

        // キッチンカー
        'kitchencar' => array(
            'management' => array(
                'label' => '管理情報',
                'column' => array(
                    array(
                        'class' => 'col-sm-4',
                        'block' => array(
                            'default' => array(
                                'label' => '基本情報',
                                'editable' => true,
                                'row' => array(
                                    array(
                                        'key' => 'phase',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'raw_title',
                                        'label' => '車両名',
                                        'editable' => false
                                    ),
                                    array(
                                        'key' => 'name',
                                        'editable' => true
                                    )
                                )
                            ),
                            'spec' => array(
                                'label' => '車両スペック',
                                'editable' => true,
                                'row' => array(
                                    array(
                                        'key' => 'vin',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'length',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'width',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'height',
                                        'editable' => true
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'class' => 'col-sm-8',
                        'block' => array()
                    )
                )
            )
        ),

        // スペース
        'space' => array(
            'management' => array(
                'label' => '管理情報',
                'column' => array(
                    array(
                        'class' => 'col-sm-4',
                        'block' => array(
                            'default' => array(
                                'label' => '基本情報',
                                'editable' => true,
                                'row' => array(
                                    array(
                                        'key' => 'serial',
                                        'editable' => false
                                    ),
                                    array(
                                        'key' => 'row_title',
                                        'label' => 'スペース名称',
                                        'editable' => false
                                    ),
                                    array(
                                        'key' => 'phase',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'name',
                                        'editable' => true
                                    )
                                )
                            ),
                            'location' => array(
                                'label' => '所在地情報',
                                'editable' => true,
                                'row' => array(
                                    array(
                                        'key' => 'region',
                                        'editable' => false
                                    ),
                                    array(
                                        'key' => 'address',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'site',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'areaDetail',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => 'latlng',
                                        'editable' => true
                                    )
                                )
                            ),
                            'time' => array(
                                'label' => '営業時間',
                                'editable' => true,
                                'row' => array(
                                    array(
                                        'key' => array( 'starting', 'startingPending' ),
                                        'label' => '開始',
                                        'editable' => true
                                    ),
                                    array(
                                        'key' => array( 'ending', 'endingPending' ),
                                        'label' => '終了',
                                        'editable' => true
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'class' => 'col-sm-8',
                        'block' => array()
                    )
                )
            ),
            /*
            'calendar' => array(
                'label' => 'カレンダー'
            )
            */
        ),

    );
    private $post_type;
    private $structure;
    private $tabnav = null;
    private $contents = null;

    private function tabnav_construct() {
        $tabnav = array(
            'element' => 'ul',
            'class' => 'nav nab-tab'
        );
        $inner = array();
        foreach( $this->structure as $key => $val ) {
            $inner[] = array(
                'element' => 'li',
                'inner' => array(
                    'element' => 'a',
                    'href' => '#' . $key,
                    'data-toggle' => 'tab',
                    'inner' => $val['label']
                )
            );
        }
        $tabnav['inner'] = $inner;
        $this->tabnav = $tabnav;
    }

    private function block_construct( $array ) {
        $blocks = array();
        foreach ( $array as $key => $arg ) {
            $element_id = sprintf( '%s-%s', $this->post_type, $key );
            $h4 = array(
                'element' => 'h4',
                'inner' => array( $arg['label'] )
            );
            if ( true === $arg['editable'] ) {
                $h4['inner'][1] = array(
                    'element' => 'small',
                    'inner' => array(
                        'element' => 'a',
                        'href' => '#',
                        'class' => 'mmsf-edit-toggle',
                        'data-target' => '#' . $element_id,
                        'inner' => array(
                            'element' => 'i',
                            'class' => 'fa fa-pencil'
                        )
                    )
                );
            }
            $blocks[] = $h4;
            $table = array(
                'element' => 'table',
                'class' => 'table table-bordered',
                'inner' => array(
                    'element' => 'tbody'
                )
            );
            $rows = array();
            foreach ( $arg['row'] as $row ) {
                $rows[] = array(
                    'element' => 'tr',
                    'inner' => array(
                        array(
                            'element' => 'th',
                            'inner' => isset( $row['label'] ) ? $row['label'] : $row['key']
                        ),
                        array(
                            'element' => 'td',
                            'inner' => $row['key']
                        )
                    )
                );
            }
            $table['inner']['inner'] = $rows;
            $blocks[] = array(
                'element' => 'div',
                'id' => $this->post_type . '-' . $key,
                'inner' => $table
            );
        }
        return $blocks;
    }

    private function column_construct( $array ) {
        $columns = array();
        foreach ( $array as $arg ) {
            $column = array(
                'element' => 'div',
                'class' => $arg['class'],
                'inner' => $this->block_construct( $arg['block'] )
            );
            $columns[] = $column;
        }
        return $columns;
    }

    private function single_content_construct( $array ) {
        if ( isset( $array['column'] ) && ( 1 < count( $array['column'] ) ) ) {
            $single_content = array(
                'element' => 'div',
                'class' => 'row',
                'inner' => $this->column_construct( $array['column'] )
            );
        } elseif ( isset( $array['column'] ) ) {
            $single_content = $this->block_construct( $array['column']['block'] );
        } else {
            $single_content = $this->block_construct( $array['block'] );
        }
        return $single_content;
    }

    private function contents_construct( $setting ) {
        if ( 1 < count( $setting ) ) {
            $this->tabnav_construct();
            $contents = array(
                'element' => 'div',
                'class' => 'tab-content',
                'inner' => array()
            );
            foreach ( $setting as $key => $array ) {
                $contents['inner'][] = array(
                    'element' => 'div',
                    'class' => 'tab-pane',
                    'id' => $key,
                    'inner' => $this->single_content_construct( $array )
                );
            }
        } else {
            foreach ( $setting as $key => $array ) {
                $contents = array(
                    'element' => 'div',
                    'id' => $key,
                    'inner' => $this->single_content_construct( $array )
                );
            }
        }
        $this->contents = $contents;
    }

    function init() {
        global $post;
        $this->post_type = $post->post_type;
        if ( isset( self::$setting[$post->post_type] ) && !empty( self::$setting[$post->post_type] ) )
            $this->contents_construct( self::$setting[$post->post_type] );
        if ( !empty( $this->tabnav ) )
            wp_localize_script( 'theme-script', 'TABNAV', $this->tabnav );
        if ( !empty( $this->contents ) )
            wp_localize_script( 'theme-script', 'CONTENT', $this->contents );
    }

}