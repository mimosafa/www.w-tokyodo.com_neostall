<?php

get_header();

the_post();

$ttl = '';
if ( $series = get_series_tax_obj() )
    $ttl .= esc_html( $series->name ) . ' ';
$ttl .= get_the_title();
$dayStr = date( 'Y/n/j D', strtotime( $post->day ) );
$eventsData = $post->eventsData;

?>
<div class="headerWrapper">
  <div class="container">
    <h2><?php echo $ttl; ?></h2>
    <h3><i class="icon-calendar"></i> <?php echo $dayStr; ?></h3>
    <p>
      <i class="icon-map-marker"></i> <?php echo get_location_str(); ?>&ensp;
      <i class="icon-time"></i> <?php echo get_opening_time_str(); ?>
    </p>
  </div>
</div>

<div class="container">
<div class="row">
<?php

// include module 'inc/evt.php'
// This module needs EVENT post data as $post.
get_template_part( 'inc/evt' );

?>
</div><!-- /.row -->
</div><!-- /.contaniner -->
<?php

get_footer();

?>