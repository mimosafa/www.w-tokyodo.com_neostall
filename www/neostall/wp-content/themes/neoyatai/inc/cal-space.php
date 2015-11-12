<?php

// Calendar Date, 'Monday' start.
$date = ( 1 == date( 'w' ) ) ? date( 'Ymd' ) : date( 'Ymd', strtotime( 'last Monday' ) );
$period = date( 'Ymd', strtotime( '+20 days', strtotime( $date ) ) );
$diff = ( strtotime( $period ) - strtotime( $date ) ) / 86400;

// Setting Scheduled Kitchencars List
$_weeks = [ 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ];
foreach ( $_weeks as $_dayname ) {
    $_kitchencars = get_post_meta( get_the_ID(), $_dayname, true );
    if ( $_kitchencars ) {
        foreach ( $_kitchencars as $_kitchencar ) {
            $lists[$_dayname][] = get_post( $_kitchencar );
        }
    }
}
/*
// Setting Scheduled Kitchencars List
if ( get_field( 'list' ) ) :
    while ( has_sub_field( 'list' ) ) {
        $dayname = ucwords( esc_html( get_sub_field( 'dayname' ) ) );
        $lists[$dayname] = get_sub_field( 'kitchencars' );
    }
endif;
*/

// Setting Activities
$a_query = array(
    'posts_per_page' => -1,
    'order_by' => 'meta_value',
    'meta_key' => 'day',
    'order' => 'ASC',
    'post_type' => 'activity',
    'meta_query' => array(
        array(
            'key' => 'day',
            'value' => array( $date, $period ),
            'compare' => 'BETWEEN',
            'type' => 'DATE'
        ),
        array(
            'key' => 'space_id',
            'value' => $post->ID,
            'compare' => '='
        )
    )
);
$activities = get_posts( $a_query );
foreach ( $activities as $activity ) {
    $a_day = $activity->day;
    $data[$a_day][] = $activity;
}
?>

<div class="calHeaderRow clearfix"><!-- カレンダーのヘッダー -->
  <div class="calHeaderBox"><?php _e( 'Monday' ); ?></div>
  <div class="calHeaderBox"><?php _e( 'Tuesday' ); ?></div>
  <div class="calHeaderBox"><?php _e( 'Wednesday' ); ?></div>
  <div class="calHeaderBox"><?php _e( 'Thursday' ); ?></div>
  <div class="calHeaderBox"><?php _e( 'Friday' ); ?></div>
  <div class="calHeaderBox" style="color:#4682b4;"><?php _e( 'Saturday' ); ?></div>
  <div class="calHeaderBox" style="color:#ff6347;"><?php _e( 'Sunday' ); ?></div>
</div>

<div id="calBody"><!-- カレンダーのボディー -->
<?php
    $day = $date;
    $limit = 7;
    for ( $i = 0; $i <= $diff; $i++ ) :
        if ( $limit == 7 )
            echo '<div class="calRow clearfix">';
        $classStr = '';
        // 本日の場合
        if ( $day == date( 'Ymd', strtotime('today') ) )
            $classStr .= 'calBoxToday';
        $day_l = date( 'l', strtotime( $day ) ); // Daynames
        // アクティビティーがない場合
        if ( ! isset( $data[$day] ) && ! isset( $lists[$day_l] ) )
            $classStr .= ' calBoxEmpty';
        $acts_url = get_post_type_archive_link( 'activity' ) . '?space_id=' . $post->ID . '&date=' . $day;
?>
<div id="cal-<?php echo $day; ?>" class="calBox <?php echo $classStr; ?>">
<?php
        $dayStr = '';
        $dayStr .= date( 'n/j', strtotime( $day ) ) . '<span>(';
        $dayStr .= mb_substr( __( $day_l ), 0, 1 );
        $dayStr .= ')</span>';
?>
  <div class="calDate">
    <?php echo $dayStr; ?>
  </div>
<?php
        if ( isset( $data[$day] ) ) :

            echo '<div class="calBoxInner">';
            foreach ( (array)$data[$day] as $activity ) {

                $k_name = get_the_display_name( $activity->actOf );
                $k_link = get_permalink( $activity->actOf );
                printf( '<p><a href="%s"><i class="icon-truck"></i> %s</a></p>', $k_link, $k_name );

            }
            echo '</div>';
/*
        else :

            if ( isset( $lists[$day_l] ) ) {
                echo '<div class="calBoxInner">';
                foreach ( $lists[$day_l] as $kitchencar ) {
                    $k_name = esc_html( $kitchencar->post_title );
                    echo '<p class="muted"><i class="icon-truck"></i> ' . $k_name . '</p>';
                }
                echo '</div>';
            }
*/
        endif;
?>
</div>
<?php
        $day = date( 'Ymd', strtotime( $day . '+1 day' ) );
        $limit--;
        if ( $limit == 0 ) {
            echo '</div>';
            $limit =7;
        }
    endfor;
?>
</div>