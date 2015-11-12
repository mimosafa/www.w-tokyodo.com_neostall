<div id="calendar" class="tab-pane">
<?php

global $monthly, $date, $period, $calS, $calE, $diff, $managements, $activities;

// Strings Date & Period
$h4 = '';
$date_Y = substr( $date, 0, 4 );
$date_m = substr( $date, 4, 2 );
if ( ! $monthly ) {

    $date_d = substr( $date, -2 );
    $date_Dname = date( 'D', strtotime( $date ) );

    $monthUri = add_query_arg( 'date', $date_Y . $date_m );
    $h4 .= sprintf(
        '%d/<a href="%s">%d</a>/%d (%s) <i class="icon-long-arrow-right"></i> ',
        $date_Y, $monthUri, $date_m, $date_d, $date_Dname
    );

    $period_Y = substr( $period, 0, 4 );
    $period_m = substr( $period, 4, 2 );
    $period_d = substr( $period, -2 );
    $period_Dname = date( 'D', strtotime( $period ) );
    if ( $date_Y !== $period_Y ) {
        $h4 .= $period_Y . '/';
    }
    if ( $date_m !== $period_m ) {
        $monthUri = add_query_arg( 'date', $period_Y . $period_m );
        $h4 .= sprintf( '<a href="%s">%d</a>/', $monthUri, $period_m );
    }
    $h4 .= sprintf( '%d (%s)', $period_d, $period_Dname );

} else {

    $h4 .= sprintf( '[月間表示] %d年%d月', $date_Y, $date_m );

}

?>
  <h4><i class="icon-calendar"></i> <?php echo $h4; ?></h4>
    <div id="calWrapper">
    <?php wp_nonce_field( 'space_add_activities_from_calendar', '_neosystem_admin_nonce' ); ?>

<?php

// Prev & Next link parameter
if ( ! $monthly ) {
    $prev = date( 'Ymd', strtotime( '-21 day', strtotime( $date ) ) );
    $next = date( 'Ymd', strtotime( '+1 day', strtotime( $period ) ) );
} else {
    $prev = date( 'Ym', strtotime( '-1 day', strtotime( $date ) ) );
    $next = date( 'Ym', strtotime( '+1 day', strtotime( $period ) ) );
}
$prevUri = add_query_arg( 'date', $prev );
$nextUri = add_query_arg( 'date', $next );

?>
    <div id="calControler" class="btn-group">
      <a class="btn btn-small" href="<?php echo $prevUri; ?>"><i class="icon-chevron-sign-left"></i> PREV</a>
      <a class="btn btn-small" href="<?php the_permalink(); ?>"><i class="icon-bookmark"></i> TODAY</a>
      <a class="btn btn-small" href="<?php echo $nextUri; ?>">NEXT <i class="icon-chevron-sign-right"></i></a>
    </div>
    <a class="btn btn-small btn-link hide" href="#" id="add-activity-all-days">アクティビティー登録(ALL)</a>

    <div class="calHeaderRow clearfix">
      <div class="calHeaderBox"><?php _e( 'Monday' ); ?></div>
      <div class="calHeaderBox"><?php _e( 'Tuesday' ); ?></div>
      <div class="calHeaderBox"><?php _e( 'Wednesday' ); ?></div>
      <div class="calHeaderBox"><?php _e( 'Thursday' ); ?></div>
      <div class="calHeaderBox"><?php _e( 'Friday' ); ?></div>
      <div class="calHeaderBox" style="color:#4682b4;"><?php _e( 'Saturday' ); ?></div>
      <div class="calHeaderBox" style="color:#ff6347;"><?php _e( 'Sunday' ); ?></div>
    </div>

    <div id="calBody">
<?php
/*
    // Setting Scheduled Kitchencars List
    if ( get_field( 'list' ) ) {
        while ( has_sub_field( 'list' ) ) {
            $dayname = ucwords( esc_html( get_sub_field( 'dayname' ) ) );
            $lists[$dayname] = get_sub_field( 'kitchencars' );
        }
    }
*/
    $_weeks = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
    $lists = array();
    foreach ( $_weeks as $_dayname ) {
        if ( $kitchencar_ids = get_post_meta( $post->ID, $_dayname, true ) ) {
            $lists[ucwords( $_dayname )] = $kitchencar_ids;
        }
    }

    $day = isset( $calS ) ? $calS : $date;
    $limit = 7;
    for ( $i = 0; $i <= $diff; $i++ ) :

        if ( $limit == 7 )
            echo '<div class="calRow clearfix">'; // -- Calendar Row -----------------------------------------------------------------

        if ( $monthly && $day < $date OR $monthly && $day > $period ) :

            echo '<div class="calBox calBoxEmpty"></div>'; // 月表示の前月と翌月分

        else :

            $boxClass = 'calBox';
            $dateClass = 'calDate';
            $dayStr = '';
            // 本日の場合
            if ( $day == date( 'Ymd' ) )
                $boxClass .= ' calBoxToday';

            $day_l = date( 'l', strtotime( $day ) ); // Daynames

            if ( isset( $activities[$day] ) /*|| isset( $lists[$day_l] )*/ ) {

                $dayStr .= sprintf(
                    '<a href="#" class="dropdown-toggle" data-toggle="dropdown">%s<span>(%s)</span></a>',
                    date( 'n/j', strtotime( $day ) ),
                    mb_substr( __( $day_l ), 0, 1 )
                );
                $dayStr .= '<ul class="dropdown-menu" style="color:#333;">';
                if ( isset( $activities[$day] ) ) {
                    $actsUri = add_query_arg(
                        array(
                            'space_id' => get_the_ID(),
                            'date' => $day
                        ),
                        get_post_type_archive_link( 'activity' )
                    );
                    $dayStr .= sprintf( '<li><a href="%s"><i class="icon icon-th-list"></i> LIST</a>', $actsUri );
                }
                $dayStr .= '<li><a href="#" data-edit-toggle-perday="' . $day . '"><i class="icon icon-pencil"></i> EDIT</a>';
                $dayStr .= '</ul>';
                $dateClass .= ' dropdown'; // bootstrap dropdown

            } else {

                $dayStr .= sprintf( '%s<span>%s</span>', date( 'n/j', strtotime( $day ) ), mb_substr( __( $day_l ), 0, 1 ) );
                if ( !isset( $lists[$day_l] ) && !isset( $managements[$day] ) )
                    $boxClass .= ' calBoxEmpty';

            }

?>
      <div class="<?php echo $boxClass; ?>">
        <div class="<?php echo $dateClass; ?>">
          <?php echo $dayStr; ?>
        </div>
<?php

            $boxInnerClass = 'calBoxInner';
            $data_str = sprintf( 'data-date="%s"', $day );

            $kitchencars = '';
            foreach ( (array) $lists[$day_l] as $kitchencar_id ) {
                $kitchencar = get_post( $kitchencar_id );
                $reserved = get_posts(
                    array(
                        'post_type' => 'activity',
                        'meta_query' => array(
                            array(
                                'key' => 'day',
                                'value' => $day,
                                'compare' => '=',
                            ),
                            array(
                                'key' => 'actOf',
                                'value' => $kitchencar->ID,
                                'compare' => '='
                            )
                        )
                    )
                );
                if ( ! $reserved )
                    $kitchencars .= sprintf( '"%d||%s", ', $kitchencar->ID, esc_attr( $kitchencar->post_title ) );
                else
                    $kitchencars .= sprintf( '"%d||%s||reserved", ', $kitchencar->ID, esc_attr( $kitchencar->post_title ) );
            }
            $kitchencars = substr( $kitchencars, 0, -2 );
            $data_str .= sprintf( " data-kitchencars='[%s]'", $kitchencars );

            $management_posts = $managements[$day];
            $activity_posts = $activities[$day];
            $html = '';
            if ( $management_posts || $activity_posts ) {

                if ( $management_posts ) {
                    foreach ( $management_posts as $management ) {
                        $management_str = '';
                        $management_id = $management->ID;
                        $m_url = get_permalink( $management_id );
                        $type = $management->type;
                        switch ( $type ) {
                            case 'off':
                                $management_str = 'おやすみ';
                                break;
                            default:
                                # code...
                                break;
                        }
                        $html .= sprintf( '<p><a href="%s" class="muted">%s</a></p>', $m_url, $management_str );
                    }
                }

                if ( $activity_posts ) {
                    foreach ( $activity_posts as $activity ) {
                        $activity_id = $activity->ID;
                        $kitchencar_id = absint( $activity->actOf );
                        $k_name = get_the_title( $kitchencar_id );
                        $a_url = get_permalink( $activity_id );
                        $phase = $activity->phase;
                        switch ( $phase ) {
                            case '9':
                                $htmlf = '<p data-activity="%d" data-kitchencar="%d"><a href="%s" title="お休み" class="text-warning"><del>%s</del></a></p>';
                                break;
                            case '2':
                                $htmlf = '<p data-activity="%d" data-kitchencar="%d"><a href="%s">%s</a></p>';
                                break;
                        }
                        $html .= sprintf( $htmlf, $activity_id, $kitchencar_id, $a_url, $k_name );
                    }
                }

            } else if ( isset( $lists[$day_l] ) ) {

                $boxInnerClass .= ' no-activities';
                if ( !$html )
                    $html .= '<p><a href="#" class="muted" data-edit-toggle-perday="' . $day . '">No Activities.</a></p>';

            }

            $attr = sprintf( 'class="%s" %s', $boxInnerClass, $data_str );

?>
        <div <?php echo $attr; ?>>
          <?php echo $html; ?>
        </div>
      </div><!-- /.calBox -->
<?php

        endif;
        $day = date( 'Ymd', strtotime( $day . '+1 day' ) );
        $limit--;
        if ( $limit == 0 ) {
            echo '</div><!-- /.calRow -->'; // -- Calendar Row, End ------------------------------------------------------------
            $limit = 7;
        }
    endfor;

?>
    </div><!-- /#calBody -->
  </div><!-- /#calWrapper -->
</div><!-- /#calendar -->