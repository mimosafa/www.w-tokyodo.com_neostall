!function($){

  var flagSK = false;
  $('[data-edit-toggle]').on('click', function(e) {

    e.preventDefault();

    var field   = $('[data-acf-lists]'),
        list    = $(this).parent('[data-acf-list]'),
        listInt = list.data('acf-list'),
        exists  = list.data('acf-kitchencars');

    if ( ! flagSK ) {

      list.mmsfBackdrop();

      $(this).html('<i class="icon icon-remove"></i> リストの編集をやめる').addClass('text-error');
      list.siblings('[data-acf-list]').children('[data-edit-toggle]').fadeOut();

      field.wrapInner('<form action="' + location.href + '" method="post" />');
      var form = field.children('form');

      var aef = '<div class="acf-edit-field">';
      list.children('[data-acf-kitchencar]').each(function() {
        var v = $(this).data('acf-kitchencar'),
            l = $(this).children('p').text();
        aef += '<label class="checkbox">\n';
        aef += '<input type="checkbox" value="' + v + '" checked="checked" /> ' + l;
        aef += '\n</label>\n';
        $(this).hide();
      });
      aef += '<input id="result-editing" type="hidden" />';
      aef += '<a href="#" class="muted" data-add-anchor><small><i class="icon icon-plus"></i> 新しいキッチンカーを追加</small></a>';
      aef += '<br>';
      aef += '<a href="#" class="hide" data-acf-edit-submit><i class="icon icon-ok"></i> 編集を完了する</a>';
      aef += '</div>';
      $(this).before(aef);

      var flagAdd = false;
      $('a[data-add-anchor]').on('click', function(e) {

        e.preventDefault();

        if ( ! flagAdd ) {

          $.ajax({
            url: MMSF.endpoint,
            type: 'POST',
            dataType: 'json',
            data: {
              'action': 'json_get_kitchencars_for_select2',
              'exists': exists
            }
          })
          .done(function(data) {

            $('a[data-add-anchor]').html('<small><i class="icon icon-remove"></i> キッチンカーの追加をやめる</small>');
            $('a[data-add-anchor]').before('<input type="hidden" class="new-kitchencar-select span2" />');
            var nks = $('.new-kitchencar-select');
            nks.select2({
              data: data,
              placeholder: 'Choose Kitchencar'
            });
            var added = '';
            nks.on('change', function(val) {
              added += '<label class="checkbox acf-edit-field-added">\n';
              added += '<input type="checkbox" value="' + val.added['id'] + '" checked="checked" /> ';
              added += '<span class="text-success">' + val.added['text'] + '</span>';
              added += '\n</label>';
              nks.siblings('#result-editing').before(added);

              nks.siblings('a[data-add-anchor]').html('<small><i class="icon icon-plus"></i> キッチンカーをさらに追加</small>');
              nks.select2('destroy').remove();
              flagAdd = ! flagAdd;
            });

          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            //console.log("complete");
          });

        } else {

          $(this).html('<small><i class="icon icon-plus"></i> 新しいキッチンカーを追加</small>');
          var nks = $('.new-kitchencar-select');
          nks.select2('destroy').remove();

        }

        flagAdd = ! flagAdd;

      });

      $('.acf-edit-field').sortable({
        items: 'label',
        stop: function() {
          $(this).acfEditFieldCheck(exists, listInt);
        }
      });

      form.on('change', function() {
        $('.acf-edit-field').acfEditFieldCheck(exists, listInt);
      });

      $('a[data-acf-edit-submit]').click(function(e) {
        e.preventDefault();
        form.submit();
      });

      $.fn.acfEditFieldCheck = function(existingData, i) {
        var valArray = [];
        this.find(':checked').each(function() {
          valArray.push($(this).val());
        });
        if ( valArray.length > 0 ) {
          if ( existingData.toString() !== valArray.toString() ) {
            this.children('input[type="hidden"]').val(valArray).attr('name', 'acf[list_' + i + '_kitchencars]');
            $('a[data-acf-edit-submit]').show();
          } else {
            this.children('input[type="hidden"]').val('').removeAttr('name');
            $('a[data-acf-edit-submit]').hide();
          }
        }
      }

    } else {

      $(this).html('<small><i class="icon icon-pencil"></i> リストを編集</small>').removeClass('text-error');
      list.siblings('[data-acf-list]').children('[data-edit-toggle]').show();

      field.children('form').children().unwrap();

      list.children('.acf-edit-field').remove();
      list.children('[data-acf-kitchencar]').each(function() {
        $(this).show();
      });

      // backdrop
      list.mmsfBackdrop();

    }

    flagSK = ! flagSK;

  });

  // Calendar - add activity (All)
  var noActivities = $('#calBody').find('.no-activities');
  if ( 0 < noActivities.length )
    $('#add-activity-all-days').show();

  var calF= false;
  $('#add-activity-all-days').on('click',function(e){

    e.preventDefault();

    var field = $(this).parent('#calWrapper');

    if ( ! calF ) {

      $(this).text('X キャンセル').after('<input type="submit" class="btn btn btn-primary btn-small" value="Submit" />');
      $('#calControler').css('position','relative');
      $('#calControler').append('<div style="position:absolute;top:0;left:0;width:100%;height:100%;background-color:#fff;opacity:.65;"></div>');
      $('div.calDate').each(function() {
        $(this).css('position','relative');
        $(this).append('<div style="position:absolute;top:0;left:0;width:100%;height:100%;background-color:#fff;opacity:.3;"></div>');
      });

      field.wrapInner('<form id="edit-activity-form" action="' + location.href + '" method="post" />');
      field.mmsfBackdrop(true);

      $('.no-activities').each(function(){
        $(this).children('p').hide();
        var d  = $(this).data('date'),
            ks = $(this).data('kitchencars');
        var html = '';
        $.each(ks, function(i, v) {
          var k = v.split('||');
          html += '<label class="checkbox" style="text-align:left;">';
          if ( !k[2] ) {
            html += '<input type="checkbox" name="addActivities'+'['+d+'][]'+'" value="'+k[0]+'" checked="checked"> ';
            html += k[1];
          } else {
            html += '<input type="checkbox" name="addActivities'+'['+d+'][]'+'" value="'+k[0]+'"> ';
            html += '<span class="text-error">' + k[1] + ' (予定あり)</span>';
          }
          html += '</label>'+'\n';
        });
        $(this).append(html);
      });

    } else {

      $(this).text('アクティビティー登録(ALL)').next('input').remove();
      $('#edit-activity-form').children().unwrap();
      $('.no-activities').each(function(){
        $(this).children('label').remove();
        $(this).children('p').show();
      });
      $('#calControler').css('position','').children('div').remove();
      $('div.calDate').each(function() {
        $(this).css('position','').children('div').remove();
      });
      field.mmsfBackdrop(false);

    }
    calF = ! calF;

  });

  // Calendar - edit activity (per day)
  $('[data-edit-toggle-perday]').on('click', function(e) {

    e.preventDefault();

    var field = $('[data-date="' + $(this).data('edit-toggle-perday') + '"]'),
        outerField = $('#calWrapper'),
        d  = field.data('date'),
        ks = field.data('kitchencars'),
        actID, text,
        kitIDs = [],
        html = '';

    html += '<div id="innerField" class="text-left">';
    field.children('p[data-activity]').each(function(i, v) {
      actID = $(this).data('activity');
      kitIDs[i] = $(this).data('kitchencar');
      text  = $(this).text();
      html += '<label class="checkbox remove-lbl" style="padding-left:0;" data-original-title="削除する">';
      html += '<input type="checkbox" value="' + actID + '" class="hide" checked="checked" data-exists-activity data-checked="true" /> ';
      html += '<span class="text-success">' + text + '</span>';
      html += '</label>';
    });
    $.each(ks,function(i, v) {
      var k = v.split('||');
      var intK = k[0] - 0; // string -> int
      if ( $.inArray( intK, kitIDs ) == -1 ) {
        html += '<label class="checkbox">';
        html += '<input type="checkbox" name="addActivities'+'[' + d + '][]" value="' + k[0] + '" data-checked="false" /> ';
        if ( !k[2] ) {
          html += k[1];
        } else {
          html += '<span class="text-error">' + k[1] + ' (予定あり)</span>';
        }
        html += '</label>'+'\n';
      }
    });
    html += '<input type="hidden" name="remove-activity" value />';
    html += '<div class="btn-group" data-edit-toggles-perday>';
    html += '<button type="button" class="btn btn-small" data-toggle-cancel><i class="icon icon-remove"></i> キャンセル</button>';
    html += '<button type="submit" class="btn btn-small" data-toggle-submit disabled><i class="icon icon-ok"></i> 完了</button>';
    html += '</div>';
    html += '</div>';
    field.children('p').hide();

    field.prepend(html);
    outerField.wrapInner('<form id="edit-activity-form" action="' + location.href + '" method="post" />');
    field.mmsfBackdrop(true);

    var rmvAct = $('input[name="remove-activity"]');
    $('[data-checked]').on('change', function() {

      rmvAct.val('');
      var rmvActVal = [];
      $('[data-exists-activity]').each(function(i) {
        if ( !$(this).is(':checked') ) {
          rmvActVal.push($(this).val());
          $(this).next('span').removeClass('text-success').addClass('text-error').css('textDecoration','line-through');
        } else {
          $(this).next('span').removeClass('text-error').addClass('text-success').css('textDecoration', '');
        }
      });
      rmvAct.val(rmvActVal);

      $('[data-checked]').each(function() {
        if ( $(this).is(':checked') != $(this).data('checked') ) {
          $('[data-toggle-submit]').removeAttr('disabled');
          return false;
        } else {
          $('[data-toggle-submit]').attr('disabled', 'disabled');
          return true;
        }
      });

    });

    // cancel
    $('[data-toggle-cancel]').on('click', function(e) {
      e.preventDefault();
      $('#innerField').remove();
      $('#edit-activity-form').children().unwrap();
      field.children('p').show();
      field.mmsfBackdrop(false);
    });

    $('label.remove-lbl').tooltip();

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
      var data = $.extend({}, $(this).data(), {element: str, 'data-value': $(this).data('value')});
      if (data.type == 'checkbox' || data.type == 'radio') {
        data = $.extend(data, {'data-checked': data.checked});
      }
      var newEl = $.newEl(data).addClass('mmsf-replaced');
      $(this).hide().after(newEl);
    }).end()
    .find('.mmsf-hide').hide()
    ;

    if (fieldName == '#space-kitchencars') {
      $.mmsfJsonGetPosts({
        query:{
          post_type: 'kitchencar',
          numberposts: -1,
          orderby: 'meta_value_num',
          meta_key: 'serial',
          order: 'ASC'
        },
        response: ['ID','post_title'],
        key: ['id','text']
      })
      .then(function(data) {
        $('.mmsf-replaced > input').each(function() {
          $(this).select2({ data: data, multiple: true });
        });
      });
    }

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
    var chck = field.find('input, select');
    form.on('change keyup mousemove', function() {
      var bool = $.mmsfFormCheck(chck);
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