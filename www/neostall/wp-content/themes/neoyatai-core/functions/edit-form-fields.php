<?php

add_action ( 'series_edit_form_fields', 'series_location_data_fields' );
function series_location_data_fields( $series ) {
    $series_slug = $series->slug;
    $series_meta = get_option( "series_{$series_slug}" );
?>
<tr class="form-field">
  <th>出店エリア情報</th>
  <td style="padding:0;">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>地域</th>
          <th>補記住所</th>
          <th>施設名</th>
          <th>出店エリア詳細</th>
          <th>緯度経度</th>
          <th>edit</th>
        </tr>
      </thead>
      <tbody>
<?php
    $i = 0;
    do {
?>
        <tr>
          <td><?php echo ( $i + 1 ); ?></td>
          <td><?php
    wp_dropdown_categories(
        array(
            'taxonomy' => 'region',
            'selected' => $series_meta['locationData'][$i]['region'],
            'hierarchical' => true,
            'hide_empty' => false,
            'name' => 'series_meta[locationData][' . $i . '][region]'
        )
    );
          ?></td>
          <td><input type="text" name="series_meta[locationData][<?php echo $i; ?>][address]" value="<?php echo $series_meta['locationData'][$i]['address'] ?>" /></td>
          <td><input type="text" name="series_meta[locationData][<?php echo $i; ?>][site]" value="<?php echo $series_meta['locationData'][$i]['site'] ?>" /></td>
          <td><input type="text" name="series_meta[locationData][<?php echo $i; ?>][areaDetail]" value="<?php echo $series_meta['locationData'][$i]['areaDetail'] ?>" /></td>
          <td><input type="text" name="series_meta[locationData][<?php echo $i; ?>][latlng]" value="<?php echo $series_meta['locationData'][$i]['latlng'] ?>" /></td>
          <td><button type="button" class="button remove-column">remove</button></td>
        </tr>
<?php
        $i++;
    } while ( isset( $series_meta['locationData'][$i] ) );
?>
      </tbody>
    </table>
    <button type="button" id="add-column" class="button">Add Column</button>
  </td>
</tr>
<script>
!function($) {

  $('#add-column').click(function() {
    var table = $(this).prev('table'),
        tr = table.children('tbody').children('tr:first-child');
    console.log(tr);
  });

}(window.jQuery);
</script>
<?php
}

add_action ( 'edited_term', 'save_series_location_data_fields');
function save_series_location_data_fields( $term_id ) {

    if ( isset( $_POST['series_meta'] ) ) {

        $series = get_term( $term_id, 'series' );
        $series_slug = $series->slug;
        $series_meta = get_option( "series_{$series_slug}" );
        $series_keys = array_keys( $_POST['series_meta'] );
        foreach ( $series_keys as $key ) {
            if ( isset( $_POST['series_meta'][$key] ) ) {
                $series_meta[$key] = $_POST['series_meta'][$key];
            }
        }
        update_option( "series_{$series_slug}", $series_meta );

    }

}