<?php
get_header();
the_post();

$h3 = sprintf( '%s | %s', date( 'Y/n/j D. ', strtotime( $post->day ) ), get_the_title() );
if ( $series = get_series_tax_obj() ) {
    $series_link = get_term_link( $series, 'series' );
    $series_name = esc_html( $series->name );
    $h3 .= sprintf( ' <small><a href="%s" class="muted">( %s )</a></small>', $series_link, $series_name );
}

?>
<h3>
  <i class="icon icon-calendar"></i> <?php echo $h3; ?>
</h3>

<ul class="nav nav-tabs" id="eventTabs">
  <li>
    <a href="#eventdata" data-toggle="tab"><i class="icon-cog"></i> 管理情報</a>
  </li>
<?php
if ( $eventsData = $post->eventsData ) {
    foreach ( $eventsData as $key => $e_data ) {
        $tab_label = $e_data['areaDetail'] ? esc_html( $e_data['areaDetail'] ) : esc_html( $e_data['site'] );
?>
  <li>
    <a href="#eventdata-<?php echo $key; ?>" data-toggle="tab"><i class="icon-map-marker"></i> <?php echo $tab_label; ?></a>
  </li>
<?php
    }
}
?>
</ul>

<div class="tab-content">

<div id="eventdata" class="tab-pane">
<div class="clearfix">
  <div class="btn-group pull-right">
    <button type="button" class="btn btn-link" data-option-table-toggle="event-management">
      <i class="icon icon-pencil"></i> 管理情報を編集する
    </button>
  </div>
</div>
<div data-option-table-wrapper="event-management">
<?php wp_nonce_field( 'edit_event_options', '_neosystem_admin_nonce' ); ?>
<table class="table table-hover" data-option-table>
  <tbody>
    <tr>
      <th>シリーズ</th>
      <td><?php echo $series_name; ?></td>
    </tr>
    <tr>
      <th>イベント名</th>
      <td data-option-item>
        <span><?php the_title();?></span>
        <div class="hide" data-option-part>
          <input type="text" class="span5" name="post_title" value="<?php echo esc_attr( get_the_title() ); ?>"  />
        </div>
      </td>
    </tr>
    <tr>
      <th>開催日</th>
      <td><?php echo date( 'Y/n/j D. ', strtotime( $post->day ) ); ?></td>
    </tr>
    <tr>
      <th>作成日</th>
      <td><?php echo $post->post_date; ?></td>
    </tr>
    <tr>
      <th>募集開始</th>
      <td></td>
    </tr>
    <tr>
      <th>募集〆切</th>
      <td></td>
    </tr>
    <tr>
      <th>出店者確定</th>
      <td></td>
    </tr>
    <tr>
      <th>WEB公開</th>
      <td data-option-item>
<?php
if ( $publication = $post->publication ) {
?>
        <span><?php echo esc_html( $publication ); ?></span>
        <div class="hide" data-option-part>
          <label class="checkbox">
            <input type="checkbox" name="publication" value="close" /> 非公開にする
          </label>
        </div>
<?php
} else {
?>
        <span class="text-error">非公開</span>
        <div class="hide" data-option-part>
          <label class="checkbox">
            <input type="checkbox" name="publication" value="open" /> 公開する
          </label>
        </div>
<?php
}
?>
      </td>
    </tr>
  </tbody>
</table>
<div class="clearfix hide" data-option-table-toggles>
  <div class="btn-group pull-right">
    <button type="button" class="btn btn-link" data-option-table-cancel><i class="icon icon-remove"></i> キャンセル</button>
    <button type="submit" class="btn btn-link" data-option-table-submit><i class="icon icon-ok"></i> 登録</button>
  </div>
</div>
</div><!-- /[data-option-table-wrapper] -->

</div>

<?php
if ( !empty( $eventsData ) ) :
    foreach ( $eventsData as $key => $e_data ) :
        $areaData = sprintf(
            '%s %s %s %s',
            esc_html( get_term( $e_data['region'], 'region' )->name ),
            esc_html( $e_data['address'] ),
            esc_html( $e_data['site'] ),
            esc_html( $e_data['areaDetail'] )
        );
        $starting = date( 'G:i', strtotime( $e_data['starting'] ) );
        $starting_value = date( 'Y-m-d\TG:i', strtotime( $e_data['starting'] ) );
        if ( $e_data['startingPending'] )
            $starting .= ' <small>( 予定 )</small>';
        $ending = date( 'G:i', strtotime( $e_data['ending'] ) );
        $ending_value = date( 'Y-m-d\TG:i', strtotime( $e_data['ending'] ) );
        if ( $e_data['endingPending'] )
            $ending .= ' <small>( 予定 )</small>';
        // $open = sprintf( '%s ~ %s', $starting, $ending );

        $jsonData = json_encode( $e_data );
?>
  <div id="eventdata-<?php echo $key; ?>" class="tab-pane" data-eventdata="<?php echo $key; ?>" data-json='<?php echo $jsonData; ?>'>
    <h4>イベント開催情報</h4>
    <div data-option-table-wrapper="area-data-<?php echo $key; ?>">
      <?php wp_nonce_field( 'edit_eventdata', '_neosystem_admin_nonce' ); ?>
      <table class="table table-hover">
        <tbody>
          <tr>
            <th>出店場所情報</th>
            <td><?php echo $areaData; ?></td>
          </tr>
          <tr>
            <th>地図情報（緯度経度）</th>
            <td><?php echo esc_html( $e_data['latlng'] ); ?></td>
          </tr>
          <tr>
            <th>営業開始時間</th>
            <td data-option-item>
              <span><?php echo $starting; ?></span>
              <div class="hide" data-option-part>
                <div class="input-append" style="margin-bottom:0;">
                  <input type="datetime-local" name="eventsData[<?php echo $key; ?>][starting]" value="<?php echo $starting_value; ?>" />
                  <span class="add-on">
                    <label class="checkbox">
                      <input type="checkbox" name="eventsData[<?php echo $key; ?>][startingPending]" value="1"<?php if ( $e_data['startingPending'] ) echo ' checked="checked"'; ?> /> 予定
                    </label>
                  </span>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th>営業終了時間</th>
            <td data-option-item>
              <span><?php echo $ending; ?></span>
              <div class="hide" data-option-part>
                <div class="input-append" style="margin-bottom:0;">
                  <input type="datetime-local" name="eventsData[<?php echo $key; ?>][ending]" value="<?php echo $ending_value; ?>" />
                  <span class="add-on">
                    <label class="checkbox">
                      <input type="checkbox" name="eventsData[<?php echo $key; ?>][endingPending]" value="1"<?php if ( $e_data['endingPending'] ) echo ' checked="checked"'; ?> /> 予定
                    </label>
                  </span>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="clearfix hide" data-option-table-toggles>
        <div class="btn-group pull-right">
          <button type="button" class="btn btn-link" data-option-table-cancel><i class="icon icon-remove"></i> キャンセル</button>
          <button type="submit" class="btn btn-link" data-option-table-submit><i class="icon icon-ok"></i> 変更</button>
        </div>
      </div>
    </div><!-- /[data-option-table-wrapper] -->
    <div class="clearfix">
      <div class="btn-group pull-right">
        <button type="button" class="btn btn-link option-table-toggle" data-option-table-toggle="area-data-<?php echo $key; ?>">
          <i class="icon icon-pencil"></i> イベント開催情報を編集
        </button>
      </div>
    </div>

    <h4>Activities</h4>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>キッチンカー</th>
          <th>提供メニュー</th>
          <th>フェーズ</th>
        </tr>
      </thead>
      <tbody>
<?php
        if ( $activities = $e_data['activities'] ) {
            foreach ( $activities as $key => $activity_id ) {
                $activity = get_post( $activity_id );
                $kitchencar = get_the_title( $activity->actOf );
                $menuitems = get_the_menuitems( $activity_id, 'event', $series->slug );
?>
        <tr>
          <td><?php printf( '<a href="%s">%d</a>', get_permalink( $activity_id ), $activity_id ); ?></td>
          <td><?php printf( '<a href="%s">%s</a>', get_permalink( $activity->actOf), get_the_title( $activity->actOf ) ); ?></td>
          <td><?php echo implode_post_title( ', ' , $menuitems['item'] ); ?></td>
          <td><?php echo get_cf_phase_label( $activity_id ); ?></td>
        </tr>
<?php
            }
        } else {
?>
        <tr>
          <td colspan="4">
            アクティビティが登録されていません <a href="#" data-edit-activities="add"><i class="icon icon-plus"></i> 追加</a>
          </td>
        </tr>
<?php
        }
?>
      </tbody>
    </table>
  </div>
<?php
    endforeach;
?>
  <div id="add-activities" class="hide">
    <?php wp_nonce_field( 'event_add_activities', '_neosystem_admin_nonce' ); ?>
    <legend>アクティビティを追加する</legend>
    <input type="hidden" id="select-kitchencars" class="span12" />
    <div class="row">
      <div class="span2">
        <h4>Activity Phase</h4>
      </div>
      <div class="span1" style="padding:8.5px 0;">
        <label class="radio">
          <input type="radio" class="activity-phase" value="1" /> 応募中
        </label>
      </div>
      <div class="span1" style="padding:8.5px 0;">
        <label class="radio">
          <input type="radio" class="activity-phase" value="2" /> 出店
        </label>
      </div>
    </div>
    <div class="clearfix">
      <div class="btn-group pull-right">
        <button type="button" class="btn btn-small" data-cancel><i class="icon icon-remove"></i> キャンセル</button>
        <button type="submit" class="btn btn-small" data-submit><i class="icon icon-ok"></i> 登録</button>
      </div>
    </div>
  </div>
<?php
endif;
?>

</div><!-- /.tab-content -->

<?php get_footer(); ?>