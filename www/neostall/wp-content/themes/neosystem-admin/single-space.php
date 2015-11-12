<?php

get_header();

?>

<h3><i class="icon-map-marker"></i> <?php the_title(); ?></h3>

<ul class="nav nav-tabs" id="spaceTabs">
<li><a href="#management" data-toggle="tab"><i class="icon-wrench"></i> 管理情報</a></li>
<li><a href="#operation" data-toggle="tab"><i class="icon-refresh"></i> 運営</a></li>
<li><a href="#calendar" data-toggle="tab"><i class="icon-calendar"></i> カレンダー</a></li>
<li><a href="#client" data-toggle="tab"><i class="icon-building"></i> 顧客情報</a></li>
<!-- li><a href="#kitchencars" data-toggle="tab"><i class="icon-truck"></i> 登録キッチンカー</a></li -->
</ul>

<div class="tab-content">

<div id="management" class="tab-pane">

<?php wp_nonce_field( 'space_management', '_neosystem_admin_nonce' ); ?>
<div class="row">

<div class="span8 pull-right">

<h4>
  <i class="icon icon-calendar"></i> スケジュール管理
  <small><a href="#" class="edit-anchor" data-target="#space-schedule"><i class="icon icon-pencil"></i></a></small>
</h4>
<div id="space-schedule">
<table class="table table-hover table-bordered">
<tbody>
<?php
$scheType = $post->sche_type;
$scheTypeList = array( 'fixed' => '固定スケジュール', 'rotate' => 'ローテーション', 'flex' => '流動スケジュール' );
$inner = array();
foreach ( $scheTypeList as $key => $val ) {
    $inner[] = array( 'element' => 'option', 'value' => $key, 'inner' => $val );
}
$arr = array(
    'class' => 'mmsf-replace-to-select',
    'data-name' => 'sche_type',
    'data-class' => 'span4',
    'data-inner' => json_encode( $inner ),
    'data-value' => esc_attr( $scheType )
);
$span_scheType = '<span ';
foreach ( $arr as $key => $val ) {
    $span_scheType .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_scheType .= '>' . $scheTypeList[$scheType] . '</span>';
?>
<tr>
  <th>スケジュール種別</th>
  <td><?php echo $span_scheType; ?></td>
</tr>
<?php
if ( 'rotate' == $scheType ) {
    $pair_id = absint( $post->rotation_pair );
    $rotation_pair = $pair_id ? sprintf( '<a href="%s">%s</a>', get_permalink( $pair_id ), esc_html( get_the_title( $pair_id ) ) ) : '';
    $arr = array(
        'class' => 'mmsf-replace-to-input',
        'data-type' => 'hidden',
        'data-name' => 'rotation_pair',
        'data-class' => 'span5',
        'data-value' => $pair_id
    );
    $span_rotationPair = '<span ';
    foreach ( $arr as $key => $val ) {
        $span_rotationPair .= $key . '="' . esc_attr( $val ) . '" ';
    }
    $span_rotationPair .= '>' . $rotation_pair . '</span>';
?>
<tr>
  <th>ローテーションペア</th>
  <td><?php echo $span_rotationPair; ?></td>
</tr>
<?php
}
?>
<tr>
  <th>曜日</th>
<?php
$weeks = array(
    'monday' => __( 'Monday' ),
    'tuesday' => __( 'Tuesday' ),
    'wednesday' => __( 'Wednesday' ),
    'thursday' => __( 'Thursday' ),
    'friday' => __( 'Friday' ),
    'saturday' => __( 'Saturday' ),
    'sunday' => __( 'Sunday' )
);
$weeks_html = '';
$keys = get_post_custom_keys( $post->ID );
$active_weeks = array();
$first = true;
foreach ( $weeks as $key => $val ) {
    $span = '<span ';
    $_week = '';
    $arr = array(
        'class' => 'mmsf-replace-to-input',
        'data-type' => 'checkbox',
        'data-name' => 'active_weeks[]',
        'data-value' => $key,
        'data-checked' => 'false',
        'data-outer' => '<label class="checkbox">' . $val . '</label>'
    );
    if ( in_array( $key, $keys ) ) {
        $arr['data-checked'] = 'true';
        if ( $first ) {
            $_week .= $val;
        } else {
            $_week .= ' / ' . $val;
        }
        $active_weeks[$key] = $val;
        $first = false;
    }
    foreach ( $arr as $key => $val ) {
        $span .= $key . '="' . esc_attr( $val ) . '" ';
    }
    $span .= '>' . $_week . '</span>';
    $weeks_html .= $span;
}
?>
  <td><?php echo $weeks_html; ?></td>
</tr>
</tbody>
</table>
</div><!-- /#space-schedule -->

<h4>
  <i class="icon icon-truck"></i> 登録キッチンカー
<?php
if ( !empty( $active_weeks ) ) {
?>
  <small><a href="#" class="edit-anchor" data-target="#space-kitchencars"><i class="icon icon-pencil"></i></a></small>
<?php
}
?>
</h4>
<div id="space-kitchencars">
<?php
if ( !empty( $active_weeks ) ) {
?>
<table class="table table-bordered">
<?php
    foreach ( $active_weeks as $week => $dayname ) {
?>
<tbody id="<?php echo $week; ?>">
  <tr>
    <th colspan="2" style="background-color:#f5f5f5;"><?php echo $dayname; ?></th>
  </tr>
<?php
        $kitchencars = get_post_meta( $post->ID, $week, true );

        $arr = array(
            'class' => 'mmsf-replace-to-input',
            'data-type' => 'hidden',
            'data-style' => 'width:100%;',
            'data-name' => "kitchencars[{$week}]",
            'data-value' => json_encode( $kitchencars ),
            'data-outer' => json_encode( array( 'element' => 'td', 'colspan' => 2, 'style' => 'min-height:36px;' ) )
        );
        $trAttr = '';
        foreach ( $arr as $key => $val ) {
            $trAttr .= $key . '="' . esc_attr( $val ) . '" ';
        }
        if ( !empty( $kitchencars ) ) {
            $firstRow = true;
            foreach ( $kitchencars as $i => $kitchencar_id ) {
                $kitchencar_ttl = sprintf( '<a href="%s" class="muted">%s</a>', get_permalink( $kitchencar_id ), get_the_title( $kitchencar_id ) );
                $contents = get_kitchencar_menuitems( $kitchencar_id, 'space', $week );
                $menuitem = implode_post_title( ', ', $contents['item'] );
                if ( $firstRow ) {
                    $firstRow = false;
?>
  <tr <?php echo $trAttr; ?>>
<?php
                } else {
?>
  <tr class="mmsf-hide">
<?php
                }
?>
    <th><?php echo $kitchencar_ttl; ?></th>
    <td><?php echo esc_html( $menuitem ); ?></td>
  </tr>
<?php
            }
        } else {
?>
  <tr <?php echo $trAttr; ?>>
    <td colspan="2" class="muted">No Kitchencars.</td>
  </tr>
<?php
        }
?>
</tbody>
<?php
    }
?>
</table>
<?php
} else {
?>
<p class="well">No Schedule.</p>
<?php
}
?>
</div><!-- /#space-kitchencars -->

</div><!-- /.span8 -->

<div class="span4 pull-left">

<h4>
  <i class="icon-cog"></i> 基本情報
  <small><a href="#" class="edit-anchor" data-target="#space-default"><i class="icon icon-pencil"></i></a></small>
</h4>
<div id="space-default">
<table class="table table-hover table-bordered">
<tbody>
<tr>
  <th>#</th>
  <td><?php echo absint( $post->serial ); ?></td>
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
  <th>名称</th>
  <td><?php echo $span_title; ?></td>
</tr>
<tr>
  <th>稼働状況</th>
<?php
$phase = absint($post->phase);
$phaseList = array( 0 => '見込み', 1 => 'アクティブ', 8 => '休止中', 9 => '終了' );
$inner = array();
foreach ( $phaseList as $key => $val ) {
    $inner[] = array( 'element' => 'option', 'value' => $key, 'inner' => $val );
}
$arr = array(
    'class' => 'mmsf-replace-to-select',
    'data-name' => 'phase',
    'data-class' => 'span2',
    'data-inner' => json_encode( $inner ),
    'data-value' => $phase
);
$span_phase = '<span ';
foreach ( $arr as $key => $val ) {
    $span_phase .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_phase .= '>' . $phaseList[$phase] . '</span>';
?>
  <td><?php echo $span_phase; ?></td>
</tr>
<?php
$publication = $post->publication ? 1 : 0;
$publicationList = array( 0 => '非公開', 1 => '公開' );
$inner = array();
foreach ( $publicationList as $key => $val ) {
    $inner[] = array( 'element' => 'option', 'value' => $key, 'inner' => $val );
}
$arr = array(
    'class' => 'mmsf-replace-to-select',
    'data-name' => 'publication',
    'data-class' => 'span2',
    'data-inner' => json_encode( $inner ),
    'data-value' => $publication
);
$span_publication = '<span ';
foreach ( $arr as $key => $val ) {
    $span_publication .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_publication .= '>' . $publicationList[$publication] . '</span>';
?>
<tr<?php if ( 0 == $publication ) echo ' class="warning muted"' ?>>
  <th>WEB公開</th>
  <td><?php echo $span_publication; ?></td>
</tr>
<tr>
  <th>トライアル</th>
  <td></td>
</tr>
<tr>
  <th>営業開始</th>
  <td></td>
</tr>
<?php
if ( 9 == $phase ) {
?>
<tr>
  <th>営業終了</th>
  <td></td>
</tr>
<?php
}
?>
</tbody>
</table>
</div><!-- /#space-default -->

<h4>
  <i class="icon icon-map-marker"></i> 所在地情報
  <small><a href="#" class="edit-anchor" data-target="#space-location"><i class="icon icon-pencil"></i></a></small>
</h4>
<div id="space-location">
<table class="table table-hover table-bordered">
<tbody>
<tr>
  <th>市区郡</th>
<?php
$region_obj = get_region_tax_obj();
if ( 0 != $region_obj->parent ) {
    $pref_obj = get_term( $region_obj->parent, 'region' );
    $pref = esc_html( $pref_obj->name );
}
$region = esc_html( $region_obj->name );
?>
  <td><?php echo $pref . $region; ?></td>
</tr>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-name' => 'address',
    'data-class' => 'span3',
    'data-value' => $post->address,
    'data-required' => 'required'
);
$span_address = '<span ';
foreach ( $arr as $key => $val ) {
    $span_address .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_address .= '>' . esc_html( $post->address ) . '</span>';
?>
<tr>
  <th>補記住所</th>
  <td><?php echo $span_address; ?></td>
</tr>
<tr>
  <th>施設</th>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-name' => 'site',
    'data-class' => 'span3',
    'data-value' => $post->site
);
$span_site = '<span ';
foreach ( $arr as $key => $val ) {
    $span_site .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_site .= '>' . esc_html( $post->site ) . '</span>';
?>
  <td><?php echo $span_site; ?></td>
</tr>
<tr>
  <th>詳細エリア</th>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-name' => 'areaDetail',
    'data-class' => 'span3',
    'data-value' => $post->areaDetail
);
$span_areaDetail = '<span ';
foreach ( $arr as $key => $val ) {
    $span_areaDetail .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_areaDetail .= '>' . esc_html( $post->areaDetail ) . '</span>';
?>
  <td><?php echo $span_areaDetail; ?></td>
</tr>
<tr>
  <th>緯度経度</th>
<?php
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'text',
    'data-name' => 'latlng',
    'data-class' => 'span3',
    'data-value' => $post->latlng
);
$span_latlng = '<span ';
foreach ( $arr as $key => $val ) {
    $span_latlng .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_latlng .= '>' . esc_html( $post->latlng ) . '</span>';
?>
  <td><?php echo $span_latlng; ?></td>
</tr>
</tbody>
</table>
</div><!-- /#space-location -->

<h4>
  <i class="icon icon-time"></i> 営業時間
  <small><a href="#" class="edit-anchor" data-target="#space-time"><i class="icon icon-pencil"></i></a></small>
</h4>
<div id="space-time">
<table class="table table-hover table-bordered">
<tbody>
<tr>
  <th>開始</th>
<?php
$start = date( 'G:i', strtotime( $post->starting ) );
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'time',
    'data-name' => 'starting',
    'data-value' => $start,
    'data-class' => 'span2'
);
$span_starting = '<span ';
foreach ( $arr as $key => $val ) {
    $span_starting .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_starting .= '>' . $start . '</span>';
$startingPending = absint( $post->startingPending );
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'checkbox',
    'data-name' => 'startingPending',
    'data-value' => 1,
    'data-outer' => '<label class="checkbox">予定</label>',
    'data-checked' => $startingPending ? 'true' : 'false'
);
$span_sp = '<span ';
foreach ( $arr as $key => $val ) {
    $span_sp .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_sp .= '>';
$span_sp .= $startingPending ? ' (予定)' : '';
$span_sp .= '</span>';
?>
  <td><?php echo $span_starting . $span_sp; ?></td>
</tr>
<tr>
  <th>終了</th>
<?php
$end = date( 'G:i', strtotime( $post->ending ) );
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'time',
    'data-name' => 'ending',
    'data-value' => $end,
    'data-class' => 'span2'
);
$span_ending = '<span ';
foreach ( $arr as $key => $val ) {
    $span_ending .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_ending .= '>' . $end . '</span>';
$endingPending = absint( $post->endingPending );
$arr = array(
    'class' => 'mmsf-replace-to-input',
    'data-type' => 'checkbox',
    'data-name' => 'endingPending',
    'data-value' => 1,
    'data-outer' => '<label class="checkbox">予定</label>',
    'data-checked' => $endingPending ? 'true' : 'false'
);
$span_ep = '<span ';
foreach ( $arr as $key => $val ) {
    $span_ep .= $key . '="' . esc_attr( $val ) . '" ';
}
$span_ep .= '>';
$span_ep .= $endingPending ? ' (予定)' : '';
$span_ep .= '</span>';
?>
  <td><?php echo $span_ending . $span_ep; ?></td>
</tr>
</tbody>
</table>
</div><!-- /#space-time -->

<h4>
  <i class="icon icon-user"></i> W.S.T.D.担当者
  <small><a href="#" class="edit-anchor" data-target="#space-admins"><i class="icon icon-pencil"></i></a></small>
</h4>
<div id="space-admins">
<table class="table table-hover table-bordered">
<tbody>
<tr>
  <th>責任者</th>
  <td>松澤 厚志</td>
</tr>
<tr>
  <th>現場担当者</th>
  <td>松澤 厚志</td>
</tr>
</tbody>
</table>
</div><!-- /#space-admins -->

</div><!-- /.span4 -->

</div><!-- /.row -->

</div><!-- /#management -->

<?php

// get_template_part( 'inc/kitchencars', 'space' );

get_template_part( 'inc/cal', 'space' ); // include module 'inc/cal-space.php'

?>

<div id="operation" class="tab-pane">
</div><!-- /#operation -->

<div id="client" class="tab-pane">
</div><!-- /#client -->

</div><!-- /.tab-content -->

<?php

get_footer();

?>