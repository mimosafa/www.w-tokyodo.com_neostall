!function($) {

  $('[data-edit-toggle]').on('click', function(e) {

    e.preventDefault();

    var field = $('[data-edit-field]');

    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: {
        'action': 'json_get_terms_data',
        'taxonomy': 'region',
        'keyProp': 'term_id',
        'valProp': 'name'
      }
    })
    .done(function(data) {

      $('#region').select2({ data: data });
      field.wrapInner('<form action="' + location.href + '" method="post" />');
      var form = field.children('form');
      field.mmsfBackdrop(true);
      field.slideDown();

      form.on('change keyup', function() {
        var formOK = true;
        $(this).find('[required]').each(function() {
          if ( ! $(this).val().length ) { formOK = false; return false; }
        });
        if ( formOK ) { $('[data-edit-submit]').removeAttr('disabled') }
        else { $('[data-edit-submit]').attr('disabled', 'disabled') }
      });

      $('[data-edit-cancel]').on('click', function(e) {

        e.preventDefault();

        form.children('div').find('input, select').val('').end().find(':checked').prop('checked', false); // children('div') ... to do not remove nonce value
        $('#region').select2('destroy').val('');
        form.children().unwrap();
        field.mmsfBackdrop(false);
        field.slideUp('400');

      });

    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      //console.log("complete");
    });

  });

}(window.jQuery);