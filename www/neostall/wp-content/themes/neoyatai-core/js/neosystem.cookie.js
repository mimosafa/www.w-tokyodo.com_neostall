!function($){

  var on  = $('#neosystem-switch-on'),
      off = $('#neosystem-switch-off');

  on.click(function(e){

    e.preventDefault();

    $.cookie('__neosystem_switch',true,{path:'/'});
    window.location.reload();

  });

  off.click(function(e){

    e.preventDefault();

    $.removeCookie('__neosystem_switch',{path:'/'});
    window.location.reload();

  });

}(window.jQuery);