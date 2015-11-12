<div class="clearfix">
  <ul class="inline pull-right">
    <li>
      <button class="editItemTrigger btn btn-link" type="button" data-type="add">
        <i class="icon-plus"></i> Add Menu Set
      </button>
    </li>
  </ul>
</div>

<?php // ------------------------------------------------------------------------------ ?>

<div id="menuSetFormDiv" style="display: none;">

<legend></legend>
<?php wp_nonce_field( 'edit_kitchencar_s_menu_set', '_neosystem_admin_nonce' ); ?>
<div class="row">

<div class="span9 pull-right">

<table class="table table-hover">
<tbody>

<tr>
<th style="width:150px;">
<label for="menuSetCat1">CATEGORY 1</label>
</th>
<td>
<select class="span3" id="menuSetCat1">
  <option value="default">ランチ・イベント共通メニュー</option>
  <option value="weekly">ランチメニュー</option>
  <option value="event">イベントメニュー</option>
</select>
</td>
</tr>

<tr>
<th>
<label for="menuSetCat1">CATEGORY 2</label>
</th>
<td>
<input type="hidden" id="menuSetCat2" class="span4" />
</td>
</tr>

<tr>
<th>
<label for="menuSetItem">ITEMS</label>
</th>
<td>
<input type="hidden" id="menuSetItem" class="span5" />
</td>
</tr>

<tr>
<th>
<label for="menuSetGenre">GENRE</label>
</th>
<td>
<input type="hidden" id="menuSetGenre" class="span4" />
</td>
</tr>

<tr>
<th>
<label for="menuSetText">TEXT</label>
</th>
<td>
<textarea id="menuSetText" class="span5"></textarea>
<input type="hidden" id="menuSetTextTemp" class="span5" />
</td>
</tr>

</tbody>
</table>
</div><!-- /.span9 -->

<div class="span3 pull-left">
<label>Select Kitchencar Image</label>
<div id="imageDiv1" class="thumbContainer clearfix"></div>
<hr>
<label>Select Menu Item Image</label>
<div id="imageDiv2" class="thumbContainer clearfix"></div>
</div><!-- /.span3 -->

<div class="span9 pull-right">
  <div id="removeMenuItemAlrt" class="hide alert alert-warning clearfix">
    Are you Sure you want to Remove This Menu Item ?
    <div class="btn-group pull-right">
      <button class="btn btn-small btn-danger" type="submit">Yes, remove this Item.</button>
      <button class="btn btn-small" type="button" id="removeMenuItemCncl">No</button>
    </div>
  </div>
  <div class="btn-group pull-right">
    <button type="submit" id="editMenuSetSubmit" class="btn" disabled="disabled">Submit</button>
    <button type="button" id="removeMenuSet" class="hide btn btn-danger">Remove</button>
    <button type="button" id="editMenuSetCancel" class="btn">Cancel</button>
  </div>
</div>

</div><!-- /.row -->
<hr>

</div><!-- /#addItemForm -->

<?php // ------------------------------------------------------------------------------ ?>

<?php
$cfkeys   = get_post_custom_keys();
$items    = array( 'default' => array(), 'weekly' => array(), 'event' => array() );
$daynames = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );

foreach ( $cfkeys as $cfkey ) {
    if ( preg_match( '/^_/', $cfkey ) ) {
        continue;
    } elseif ( preg_match( '/^default_/', $cfkey ) ) {
        $arg = str_replace( 'default_', '', $cfkey );
        $items['default'][$arg] = get_post_meta( $post->ID, $cfkey, true );
    } elseif ( preg_match( '/^weekly_/', $cfkey ) ) {
        $arg = str_replace( 'weekly_', '', $cfkey );
        $items['weekly'][$arg] = get_post_meta( $post->ID, $cfkey, true );
    } elseif ( preg_match( '/^event_/', $cfkey ) ) {
        $arg = str_replace( 'event_', '', $cfkey );
        $items['event'][$arg] = get_post_meta( $post->ID, $cfkey, true );
    }
}

foreach ( $items as $type => $itemsArray ) :
    if ( 'default' == $type ) {
        $str = 'ランチ・イベント共通メニュー';
    } elseif ( 'weekly' == $type ) {
        $str = 'ランチメニュー';
    } elseif ( 'event' == $type ) {
        $str = 'イベントメニュー';
    }
    ksort( $itemsArray, SORT_NATURAL );
?>

<div class="items-div" data-type="<?php echo esc_attr( $type ); ?>" data-label="<?php echo esc_attr( $str ); ?>">
<h4><?php echo $str; ?></h4>

<?php
    if ( ! empty( $itemsArray ) ) :
?>

<table class="table table-hover table-bordered table-condensed">
  <thead>
    <tr>
      <th>#</th>
      <th>item</th>
      <th>genre</th>
      <th>text</th>
      <th width="94">img1</th>
      <th width="94">img2</th>
      <th width="94">Edit!</th>
    </tr>
  </thead>
<tbody>
<?php
        foreach ( $itemsArray as $key => $val ) :
            if ( ! is_numeric( $key ) ) {
                if ( 'weekly' == $type ) {
                    $s_key = mb_substr( __( ucwords( $key ) ), 0, 1 );
                } elseif ( 'event' == $type ) {
                    $term = get_term_by( 'slug', $key, 'series' );
                    $s_key = $term->name;
                }
            } else {
                $s_key = $key + 1;
            }
            if ( !$img1 = wp_get_attachment_image( $val['img1'], array( 85, 85 ) ) )
                $img1 = '<img width="85" height="85" src="' . get_template_directory_uri() . '/images/noimage-150x150.png">';
            if ( !$img2 = wp_get_attachment_image( $val['img2'], array( 85, 85 ) ) )
                $img2 = '<img width="85" height="85" src="' . get_template_directory_uri() . '/images/noimage-150x150.png">';
?>
<tr id="<?php echo esc_attr( "{$type}_{$key}" ); ?>">
<td><?php
            echo $s_key;
?></td>
<td><?php
            echo implode_post_title( ', ' , $val['item'], true );
?></td>
<td><?php
            echo implode_term_name( ', ' , $val['genre'], 'genre' );
?></td>
<td><?php
            echo esc_html( $val['text'] );
?></td>
<td><?php
            echo $img1;
?></td>
<td><?php
            echo $img2;
?></td>
<td>
  <div class="btn-group btn-group-vertical">
    <button class="editItemTrigger btn btn-link" type="button" data-type="modify">
      <i class="icon-pencil"></i> Edit
    </button>
  </div>
</td>
</tr>
<?php
        endforeach;
?>
</tbody>
</table>

<?php
    else :
        printf( '<p class="well muted text-center">提供メニューリスト [%s] は登録されていません</p>', $str );
    endif;
?>

</div><!-- /.items-div -->

<?php
endforeach;
?>