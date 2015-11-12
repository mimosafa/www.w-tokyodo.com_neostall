!function($){

  $('.panel').removeClass('loading');
  $('.panel-inner:first').fadeIn();
  $('.tab-ul li:first').addClass('active');
  $('.tab-ul').fadeIn();
  $('.tab-ul li').on('click', function(e) {
    e.preventDefault();
    $('.tab-ul li').removeClass('active');
    $(this).addClass('active');
    $('.panel-inner').hide();
    $($(this).find('a').attr('href')).fadeIn();
    window.scrollBy(0,1); // for lazyload.
  });

  $('[data-location]').on('click', function() {
    var location = $(this).data('location'),
        target = $('#span-location');
    target.fadeOut('fast', function() {
      $(this).html(location);
      $(this).fadeIn('fast');
    });
  });

  $('[data-timetable]').on('click', function() {
    var timetable = $(this).data('timetable'),
        target = $('#span-timetable');
    target.fadeOut('fast', function() {
      $(this).html(timetable);
      $(this).fadeIn('fast');
    });
  });

}(window.jQuery);