<?php
global $post, $obj, $str;
$phase = absint( $obj->phase );
$kitchencar_id = $obj->actOf ? absint( $obj->actOf ) : $obj->ID;
$kit_link = get_permalink( $kitchencar_id );
$name = get_the_display_name( $kitchencar_id );
$commentClass = '';
if ( 9 === $phase ) {
    $name = '<del>' . $name . '</del>';
    $name .= ' <small>※お休みいたします</small>';
    $commentClass .= " absent";
}
$menu_arr = get_the_menuitems( $obj->ID, $post->post_type, $str );
?>
<div class="shop clearfix">
  <div class="comment<?php echo $commentClass; ?>">
    <h3><a href="<?php echo $kit_link; ?>"><?php echo $name; ?></a></h3>
    <p><?php echo esc_html( $menu_arr['text'] ); ?></p>
  </div>
<?php
if ( 9 !== $phase ) :
?>
  <div class="photo">
<?php
    $car_img_html = '';
    if ( $img1 = absint( $menu_arr['img1'] ) ) {
        $car_img = wp_get_attachment_image_src( $img1, 'medium' );
        $car_img_url = wp_get_attachment_url( $img1 );
        $car_img_html .= '<a class="modal" href="' . esc_url( $car_img_url ) . '">';
        $car_img_html .= '<img src="' . get_stylesheet_directory_uri() . '/images/white.gif" data-original="' . esc_url( $car_img[0] ) . '" width="88" height="66" class="lazy" />';
        $car_img_html .= '<noscript>';
        $car_img_html .= '<img src="' . esc_url( $menu_img[0] ) . '" width="88" height="66" />';
        $car_img_html .= '</noscript>';
        $car_img_html .= '</a>';
    } else {
        $car_img_html .= '<div style="display:inline;float:left;">';
        $car_img_html .= '<img src="' . get_stylesheet_directory_uri() . '/images/white.gif" data-original="' . get_template_directory_uri() . '/images/noimage-300x225.png" width="88" height="66" class="lazy" />';
        $car_img_html .= '<noscript>';
        $car_img_html .= '<img src="' . get_template_directory_uri() . '/images/noimage-300x225.png" width="88" height="66" />';
        $car_img_html .= '</noscript>';
        $car_img_html .= '</div>';
    }
    echo $car_img_html;

    $item_img_html = '';
    if ( $img2 = absint( $menu_arr['img2'] ) ) {
        $item_img = wp_get_attachment_image_src( $img2, 'medium' );
        $item_img_url = wp_get_attachment_url( $img2 );
        $item_img_html .= '<a class="modal" href="' . esc_url( $item_img_url ) . '">';
        $item_img_html .= '<img src="' . get_stylesheet_directory_uri() . '/images/white.gif" data-original="' . esc_url( $item_img[0] ) . '" width="88" height="66" class="lazy" />';
        $item_img_html .= '<noscript>';
        $item_img_html .= '<img src="' . esc_url( $menu_img[0] ) . '" width="88" height="66" />';
        $item_img_html .= '</noscript>';
        $item_img_html .= '</a>';
    } else {
        $item_img_html .= '<div style="display:inline;float:left;">';
        $item_img_html .= '<img src="' . get_stylesheet_directory_uri() . '/images/white.gif" data-original="' . get_template_directory_uri() . '/images/noimage-300x225.png" width="88" height="66" class="lazy" />';
        $item_img_html .= '<noscript>';
        $item_img_html .= '<img src="' . get_template_directory_uri() . '/images/noimage-300x225.png" width="88" height="66" />';
        $item_img_html .= '</noscript>';
        $item_img_html .= '</div>';
    }
    echo $item_img_html;
?>
  </div>
  <div class="shopinfo clearfix">
    <dl>
      <dt>ジャンル</dt>
      <dd><?php echo implode_term_name( ', ' , $menu_arr['genre'], 'genre' ); ?></dd>
    </dl>
    <dl>
      <dt>メニュー</dt>
      <dd><?php echo implode_post_title( ', ' , $menu_arr['item'] ); ?></dd>
    </dl>
  </div>
<?php
endif;
?>
</div>