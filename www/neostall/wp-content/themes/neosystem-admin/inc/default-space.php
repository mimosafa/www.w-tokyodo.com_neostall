<div id="default" class="tab-pane">
<?php

// nonce
wp_nonce_field( 'space_single', 'nyt_edit_default_nonce' );

// 所在地情報
$region_obj = get_region_tax_obj();
if ( 0 != $region_obj->parent ) {
    $pref_obj = get_term( $region_obj->parent, 'region' );
    $pref = $pref_obj->name;
}
$region  = $region_obj->name;
$address = esc_html( $post->address );

// 施設
$site       = esc_html( $post->site );

// 営業時間
$start = date( 'G:i', strtotime( esc_html( $post->starting ) ) );
$end   = date( 'G:i', strtotime( esc_html( $post->ending ) ) );

?>

<table id="table-default" class="table table-hover" data-title="基本情報">

<tbody>

<?php
// Serial
$serial = (int)$post->serial;
$attr = '';
$attr .= 'data-field="serial" ';
$attr .= 'data-value="' . (int)$serial . '" ';
$attr .= 'data-original="' . $serial . '" ';
$attr .= 'data-element="number" ';
?>
<tr>
<th>#</th>
<td <?php echo $attr; ?>>
  <span><?php echo $serial; ?></span>
  <a href="#" class="edit"> <i class="icon-pencil"></i></a>
  <a href="#" class="done hide"> <i class="icon-ok"></i></a>
  <a href="#" class="cncl hide"> <i class="icon-remove"></i></a>
</td>
</tr>

<?php
// WEB表示
$cf_pblc = (int)$post->publication;
$pblc    = $cf_pblc ? 'Display' : 'Hidden';
$attr = '';
$attr .= 'data-field="publication" ';
$attr .= 'data-value="' . (int)$cf_pblc . '" ';
$attr .= 'data-original="' . $pblc . '" ';
$attr .= 'data-element="select" ';
$attr .= "data-options='";
$attr .= '["0||Hidden","1||Display"]';
$attr .= "'";
?>
<tr>
<th>WEB表示</th>
<td <?php echo $attr; ?>>
  <span><?php echo $pblc; ?></span>
  <a href="#" class="edit"> <i class="icon-pencil"></i></a>
  <a href="#" class="done hide"> <i class="icon-ok"></i></a>
  <a href="#" class="cncl hide"> <i class="icon-remove"></i></a>
</td>
</tr>

<?php
// スケジュール種別
$cf_stype = $post->sche_type;
switch ( $cf_stype ) {
    case 'fixed' :
        $stype = '固定スケジュール';
        break;
    case 'rotate' :
        $stype = 'ローテーション';
        break;
    case 'flex' :
        $stype = '流動スケジュール';
        break;
}
$attr = '';
$attr .= 'data-field="sche_type" ';
$attr .= 'data-value="' . esc_attr( $cf_stype ) . '" ';
$attr .= 'data-original="' . $stype . '" ';
$attr .= 'data-element="select" ';
$attr .= "data-options='";
$attr .= '["fixed||固定スケジュール","rotate||ローテーション","flex||流動スケジュール"]';
$attr .= "'";
?>
<tr>
<th>スケジュール種別</th>
<td <?php echo $attr; ?>>
  <span><?php echo $stype; ?></span>
  <a href="#" class="edit"> <i class="icon-pencil"></i></a>
  <a href="#" class="done hide"> <i class="icon-ok"></i></a>
  <a href="#" class="cncl hide"> <i class="icon-remove"></i></a>
</td>
</tr>

<?php
// 状況
$cf_phase = (int)$post->phase;
$phase = get_cf_phase_label( $post->ID );
$attr = '';
$attr .= 'data-field="phase" ';
$attr .= 'data-value="' . esc_attr( $cf_phase ) . '" ';
$attr .= 'data-original="' . $phase . '" ';
$attr .= 'data-element="select" ';
$attr .= "data-options='";
$attr .= '["0||見込み","1||アクティブ","2||期間限定（短期）","8||休止","9||終了"]';
$attr .= "'";
?>
<tr>
<th>状況</th>
<td <?php echo $attr; ?>>
  <span><?php echo $phase; ?></span>
  <a href="#" class="edit"> <i class="icon-pencil"></i></a>
  <a href="#" class="done hide"> <i class="icon-ok"></i></a>
  <a href="#" class="cncl hide"> <i class="icon-remove"></i></a>
</td>
</tr>

<tr>
<th>所在地</th>
<td>
  <?php echo $pref; ?>
  <?php echo $region; ?>
  <?php echo $address; ?>
</td>
</tr>

<tr>
<th>施設</th>
<td><?php echo $site; ?></td>
</tr>

<?php
// 詳細エリア
$areaDetail = $post->areaDetail;
$attr = '';
$attr .= 'data-field="areaDetail" ';
$attr .= 'data-value="' . esc_attr( $areaDetail ) . '" ';
$attr .= 'data-original="' . $areaDetail . '" ';
$attr .= 'data-element="text" ';
?>
<tr>
<th>詳細エリア</th>
<td <?php echo $attr; ?>>
  <span><?php echo esc_html( $areaDetail ); ?></span>
  <a href="#" class="edit"> <i class="icon-pencil"></i></a>
  <a href="#" class="done hide"> <i class="icon-ok"></i></a>
  <a href="#" class="cncl hide"> <i class="icon-remove"></i></a>
</td>
</tr>

<?php
// 緯度経度
$latlng  = $post->latlng;
$attr = '';
$attr .= 'data-field="latlng" ';
$attr .= 'data-value="' . esc_attr( $latlng ) . '" ';
$attr .= 'data-original="' . $latlng . '" ';
$attr .= 'data-element="text" ';
?>
<tr>
<th>緯度経度</th>
<td <?php echo $attr; ?>>
  <span><?php echo esc_html( $post->latlng ); ?></span>
  <a href="#" class="edit"> <i class="icon-pencil"></i></a>
  <a href="#" class="done hide"> <i class="icon-ok"></i></a>
  <a href="#" class="cncl hide"> <i class="icon-remove"></i></a>
</td>
</tr>

<tr>
<th>営業時間</th>
<td><?php echo "{$start} ~ {$end}"; ?></td>
</tr>

<tr>
<th>トライアル開始日</th>
<td></td>
</tr>

<tr>
<th>本営業開始日</th>
<td></td>
</tr>

<tr>
<th>営業終了日</th>
<td></td>
</tr>

<tr>
<th>担当者 (WSTD)</th>
<td></td>
</tr>

<tr>
<th>担当者 (スペース)</th>
<td></td>
</tr>

</tbody>
</table>

</div>