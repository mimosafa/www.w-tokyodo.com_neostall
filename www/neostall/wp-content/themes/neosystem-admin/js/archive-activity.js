!function($){

  // Edit Forms Action
  $('[data-mmsf-feemi-toggle]').on('click', function(e) {

    e.preventDefault();

    var field = $('[data-mmsf-feemi]');
    field.wrapInner('<form action="" method="post" id="add-new-vendor" />');
    var form = $('#add-new-vendor');

    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: {
        'action': 'json_get_kitchencars_for_select2'
      }
    })
    .done(function(data) {

      var kitchencar = $('#kitchencar');
      kitchencar.select2({ data: data });

      field.slideDown('fast', function() {
        form.mmsfBackdrop(true);
      });

      form.on('change', function() {
        var formOK = false;
        form.find('[required]').each(function() {
          if ( !$(this).val() ) {
            formOK = false;
            return false;
          } else {
            formOK = true;
            return true;
          }
        });
        if ( formOK ) {
          $('[data-mmsf-feemi-submit]').removeAttr('disabled');
        } else {
          $('[data-mmsf-feemi-submit]').attr('disabled', 'disabled');
        }
      });

    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      //console.log("complete");
    });

    $('[name="post_type"]').on('change', function() {

      if ( 'space' == $('[name="post_type"]:checked').val() ) {

        $.ajax({
          url: MMSF.endpoint,
          type: 'POST',
          dataType: 'json',
          data: {
            'action': 'json_get_spaces_for_select2'
          }
        })
        .done(function(data) {

          var place = $('#place');
          place.select2({ data: data });

        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          //console.log("complete");
        });

      }

    });

  });

  $('[data-mmsf-feemi-cancel]').on('click', function(e) {

    e.preventDefault();

    var field = $('[data-mmsf-feemi]'),
        form = $('#add-new-vendor'),
        kitchencar = $('#kitchencar'),
        place = $('#place');

    $('[name="post_type"]').each(function() {
      $(this).attr('checked', false);
    });
    kitchencar.select2('destroy').val('');
    place.select2('destroy').val('');
    form.mmsfBackdrop(false);
    form.children().unwrap();
    field.slideUp();

  });

}(window.jQuery);