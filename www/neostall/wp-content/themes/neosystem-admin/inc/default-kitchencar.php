<div id="default" class="tab-pane">
<?php wp_nonce_field( 'edit_kitchencar_car_spec', '_neosystem_admin_nonce' ); ?>
<div class="row">

<div class="span4">

<h4>
  <i class="icon-picture"></i> 車両写真
</h4>
<div>
<?php
if ( !$thumb = get_the_post_thumbnail( $kitchencar->ID, 'medium' ) )
    $thumb = '<img width="300" height="225" src="' . get_template_directory_uri() . '/images/noimage-300x225.png">';
echo $thumb;
?>
</div>

<h4>
  <i class="icon-cog"></i> 基本情報
  <small><a href="#" class="edit-anchor" data-target="#kitchencar-default"><i class="icon icon-pencil"></i></a></small>
</h4>
<div id="kitchencar-default">
<table class="table table-hover table-bordered">
<tbody>
<?php
$phase = absint( $post->phase );
$phaseList = array( 1 => 'アクティブ', 9 => '非稼働' );
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
?>
<tr>
  <th>稼働状況</th>
  <td><?php echo $span_phase; ?></td>
</tr>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-name' => 'post-title',
    'data-class' => 'span3',
    'data-value' => get_the_title(),
    'data-required' => 'required'
);
$span_title = '<span ';
foreach ( $arr as $key => $val ) {
    $span_title .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_title .= '>' . esc_html( get_the_title() ) . '</span>';
?>
<tr>
  <th>車両名</th>
  <td><?php echo $span_title; ?></td>
</tr>
<tr>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-name' => 'display-name',
    'data-class' => 'span3',
    'data-value' => $post->name
);
$span_name = '<span ';
foreach ( $arr as $key => $val ) {
    $span_name .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_name .= '>' . esc_html( $post->name ) . '</span>';
?>
  <th>WEB表示</th>
  <td><?php echo $span_name; ?></td>
</tr>
</tbody>
</table>

</div><!-- /#kitchencar-default -->


<h4>
  <i class="icon-truck"></i> 車両スペック
  <small><a href="#" class="edit-anchor" data-target="#kitchencar-spec"><i class="icon icon-pencil"></i></a></small>
</h4>
<div id="kitchencar-spec">
<table class="table table-hover table-bordered">
<tbody>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-name' => 'vin',
    'data-class' => 'span3',
    'data-value' => $post->vin
);
$span_vin = '<span ';
foreach ( $arr as $key => $val ) {
    $span_vin .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_vin .= '>' . esc_html( $post->vin ) . '</span>';
?>
<tr>
  <th>車両No.</th>
  <td><?php echo $span_vin ?></td>
</tr>
<?php
$length = absint( $post->length );
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'number',
    'data-name' => 'length',
    'data-class' => 'span1',
    'data-value' => $length
);
$span_length = '<span ';
foreach ( $arr as $key => $val ) {
    $span_length .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_length .= '>' . $length . '</span>';
?>
<tr>
  <th>長さ</th>
  <td>
    <?php echo $span_length; ?>
    <span>mm</span>
  </td>
</tr>
<?php
$width = absint( $post->width );
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'number',
    'data-name' => 'width',
    'data-class' => 'span1',
    'data-value' => $length
);
$span_width = '<span ';
foreach ( $arr as $key => $val ) {
    $span_width .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_width .= '>' . $width . '</span>';
?>
<tr>
  <th>幅</th>
  <td>
    <?php echo $span_width; ?>
    <span>mm</span>
  </td>
</tr>
<?php
$height = absint( $post->height );
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'number',
    'data-name' => 'height',
    'data-class' => 'span1',
    'data-value' => $height
);
$span_height = '<span ';
foreach ( $arr as $key => $val ) {
    $span_height .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_height .= '>' . $height . '</span>';
?>
<tr>
  <th>高さ</th>
  <td>
    <?php echo $span_height; ?>
    <span>mm</span>
  </td>
</tr>
</tbody>
</table>
</div><!-- /#kitchencar-spec -->

</div><!-- /.span4 -->

<div class="span8">

</div><!-- /.span8 -->

</div><!-- /.row -->
</div><!-- /#default -->