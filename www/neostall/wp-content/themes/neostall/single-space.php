<?php get_header();
the_post();

// Setting Scheduled Kitchencars List
if ( get_field( 'list' ) ) {
    while ( has_sub_field( 'list' ) ) {
        $dayname = ucfirst ( esc_html( get_sub_field( 'dayname' ) ) );
        $lists[$dayname] = get_sub_field( 'kitchencars' );
    }
}
$days = get_formatted_day_arrays( 21, '', 'Ymd', 'l', 'U' );
$ts_end = strtotime( '+6 days' );
$content_array = array();
foreach ( $days as $day_arr ) {
    $day_val  = $day_arr[0];
    $week     = $day_arr[1];
    $ts       = $day_arr[2];
    $m_query  = array(
        'post_type' => 'management',
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => 'space_id',
                'value' => $id,
                'compare' => '='
            ),
            array(
                'key' => 'day',
                'value' => $day_val,
                'compare' => '='
            )
        )
    );
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
    if ( $managements = get_posts( $m_query ) )
        $content_array[$ts]['managements'] = $managements;
    if ( $activities = get_posts( $a_query ) )
        $content_array[$ts]['activities'] = $activities;
    if ( isset( $lists[$week] ) )
        $content_array[$ts]['lists']      = $lists[$week];
    if ( isset( $content_array[$ts] ) )
        $content_array[$ts]['dayname']    = $week;
}
?>
<h1><?php the_title(); ?></h1>
<dl>
  <dt>所在地</dt>
  <dd><?php echo get_location_str(); ?></dd>
  <dt>営業時間</dt>
  <dd><?php echo get_opening_time_str(); ?></dd>
</dl>
<ul>
  <li style="display:none;"><a href="#monday"><?php _e( 'Monday' ); ?></a></li>
  <li style="display:none;"><a href="#tuesday"><?php _e( 'Tuesday' ); ?></a></li>
  <li style="display:none;"><a href="#wednesday"><?php _e( 'Wednesday' ); ?></a></li>
  <li style="display:none;"><a href="#thursday"><?php _e( 'Thursday' ); ?></a></li>
  <li style="display:none;"><a href="#friday"><?php _e( 'Friday' ); ?></a></li>
  <li style="display:none;"><a href="#saturday"><?php _e( 'Saturday' ); ?></a></li>
  <li style="display:none;"><a href="#sunday"><?php _e( 'Sunday' ); ?></a></li>
  <li style="display:none;margin-left:5px;"><a href="#calendar">CALENDAR</a></li>
</ul>
  <div id="tabContentSpace" class="panel loading">
    <div id="weekSche">
<?php
if ( $content_array ) :
    foreach ( $content_array as $ts => $array ) :

        if ( $ts > $ts_end )
            continue;

        $daynameId  = $array['dayname'];
        $ttl_day = '';
        $managements = array();
        $space_off = false;

        if ( $management_posts = $array['managements'] ) {
            foreach ( $management_posts as $management_post ) {
                $m_type = $management_post->type;
                if ( 'off' == $m_type ) {
                    $ttl_day .= '【お休み】 ' . date( 'n月j日 ', $ts );
                    $space_off = true;
                }
                $managements[] = $management_post;
            }
        }

        if ( $objs = $array['activities'] ) {
            $ttl_day .= date( 'n月j日 ', $ts ) . __( $daynameId );
        } else {
            $objs = $array['lists'];
            $ttl_day .= __( $daynameId );
            if ( !$space_off )
                $ttl_day .= ' ( 予定 )';
        }
?>
      <div class="panel-inner day" id="<?php echo strtolower( $daynameId ); ?>">
<?php
        if ( !empty( $managements ) ) {
            foreach ( $managements as $management ) {
                echo '<p class="well">';
                echo $management->post_excerpt; // -------------------------------------- use apply_filters ?
                if ( $space_off )
                    echo '<br><span style="color:#999;">' . __( $daynameId ) . 'に出店を予定しているキッチンカーはご覧のキッチンカーです</span>';
                echo '</p>';
            }
            if ( $space_off ) {
                echo '<div style="opacity:0.75;">';
            }
        }
?>
        <h2 class="inner-ttl"><?php echo $ttl_day; ?></h2>
<?php

        foreach ( $objs as $obj ) {
            global $obj, $str;
            $str = strtolower( $daynameId );
            get_template_part( 'inc/shop' );
        }

        if ( $space_off )
            echo '</div>';
?>
      </div>
<?php
    endforeach;
endif;
?>
      <div class="panel-inner day" id="calendar">
        <div class="clearfix">
          <div class="eventBox">
            <dl class="clearfix">
<?php
foreach ( $content_array as $ts => $array ) {
    $dayStr = date( 'n月j日', $ts ) . ' (' .  mb_substr( __( $array['dayname'] ), 0, 1 ) . ')';
    $contents = '';
    $attr_str = '';
    if ( $managements = $array['managements'] ) {
        foreach ( $managements as $management ) {
            $contents .= $management->post_excerpt;
            $contents .= '<br>';
            $attr_str .= ' style="background-color:#f5f5f5;"';
        }
    }
    if ( $activities = $array['activities'] ) {
        foreach ( $activities as $activity ) {
            $name = '';
            if ( $kitchencar_id = absint( $activity->actOf ) ) {
                $name = get_the_display_name( $kitchencar_id );
                if ( '9' == $activity->phase )
                    $name = '<del>' . $name . '</del> <small>※お休みいたします</small>';
                $contents .= $name;
                $contents .= ' <span style="color:#ccc;">/</span> ';
            }
        }
        $contents = substr( $contents, 0, -36 );
    }
    if ( !$contents && ( $list = $array['lists'] ) ) {
        $contents .= '<span style="color:#999;">(予定) ';
        foreach ( $list as $kitchencar ) {
            $contents .= get_the_display_name( $kitchencar->ID );
            $contents .= ' <span style="color:#ccc;">/</span> ';
        }
        $contents = substr( $contents, 0, -36 );
        $contents .= '</span>';
    }
?>
              <dt<?php echo $attr_str; ?>><?php echo $dayStr; ?></dt>
              <dd<?php echo $attr_str; ?>><?php echo $contents; ?></dd>
<?php
}
?>
            </dl>
          </div>
        </div>
      </div><!-- /#calendar -->

    </div><!-- /#weekSche -->
  </div><!-- /.panel -->

  <p>
    <a href="<?php echo get_post_type_archive_link( 'space' ); ?>" class="linkMark">
      <?php echo esc_html( get_post_type_object( 'space' )->label ); ?>
    </a>
  </p>

<?php get_footer(); ?>