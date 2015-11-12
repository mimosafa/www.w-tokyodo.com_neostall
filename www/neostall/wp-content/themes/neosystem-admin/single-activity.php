<?php
get_header();
the_post();
?>
<?php wp_nonce_field( 'edit_activity', '_neosystem_admin_nonce' ); ?>
<h3>Activity <small class="muted">( <?php the_title(); ?> )</small></h3>
<div id="activity-field" class="row">
<div class="span4">
<?php
$day = get_post_meta( $id, 'day', true );
if ( $actOn = absint( $post->space_id ) ) {
    $post_type = 'space';
    $str = strtolower( date( 'l', strtotime( $day ) ) );
    $site = esc_html( get_post_meta( $actOn, 'site', true ) );
    $areaDetail = esc_html( get_post_meta( $actOn, 'areaDetail', true ) );
} elseif ( $actOn = absint( $post->event_id ) ) {
    $post_type = 'event';
    $series_obj = get_series_tax_obj( $actOn );
    $str = esc_html( $series_obj->slug );
    $eventsData = get_post_meta( $actOn, 'eventsData', true );
    foreach ( $eventsData as $data ) {
        if ( in_array( $id, $data['activities'] ) ) {
            $site = esc_html( $data['site'] );
            $areaDetail = esc_html( $data['areaDetail'] );
            break;
        }
    }
}
$kitchencar_id = absint( $post->actOf );
?>
<h4><i class="icon-map-marker"></i> 基本情報</h4>
<div id="activity-default">
<table class="table table-hover table-bordered">
  <tbody>
    <tr>
      <th>出店日</th>
      <td><?php echo date( 'Y/n/j D.', strtotime( $day ) ); ?></td>
    </tr>
<?php
$kitchencar_html = sprintf( '<a href="%s">%s</a>', get_permalink( $kitchencar_id ), esc_html( get_the_title($kitchencar_id ) ) );
?>
    <tr>
      <th>キッチンカー</th>
      <td><?php echo $kitchencar_html; ?></td>
    </tr>
<?php
if ( $series_obj ) {
?>
    <tr>
      <th>Series</th>
      <td><?php echo esc_html( $series_obj->name ); ?></td>
    </tr>
<?php
}
$actOn_html = sprintf( '<a href="%s">%s</a>', get_permalink( $actOn ), esc_html( get_the_title( $actOn ) ) );
?>
    <tr>
      <th><?php echo ucwords( $post_type ); ?></th>
      <td><?php echo $actOn_html; ?></td>
    </tr>
    <tr>
      <th>施設 / 会場</th>
      <td><?php echo $site; ?></td>
    </tr>
    <tr>
      <th>詳細エリア</th>
      <td><?php echo $areaDetail; ?></td>
    </tr>
  </tbody>
</table>
</div><!-- /#activity-default -->

<h4>
  <i class="icon-wrench"></i> 管理情報
  <small><a href="#" class="edit-anchor" data-target="#activity-management"><i class="icon icon-pencil"></i></a></small>
</h4>

<div id="activity-management">
<table class="table table-hover table-bordered">
  <tbody>
<?php
$phase = absint( $post->phase );
$phaseList = array( 1 => '応募中', 2 => '出店', 8 => 'キャンセル', 9 => 'お休み', 'remove' => 'アクティビティーを削除' );
$inner_arr = array();
foreach ( $phaseList as $k => $v ) {
    $inner_arr[] = array( 'element' => 'option', 'value' => $k, 'inner' => $v );
}
$arr = array(
    'class' => 'mmsf-replace-to-select',
    'data-name' => 'phase',
    'data-inner' => json_encode( $inner_arr ),
    'data-value' => $phase
);
$span_phase = '<span ';
foreach ( $arr as $key => $val ) {
    $span_phase .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_phase .= '>' . $phaseList[$phase] . '</span>';
$phase_tr_class = ( 9 == $phase ) ? ' class="error"' : '';
?>
    <tr<?php echo $phase_tr_class; ?>>
      <th>Phase</th>
      <td><?php echo $span_phase; ?></td>
    </tr>
  </tbody>
</table>
<div class="form-toggles clearfix hide">
  <div class="btn-group pull-right">
    <button type="button" class="btn btn-small cncl">Cancel</button>
    <button type="submit" class="btn btn-small" disabled>Done</button>
  </div>
</div>
</div><!-- /#activity-management -->

</div><!-- /.span4 -->

<?php
$menuitems = get_kitchencar_menuitems( $kitchencar_id, $post_type, $str );
if ( ! $img1 = wp_get_attachment_image( $menuitems['img1'], 'thumbnail' ) ) {
    $img1 = '<img src="http://fakeimg.pl/150x150/?text=No Image">'; // "http://fakeimg.pl/" API
}
$img2 = wp_get_attachment_image( $menuitems['img2'], 'thumbnail' );
$item = implode_post_title( ', ' , $menuitems['item'] );
$genre = implode_term_name( ', ' , $menuitems['genre'], 'genre' );
$text = esc_html( $menuitems['text'] );
?>
<div class="span8<?php if ( 9 == $phase ) echo ' muted'; ?>">
<h4>
  <i class="icon-food"></i> コンテンツ情報
  <small>
    <a href="#" class="edit-anchor" data-target="#activity-contents">
      <i class="icon icon-pencil"></i>
    </a>
  </small>
</h4>
<div id="activity-contents">
<table class="table table-hover table-bordered">
<tbody>
<?php
$item = $menuitems['item'];
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-class' => 'mmsf-select2-menuitems',
    'data-id' => 'menu-item',
    'data-type' => 'hidden',
    'data-name' => 'item',
    'data-style' => 'width: 100%;',
    'data-value' => implode( ',', $item ),
    'data-required' => true
);
$span_item = '<span ';
foreach ( $arr as $key => $val ) {
    $span_item .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_item .= '>' . implode_post_title( ', ' , $item ) . '</span>';
?>
<tr>
  <th>提供メニュー</th>
  <td><?php echo $span_item; ?></td>
</tr>
<?php
$genre = $menuitems['genre'];
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-class' => 'mmsf-select2-genres',
    'data-id' => 'menu-genre',
    'data-type' => 'hidden',
    'data-name' => 'genre',
    'data-style' => 'width: 100%;',
    'data-value' => implode( ',', $genre ),
    'data-required' => true
);
$span_genre = '<span ';
foreach ( $arr as $key => $val ) {
    $span_genre .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_genre .= '>' . implode_term_name( ', ' , $genre, 'genre' ) . '</span>';
?>
<tr>
  <th>ジャンル</th>
  <td><?php echo $span_genre; ?></td>
</tr>
<?php
$text = $menuitems['text'];
$arr = array(
    'class' => 'mmsf-replace-to-textarea',
    'data-name' => 'text',
    'data-class' => 'span6',
    'data-value' => $text
);
$span_text = '<span ';
foreach ( $arr as $key => $val ) {
    $span_text .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_text .= '>' . esc_html( $text ) . '</span>';
?>
<tr>
  <th>紹介</th>
  <td><?php echo $span_text; ?></td>
</tr>
<tr>
  <th>車両写真</th>
  <td><?php echo $img1; ?></td>
</tr>
<tr>
  <th>提供商品写真</th>
  <td><?php echo $img2; ?></td>
</tr>
</tbody>
</table>
<div class="form-toggles clearfix hide">
  <div class="btn-group pull-right">
    <button type="button" class="btn btn-small cncl">Cancel</button>
    <button type="submit" class="btn btn-small" disabled>Done</button>
  </div>
</div>
</div><!-- /#activity-contents -->
</div><!-- /.span8 -->

</div><!-- /#activity-field /.row -->
<?php
get_footer();
?>