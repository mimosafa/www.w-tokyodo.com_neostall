<div id="kitchencars" class="tab-pane" data-mmsf-feemi>

<div class="clearfix">
  <ul class="inline pull-right">
    <li>
      <button class="btn btn-link" type="button" data-mmsf-feemi-toggle="add">
        <i class="icon-plus"></i> Add Kitchencar
      </button>
    </li>
  </ul>
</div>

<div style="display:none;" data-mmsf-feemi-template>

  <?php wp_nonce_field( 'edit_vendor_s_kitchencars', '_neosystem_admin_nonce' ); ?>
  <input type="hidden" data-mmsf-feemi-name="postid" />

  <div class="row">

    <div class="span9 pull-right">
      <table class="table table-hover">
        <tbody>
          <tr>
            <th style="width:150px;">
              <label>車両名</label>
            </th>
            <td>
              <input class="input-xlarge" type="text" data-mmsf-feemi-name="title" placeholder="Japanese" required />
              <input class="input-medium" type="text" data-mmsf-feemi-name="slug" placeholder="半角英数字（ハイフン可）" required />
            </td>
          </tr>
          <tr>
            <th>
              <label>車両No.</label>
            </th>
            <td>
              <input class="span3" type="text" data-mmsf-feemi-name="vin" />
            </td>
          </tr>
          <tr>
            <th>
              <label>車両サイズ</label>
            </th>
            <td>
              <input class="input-small" type="number" data-mmsf-feemi-name="len" placeholder="長さ" />
              <input class="input-small" type="number" data-mmsf-feemi-name="wid" placeholder="幅" />
              <input class="input-small" type="number" data-mmsf-feemi-name="hei" placeholder="高さ" />
            </td>
          </tr>
          <tr>
            <th>
              <label>順番</label>
            </th>
            <td>
              <input type="hidden" class="span2" data-mmsf-feemi-name="order" />
            </td>
          </tr>
          <tr>
            <th>
              <label>フェーズ</label>
            </th>
            <td>
              <select class="span2" data-mmsf-feemi-name="phase">
                <option value="0">見込み</option>
                <option value="1">稼働中</option>
                <option value="8">故障中</option>
                <option value="9">廃車</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
    </div><!-- /.span9 -->

    <div class="span3 pull-left">

      <fieldset class="thumbContainer clearfix" data-mmsf-feemi-name="thumb">
      </fieldset>

      <div data-mmsf-dropzone>
        <div class="hide dz-default dz-message">
          <span>Drag &amp; Drop Photo Item Here, or Click Here to Select Your File.</span>
        </div>
        <button type="button" class="btn btn-small btn-link" data-mmsf-dropzone-toggle>
          <i class="icon-plus"></i> New Image Upload.
        </button>
      </div>

    </div><!-- /.span3 -->

    <div class="span9 pull-right">
      <div class="btn-group pull-right">
        <button type="submit" class="btn" data-mmsf-feemi-submit disabled="disabled">Submit</button>
        <button type="button" class="btn" data-mmsf-feemi-cancel>Cancel</button>
      </div>
    </div>

  </div><!-- /.row -->

  <hr>
</div><!-- /#addItemForm -->

<div class="sortableList">

<?php

global $kitchencars;

if ( isset( $kitchencars ) ) :
    if ( 1 < count( $kitchencars ) )
        wp_nonce_field( 'edit_order', 'nyt_edit_items_order_nonce' ); // nonce
    foreach ( $kitchencars as $post ) {
        setup_postdata( $post );
        if ( ! $thumb = get_the_post_thumbnail( $id, 'medium' ) ) {
            $thumb = '<img src="http://fakeimg.pl/300x225/?text=No Image">'; // "http://fakeimg.pl/" API
        }
        $order = (int) $post->menu_order;
        $vin = esc_html( $post->vin );
        $length = esc_html( $post->length );
        $width = esc_html( $post->width );
        $height = esc_html( $post->height );
        $size = '';
        if ( $length && $width && $height )
            $size = sprintf( '(len) %dmm x (wid) %dmm x (hei) %dmm', $length, $width, $height );
?>
<div class="sortableItem" data-order="<?php echo $order; ?>" data-postid="<?php echo $id; ?>" data-mmsf-feemi-existing>

<div class="row">

<div class="span3">
  <?php echo $thumb; ?>
</div>

<div class="span9">
<table class="table table-hover">
  <tbody>
    <tr>
      <th style="width:150px;">#<?php echo ( $order + 1 ); ?></th>
      <td>
        <a href="<?php the_permalink(); ?>"<strong><?php the_title(); ?></strong></a>
      </td>
    </tr>
    <tr>
      <th>VIN</th>
      <td><?php echo $vin; ?></td>
    </tr>
    <tr>
      <th>SIZE</th>
      <td><?php echo $size; ?></td>
    </tr>
  </tbody>
</table>

<div class="btn-group pull-right"><?php /*
  <a class="editItemTrigger btn btn-link" type="button" data-type="modify">
    <i class="icon-pencil"></i> Edit
  </button> */ ?>
<?php
        if ( 1 < count( $kitchencars ) ) {
?>
  <span class="btn btn-link changeOrder"><i class="icon-move"></i> change order</span>
<?php
        }
?>
</div>

</div>

</div><!-- /.row -->
<hr>

</div>
<?php
    }
    wp_reset_postdata();
else :
?>
<p class="well">No Kitchencars.</p>
<?php
endif;
?>

</div>

</div><!-- /#kitchencars -->