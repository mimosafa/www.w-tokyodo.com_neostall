<?php
/****************************************
 * Calendar Setup
 * - Calendar Date, 'Monday' start.
 * - Calendar Period, 'Sunday' end.
 **/
function neoyatai_admin_calendar_setup() {

    if ( is_singular( 'space' ) ) {

        $queried = get_queried_object();

        global $monthly, $date, $period, $calS, $calE, $diff, $managements, $activities;

        $monthly = false;
        if ( isset( $_GET['date'] ) && checkdate_Ymd( $_GET['date'] ) ) {

            if ( 8 === strlen( $_GET['date'] ) ) {

                $int = strtotime( $_GET['date'] );
                if ( 1 == date( 'w', $int ) ) {
                    $date = $_GET['date'];
                } else {
                    $date = date( 'Ymd', strtotime( 'last Monday', $int ) );
                }

            } else {

                $date = $_GET['date'] . '01';
                $int = strtotime( $date );
                if ( 1 == date( 'w', $int ) ) {
                    $calS = $date;
                } else {
                    $calS = date( 'Ymd', strtotime( 'last Monday', $int ) );
                }
                $period = date( 'Ymt', $int );
                if ( 0 == date( 'w', strtotime( $period ) ) ) {
                    $calE = $period;
                } else {
                    $calE = date( 'Ymd', strtotime( 'Next Sunday', strtotime( $period ) ) );
                }
                $monthly = true;

            }

        }

        if ( ! $date_int = strtotime( $date ) ) {
            $date = ( 1 == date( 'w' ) ) ? date( 'Ymd' ) : date( 'Ymd', strtotime( 'last Monday' ) );
            $date_int = strtotime( $date );
        }
        if ( ! $period_int = strtotime( $period ) ) {
            $period = date( 'Ymd', strtotime( '+20 days', $date_int ) );
            $period_int = strtotime( $period );
        }

        // Number of display days
        if ( ! $monthly )
            $diff = ( $period_int - $date_int ) / 86400;
        else
            $diff = ( strtotime( $calE ) - strtotime( $calS ) ) / 86400;

        // Setting Managements
        $m_query = array(
            'posts_per_page' => -1,
            'order_by' => 'meta_value',
            'meta_key' => 'day',
            'order' => 'ASC',
            'post_type' => 'management',
            'meta_query' => array(
                array(
                    'key' => 'day',
                    'value' => array( $date, $period ),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                ),
                array(
                    'key' => 'space_id',
                    'value' => $queried->ID,
                    'compare' => '='
                )
            )
        );
        $management_posts = get_posts( $m_query );
        $managements = array();
        foreach ( $management_posts as $management_post ) {
            $m_days = get_post_meta( $management_post->ID, 'day' );
            foreach ( $m_days as $m_day )
                $managements[$m_day][] = $management_post;
        }

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
                    'value' => $queried->ID,
                    'compare' => '='
                )
            )
        );
        $activity_posts = get_posts( $a_query );

        $activities = array();
        foreach ( $activity_posts as $activity_post ) {
            $a_day = $activity_post->day;
            $activities[$a_day][] = $activity_post;
        }

    }

}
add_action( 'template_redirect', 'neoyatai_admin_calendar_setup' );