<div id="menu_items" class="tab-pane" data-mmsf-feemi>

<div class="clearfix">
  <ul class="inline pull-right">
    <li>
      <button class="btn btn-link" type="button" data-mmsf-feemi-toggle="add">
        <i class="icon-plus"></i> Add Menu Item
      </button>
    </li>
  </ul>
</div>

<div style="display:none;" data-mmsf-feemi-template>

  <?php wp_nonce_field( 'edit_vendor_s_menu_items', '_neosystem_admin_nonce' ); ?>
  <input type="hidden" data-mmsf-feemi-name="postid" />

  <div class="row">

    <div class="span9 pull-right">
      <table class="table table-hover">
        <tbody>
          <tr>
            <th style="width:150px;">
              <label>NAME</label>
            </th>
            <td>
              <input class="span3" type="text" data-mmsf-feemi-name="title" required />
            </td>
          </tr>
          <tr>
            <th>
              <label>GENRE</label>
            </th>
            <td>
              <input type="hidden" class="span4" data-mmsf-feemi-name="genre" required />
            </td>
          </tr>
          <tr>
            <th>
              <label>DESCRIPTION</label>
            </th>
            <td>
              <textarea class="span5" data-mmsf-feemi-name="content"></textarea>
            </td>
          </tr>
          <tr>
            <th>
              <label>ORDER</label>
            </th>
            <td>
              <input type="hidden" class="span2" data-mmsf-feemi-name="order" />
            </td>
          </tr>
        </tbody>
      </table>
    </div><!-- /.span9 -->

    <div class="span3 pull-left">

      <fieldset class="thumbContainer clearfix" data-mmsf-feemi-name="thumb">
<?php
$attachments = get_children(
    array(
        'post_parent' => $id,
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'post_status' => 'inherit'
    )
);
foreach ( $attachments as $attachment ) {
    $attachment_id = $attachment->ID;
    $attachment_html = wp_get_attachment_image( $attachment_id, 'thumbnail' );
?>
        <label>
          <input type="radio" value="<?php echo $attachment_id; ?>" />
          <?php echo $attachment_html; ?>
        </label>
<?php
}
?>
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
</div>

<div class="sortableList">

<?php // --------------------- loop: vendor's 'menu_item'

global $menuItems;

if ( isset( $menuItems ) ) :
    if ( 1 < count( $menuItems ) )
        wp_nonce_field( 'edit_order', 'nyt_edit_items_order_nonce' ); // nonce
    foreach ( $menuItems as $post ) :
        setup_postdata( $post );
        $attr = '';
        $attr .= sprintf( 'data-mmsf-feemi-postid="%d" ', $id );
        $attr .= sprintf( 'data-mmsf-feemi-title="%s" ', esc_attr( get_the_title() ) );
        $attr .= sprintf( 'data-mmsf-feemi-content="%s" ', esc_attr( get_the_content() ) );
        if ( ! $thumb = get_the_post_thumbnail( $id, 'medium' ) ) {
            $thumb = sprintf( '<img src="%s">', get_template_directory_uri() . '/images/noimage-300x225.png' ); // "http://fakeimg.pl/" API
        } else {
            $attr .= sprintf( 'data-mmsf-feemi-thumb="%d" ', $post->_thumbnail_id );
        }
        $order = (int) $post->menu_order;
        $attr .= sprintf( 'data-mmsf-feemi-order="%d" ', $order );
        $genre = '';
        $gnrArr = get_the_terms( $post, 'genre' );
        if ( ! empty( $gnrArr ) ) {
            $attr .= "data-mmsf-feemi-genre='[";
            foreach ( $gnrArr as $gnr ) {
                $genre .= esc_html( $gnr->name );
                $genre .= ', ';
                $attr .= sprintf( '"%s",', esc_attr( $gnr->name ) );
            }
            $genre = substr( $genre, 0, -2 );
            $attr = substr( $attr, 0, -1 );
            $attr .= "]'";
        }
?>
<div id="menuitem-<?php the_ID(); ?>" class="sortableItem" <?php echo $attr; ?> data-mmsf-feemi-existing>

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
      <th>GENRE</th>
      <td><?php echo $genre; ?></td>
    </tr>
    <tr>
      <th>DESCRIPTION</th>
      <td><?php the_content(); ?></td>
    </tr>
  </tbody>
</table>

<div class="btn-group pull-right">
  <button class="btn btn-link" type="button" data-mmsf-feemi-toggle="modify">
    <i class="icon-pencil"></i> Edit
  </button>
<?php
        if ( 1 < count( $menuItems ) ) {
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
    endforeach;
    wp_reset_postdata();
else :
?>
<p class="well">No Menu Items.</p>
<?php
endif;
?>

</div>

</div><!-- /#menu_items -->