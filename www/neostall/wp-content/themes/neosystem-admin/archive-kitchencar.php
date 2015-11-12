<?php

get_header();

$url = get_post_type_archive_link( 'kitchencar' );

?>
<h3>Kitchencars</h3>

<?php
if ( have_posts() ) :
?>
<table class="table table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>キッチンカー名 <small class="muted">(WEB表示名)</small></th>
      <th>事業者名</th>
      <th>車両No.</th>
      <th>長さ</th>
      <th>幅</th>
      <th>高さ</th>
      <th>Phase</th>
    </tr>
  </thead>
  <tbody>
<?php
    while ( have_posts() ) : the_post();
        $serial = esc_html( $post->serial );
        $name = get_the_title();
        if ( !empty( $post->name ) )
            $name .= ' <small class="muted">(' . esc_html( $post->name ) . ')</small>';
        $vendor = esc_html( get_the_title( $post->post_parent ) );
        $v_link = esc_html( get_permalink( $post->post_parent ) );
        $vin = esc_html( $post->vin );
        $len = esc_html( $post->length );
        $wid = esc_html( $post->width );
        $hei = esc_html( $post->height );
        $phase = absint( $post->phase );
        $trClass = '';
        if ( 9 == $phase )
            $trClass = 'muted';
?>
    <tr class="<?php echo $trClass; ?>">
      <td>
        <?php echo $serial; ?>
      </td>
      <td>
        <a href="<?php the_permalink(); ?>"<?php if ( 9 == $phase ) echo ' class="muted"'; ?>><?php echo $name; ?></a>
      </td>
      <td>
        <?php printf( '<a class="muted" href="%s">%s</a>', $v_link, $vendor ); ?>
      </td>
      <td>
        <?php echo $vin; ?>
      </td>
      <td>
        <?php echo $len; ?>
      </td>
      <td>
        <?php echo $wid; ?>
      </td>
      <td>
        <?php echo $hei; ?>
      </td>
      <td>
        <?php if ( 1 !== $phase ) echo get_cf_phase_label(); ?>
      </td>
    </tr>
<?php
    endwhile;
else :
?>
<p class="well">No Kitchencars.</p>
<?php
endif;
?>
  </tbody>
</table>

<?php
previous_posts_link();
next_posts_link();

if ( !empty( $_SERVER['argv'] ) )
    echo '<a href="' . $url . '">All Kitchencars</a>';
?>
<?php get_footer(); ?>