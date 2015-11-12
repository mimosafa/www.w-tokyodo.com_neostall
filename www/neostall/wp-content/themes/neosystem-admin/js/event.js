!function($){

  $('[data-edit-activities]').on('click', function(e) {

    e.preventDefault();

    var key = $(this).closest('[data-eventdata]').data('eventdata');
    console.log(key);

    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: {
        'action': 'json_get_kitchencars_for_select2'
      }
    })
    .done(function(data) {

      var addact = $('#add-activities'),
          slctKC = $('#select-kitchencars'),
          radio  = $('.activity-phase');

      slctKC.attr('name', 'eventsData[' + key + '][activities]');
      slctKC.select2({ multiple: true, data: data });
      radio.each(function() {
        $(this).attr('name', 'eventsData[' + key + '][phase]');
      });

      addact.wrapInner('<form id="add-activities-form" action="' + location.href + '" method="post" />');
      addact.slideDown().mmsfBackdrop(true);

      var form = $('#add-activities-form');

      $('[data-cancel]').on('click', function(e) {

        e.preventDefault();

        addact.slideUp().mmsfBackdrop(false);
        slctKC.select2('destroy').val('');
        form.children().unwrap();

      });

    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      //console.log("complete");
    });

  });

  //
  $('[data-option-table-toggle]').on('click', function(e) {

    e.preventDefault();

    var target = $(this).data('option-table-toggle'),
        wrapper = $('[data-option-table-wrapper="' + target + '"]');

    wrapper.wrapInner('<form id="form-' + target + '" action="' + location.href + '" method="post" />');
    var form = $('#form-' + target);

    form.children('[data-option-table-toggles]').show();
    form.find('[data-option-item]').each(function() {
      $(this).children('span').hide();
      $(this).children('[data-option-part]').show();
    });
    form.mmsfBackdrop(true);

    $('[data-option-table-cancel]').on('click', function() {
      form.find('[data-option-item]').each(function() {
        $(this).children('span').show();
        $(this).children('[data-option-part]').hide();
      });
      form.mmsfBackdrop(false);
      $(this).closest('[data-option-table-toggles]').hide();
      form.children().unwrap();
    });

  });

}(window.jQuery);