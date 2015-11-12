<?php

/**
 *
 */ ?>

<div class="container"?>

  <ul id="weekly-tabs" class="nav nav-pills">
    <li v-repeat="tab"><a href="#week" data-sort="{{dayname}}">{{daynameLocal}}</a></li>
    <li><a href="#calendar"><i class="fa fa-calendar"></i> <?php _e( 'Calendar' ); ?></a></li>
  </ul>

  <div class="tab-contents">

    <div class="tab-pane" id="week">
    </div>

    <div class="tab-pane" id="calendar">
    </div>

  </div>

  <div id="google-map" style="height:450px;"></div>

  <script>
    var latlng = new google.maps.LatLng(<?php echo get_post_meta( $post -> ID, 'latlng', true );; ?>);
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
      /*title: <?php //echo "'" . esc_html( $neoyatai -> name ) . "'"; ?>*/
    });
  </script>

</div>

<?php
add_action( 'wp_footer', function() { ?>
<script>
  ( function( $ ) {
    var data = _NEOYATAI_CONTENTS_ALL;

    var header = new Vue( {
      el: '#space-single-header',
      data: {
        title: data.name,
        region: data.region,
        /*
        location: 'Marunouchi',
        open: '11:30~'
        */
      },
      computed: {
        location: function() {
          var str = '';
          $.each( this.region, function( i, v ) {
            str += v.name;
          } );
          return str;
        }
      }
    } );

    var tabs = new Vue( {
      el: '#weekly-tabs',
      data: {
        tab: [
          {
            dayname: 'Monday',
            daynameLocal: '月曜日'
          },
          {
            dayname: 'Tuesday',
            daynameLocal: '火曜日'
          }
        ]
      }
    } );
  } )( jQuery );
</script>
<?php
}, 99 );
