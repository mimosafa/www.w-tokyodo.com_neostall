<?php
get_header();
the_post();
$h1 = '';
$ttl = '';

$series = get_series_tax_obj();
$str = $series->slug;
$series_options = get_option( 'series_' . $str );
if ( $series_options['front_end_archive'] ) {
    $h1 .= esc_html( $series->name );
    $ttl .= get_the_title();
} else {
    $h1 .= get_the_title();
}
?>
<div id="main">
<div id="ttl" class="event">
  <h1><?php echo $h1; ?></h1>
</div>
<?php
// EVENT DATA
$eventsData = $post->eventsData;
$e_data = $eventsData[0];
$multiarea = ( 1 < count( $eventsData ) ) ? true : false;
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
$day = date( 'Y年n月j日 (', strtotime( $post->day ) ) . mb_substr( __(date( 'D', strtotime( $post->day ) ) ), 0, 1 ) . ') ';
$timetablef = !isset( $post->multiarea ) ? '%s ~ %s' : '<span id="span-timetable">%s ~ %s</span>';
$starting = date( 'G:i', strtotime( $e_data['starting'] ) );
$starting .= $e_data['startingPending'] ? ' <small>( 予定 )</small>' : '';
$ending = date( 'G:i', strtotime( $e_data['ending'] ) );
$ending .= $e_data['endingPending'] ? ' <small>( 予定 )</small>' : '';
$timetable = $day . sprintf( $timetablef, $starting, $ending );
?>
<div id="placeInfo" class="clearfix">
  <dl class="clearfix">
<?php if ( $ttl ) { ?>
    <dt>イベント名</dt>
    <dd><?php echo $ttl; ?></dd>
<?php } ?>
    <dt>所在地</dt>
    <dd><?php echo $location; ?></dd>
    <dt>営業日時</dt>
    <dd><?php echo $timetable; ?></dd>
<?php
if ( $post->post_content || has_post_thumbnail() ) :
    $attachment_html = '';
    if ( has_post_thumbnail() ) {
        $attachment_html .= sprintf( '<a href="%s" class="modal event-thumb">', wp_get_attachment_url( get_post_thumbnail_id() ) );
        $attachment_html .= get_the_post_thumbnail( get_the_ID(), array( 100, 100 ) );
        $attachment_html .= '</a>';
    } ?>
    <dt>イベント内容</dt>
    <dd <?php post_class(); ?>><?php
    if ( $attachment_html )
        echo $attachment_html;
    the_content(); ?></dd>
<?php endif; ?>
  </dl>
</div><!-- /#placeInfo -->
<?php if ( $multiarea ) { ?>
<ul class="tab-ul">
<?php
    foreach ( $eventsData as $key => $data ) {
        $location_data = sprintf( '%s %s %s', $data['address'], $data['site'], $data['areaDetail'] );
        $starting_data = date( 'G:i', strtotime( $data['starting'] ) );
        $starting_data .= $data['startingPending'] ? ' <small>( 予定 )</small>' : '';
        $ending_data = date( 'G:i', strtotime( $data['ending'] ) );
        $ending_data .= $data['endingPending'] ? ' <small>( 予定 )</small>' : '';
        $timetable_data = sprintf( '%s ~ %s', $starting_data, $ending_data );
?>
<li>
  <a href="#panel-<?php echo $key; ?>" data-location="<?php echo esc_attr( $location_data ); ?>" data-timetable="<?php echo esc_attr( $timetable_data ); ?>">
    <?php echo esc_html( $data['areaDetail'] ); ?>
  </a>
</li>
<?php
    }
?>
</ul>
<div class="panel loading">
<?php
}
?>
<div id="weekSche">
<?php
foreach ( $eventsData as $key => $data ) : // -- eventsData loop
    if ( $multiarea ) {
?>
<div class="panel-inner multi-area" id="panel-<?php echo $key; ?>">
<?php
    }
?>
  <div class="day">
    <p class="inner-ttl">出店ネオ屋台</p>
<?php
    if ( $activities = $data['activities'] ) {
        foreach ( $activities as $activity_id ) {
            global $obj, $str;
            $obj = get_post( $activity_id );
            get_template_part( 'inc/shop' );
        }
    } else {
        echo '<p class="well" style="text-align:center;">出店キッチンカーは確定し次第、こちらに掲載させていただきます。</p>';
    }
?>
  </div><!-- /.day -->
<?php
    if ( $multiarea ) {
?>
</div><!-- /.panel-inner -->
<?php
    }
endforeach; // -- END: eventsData loop
?>
</div><!-- /#weekSche -->
<?php
if ( $multiarea ) {
?>
</div><!-- /.panel -->
<?php
}
?>
<p class="linkMark"><a href="<?php echo get_post_type_archive_link( 'event' ); ?>">一覧に戻る</a></p>
<p class="linkMark"><a href="<?php echo home_url(); ?>">トップページに戻る</a></p>
<!-- <?php var_dump($str); ?> -->
</div><!-- /#main -->
<?php
get_sidebar();
get_footer();
?>