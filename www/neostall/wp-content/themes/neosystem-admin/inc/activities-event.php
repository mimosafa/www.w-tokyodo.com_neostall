<div id="activities" class="tab-pane">
<?php

$eventsData = $post->eventsData;
if ( $eventsData ) :

    foreach ( $eventsData as $key => $e_data ) {
        $e_json = json_encode( $e_data );
?>
<h4><i class="icon icon-map-marker"></i> <?php echo esc_html( $e_data['areaDetail'] ); ?></h4>
<div class="row" data-eventdata='<?php echo $e_json; ?>' data-eventdata-key="<?php echo $key; ?>">
<div class="span3">
<table class="table table-bordered table-hover">
<tbody>
<tr>
<th style="white-space:nowrap;">地域</th>
<td><?php echo esc_html( get_term( $e_data['region'], 'region' )->name ); ?></td>
</tr>
<tr>
<th style="white-space:nowrap;">補記住所</th>
<td><?php echo esc_html( $e_data['address'] ); ?></td>
</tr>
<tr>
<th style="white-space:nowrap;">施設</th>
<td><?php echo esc_html( $e_data['site'] ); ?></td>
</tr>
<tr>
<th style="white-space:nowrap;">緯度経度</th>
<td style="word-break:break-all;"><?php echo esc_html( $e_data['latlng'] ); ?></td>
</tr>
<?php
        $starting = '';
        $starting .= esc_html( $e_data['starting'] );
        if ( $e_data['startingPending'] )
            $starting .= ' <small>( 予定 )</small>';
?>
<tr>
<th style="white-space:nowrap;">営業開始</th>
<td><?php echo $starting; ?></td>
</tr>
<?php
        $ending = '';
        $ending .= esc_html( $e_data['ending'] );
        if ( $e_data['endingPending'] )
            $ending .= ' <small>( 予定 )</small>';
?>
<tr>
<th style="white-space:nowrap;">営業終了</th>
<td><?php echo $ending; ?></td>
</tr>
</tbody>
</table>
</div>
<div class="span9">
<?php
        if ( $activities = $e_data['activities'] ) {

        } else {
?>
<p class="lead text-center">
  キッチンカーが登録されていません
  <button type="button" class="btn btn-link" data-toggle-add>キッチンカーを登録する</button>
</p>
<?php
        }
?>
</div>
</div>
<?php

    }

endif;

?>
</div>