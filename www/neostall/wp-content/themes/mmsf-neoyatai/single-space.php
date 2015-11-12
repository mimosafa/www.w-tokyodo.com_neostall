<?php

function location_html() {
	global $post;
	$loc = '';
	$terms = get_the_region_objects( $post );
	foreach ( $terms as $term ) {
		$loc .= sprintf(
			'<a href="%s">%s</a>',
			esc_url( get_term_link( $term, 'region' ) ),
			esc_html( $term -> name )
		);
		$loc .= ' ';
	}
	$keys = [ 'address', 'site', 'areaDetail' ];
	foreach ( $keys as $key ) {
		if ( $val = get_post_meta( $post -> ID, $key, true ) ) {
			$loc .= esc_html( $val ) . ' ';
		}
	}
	echo rtrim( $loc );
}

function open_html() {
	global $post;
	$keys = [ 'starting', 'ending' ];
	$array = [];
	foreach ( $keys as $key ) {
		if ( $time = get_post_meta( $post -> ID, $key, true ) ) {
			$array[$key] = strval( $time );
			if ( get_post_meta( $post -> ID, "{$key}Pending", true ) )
				$array[$key] .= ' <small>(予定)</small>';
		}
	}
	if ( !$array ) {
		echo '未定';
	} else {
		if ( 2 === count( $array ) ) {
			echo implode( ' ~ ', $array );
		} else {
			if ( isset( $array['starting'] ) )
				echo $array['starting'] . ' ~';
			else
				echo '~ ' . $array['ending'];
		}
	}
}

/**
 *
 */
get_header(); ?>

<header id="space-single-header">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <div id="default-information">
      <p><i class="fa fa-map-marker"></i> <?php location_html(); ?></p>
      <p><i class="fa fa-clock-o"></i> <?php open_html(); ?></p>
    </div>
  </div>
</header>

<div class="container" id="wstd-contents">

<?php
do_action( 'neoyatai_singular_contents' );
do_action( 'neoyatai_contents' ); ?>

<?php /*
<div id="google-map" style="height:450px;"></div>
<script>
var latlng = new google.maps.LatLng(<?php echo $neoyatai -> location['latlng']; ?>);
var myOptions = {
  zoom: 18,
  center: latlng,
  mapTypeId: google.maps.MapTypeId.ROADMAP,
  scrollwheel: false,
  draggable: false
};
var map = new google.maps.Map(document.getElementById('google-map'), myOptions);
var marker = new google.maps.Marker({
  position: latlng,
  map: map,
  title: <?php echo "'" . esc_html( $neoyatai -> name ) . "'"; ?>
});
</script>

<!--
<section>
<pre>
<?php var_dump( $neoyatai ); ?>
</pre>
</section>-->
*/ ?>

</div>

<?php

get_footer();