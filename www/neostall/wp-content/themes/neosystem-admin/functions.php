<?php

/****
 * REQUIRE PHPs
 **/
require_once( STYLESHEETPATH . '/functions/scripts-styles.php' );
require_once( STYLESHEETPATH . '/functions/queries.php' );
require_once( STYLESHEETPATH . '/functions/ajax-load.php' );
require_once( STYLESHEETPATH . '/functions/ajax-action.php' );
require_once( STYLESHEETPATH . '/functions/functions.php' );

/****
 * include modules
 **/
get_template_part( 'mod/edit-space' );
get_template_part( 'mod/edit-kitchencar' );
get_template_part( 'mod/edit-order' );
get_template_part( 'mod/add-menu_item' );
get_template_part( 'mod/setup' );

/****
 * remove admin-bar
function kill_admin_bar() {
    return false;
}
add_filter( 'show_admin_bar', 'kill_admin_bar', 1000 );
 **/

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

/****
 * custom fields label
 **/
$label_phase = array(
    'activity' => array(
        1 => '応募中',
        2 => '出店',
        8 => 'キャンセル',
        9 => 'お休み'
    ),
    'space' => array(
        0 => '見込み',
        1 => 'アクティブ',
        //2 => '期間限定（短期）',
        8 => '休止',
        9 => '終了',
    ),
    'kitchencar' => array(
        1 => 'アクティブ',
        9 => '非稼働'
    )
);
function get_cf_phase_label( $post_id = 0 ) {
    global $label_phase;
    $post = get_post( $post_id );
    $phase = (int) $post->phase;
    $post_type = $post->post_type;
    $label = $label_phase[$post_type][$phase];
    return $label;
}