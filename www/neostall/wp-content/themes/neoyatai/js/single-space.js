!function($) {

  var today   = new Date(),
      weeks   = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
      dayname = weeks[today.getDay()],
      f       = false;
  $( '#tabContentSpace' ).children( 'div.tab-pane ').each( function() {
    var id = $(this).attr( 'id' );
    var a  = $( '#tabWeeks' ).find( 'a[href=#' + id + ']' )
    a.parent( 'li' ).removeClass( 'hide' );
    if ( id == dayname ) {
      a.tab( 'show' ).attr( 'title', 'Today' ).prepend( '<i class="icon-bookmark"></i> ' ); // Bootstrap
      f = true;
    }
  });
  if ( !f )
    $( '#tabWeeks a[href=#calendar]' ).tab( 'show' ); // Bootstrap
  $( '#tabWeeks a' ).on( 'shown', function() { // for lazyload
    window.scrollBy( 0, 1 );
  });

}(window.jQuery);