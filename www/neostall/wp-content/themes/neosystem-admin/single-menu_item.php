<?php

get_header();

the_post();

$vendorTtl = get_the_title( $post->post_parent );
$vendorUrl = get_permalink( $post->post_parent );
$vendor = sprintf( '<a href="%s">%s</a>', $vendorUrl, $vendorTtl );

$order = (int) $post->menu_order + 1;

?>
<h3>
  <i class="icon-food"></i> Menu Item #<?php echo $order; ?>
  <small> / <?php echo $vendor; ?></small>
</h3>

<div class="row">

<div class="span3">
<?php
if ( ! $thumb = get_the_post_thumbnail( $menu_item->ID, 'medium' ) ) {
    $thumb = '<img src="http://fakeimg.pl/300x225/?text=No Image">'; // "http://fakeimg.pl/" API
}
echo $thumb;
?>
</div>

<div class="span9">

<table class="table table-hover table-borderd">

<tbody>

<?php
// Serial
$title = get_the_title();
$attr = '';
$attr .= 'data-field="post_title" ';
$attr .= 'data-value="' . esc_attr( $title ) . '" ';
$attr .= 'data-original="' . esc_attr( $title ) . '" ';
$attr .= 'data-element="text" ';
?>
<tr>
<th>NAME</th>
<td <?php echo $attr; ?>>
  <span><?php echo esc_html( $title ); ?></span>
  <a href="#" class="edit"> <i class="icon-pencil"></i></a>
  <a href="#" class="done hide"> <i class="icon-ok"></i></a>
  <a href="#" class="cncl hide"> <i class="icon-remove"></i></a>
</td>
</tr>

<?php
$genre = '';
$gnrVal = "'[";
$gnrArr = get_the_terms( $id, 'genre' );
if ( ! empty( $gnrArr ) ) {
    foreach ( $gnrArr as $gnr ) {
        $genre .= esc_html( $gnr->name );
        $genre .= ', ';
        $gnrVal .= sprintf( '"%d",', $gnr->term_id );
    }
    $genre = substr( $genre, 0, -2 );
    $gnrVal = substr( $gnrVal, 0, -1 );
}
$gnrVal .= "]'";
$attr = '';
$attr .= 'data-field="genre" ';
$attr .= sprintf( 'data-value=%s ', $gnrVal );
$attr .= 'data-original="' . esc_attr( $genre ) . '" ';
$attr .= 'data-element="" '; // ----------------------------------------------
?>
<tr>
<th>GENRE</th>
<td <?php echo $attr; ?>>
  <span><?php echo $genre; ?></span>
</td>
</tr>

<tr>
<th>DESCRIPTION</th>
<td><?php echo get_the_content(); ?></td>
</tr>

</tbody>

</table>

</div>

</div><!-- /.row -->

<?php

get_footer();

?>