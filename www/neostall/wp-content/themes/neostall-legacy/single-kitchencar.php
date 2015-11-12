<?php
get_header();
the_post();

$name = get_the_display_name();
$thumb_id = absint( $post->_thumbnail_id );
$thumb = mmsf_get_lazyload_image( $thumb_id, 'medium' );
$thumb_url = wp_get_attachment_url( $thumb_id );

$activities = get_posts(
    array(
        'numberposts' => -1,
        'post_type' => 'activity',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_key' => 'day',
        'meta_query' => array(
            array(
                'key' => 'day',
                'value' => array( date( 'Ymd' ), date( 'Ymd', strtotime( '+13 days' ) ) ),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            ),
            array(
                'key' => 'actOf',
                'value' => $id,
                'compare' => '='
            )
        )
    )
);

?>
<div id="main">
<div id="ttl" class="shopcar">
<h1><?php echo $name; ?></h1>
</div>

<div class="clearfix">
<?php
?>
<a class="modal" href="<?php echo esc_url( $thumb_url ); ?>"><?php echo $thumb; ?></a>
  <div id="ttl" class="event" style="margin-top:25px;">
    <p style="font-size:120%;font-weight:bold;padding-left:60px;padding-top:18px;color:#333;">
      出店予定
      <small> ~ <?php printf( '%s (%s)', date( 'n月j日', strtotime( '+13 days' ) ), mb_substr( __( date( 'l', strtotime( '+13 days' ) ) ), 0, 1 ) ); ?></small>
    </p>
  </div>
  <p style="margin-bottom:1em;text-align:right;">ただいま調整中です。全ての予定が掲載されているわけではございません。</p>
  <div class="eventBox">
    <dl class="clearfix">
<?php

if ( $activities ) :

    $ev_flag = array();
    foreach ( $activities as $post ) :

        setup_postdata( $post );
        if ( '2' != $post->phase )
            continue;
        $day = $post->day;
        $dayStr = date( 'n月j日 (', strtotime( $day ) ) . mb_substr( __( date( 'D', strtotime( $day ) ) ), 0, 1 ) . ')';
        $ttl = '';
        if ( $event_id = $post->event_id ) {

            if ( !get_post( $event_id )->publication )
                continue;

            if ( isset( $ev_flag[$day][$event_id] ) )
                continue;

            //$ttl .= '[ イベント ] ';
            $series = get_series_tax_obj( $event_id );
            $series_options = get_option( 'series_' . $series->slug );
            if ( $series_options['front_end_archive'] )
                $ttl .= sprintf( '[ %s ] ', esc_html( $series->name ) );
            $ttl .= sprintf( '<a href="%s">%s</a>', get_permalink( $event_id ), get_the_title( $event_id ) );

        } elseif ( $space_id = $post->space_id ) {

            if ( !get_post( $space_id )->publication )
                continue;

            //$ttl .= '[ ランチ ] ';
            $ttl .= sprintf( '<a href="%s">%s</a>', get_permalink( $space_id ), get_the_title( $space_id ) );

        }

?>
      <dt><?php echo $dayStr; ?></dt>
      <dd><?php echo $ttl; ?></dd>
<?php

        $ev_flag[$day][$event_id] = true;

    endforeach;
    wp_reset_postdata();

else :

?>
      <dt></dt>
      <dd>現在のところネオ屋台村での出店は予定されていません</dd>
<?php

endif;

?>
    </dl>
  </div>
</div>

<p class="linkMark"><a href="<?php echo get_post_type_archive_link( 'kitchencar' ); ?>">一覧に戻る</a></p>
<p class="linkMark"><a href="<?php home_url(); ?>">トップページに戻る</a></p>

</div><!-- /#main -->
<?php

get_sidebar();

get_footer();

?>