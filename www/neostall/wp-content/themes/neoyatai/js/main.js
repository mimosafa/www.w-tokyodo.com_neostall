!function($) {

  // WSTD Company ID - Bootstrap Dropdown
  $('#wstd-id').dropdown();

  // Back to Top
  $(window).scroll(function(){
    if($(this).scrollTop() > 200){
      $('#backToTop').fadeIn();
    }else{
      $('#backToTop').fadeOut();
    }
  });
  $('#backToTop a').click(function(e){
    e.preventDefault();
    $('body, html').animate({
      scrollTop:0
    },500);
  });

  // Bootstrap Tab UI
  $('#nytTab a:first').tab('show'); // Default
  $('#nytTab a#visible-tab').tab('show'); // if especialy display tab, for example today's tab on 'space' single page.
  $('#nytTab a').on('shown', function() {
    window.scrollBy(0,1); // for lazyload
    var locStr = $(this).data('location');
    if(locStr){
      $('#span-location').hide().html(locStr).fadeIn();
    }
    var ttStr = $(this).data('timetable');
    if(ttStr){
      $('#span-timetable').hide().html(ttStr).fadeIn();
    }
  });

  $('.nytAffix').affix({
    offset: {
      top:372,
      bottom:200
    }
  });

  //
  $('.archiveCell').click(function(){
    window.location=$(this).find('a').attr('href');
  });

  $('a[href=#map]').click(function(e){
    e.preventDefault();
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
  });

}(window.jQuery);