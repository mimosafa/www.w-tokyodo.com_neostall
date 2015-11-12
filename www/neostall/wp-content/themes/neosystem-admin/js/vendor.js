!function($){

  // Edit Forms Action
  $('[data-mmsf-feemi]').on('click', '[data-mmsf-feemi-toggle]', function(e) {

    e.preventDefault();

    var wrapper  = $(this).closest('[data-mmsf-feemi]'),
        feemiID  = wrapper.attr('id'),
        tmplt    = wrapper.find('[data-mmsf-feemi-template]'),
        editType = $(this).data('mmsf-feemi-toggle');

    var item, field, formItems;
    if ( 'modify' == editType ) {
      item = $(this).closest('[data-mmsf-feemi-existing]');
      field = tmplt.clone(true, true).insertBefore(item);
    } else {
      field = tmplt.clone(true, true).insertBefore(tmplt);
    }
    formItems = field.find('[data-mmsf-feemi-name]');

    var nowData = [];
    if ( item ) {
      $.each(item.data(), function(i, v) {
        if ( i.indexOf('mmsfFeemi') != -1 ) {
          k = i.replace('mmsfFeemi','').toLowerCase();
          nowData[k] = v;
        }
      });
    }

    // Item's order data
    var orderData = [];
    for ( i = 0; i < $(wrapper.find('[data-mmsf-feemi-existing]')).length; i++ ) {
      orderData.push({ id: i, text: (i + 1) + '' }); // " + '' " for string
    };
    if ( 'add' == editType ) {
      orderData.push({ id: i, text: (i + 1) + '' });
      nowData['order'] = i;
    }

    // each formitems add value.
    formItems.each(function() {
      var label = $(this).data('mmsf-feemi-name');
      if ( 'fieldset' == $(this)[0].localName ) {
        $(this).find('input[type="radio"]').each(function() {
          $(this).attr('name', label);
          if ( $(this).val() == nowData[label] )
            $(this).attr('checked','checked');
        });
      } else {
        $(this).val(nowData[label]).attr('name',label);
      }
    });

    // Select2.js
    // - genre
    field.find('[data-mmsf-feemi-name="genre"]').select2({
      multiple: true,
      placeholder: 'Select Genres',
      tags: VENDOR.genres,
      tokenSeparators: [",", " "]
    });
    // - order
    field.find('[data-mmsf-feemi-name="order"]').select2({ data: orderData });
    // - phase
    field.find('[data-mmsf-feemi-name="phase"]').val(1).select2();

    // create <form>
    field.wrapInner('<form id="form-' + feemiID + '" method="post" action="' + location.href + '" />');
    var form = $('#form-' + feemiID);
    form.append('<input type="hidden" name="editType" value="' + editType + '" />');

    // window action

    // - backdrop
    field.mmsfBackdrop();

    // - scroll & slidedown
    var topOffset;
    if ( 'modify' == editType ) {
      topOffset = item.offset().top - 80;
    } else {
      topOffset = $('#menu_items').offset().top - 45;
    }
    field.slideDown('300', function() {
      $('html, body').animate({scrollTop:topOffset}, 500);
    });

    // Dropzone.js
    var zone = field.find('[data-mmsf-dropzone]').attr('id', feemiID + '-drop-zone');
    var thumbs = zone.siblings('[data-mmsf-feemi-name="thumb"]');
    var newDropzone = new Dropzone('#' + feemiID + '-drop-zone', {
      url: VENDOR.endpoint,
      params: {
        'action': 'file_upload_from_dropzone',
        'enctype': 'multipart/form-data',
        'parent': VENDOR.vendor_id
      },
      maxFilesize: 4,
      uploadMultiple: false,
      acceptedFiles: 'image/*',
      createImageThumbnails: false,
      previewTemplate: '<div class="dz-preview dz-file-preview"><div class="dz-progress progress"><div class="dz-upload bar" data-dz-uploadprogress></div></div><div class="dz-details"><div class="dz-filename"><span data-dz-name></span></div><div class="dz-size" data-dz-size></div></div></div>',
      init: function() {
        this
        // added
        .on('addedfile', function() {
          zone.children('.dz-message').hide();
          zone.removeClass('mmsf-dropzone');
        })
        // error
        .on('error', function() {
          zone.addClass('mmsf-dropzone');
          zone.children('.dz-message').show();
        })
        // success
        .on('success', function(f,r) {
          var newThumb = '';
          $.each(r, function(i, v) {
            newThumb += '<label>\n';
            newThumb += '<input type="radio" name="' + thumbs.data('mmsf-feemi-name') + '" value="' + i + '" checked="checked" />\n';
            newThumb += v;
            newThumb += '</label>\n';
          });
          thumbs.append(newThumb);
          thumbs.show();
          myDropzoneDisable(zone);
          console.log(f);
          console.log(r);
        })
        .on('complete', function(f) {
          //this.removeFile(f);
        });
      },
    });

    // Dropzone.js Trigger
    var uptrflag = false;
    zone.on('click', '[data-mmsf-dropzone-toggle]', function() {

      var toggle   = $(this);

      if ( ! uptrflag ) {

        thumbs.hide(10, function() {
          toggle.hide().html('<i class="icon-remove"></i> Cancel Upload.');
          myDropzoneEnable(zone);
        });

      } else {

        zone.hide(10, function() {
          toggle.hide().html('<i class="icon-plus"></i> New Image Upload.');
          myDropzoneDisable(zone);
          zone.show(10, function() {
            thumbs.show();
          });
        });

      }
      uptrflag = ! uptrflag;

    });

    function myDropzoneEnable(zone) {
      zone.addClass('mmsf-dropzone').children('.dz-message').fadeIn();
      zone.find('[data-mmsf-dropzone-toggle]').fadeIn();
      newDropzone.enable();
    }

    function myDropzoneDisable(zone) {
      zone.children('.dz-message').fadeOut(200, function() {
        zone.removeClass('mmsf-dropzone');
        zone.find('[data-mmsf-dropzone-toggle]').fadeIn();
      });
      zone.children('.dz-preview').fadeOut(200, function() {this.remove()});
      newDropzone.disable();
    }

    // Submit & Cancel
    var sbmt = field.find('[data-mmsf-feemi-submit]'),
        cncl = field.find('[data-mmsf-feemi-cancel]');
    // - submit
    form.on('change keyup', function() {
      var formOK = true;
      var nowVal, dataVal;
      field.find('[data-mmsf-feemi-name]').each(function() {
        dataVal = nowData[$(this).data('mmsf-feemi-name')];
        if ( 'fieldset' == $(this)[0].localName ) {
          nowVal = $(this).find('input:checked, input:selected, option:selected').val();
        } else {
          nowVal = $(this).val();
        }
        if ( dataVal == nowVal ) { formOK = false; return true; }
        else { formOK = true; return false; }
      });
      var required = $(this).find('[required]');
      required.each(function() {
        if ( ! $(this).val().length ) { formOK = false; return false; }
      });

      if ( formOK == true ) { submitControl(true) }
      else { submitControl(false) }

      function submitControl(bool) {
        if (bool) { sbmt.removeAttr('disabled').addClass('btn-primary'); }
        else { sbmt.attr('disabled','disabled').removeClass('btn-primary'); }
      }
    });
    // - cancel
    cncl.one('click', function(){
      uptrflag = false;
      $('[data-mmsf-dropzone]').off('click');
      field.mmsfBackdrop();
      field.slideUp('150', function() {
        $(this).remove();
      });
    });

  });

  /**
  *
  $('.edit-anchor').on('click', function(e) {

    e.preventDefault();

    $('#default').wrapInner('<form action="" method="post" />');
    var form = $('#adefault').children('form');
    var field = $($(this).data('target'));

    $('.editable-part', field).each(function() {
      $(this).mmsfReplaceToFormEl({elementClass:'form-part span3'});
    });
    field.find('.form-toggles').show();
    field.mmsfBackdrop();

    field.find('.cncl').on('click', function(e) {
      e.preventDefault();
      field.find('.form-part').remove().end().find('.editable-part').show().end().find('.form-toggles').hide();
      form.children().unwrap();
      field.mmsfBackdrop();
    });

  });
  */

  $('.edit-anchor').on('click', function(e) {
    e.preventDefault();

    var field = $($(this).data('target'));

    field.find('[class^="mmsf-replace-to-"]').each(function() {
      var data = {}, newEl;
      data.element = $(this).attr('class').replace('mmsf-replace-to-', '');
      data = $.extend(data, $(this).data(), {'data-value': $(this).data('value')});
      newEl = $.newEl(data).addClass('mmsf-replaced');
      $(this).hide().after(newEl);
    });

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
    field.append(toggles);
    field.mmsfBackdrop().wrapInner($.newEl({element: 'form', action: location.href, method:'post'}));
    var form = field.children('form');

    var sbmt = form.find('#sbmt');
    var chck = form.find('.mmsf-replaced');
    form.on('change keyup mousemove', function() {
      var formOK = true, ov, nv;
      chck.each(function() {
        ov = $(this).data('value');
        nv = $(this).val();
        if ($(this).attr('required') == 'required' && !$(this).val().length) {
          formOK = false;
          return false;
        } else {
          if (ov == nv) {
            formOK = false;
            return true;
          } else {
            formOK = true;
            return false;
          }
        }
      });
      if (!formOK) {
        sbmt.attr('disabled', 'disabled');
      } else {
        sbmt.removeAttr('disabled');
      }
    });

    field.find('#cncl').on('click', function() {
      field.find('.mmsf-replaced').prev().show().end().remove();
      form.children().unwrap();
      field.mmsfBackdrop();
      toggles.remove();
    });
  });

}(window.jQuery);