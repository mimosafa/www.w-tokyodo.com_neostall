!function($) {

  $('[data-option-table-wrapper]').on('click', '[data-option-table-toggle="add"]', function(e) {

    e.preventDefault();

    $(this).addClass('hide');
    $(this).siblings('.hide').removeClass('hide');
    $(this).siblings('[data-option-table-toggle="edit"]').addClass('hide');

    var field = $(this).closest('[data-option-table-wrapper]'),
        table = field.children('table[data-option-table]'),
        dataName = table.data('option-table'),
        dataNum = table.data('number');
    field.mmsfBackdrop(true);

    field.wrapInner('<form id="' + dataName + '-form" action="' + location.href + '" method="post" />');
    var form = $('#' + dataName + '-form');

    var tmplt = table.find('[data-option-table-template]').clone().removeClass('hide');
    tmplt.find('[data-option-table-form]').each(function() {
      var name;
      name = dataName + '[' + dataNum + '][' + $(this).data('option-table-form') + ']';
      $(this).attr('name', name);
    });

    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: {
        'action': 'json_get_terms_data',
        'responce': 'keyval',
        'keyFormat': 'id',
        'valFormat': 'text',
        'keyProp': 'term_id',
        'valProp': 'name',
        'taxonomy': 'region'
      }
    })
    .done(function(data) {
      //console.log(data);
      $('[data-option-table-form="region"]').select2({data:data});
    })
    .fail(function() {
      //console.log("error");
    })
    .always(function() {
      //console.log("complete");
    });

    table.children('tbody').append(tmplt);

    var btnCncl = $(this).siblings('[data-option-table-toggle="cancel"]'),
        btnSbmt = $(this).siblings('[data-option-table-toggle="submit"]');

    btnCncl.on('click', function(e) {

      e.preventDefault();

      $(this).addClass('hide');
      $(this).siblings('.hide').removeClass('hide');
      btnSbmt.addClass('hide');

      field.mmsfBackdrop(false);
      form.children().unwrap();

      $('[data-option-table-form="region"]').select2('destroy');

      tmplt.remove();

    });

  });

  /**
   *
   */
  $('[data-editable]').on('click', '[data-edit]', function(e) {

    e.preventDefault();

    $(this).siblings('[data-toggles]').show();

    var field = $(this).closest('[data-editable]'),
        row   = $(this).closest('[data-row]'),
        cell  = row.find('[data-cell]');
    field.find('[data-edit]').hide();
    cell.each(function() {
      $(this).children('[data-option-label]').hide().end().children('[data-option]').show();
    });
    field.wrapInner('<form action="' + location.href + '" method="post" />');
    var form = field.children('form');
    field.mmsfBackdrop(true);

    form.on('change keyup', function() {
      var formOK = false;
      $(this).find('[data-cell]').each(function() {
        var oldval = $(this).data('value') + '',
            newval = $(this).children('[data-option]').children().val() + '';
        if ( oldval != newval ) {
          formOK = true;
          return false;
        }
      });
      console.log(formOK);
      if ( formOK ) {
        $(this).find('[data-submit]').prop('disabled', false);
      } else {
        $(this).find('[data-submit]').attr('disabled', 'disabled');
      }
    });

    $('[data-cancel]').on('click', function(e) {

      e.preventDefault();

      field.find('[data-edit]').show();
      $(this).parent('[data-toggles]').hide();
      cell.each(function() {
        $(this).children('[data-option]').hide().end().children('[data-option-label]').show();
      });
      form.children().unwrap();
      field.mmsfBackdrop(false);

    });

  });

  /**
   * add event
   */
  var addEvents = $('[data-edit-toggle="add"]');

  addEvents.on('click', function(e) {

    e.preventDefault();

    var field = $('[data-edit-field]');

    field.wrapInner('<form action="' + location.href + '" method="post" id="edit-form" />');
    field.slideDown('400', function() {
      $(this).mmsfBackdrop(true);
    });

    var form = $('#edit-form'),
        areaField = $('[data-area-field]');

    form.on('change keyup', function() {

      $('#event-day').change(function() {
        var dateval = $(this).val();
        form.find('[data-area-name="starting"]').each(function() {
          $(this).val(dateval + 'T10:00');
        });
        form.find('[data-area-name="ending"]').each(function() {
          $(this).val(dateval + 'T15:00');
        });
      });

      if ( $('input#multiarea').prop('checked') ) {
        $('input[data-area]').each(function() {
          $(this).attr('type', 'checkbox');
          $(this).parent('label').removeClass('radio').addClass('checkbox');
        });
      } else {
        $('input[data-area]').each(function() {
          $(this).attr('type', 'radio');
          $(this).parent('label').removeClass('checkbox').addClass('radio');
        });
      }

      $(this).find('[data-area]').each(function(i) {
        if ( $(this).prop('checked') ) {
          areaField.find('#field-' + $(this).data('area')).removeClass('hide');
          $('#field-' + $(this).data('area')).find('[data-area-name]').each(function() {
            $(this).attr('name', 'area[' + i + '][' + $(this).data('area-name') + ']');
          });
        } else {
          areaField.find('#field-' + $(this).data('area')).addClass('hide');
          $('#field-' + $(this).data('area')).find('[data-area-name]').each(function() {
            $(this).removeAttr('name');
          });
        }
      });

      var formOK = true;
      var required = form.find('[data-required]');
      required.each(function() {
        if ( ! $(this).val().length ) { formOK = false; return false; }
      });
      if ( formOK ) {
        $('[data-edit-submit]').removeAttr('disabled');
      } else {
        $('[data-edit-submit]').attr('disabled', 'disabled');
      }

    });

    var cncl = $('[data-edit-cancel]');
    cncl.on('click', function(e) {
      form.find('input#event-day, input#post_title').val('');
      form.find('input#multiarea, input[name="areas"]').removeAttr('checked');
      areaField.find('[data-area-item]').each(function() {
        $(this).addClass('hide').find('[data-area-name]').each(function() {
          $(this).removeAttr('name');
        });
      });
      $('input[data-area]').each(function() {
        $(this).attr('type', 'radio');
        $(this).parent('label').removeClass('checkbox').addClass('radio');
      });
      form.children().unwrap();
      field.mmsfBackdrop(false);
      field.slideUp('400');
    });

  });

}(window.jQuery);