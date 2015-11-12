<?php

class NeoyataiDataFormat {

    public static $_default = array(
        'form_element' => 'input',
        'input_type' => 'text',
        'required' => false
    );

    public static $formats = array(
        'kitchencar' => array(
            'phase' => array(
                'label' => '稼働状況',
                'value_list' => array(
                    0 => '見込',
                    1 => '稼働中',
                    7 => '非稼働',
                    8 => '譲渡',
                    9 => '廃車'
                ),
                'form_element' => 'select',
                'required' => true
            ),
            'name' => array(
                'label' => 'WEB表示',
                'value_not_exists' => '<span class="text-muted">* 車両名と同一</span>',
            ),
            'vin' => array(
                'label' => '車両No.'
            ),
            'length' => array(
                'label' => '長さ',
                'suffix' => ' mm',
                'input_type' => 'number'
            ),
            'width' => array(
                'label' => '幅',
                'suffix' => ' mm',
                'input_type' => 'number'
            ),
            'height' => array(
                'label' => '高さ',
                'suffix' => ' mm',
                'input_type' => 'number'
            )
        ),
        'space' => array(
            'serial' => array(
                'label' => '#',
            ),
            'phase' => array(
                'label' => '稼働状況',
                'value_list' => array(
                    0 => '見込み',
                    1 => '営業中',
                    8 => '休止',
                    9 => '終了'
                ),
                'form_element' => 'select'
            ),
            'publication' => array(
                'label' => 'WEB公開',
                'value_list' => array(
                    1 => '公開'
                ),
                'value_not_exists' => '非公開',
                'value_exeption' => '公開'
            ),
            'address' => array(
                'label' => '補記住所'
            ),
            'site' => array(
                'label' => '施設'
            ),
            'areaDetail' => array(
                'label' => '詳細エリア'
            ),
            'latlng' => array(
                'label' => '緯度経度'
            ),
            'starting' => array(
                'label' => '営業開始',
                'input_type' => 'time'
            ),
            'ending' => array(
                'label' => '営業終了',
                'input_type' => 'time'
            ),
            'startingPending' => array(
                'value_list' => array(
                    0 => '',
                    1 => ' <small>( 予定 )</small>'
                ),
                'input_type' => 'checkbox'
            ),
            'sche_type' => array(
                'label' => '',
                'value_list' => array(
                    'fixed' => '固定スケジュール',
                    'rotate' => 'ローテーション',
                    'flex' => '流動スケジュール'
                ),
                'form_element' => 'select'
            )
        )
    );

    public static function get_format( $post_type = '' ) {
        $post_type = !$post_type ? get_post_type() : $post_type;
        return isset( self::$formats[$post_type] ) ? self::$formats[$post_type] : null;
    }

    public static function set_element_data() {}

}

class NeoyataiFunction {

    /**
     * add filter 'the_title' ... WP Function
     */
    function the_title( $title, $id ) {
        $post_type = get_post_type( $id );

        if ( 'kitchencar' === $post_type ) {

            if ( is_post_type_archive( 'kitchencar' ) ) {
                $permalink = esc_url( apply_filters( 'the_permalink', get_permalink( $id ) ) );
                $title = sprintf( '<a href="%s">%s</a>', $permalink, $title );
                if ( $name = get_post_meta( $id, 'name', true ) )
                    return $title . ' <small>( ' . $name . ' )</small>';
            }

            if ( is_singular( 'kitchencar' ) ) {
                $parent_id = get_post( $id )->post_parent;
                $parent = sprintf( '<a href="%s">%s</a>',
                    esc_url( get_permalink( $parent_id ) ),
                    esc_html( self::get_raw_title( $parent_id ) )
                );
                return $title . ' <small> / 事業者名: ' . $parent . '</small>';
            }

        } elseif ( 'event' === $post_type ) {

            if ( is_singular( 'event' ) ) {
                $series = get_the_series( $id )->name;
                return $series . ' / ' . $title;
            }

        }

        return $title;
    }

    /**
     * add filter 'get_custom_field' ... my Function
     */
    function get_custom_field( $value, $key, $post ) {
        $post_type = get_post_type( $post );

        $format = NeoyataiDataFormat::get_format( $post_type )[$key];
        if ( !isset( $format ) ) {
            return $value;
        } else {
            $format = array_merge( NeoyataiDataFormat::$_default, $format );
        }

        if ( is_singular() ) {

            $attr = array();
            if ( isset( $format['form_element'] ) && !empty( $format['form_element'] ) ) {
                $array = array(
                    'element' => '',
                    'class' => 'form-control input-sm', // Bootstrap
                    'data-value' => $value
                );
                if ( isset( $format['required'] ) && ( true === $format['required'] ) )
                    $array['required'] = 'required';

                if ( ( 'input' === $format['form_element'] ) && isset( $format['input_type'] ) && !empty( $format['input_type'] ) ) {
                    $array['element'] = 'input';
                    $array['type'] = $format['input_type'];
                    $array['value'] = $value;
                    $attr['data-element'] = sprintf( "'%s'", json_encode( $array ) );
                } elseif ( ( 'select' === $format['form_element'] ) && isset( $format['value_list'] ) && !empty( $format['value_list'] ) ) {
                    $array['element'] = 'select';
                    $array['inner'] = array();
                    foreach ( $format['value_list'] as $key => $val ) {
                        $option = array( 'element' => 'option' );
                        $option['value'] = $key;
                        $option['inner'] = $val;
                        if ( $key == $value )
                            $option['selected'] = 'selected';
                        $array['inner'][] = $option;
                    }
                    $attr['data-element'] = sprintf( "'%s'", json_encode( $array ) );
                }
            }

        }

        if ( isset( $format['value_list'] ) && !empty( $format['value_list'] ) ) {
            if ( isset( $format['value_list'][$value] ) )
                $value = $format['value_list'][$value];
            else
                $value = !isset( $format['value_exeption'] ) ? $value : $format['value_exeption'];
        }

        if ( ( '' === $value ) && isset( $format['value_not_exists'] ) )
            $value = $format['value_not_exists'];

        if ( isset( $format['suffix'] ) )
            $value = $value . $format['suffix'];

        if ( isset( $attr ) && !empty( $attr ) ) {
            $span = '<span';
            foreach ( $attr as $key => $val ) {
                $span .= ' ' . esc_attr( $key ) . '=' . $val;
            }
            $span .= '>';
            $value = $span . $value . '</span>';
        }

        return $value;

    }

    private function filters() {
        add_filter( 'the_title', array( $this, 'the_title' ), 10, 2 );
        add_filter( 'get_custom_field', array( $this, 'get_custom_field' ), 10, 3 );
    }

    function __construct() {
        $this->filters();
    }

    /**
     * get post_title, pass no filter.
     */
    public static function get_raw_title( $post = 0 ) {
        $post = get_post( $post );
        return isset( $post->post_title ) ? $post->post_title : '';
    }

}
new NeoyataiFunction();

if ( !function_exists( 'get_raw_title' ) ) {
    function get_raw_title( $post = 0 ) {
        return NeoyataiFunction::get_raw_title( $post );
    }
}

if ( !function_exists( 'get_custom_field' ) ) {

    /**
     * カスタムフィールドの取得 ($single の指定なし)
     *
     * @uses absint
     * @uses get_post
     * @uses get_post_meta
     * @uses apply_filters
     *
     * @param string $key CustomField's Key
     * @param int|$WP_Post $post
     * @return string|array $value Custom Field's value, if allowed multiple, return Array, if not return single value
     */
    function get_custom_field( $key, $post = '' ) {
        $post = get_post( $post );
        $value = get_post_meta( $post->ID, $key, false );
        if ( !$value ) {
            $value = '';
        } else {
            $value = ( 2 > count( $value ) ) ? $value[0] : $value;
        }

        /* my plugin filter */
        return apply_filters( 'get_custom_field', $value, $key, $post );
    }

}

if ( !function_exists( 'get_the_series' ) ) {

    /**
     * カスタムタクソノミー、 series の取得
     *
     * @param int|$WP_post $post
     * @return object
     */
    function get_the_series( $post = '' ) {
        $series = get_the_terms( $post, 'series' );
        if ( !$series )
            return;
        $series = array_pop( $series );

        return apply_filters( 'get_the_series', $series, $post );
    }

}

if ( !function_exists( 'get_the_region' ) ) {

    /**
     * カスタムタクソノミー、 region の取得
     *
     * @param int|$WP_post $post
     * @return object
     */
    function get_the_region( $post = '' ) {
        $region = get_the_terms( $post, 'region' );
        if ( !$region )
            return;
        $region = array_pop( $region );

        return apply_filters( 'get_the_region', $region, $post );
    }

}


/*
class NeoyataiPost {

    function __construct( $post ) {
        $post = get_post( $post, 'ARRAY_A' );
        $args = array_merge( $post, MmsfClass::get_post_custom( $post->ID ) );
        foreach ( $args as $key => $val )
            $this->$key = $val;
    }

}

class MmsfClass {

    public static function get_post_custom( $post_id ) {
        $func = function( $array ) {
            $var = ( 2 > count( $array ) ) ? $array[0] : $array;
            if ( is_numeric( $var ) )
                $var = $var + 0;
            elseif ( is_serialized( $var ) ) // WordPress' Function.
                $var = unserialize( $var );
            return $var;
        };
        return array_map( $func, get_post_custom( $post_id ) );
    }

}
*/