<div id="locationsite" class="tab-pane row">
<div class="span10 pull-right">
<?php

$area_array = get_post_meta( $id, 'locationData', true );

if ( ! empty( $area_array ) ) :

?>
<table class="table table-hover">
<thead>
<tr>
<th>地域</th>
<th>住所</th>
<th>施設</th>
<th>詳細エリア</th>
<th>緯度経度</th>
</tr>
</thead>
<tbody>
<?php

    foreach ( $area_array as $area ) {

        // 地域
        $region_obj = get_term( $area['region'], 'region' );
        $region = $region_obj->name;

        // 住所
        $address = $area['address'];

        // 施設
        $site = $area['site'];

        // 詳細エリア
        $areaDetail = $area['areaDetail'];

        // 緯度経度
        $latlng     = $area['latlng'];

?>
<tr>
<td><?php echo $region; ?></td>
<td><?php echo $address; ?></td>
<td><?php echo $site; ?></td>
<td><?php echo $areaDetail; ?></td>
<td><?php echo $latlng; ?></td>
</tr>
<?php

    }

?>
<tbody>
</table>
<?php

endif;

?>
</div>
<div class="span2 pull-left">
</div>
</div>