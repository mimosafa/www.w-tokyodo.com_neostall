<?php

if ( ! $post->publication ) {
    header( "HTTP/1.0 404 Not Found" );
    include '404.php';
    exit;
}

get_header();

$days = get_formatted_day_arrays( 7, '', 'Ymd', 'l' );

// Setting Scheduled Kitchencars List
$_weeks = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];
foreach ( $_weeks as $_dayname ) {
    $_kitchencars = get_post_meta( get_the_ID(), $_dayname, true );
    if ( $_kitchencars ) {
        foreach ( $_kitchencars as $_kitchencar ) {
            $list[$_dayname][] = get_post( $_kitchencar );
        }
    }
}
/*
if ( get_field( 'list' ) ) {
    while ( has_sub_field( 'list' ) ) {
        $dayname = esc_html( get_sub_field( 'dayname' ) );
        $lists[$dayname] = get_sub_field( 'kitchencars' );
    }
}
*/

$content_array = array();
foreach ( $days as $day_arr ) {
    $day_val  = $day_arr[0];
    $week     = strtolower( $day_arr[1] );
    $a_query  = array(
        'post_type'   => 'activity',
        'numberposts' => -1,
        'meta_query'  => array(
            array(
                'key'     => 'space_id',
                'value'   => $post->ID,
                'compare' => '='
            ),
            array(
                'key'     => 'day',
                'value'   => $day_val,
                'compare' => '='
            )
        )
    );
    if ( $activities = get_posts( $a_query ) )
        $content_array[$day_val]['activities'] = $activities;
    if ( isset( $lists[$week] ) )
        $content_array[$day_val]['lists']      = $lists[$week];
    if ( isset( $content_array[$day_val] ) )
        $content_array[$day_val]['dayname']    = $week;
}

?>
<div class="headerWrapper">
  <div class="container">
    <h2><i class="icon-food"></i> <?php the_title(); ?></h2>
    <p>
      <a href="#map"><i class="icon-map-marker"></i> <?php echo get_location_str(); ?></a>&ensp;
      <i class="icon-time"></i> <?php echo get_opening_time_str(); ?>
    </p>
  </div>
</div>

<div class="container">
  <ul class="nav nav-pills" id="tabWeeks">
    <li class="hide"><a href="#monday" data-toggle="pill"><?php _e( 'Monday' ); ?></a></li>
    <li class="hide"><a href="#tuesday" data-toggle="pill"><?php _e( 'Tuesday' ); ?></a></li>
    <li class="hide"><a href="#wednesday" data-toggle="pill"><?php _e( 'Wednesday' ); ?></a></li>
    <li class="hide"><a href="#thursday" data-toggle="pill"><?php _e( 'Thursday' ); ?></a></li>
    <li class="hide"><a href="#friday" data-toggle="pill"><?php _e( 'Friday' ); ?></a></li>
    <li class="hide"><a href="#saturday" data-toggle="pill"><?php _e( 'Saturday' ); ?></a></li>
    <li class="hide"><a href="#sunday" data-toggle="pill"><?php _e( 'Sunday' ); ?></a></li>
    <li class="hide">
      <a href="#calendar" data-toggle="pill"><i class="icon-calendar"></i> <?php _e( 'Calendar' ); ?></a>
    </li>
  </ul>
<div class="tab-content" id="tabContentSpace">
<?php
if ( $content_array ) :
    foreach ( $content_array as $date => $array ) :
        $daynameId  = $array['dayname'];
        if ( !$activities = $array['activities'] )
            $actibities = null;
        $lists = $array['lists'];
?>
<div class="row tab-pane" id="<?php echo $daynameId; ?>">
<?php
        if ( $activities ) {
            foreach ( $activities as $activity ) {
                $obj_id = $activity->ID;
                $kit_id = (int)$activity->actOf;
                // name
                $name = get_the_display_name( $kit_id );
                // menu content
                $menu_arr = legacy_get_the_menuitems( $obj_id, 'space', $daynameId );
                // Add ID for HoverCaption (jQuery plugin)
                $capID = 'nytDesc-' . $daynameId . '-' . $obj_id;
                // include module 'inc/nyt-span4.php'
                // This module needs difined var $name, $menu_arr, $capID.
                get_template_part( 'inc/nyt', 'span4' );
            }
        } else if ( $lists ) {
            foreach ( $lists as $kitchencar ) {
                $obj_id = $kitchencar->ID;
                // name
                $name = get_the_display_name( $obj_id );
                // menu content
                $menu_arr = legacy_get_the_menuitems( $obj_id, 'space', $daynameId );
                // Add ID for HoverCaption (jQuery plugin)
                $capID = 'nytDesc-' . $daynameId . '-' . $obj_id;
                // include module 'inc/nyt-span4.php'
                // This module needs difined var $name, $menu_arr, $capID.
                get_template_part( 'inc/nyt', 'span4' );
            }
        }
?>
</div>
<?php
    endforeach;
endif;
?>

<div class="tab-pane" id="calendar">
<?php get_template_part( 'inc/cal', 'space' ); // include module 'mod/cal.php' ?>
</div>

</div><!-- /.tab-content -->

</div><!-- /.contaniner -->

<div class="container" style="height:400px;margin-bottom:50px;margin-top:50px;">
    <?php get_template_part( 'inc/map' ); ?>
</div>

<?php

get_footer();

?>