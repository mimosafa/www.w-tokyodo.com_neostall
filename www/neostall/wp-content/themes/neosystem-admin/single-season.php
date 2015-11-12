<?php
/*
if ( isset( $_POST['area'] ) ) {

    add_post_meta( $post->ID, 'locationData', $_POST['area'] );
}
*/
get_header();

the_post();

$term = get_series_tax_obj();

?>

<div class="container">

<h3>
  <?php the_title(); ?> <small class="muted">( <?php echo $term->name; ?> )</small>
</h3>

<ul class="nav nav-tabs" id="seasonTabs">
  <li><a href="#schedule" data-toggle="tab"><i class="icon-calendar"></i> スケジュール</a></li>
  <li><a href="#locationsite" data-toggle="tab"><i class="icon-map-marker"></i> 施設/出店エリア</a></li>
</ul>

<div class="tab-content">

<?php

get_template_part( 'inc/schedule', 'season' ); // include module 'inc/schedule-season.php'

get_template_part( 'inc/location', 'season' ); // include module 'inc/location-season.php'

?>

</div><!-- /.tab-content -->

</div><!-- /.container -->

<?php

get_footer();

?>