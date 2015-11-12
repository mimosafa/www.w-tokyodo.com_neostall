<?php

get_header();

$url = get_post_type_archive_link( 'activity' );

$f_day = false;
$f_on = false;
$f_k = false;
$h = '';
if ( isset( $_GET['date'] ) && preg_match( '/^\d{8}$/', $_GET['date'] ) ) {
    if ( checkdate_Ymd( $_GET['date'] ) ) {
        $h .= sprintf( '<a href="%s?date=%s">%s</a> | ', $url, $_GET['date'], date( 'Y/n/j D.', strtotime( $_GET['date'] ) ) );
        $f_day = true;
    }
}
if ( $place_id = absint( $_GET['space_id'] ) and 'space' == get_post( $place_id )->post_type ) {
    $h .= sprintf( '<a href="%s?space_id=%d">%s</a> | ', $url, $place_id, get_the_title( $place_id ) );
    $f_on = true;
} elseif ( $place_id = absint( $_GET['event_id'] ) and 'event' == get_post( $place_id )->post_type ) {
    $day = get_post_meta( $place_id, 'day', true );
    $event_day = sprintf( '<a href="%s?date=%s">%s</a>', $url, $day, date( 'Y/n/j D.', strtotime( $day ) ) );
    $series_str = ( $series = get_series_tax_obj( $place_id ) ) ? esc_html( $series->name ) . ' - ' : '';
    $h .= sprintf( '%s | %s%s | ', $event_day, $series_str, get_the_title( $place_id ) );
    $f_day = true;
    $f_on = true;
}
if ( $k_id = absint( $_GET['k_id'] ) and 'kitchencar' == get_post( $k_id )->post_type ) {
    $h .= get_the_title( $_GET['k_id'] ) . ' | ';
    $f_k = true;
}
if ( $h ) {
    $h = '<a href="' . $url . '">Activities</a> | ' . substr( $h, 0, -3 );
} else {
    $h .= 'All Activity';
}
?>
<h3><?php echo $h; ?></h3>

<div class="row">
  <div class="span6">
  </div>
  <div class="span6">
    <ul class="inline pull-right">
      <li>
        <a href="<?php echo add_query_arg( array( 'date' => date( 'Ymd' ) ), $url ); ?>" class="btn btn-link">
          <i class="icon icon-bookmark"></i> TODAY
        </a>
      </li>
      <li>
        <button class="btn btn-link" type="button" data-filter-toggle>
          <i class="icon icon-filter"></i> FILTER
        </button>
      </li>
      <li>
        <button class="btn btn-link" type="button" data-mmsf-feemi-toggle>
          <i class="icon-plus"></i> アクティビティーを追加
        </button>
      </li>
    </ul>
  </div>
</div>

<div style="display:none;" data-mmsf-feemi>

  <?php wp_nonce_field( 'add_vendor', '_neosystem_admin_nonce' ); ?>
  <legend>アクティビティーを追加</legend>
  <div class="row">
    <div class="span2">
      <label for="date">日付</label>
<?php
if ( isset( $_GET['date'] ) && $f_day ) {
    $date_value = date( 'Y-m-d', strtotime( $_GET['date'] ) );
?>
      <input type="date" class="span2" name="date" value="<?php echo $date_value; ?>" readonly />
<?php
} else {
?>
      <input type="date" id="date" class="span2" name="date" value="<?php echo date( 'Y-m-d' ); ?>" required />
<?php
}
?>
    </div>
<?php
if ( !$f_on ) {
?>
    <div class="span2">
      <label>出店先</label>
      <label class="radio inline">
        <input type="radio" name="post_type" value="space" /> ランチ
      </label>
      <label class="radio inline">
        <input type="radio" name="post_type" value="event" /> イベント
      </label>
    </div>
    <div class="span3">
      <input type="hidden" id="place" class="span3" name="place" style="margin-top:24px;" required />
    </div>
<?php
} else {
?>
    <div class="span3">
      <input type="text" class="span3" value="<?php echo get_the_title( $place_id ); ?>" style="margin-top:24px;" readonly />
      <input type="hidden" name="place" value="<?php echo $place_id; ?>" />
    </div>
<?php
}
?>
    <div class="span3">
      <label for="kitchencar">キッチンカー</label>
      <input type="hidden" id="kitchencar" class="span3" name="kitchencar" required />
    </div>

    <div class="span2">
      <div class="btn-group pull-right" style="margin-top:25px;">
        <button type="submit" class="btn btn-small" data-mmsf-feemi-submit disabled="disabled"><i class="icon icon-ok"></i> 登録する</button>
        <button type="button" class="btn btn-small" data-mmsf-feemi-cancel><i class="icon icon-remove"></i> キャンセル</button>
      </div>
    </div>

  </div>

</div><!-- /[data-mmsf-feemi-template] -->

<?php
if ( have_posts() ) :
?>
<table class="table table-hover">
  <thead>
    <tr>
      <?php if ( !$f_day ) { ?><th>Date</th><?php } ?>
      <?php if ( !$f_on ) { ?><th>Type</th>
      <th>Title</th><?php } ?>
      <?php if ( !$f_k ) { ?><th>Kitchencar</th><?php } ?>
      <th>Phase</th>
      <th>#</th>
    </tr>
  </thead>
  <tbody>
<?php
    while ( have_posts() ) : the_post();
        // Date Parameter
        if ( !$f_day ) {
            $day = date( 'n/j D.', strtotime( esc_html( $post->day ) ) );
            $dayUrl = $url . '?date=' . esc_html( $post->day );
        }
        // Space / Event Parametar
        if ( !$f_on ) {
            if ( $post->space_id ) { // Space
                $type = '<span class="label label-warning">Space</span>';
                $on = get_the_title( (int)$post->space_id );
                $onUrl = $url . '?space_id=' . $post->space_id . '&date=' . esc_html( $post->day );
            } elseif ( $post->event_id ) { // Event
                $type = '<span class="label label-info">Event</span>';
                $on = '';
                if ( $series_obj = get_series_tax_obj( $post->event_id ) ) {
                    $on .= esc_html( $series_obj->name ) . ' - ';
                }
                $on .= get_the_title( (int)$post->event_id );
                $onUrl = $url . '?event_id=' . $post->event_id;
            }
        }
        // Kitchencar Parameter
        if ( !$f_k ) {
            $kc = get_the_title( (int)esc_html( $post->actOf ) );
            $kcUrl = $url . '?k_id=' . esc_html( $post->actOf );
        }
        // Phase
        $phase = get_cf_phase_label();
?>
    <tr>
      <?php if ( !$f_day ) { ?><td>
        <a href="<?php echo $dayUrl; ?>"><?php echo $day; ?></a>
      </td><?php } ?>
      <?php if ( !$f_on ) { ?><td>
        <?php echo $type; ?>
      </td>
      <td>
        <a href="<?php echo $onUrl; ?>"><?php echo $on; ?></a>
      </td><?php } ?>
      <?php if ( !$f_k ) { ?><td>
        <a href="<?php echo $kcUrl; ?>"><?php echo $kc; ?></a>
      </td><?php } ?>
      <td>
        <?php echo $phase; ?>
      </td>
      <td>
        <a href="<?php the_permalink(); ?>"><?php the_ID(); ?></a>
      </td>
    </tr>
<?php
    endwhile;
else :
?>
<p class="well">No Activities.</p>
<?php
endif;
?>
  </tbody>
</table>

<?php
previous_posts_link();
next_posts_link();

if ( ! empty( $_SERVER['argv'] ) )
    echo '<a href="' . $url . '">All Activity</a>';
?>

<?php get_footer(); ?>