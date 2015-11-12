<?php

get_header();

the_post();

// h2 & h3
$h2 = '';
$h3 = '';
$str = ''; // ---
if ( $series = get_series_tax_obj() ) {
    $h2 .= esc_html( $series->name ) . ' ';
    $h3 = date( 'Y/n/j D. ', strtotime( $post->day ) ) . get_the_title();
    $str = $series->slug; // ---
} else {
    $h2 .= get_the_title();
    $h3 = date( 'Y/n/j D.', strtotime( $post->day ) );
}

// EVENT DATA
$eventsData = $post->eventsData;
$e_data = $eventsData[0];
// LOCATION
$locationf = !isset( $post->multiarea ) ? '%s %s %s %s' : '%s <span id="span-location">%s %s %s</span>';
$region_obj = get_region_tax_obj();
$region_str = '';
if ( 0 != $region_obj->parent ) {
    $pref_obj = get_term( $region_obj->parent, 'region' );
    $region_str .= esc_html( $pref_obj->name );
}
$region_str .= esc_html( $region_obj->name );
$location = sprintf( $locationf, $region_str, esc_html( $e_data['address'] ), esc_html( $e_data['site'] ), esc_html( $e_data['areaDetail'] ) );
// TIMETABLE
$timetablef = !isset( $post->multiarea ) ? '%s ~ %s' : '<span id="span-timetable">%s ~ %s</span>';
$starting = date( 'G:i', strtotime( $e_data['starting'] ) );
$starting .= $e_data['startingPending'] ? ' <small>( 予定 )</small>' : '';
$ending = date( 'G:i', strtotime( $e_data['ending'] ) );
$ending .= $e_data['endingPending'] ? ' <small>( 予定 )</small>' : '';
$timetable = sprintf( $timetablef, $starting, $ending );
?>
<div class="headerWrapper">
  <div class="container">
    <h2><?php echo $h2; ?></h2>
    <h3><i class="icon-calendar"></i> <?php echo $h3; ?></h3>
    <p>
      <i class="icon-map-marker"></i> <?php echo $location; ?>&ensp;
      <i class="icon-time"></i> <?php echo $timetable; ?>
    </p>
  </div>
</div>
<div class="container">
<?php
if ( isset( $post->multiarea ) && ( 0 < count( $eventsData ) ) ) {
    echo '<ul class="nav nav-pills" id="nytTab">';
    foreach ( $eventsData as $key => $data ) {
        if ( isset( $data['activities'] ) && !empty( $data['activities'] ) ) {
            $location_str = sprintf( '%s %s %s', esc_html( $data['address'] ), esc_html( $data['site'] ), esc_html( $data['areaDetail'] ) );
            $starting = date( 'G:i', strtotime( $data['starting'] ) );
            $starting .= $data['startingPending'] ? ' <small>( 予定 )</small>' : '';
            $ending = date( 'G:i', strtotime( $data['ending'] ) );
            $ending .= $data['endingPending'] ? ' <small>( 予定 )</small>' : '';
            $timetable_str = sprintf( '%s ~ %s', esc_attr( $starting ), esc_attr( $ending ) );
            printf(
                '<li><a href="#tab-%d" data-toggle="pill" data-location="%s" data-timetable="%s">%s</a></li>',
                $key,
                $location_str,
                $timetable_str,
                esc_html( $data['areaDetail'] )
            );
        }
    }
    echo '</ul>';
    echo '<div class="tab-content">';
}
foreach ( $eventsData as $key => $data ) {
    if ( $activities = $data['activities'] ) {
?>
<div class="row tab-pane" id="tab-<?php echo $key; ?>">
<?php
        foreach ( $activities as $activity_id ) {
            global $name, $menu_arr, $capID;
            $kitchencar_id = get_post( $activity_id )->actOf;
            $name = get_the_display_name( $kitchencar_id );
            $menu_arr = legacy_get_the_menuitems( $activity_id, 'event', $str ); // --
            $capID = 'nytDesc-' . $activity_id;
            get_template_part( 'inc/nyt', 'span4' );
        }
?>
</div><!-- /.row -->
<?php
    }
}
if ( isset( $post->multiarea ) && ( 0 < count( $eventsData ) ) )
    echo '</div><!-- /.tab-content -->';
?>
<div style="height:400px;margin-bottom:50px;margin-top:50px;">
    <?php get_template_part( 'inc/map' ); ?>
</div>
</div><!-- /.contaniner -->
<?php

get_footer();

?>