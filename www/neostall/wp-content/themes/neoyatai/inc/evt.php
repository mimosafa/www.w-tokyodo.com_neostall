<?php
global $series_slug;
global $name, $menu_arr, $capID; // for included module 'inc/nyt-span4.php'
// activities
$child_query = array(
    'numberposts' => -1,
    'post_type' => 'activity',
    'post_status' => 'publish',
    'post_parent' => $post->ID
);
$activities = get_children( $child_query, OBJECT );
if ( $activities ) {
    foreach ( $activities as $activity ) {
        $obj_id = $activity->ID;
        $kit_id = (int)$activity->actOf;
        // name
        $name = get_the_display_name( $kit_id );
        // menu content
        $menu_arr = legacy_get_the_menuitems( $obj_id, 'event', $series_slug );
        // Add ID for HoverCaption (jQuery plugin)
        $capID = 'nytDesc-' . $obj_id;
        // include module 'inc/nyt-span4.php'
        // This module needs difined var $name, $menu_arr, $capID.
        get_template_part( 'inc/nyt', 'span4' );
    }
}
?>