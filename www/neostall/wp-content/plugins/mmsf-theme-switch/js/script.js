!function($){

  var sKey = MMSF_THEME_SWITCH.switch_key,
      sVal = MMSF_THEME_SWITCH.switch_val;

  var themeSwitch  = $('#mmsf-theme-switch');

  themeSwitch.click(function(e){
    e.preventDefault();

    var cookie = $.cookie(sKey);
    if (cookie) {
      $.removeCookie(sKey, {path:'/'});
    } else {
      $.cookie(sKey, sVal, {path:'/'})
    }
    window.location.reload();

  });

}(window.jQuery);