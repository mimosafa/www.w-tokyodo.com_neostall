<?php

global $name, $menu_arr, $capID;

// image html
$img1 = mmsf_get_lazyload_image( $menu_arr['img1'], array( 370, 278 ) );
$img2 = wp_get_attachment_image( $menu_arr['img2'], 'thumbnail' );
$img2_medium = wp_get_attachment_image( $menu_arr['img2'], 'medium' );

// Array to String
if ( is_array( $menu_arr['item'] ) ) {
    $item = implode_post_title( ', ', $menu_arr['item'] );
} else {
    $item = $menu_arr['item'];
}
if ( is_array( $menu_arr['genre'] ) ) {
    $genre = implode_term_name( ', ', $menu_arr['genre'], 'genre' );
} else {
    $genre = $menu_arr['genre'];
}
?>
<section class="span4 kitchencarCell">
  <div class="nytName">
    <h4><?php echo $name; ?></h4>
  </div>
  <a href="#<?php echo $capID; ?>" class="nytImage">
    <?php echo $img1; ?>
  </a>
  <div id="<?php echo $capID; ?>" class="nytDesc hide">
    <a data-toggle="modal" href="#m-<?php echo $capID; ?>" class="nytImageThumb">
      <?php echo $img2; ?><i class="icon-resize-full icon-2x"></i>
    </a>
    <p>
      <span class="label"><?php echo esc_html( $genre ); ?></span> <?php echo esc_html( $item ); ?>
    </p>
    <p>
      <?php echo esc_html( $menu_arr['text'] ); ?>
    </p>
  </div>
  <div id="m-<?php echo $capID; ?>" class="modal hide fade" tabindex="-1"><?php // ----------------------------------------- Bootstrap Modal ?>
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">×</button>
      <h3 id="myModalLabel">
        <?php echo $name; ?>
      </h3>
    </div>
    <div class="modal-body">
      <div style="text-align:center;margin-bottom:15px;">
        <?php echo $img2_medium; ?>
      </div>
      <p>
        <span class="label"><?php echo esc_html( $genre ); ?></span> <?php echo esc_html( $item ); ?>
      </p>
      <p>
        <?php echo esc_html( $menu_arr['text'] ); ?>
      </p>
    </div>
    <div class="modal-footer">
      <a href="#" class="m-next">
        <i class="icon-angle-right icon-2x"></i>
      </a>
    </div>
  </div>
</section>