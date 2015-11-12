<?php
$latlng = esc_html( $post->latlng );
$address = esc_html( $post->address );
$site = esc_html( $post->site );
$areaDetail = esc_html( $post->areaDetail );

if ( $latlng ) {
?>
<script>
  function initialize() {
    var latlng = new google.maps.LatLng( <?php echo $latlng; ?> );
    var div = document.getElementById( 'map' );
    var options = {
      center: latlng,
      zoom: 18,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      //mapTypeControl: false,
      navigationControlOptions: {
        style: google.maps.NavigationControlStyle.SMALL,
        position: google.maps.ControlPosition.TOP_LEFT,
      },
      //streetViewControl: false,
      scrollwheel: false,
    };
    var map = new google.maps.Map( div, options );
    var marker = new google.maps.Marker({
      position: map.getCenter(),
      map: map,
    });
    var infowindow = new google.maps.InfoWindow({
      content: '<p><?php echo $site . ' / ' . $areaDetail; ?></p><p><?php echo $address; ?></p>',
    });
  };
  google.maps.event.addDomListener(window, 'load', initialize);
  $('#toggle-right').click(function(){
    $('#map').empty();
    initialize();
  });
</script>
<div id="map"></div>
<?php
}
?>