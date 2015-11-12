!function($){

  var today   = new Date(),
      weeks   = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
      dayname = weeks[today.getDay()],
      f       = false;
  $('.panel').removeClass('loading');
  $( '#weekSche' ).children('.panel-inner').each( function() {
    var id = $(this).attr( 'id' ),
        a  = $( '#tabWeeks' ).find( 'a[href=#' + id + ']' );
    a.parent('li').show();
    if ( id == dayname ) {
      a.parent('li').addClass('active');
      $(this).fadeIn();
      f = true;
    }
  });
  if (!f) {
    if ( $('#calendar').length > 0 ) {
      $('#calendar').fadeIn();
      $('a[href="#calendar"]').parent('li').addClass('active');
    } else {
      $('.panel-inner:first').fadeIn();
      var actv = $('.panel-inner:first').attr('id');
      $('a[href="#' + actv + '"]').parent('li').addClass('active');
    }
  }

  $('.tab-ul li').on('click', function(e) {
    e.preventDefault();
    $('.tab-ul li').removeClass('active');
    $(this).addClass('active');
    $('.panel-inner').hide();
    $($(this).find('a').attr('href')).fadeIn();
    window.scrollBy(0,1); // for lazyload.
  });

}(window.jQuery);