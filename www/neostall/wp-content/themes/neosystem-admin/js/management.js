!function($){

  //
  $('[data-option-table-toggle]').on('click', function(e) {

    e.preventDefault();

    //space data
    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: {
        'action': 'json_get_spaces_for_select2'
      }
    })
    .done(function(data) {

      //console.log(data);

      //select2
      $('input[name="target-space"]').select2({
        data: data,
        multiple: true
      });

      $('[data-option-table-wrapper').wrapInner('<form id="form-option" action="' + location.href + '" method="post" />');
      var form = $('#form-option');

      form.find('[data-view]').hide().end().find('[data-control]').show();
      form.children('[data-option-table-toggles]').show();
      form.find('[data-option-item]').each(function() {
        $(this).children('span').hide();
        $(this).children('[data-option-part]').show();
      });
      form.mmsfBackdrop(true);

      $('[data-option-table-cancel]').on('click', function() {
        //select2
        $('input[name="target-space"]').select2('destroy'); // なんか一回目のキャンセルでは'destroy'されない...
        form.find('[data-option-item]').each(function() {
          $(this).children('span').show();
          $(this).children('[data-option-part]').hide();
        });
        form.find('[data-view]').show().end().find('[data-control]').hide();
        form.mmsfBackdrop(false);
        $(this).closest('[data-option-table-toggles]').hide();
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

}(window.jQuery);