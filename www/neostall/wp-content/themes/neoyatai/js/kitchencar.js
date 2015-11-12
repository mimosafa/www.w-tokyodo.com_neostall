!function($) {

  // @space Single Kitchencar Caption
  $( '.nytImage' ).hcaptions({
    effect : 'fade',
    speed  : 200
  });

  // lazyload
  $( 'img.lazy' ).show().lazyload({
    event : 'scroll',
    effect : 'fadeIn'
  });

  $( '.nytName h4' ).fadeIn( 400 );

  $( '.m-next' ).click( function(e){
    e.preventDefault();
    $(this).parent().parent( 'div.modal' ).modal( 'hide' );
    var section = $(this).closest( 'section' );
    var next = section.next( 'section' );
    next.children( 'div.modal' ).modal( 'show' );
  });

}(window.jQuery);