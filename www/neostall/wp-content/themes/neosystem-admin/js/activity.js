!function($){

  $('.edit-anchor').on('click', function(e) {

    e.preventDefault();

    var wrapperId = 'activity-field';
    var fieldName = $(this).data('target');
    var field = $(fieldName);

    var postMethods = ['menuitems'];
    var termMethods = ['genres'];

    field
    .find('[class^="mmsf-replace-to-"]').each(function() {
      var str = $(this).attr('class').replace('mmsf-replace-to-', '');
      var data = $.extend({}, $(this).data(), {
        element: str,
        'data-value': $(this).data('value')
      });
      if (data.type == 'checkbox' || data.type == 'radio') {
        data = $.extend(data, {
          'data-checked': data.checked
        });
      }
      var newEl = $.newEl(data).addClass('mmsf-replaced');
      $(this).hide().after(newEl);
    }).end()
    .find('[class^="mmsf-select2"]').each(function() {
      if (!$(this).hasClass('mmsf-select2')) {
        var $thisS2 = $(this);
        var method = $.trim($thisS2.attr('class').replace('mmsf-replaced', '').replace('mmsf-select2-', ''));
        var _d = {key: ['id', 'text'], response: []};
        if ($.inArray(method, postMethods) > -1) {
          _d.postid = MMSF.vendor_id;
          _d.response = ['ID', 'post_title'];
        } else if ($.inArray(method, termMethods) > -1) {
          _d.postid = MMSF.vendor_id;
          _d.response = ['term_id', 'name'];
        }
      } else {
        // ----------------
      }
      $.mmsfJsonGet(method, _d).then(function(d) {
        $thisS2.select2({data: d, multiple: true});
      });
    }).end()
    .find('.mmsf-hide').hide()
    ;

    var toggles = $.newEl({
      element: 'div',
      inner: {
        element: 'div',
        class: ['btn-group', 'pull-right'],
        inner: [{
          element: 'button',
          type: 'button',
          class: ['btn', 'btn-small'],
          inner: 'Cancel',
          id: 'cncl'
        }, {
          element: 'button',
          type: 'submit',
          class: ['btn', 'btn-small'],
          inner: 'Submit',
          disabled: 'disabled',
          id: 'sbmt'
        }]
      },
      class: 'clearfix'
    });
    field.append(toggles).mmsfBackdrop();
    $('#' + wrapperId).wrapInner($.newEl({element: 'form', action: location.href, method:'post'}));
    var form = $('#' + wrapperId).children('form');

    var sbmt = form.find('#sbmt');
    var chck = field.find('input, textarea, select');
    form.on('change keyup mousemove', function() {
      var bool = $.mmsfFormCheck(chck); // ------------------------------------------------ function: jQuery.mmsfFormCheck() @ main.js
      if (!bool) {
        sbmt.attr('disabled', 'disabled').removeClass('btn-primary');
      } else {
        sbmt.removeAttr('disabled').addClass('btn-primary');
      }
    });

    field.find('#cncl').on('click', function() {
      field
      .find('.mmsf-hide').show().end()
      .find('.mmsf-replaced').prev().show().end().remove().end()
      .mmsfBackdrop()
      ;
      form.children().unwrap();
      toggles.remove();
    });

  });

}(window.jQuery);