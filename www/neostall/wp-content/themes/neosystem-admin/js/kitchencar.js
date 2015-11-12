!function($){

  $('.editItemTrigger').on('click',function(){

    $('body').css('overflow','hidden');
    var editType = $(this).data('type');
    var cfkey;
    if ( 'modify' == editType ) { cfkey = $(this).closest('tr').prop('id'); }

    $.ajax({
      url: KITCHENCAR.endpoint,
      type: 'POST',
      dataType: 'json',
      data: {
        'action': 'json_get_kitchencar_s_menu_set_data',
        'kitchencar_id': KITCHENCAR.kitchencar_id,
        'editType': editType,
        'cfkey': cfkey
      }
    })
    .done(function(data){

      var div = $('#menuSetFormDiv'),
          lg  = div.children('legend'),
          sC1 = $('#menuSetCat1'),
          sC2 = $('#menuSetCat2'),
          sI  = $('#menuSetItem'),
          sG  = $('#menuSetGenre'),
          sT  = $('#menuSetText'),
          sTT = $('#menuSetTextTemp'),
          i1  = $('#imageDiv1'),
          i2  = $('#imageDiv2'),
          backdrop = $('<div class="mmsf-backdrop" style="display:none;"></div>');
      $('body').append(backdrop);
      div.addClass('mmsf-focused-element');
      backdrop.fadeIn();

      var sC2dataW,
          sIdata = data.menu_items,
          sGdata = data.genres,
          lgTxt,
          sC1val = 'default',
          sC2val, sIval, sGval, sTval, thmb1, thmb2;

      if ( 'modify' == editType ) {
        var sC = cfkey.split('_');
        sC1val = sC[0];
        if ( ! $.isNumeric(sC[1]) )
          sC2val = sC[1];
        sIval = data.exists['item'];
        sGval = data.exists['genre'];
        sTval = data.exists['text'];
        thmb1 = data.exists['img1'];
        thmb2 = data.exists['img2'];
        lgTxt = 'Edit this Menu Set.';
      } else {
        lgTxt = 'Add New Menu Set';
      }

      lg.html(lgTxt);
      sC1.val(sC1val).attr('name','cat1');
      sC2.val(sC2val).attr('name','cat2');
      sI.val(sIval).attr('name','items');
      sG.val(sGval).attr('name','genres');
      sT.val(sTval).attr('name','text');

      sC2dataW = [
        {id:'monday',text:'月曜日'},
        {id:'tuesday',text:'火曜日'},
        {id:'wednesday',text:'水曜日'},
        {id:'thursday',text:'木曜日'},
        {id:'friday',text:'金曜日'},
        {id:'saturday',text:'土曜日'},
        {id:'sunday',text:'日曜日'}
      ];

      // select2.js
      sC1.select2();
      if ( 'modify' == editType )
        sC1.select2().select2('readonly',true);

      if ( sC2val !== void 0 ) {
        if ( 'weekly' == sC1val ) {
          sC2.select2({data:sC2dataW}).select2('readonly',true);
        } else if ( 'event' == sC1val ) {
          $.ajax({
            url: KITCHENCAR.endpoint,
            type: 'POST',
            dataType: 'json',
            data: {
              'action': 'json_get_terms_data',
              'responce': 'keyval',
              'keyFormat': 'id',
              'valFormat': 'text',
              'keyProp': 'slug',
              'valProp': 'name',
              'taxonomy': 'series'
            }
          })
          .done(function(data) {
            sC2.select2({data:data}).select2('readonly',true);
          })
          .fail(function() {
            //console.log("error");
          })
          .always(function() {
            //console.log("complete");
          });
        }
      }

      sC1.on('change',function(){
        sC2.val('');
        if ( 'weekly' == $(this).val() ) {
          sC2.select2({ data: sC2dataW, placeholder: 'Select week. (allow empty)', allowClear: true });
        } else if ( 'event' == $(this).val() ) {
          $.ajax({
            url: KITCHENCAR.endpoint,
            type: 'POST',
            dataType: 'json',
            data: {
              'action': 'json_get_terms_data',
              'responce': 'keyval',
              'keyFormat': 'id',
              'valFormat': 'text',
              'keyProp': 'slug',
              'valProp': 'name',
              'taxonomy': 'series'
            }
          })
          .done(function(data) {
            sC2.select2({ data: data, placeholder: 'Select Event Series. (allow empty)', allowClear: true });
          })
          .fail(function() {
            //console.log("error");
          })
          .always(function() {
            //console.log("complete");
          });

          // sC2.select2();
        } else {
          sC2.select2('destroy');
        }
      });

      sI.select2({ multiple: true, placeholder: 'Select Items', data: sIdata });
      sI.select2('container').find("ul.select2-choices").sortable({
        containment: 'parent',
        start: function() { sI.select2('onSortStart'); },
        update: function() { sI.select2('onSortEnd'); }
      });
      sG.select2({ multiple: true, placeholder: 'Select Genres', data: sGdata });
      sG.select2('container').find("ul.select2-choices").sortable({
        containment: 'parent',
        start: function() { sG.select2('onSortStart'); },
        update: function() { sG.select2('onSortEnd'); }
      });

      if ( data.texts ) {
        sT.after('<button id="textTemplateButton" class="btn btn-small btn-link" type="button" style="display:block;"><i class="icon-plus"></i> Template Text</button>');
        var tTB = $('#textTemplateButton');
        tTB.on('click',function(){
          $(this).hide();
          sTT.select2({data:data.texts, placeholder:'Choose Template Text.'});
        });
        sTT.on('change',function(){
          if ( $(this).val().length > 0 ) {
            sT.val($(this).val());
            $(this).select2('destroy').val('');
            tTB.show();
          }
        })
      }

      var i1Html = '';
      $.each(data.images1, function(i, o){
        i1Html += '<label for="img-' + i + '">\n';
        if ( thmb1 == i ) {
          i1Html += '<input type="radio" name="img1" value="' + i + '" id="img-' + i + '" checked="checked">';
        } else {
          i1Html += '<input type="radio" name="img1" value="' + i + '" id="img-' + i + '">';
        }
        i1Html += o + '</label>\n';
      });
      i1.html(i1Html);

      var i2Html = '';
      $.each(data.images2, function(i, o){
        i2Html += '<label for="img-' + i + '">\n';
        if ( thmb2 == i ) {
          i2Html += '<input type="radio" name="img2" value="' + i + '" id="img-' + i + '" checked="checked">';
        } else {
          i2Html += '<input type="radio" name="img2" value="' + i + '" id="img-' + i + '">';
        }
        i2Html += o + '</label>\n';
      });
      i2.html(i2Html);

      div.wrapInner('<form id="editMenuSetForm" method="post" action="' + location.href + '" />');
      var form = $('#editMenuSetForm');
      form.append('<input type="hidden" id="menuSetEditType" name="editType" value="' + editType + '" />');
      form.append('<input type="hidden" id="kitchencarID" name="kitchencar_id" value="' + KITCHENCAR.kitchencar_id + '" />');
      if ( 'modify' == editType )
        form.append('<input type="hidden" id="customFieldKey" name="cfkey" value="' + cfkey + '" />');

      var rmms = $('#removeMenuSet');

      if ( 'modify' == editType )
        rmms.removeClass('hide');

      var top = $(window).scrollTop(),
          topOffset = $('#menu_items').offset().top - 45;
      if ( top <= 135 ) {
        div.slideDown('300', function() {
          $('html, body').animate({scrollTop:topOffset}, 500);
        });
      } else {
        $('html, body').animate({scrollTop:topOffset}, 500, function(){
          div.slideDown('300');
        });
      }

      var sbmt = $('#editMenuSetSubmit'),
          cncl = $('#editMenuSetCancel');

      form.on('change keyup focus blur', function(){
        if (
          sC1.val().length > 0
          && sI.val().length > 0
          && sG.val().length > 0
          && sT.val().length > 0
          //&& $('input:radio[name=img1]:checked').length > 0 // 車両イメージがない場合あり...
          //&& $('input:radio[name=img2]:checked').length > 0 // メニューイメージもない場合あり...
        ) {
          if ( 'add' == editType ) {
            submitControl(true)
          } else if ( 'modify' == editType ) {
            if (
              sI.val() != sIval
              || sG.val() != sGval
              || sT.val() != sTval
              || $('input:radio[name=img1]:checked').val() != thmb1
              || $('input:radio[name=img2]:checked').val() != thmb2
            ) {
              submitControl(true);
            } else {
              submitControl(false);
            }
          }
        } else { submitControl(false) }
      });

      rmms.click(function() {
        $(this).attr('disabled','disabled');
        cncl.attr('disabled','disabled');
        $('#menuSetEditType').val('remove');
        $('#removeMenuItemAlrt').show();
        $('#removeMenuItemCncl').click(function(){
          $('#removeMenuItemAlrt').fadeOut('400');
          rmms.removeAttr('disabled');
          cncl.removeAttr('disabled');
          $('#menuSetEditType').val('modify');
        });
      });

      cncl.click(function(){
        form.children().unwrap();
        $('#menuSetEditType').remove();
        $('#kitchencarID').remove();
        $('#customFieldKey').remove();
        $('#textTemplateButton').remove();
        $('.mmsf-backdrop').remove();
        $('body').css('overflow','');
        div.slideUp('150', function() {
          div.removeClass('mmsf-focused-element');
          lg.text('');
          if ( 'modify' == editType ) {
            sC1.select2('readonly',false);
            sC2.select2('readonly',false);
          }
          sC1.removeAttr('name').val('').select2('destroy');
          sC2.removeAttr('name').val('').select2('destroy');
          sI.removeAttr('name').val('').select2('destroy');
          sG.removeAttr('name').val('').select2('destroy');
          sT.removeAttr('name').val('');
          sTT.select2('destroy').val('');
          i1.empty();
          i2.empty();
          $('#removeMenuSet').addClass('hide');
          submitControl(false);
        });
      });

      function submitControl(bool){
        if (bool) { sbmt.removeAttr('disabled').addClass('btn-primary'); }
        else { sbmt.attr('disabled','disabled').removeClass('btn-primary'); }
      }

    })
    .fail(function(){
      $('body').css('overflow','');
      console.log('fail');
    })
    .always(function(){
      // console.log();
    });

  });

}(window.jQuery);

!function($) {

  $('.edit-anchor').on('click', function(e) {

    e.preventDefault();

    var wrapperId = $(this).closest('.tab-pane').attr('id');
    var fieldName = $(this).data('target');
    var field = $(fieldName);

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
      if ($(this).hasClass('mmsf-select2')) {
        data = $.extend(data, {class: 'mmsf-select2'});
      }
      var newEl = $.newEl(data).addClass('mmsf-replaced');
      $(this).hide().after(newEl);
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