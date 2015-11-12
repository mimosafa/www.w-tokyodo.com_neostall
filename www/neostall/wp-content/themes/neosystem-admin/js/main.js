!function($){

  // ------------------------------ Control EnterKey Pressing on input[type="text"]
  // -----------------------------> http://www.koikikukan.com/archives/2012/01/20-015555.php

  $('input[type=text],input[type=number]').keypress(function(e) {
    if ( ( e.which && 13 == e.which ) || ( e.keyCode && 13 == e.keyCode ) ) {
      return false;
    } else {
      return true;
    }
  });

  // ------------------------------ Tab UI (Bootstrap & jQuery.cookie)

  // jQuery.Cookie
  $('ul.nav-tabs > li > a').click(function(){
    var href = $(this).attr('href'),
        tabs = $(this).parent('li').parent('ul.nav-tabs').attr('id');
    $.cookie(tabs + '-active-tab',href);
  });

  // Bootstrap Tab UI
  var key = $('ul.nav-tabs').attr('id') + '-active-tab';
  var activeTab = $.cookie(key);
  if ( activeTab ) {
    $('ul.nav-tabs > li > a[href="' + activeTab +'"]').tab('show');
  } else {
    // Default
    $('ul.nav-tabs > li > a:first').tab('show');
    // if especialy display tab, for example today's tab on 'space' single page.
    $('ul.nav-tabs > li > a#visible-tab').tab('show');
  }

  // ------------------------------ Edit Table
/*
  // - edit
  $('a.edit').on('click',function(e){
    e.preventDefault();
    $(this).siblings('a.done').removeClass('hide');
    $(this).addClass('hide');

    var td    = $(this).parent('td'),
        table = td.parent().parent().parent('table');
        data  = td.data();

    var html  = replaceSpanToEditable(data); // my function
    td.children('span').replaceWith(html);
  });

  // - done
  $('a.done').on('click',function(e){
    e.preventDefault();

    var td      = $(this).parent('td'),
        table   = td.parent().parent().parent('table');
        data    = td.data(),
        element = td.children('.editable-element'),
        val     = element.val();

    if ( val != data['value'] ) {
      var name = data['field'],
          html = '';

      if ( 'select' == data['element'] ) {
        var text = element.children('option:selected').text();
      } else if ( 'text' || 'number' == data['element'] ) {
        var text = element.val();
      }

      html += '<input type="hidden" name="' + name + '" value="' + val + '" />\n'
      html += '<span class="modify">' + text + '</span>';
      element.replaceWith(html);
      $(this).addClass('hide');
      $(this).siblings('.cncl').removeClass('hide');

    } else {

      $(this).addClass('hide');
      $(this).siblings('.edit').removeClass('hide');
      element.replaceWith('<span>' + data['original'] + '</span>');

    }

    tableControl( table );
  });

  // - cncl
  $('a.cncl').on('click',function(e){
    e.preventDefault();

    $(this).addClass('hide');
    $(this).siblings('.edit').removeClass('hide');

    var td    = $(this).parent('td'),
        table = td.parent().parent().parent('table'),
        origi = td.data('original');

    td.children('input[type=hidden]').remove();
    td.children('span').removeClass('modify').text(origi);

    tableControl( table );
  });

  // - modify
  $('td').on('dblclick','span.modify',function(e) {
    var td    = $(this).parent(td),
        table = td.parent().parent().parent('table'),
        data = td.data();

    td.children('.cncl').addClass('hide');
    td.children('.done').removeClass('hide');

    var inputHidden = td.children('input[type=hidden]');
    data['modifiedVal'] = inputHidden.val();
    inputHidden.remove();

    var html  = replaceSpanToEditable(data);
    td.children('span').replaceWith(html);
  });

  // My function
  function replaceSpanToEditable( data ) {
    var html = '',
        value;
    if ( ! data['modifiedVal'] )
      value = data['value'];
    else
      value = data['modifiedVal'];

    if ( 'select' == data['element'] ) {

      html += '<select class="editable-element">\n';
      $.each(data['options'], function(i,v){
        var opt = v.split('||');
        html += '<option value="' + opt[0] + '"';
        if ( opt[0] == value )
          html += ' selected'
        html += '>' + opt[1] + '</option>\n';
      });
      html += '</select>';

    } else if ( 'text' || 'number' == data['element'] ) {

      html += '<input class="editable-element" type="' + data['element'] +'" value="' + value + '" />';

    }

    data['modifiedVal'] = null;
    return html;
  }

  // My function: Wrap <table> by <form>
  function tableControl( table ) {
    var ttl     = table.data('title'),
        url     = location.href,
        formID  = 'form-' + table.attr('id'),
        wrapper = table.parent();
        mod     = table.find('span.modify');

    if ( 0 < mod.length ) {

      if ( 0 == $('#' + formID).length ) {
        wrapper.wrapInner('<form id="' + formID +'" action="' + url + '" method="post" />');

        var content = '';
        content += '<div id="form-sbmt" class="alert">\n';
        content += ttl + 'の内容が変更されています ';
        content += '<input type="text" style="display:none;" />\n'; // for Press EnterKey
        content += '<input class="btn btn-mini pull-right" type="submit" value="Done!!" disabled="disabled" />\n';
        content += '</div>';
        $('#' + formID).prepend(content);

      }

      var editing = table.find('.editable-element');
      if ( ! editing.length ) {
        $('#form-sbmt').children('input').removeAttr('disabled');
      } else {
        $('#form-sbmt').children('input').attr('disabled','disabled');
      }

    } else {

      $('#' + formID).children().unwrap();
      $('#form-sbmt').fadeOut('slow', function(){
        $(this).remove();
      });

    }
  }

  // ------------------------------ Items Order Change (jQuery UI)
*/
  $('.sortableList').sortable({
    item:   '.sortableItem',
    handle: '.changeOrder',
    update: function() {
      $(this).children('.sortableItem').each(function(i){
        $(this).children('input[type=hidden]').remove();
        $(this).removeClass('changed-order');
        var oData = $(this).data('mmsf-feemi-order');
        if ( i != oData ) {
          var id = $(this).data('mmsf-feemi-postid');
          var input = '<input type="hidden" name="order[' + id + ']" value="' + i + '" />';
          $(this).prepend(input);
          $(this).addClass('changed-order');
        }
      });

      var changed = $(this).find('.changed-order');
      if ( 0 < changed.length ) {
        var form = $(this).parent('form');

        if ( 0 == form.length ) {
          $(this).wrap('<form action="' + location.href + '" method="post" />');
          $(this).parent('form').prepend('<div id="form-sbmt" class="alert">\nOrder of Items is Cahanged !<input class="btn btn-mini pull-right" type="submit" value="Done!!" />\n</div>');
        }

      } else {

        $(this).unwrap();
        $('#form-sbmt').fadeOut('slow', function(){
          $(this).remove();
        });

      }

    }
  });

}(window.jQuery);

// ----------------------------------------------------------------------
!function($) {

  var responseExpected = {
    post: ['ID', 'post_author',  'post_date',  'post_date_gmt', 'post_content', 'post_title', 'post_excerpt',
      'post_status', 'comment_status', 'ping_status', 'post_password', 'post_name', 'to_ping', 'pinged',
      'post_modified', 'post_modified_gmt', 'post_content_filtered', 'post_parent', 'guid', 'menu_order',
      'post_type', 'post_mime_type', 'comment_count', 'ancestors', 'filter'],
    term: ['term_id', 'name', 'slug', 'term_group', 'term_taxonomy_id', 'taxonomy', 'description', 'parent', 'count']
  };
  var queryArg = {
    post: ['post_type', 'numberposts', 'orderby', 'order'],
    term: []
  }

  var methods = {

    init: function(options) {

      var data = $.extend(true, {}, $.mmsfJsonGet.setting, options);

      if (!$.isEmptyObject(data.format)) {
        data.key = [];
        data.response = [];
        $.each(data.format, function(i, v) {
          data.key = data.key.concat(i);
          data.response = data.response.concat(v);
        });
        delete data.format;
      }

      return ajaxDeferred(data);

    },

    menuitems: function(options) {

      var o = $.extend({}, options); // clone 'options'
      var d = {key: [], response: []}; // expected data
      var q = {}; // expected query

      if (o.postid && $.isNumeric(o.postid)) { // vendor
        q.post_parent = o.postid;
        delete o.postid;
        q.numberposts = -1;
        q.orderby = 'menu_order';
        q.order = 'ASC';
      }

      if (!o.response) {
        $.each(o, function(i, v) {
          if ($.inArray(i, queryArg.post) > -1) {
            q[i] = v;
          } else if ($.inArray(v, responseExpected.post) > -1) {
            d.key = d.key.concat(i);
            d.response = d.response.concat(v);
          }
        });
      } else {
        $.extend(d, o);
      }

      var data = $.extend(true, {}, d, {query: q}, {target: 'posts', query: {post_type: 'menu_item'}});

      return ajaxDeferred(data);

    },

    genres: function(options) {

      var o = $.extend({}, options);
      var d = {postid: 0, key: [], response: []};

      if (!o.response) {
        $.each(o, function(i, v) {
          if ('key' === i && typeof v === 'object') {
            return false;
          } else if ('postid' === i && $.isNumeric(v)) {
            d.postid = v;
          } else if ($.inArray(v, responseExpected.term) > -1) {
            d.key = d.key.concat(i);
            d.response = d.response.concat(v);
          }
        });
      } else {
        $.extend(d, o);
      }

      var data = $.extend({}, d, {target: 'theTerms', taxonomy: 'genre'});

      return ajaxDeferred(data);

    }

  };

  function ajaxDeferred(data) {

    var _data = $.extend(true, {}, data, {action: 'mmsf_json_get'});
    var defer = $.Deferred();

    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: _data,
      success: defer.resolve,
      error: defer.reject
    });
    return defer.promise();

  };

  $.mmsfJsonGet = function( method ) {

    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ) );
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' + method + ' dose not exist on This Function.' );
    }

  }

  $.mmsfJsonGet.setting = {
    target: 'posts',
    response: [],
    key: [],
    format: {}
  };

}(window.jQuery);

// ---------------------------------------------------------------------- mmsfJsonGetPosts
!function($) {

  $.mmsfJsonGetPosts = function(options) {

    var defer = $.Deferred();

    var o = $.extend({
      query: { post_type: '' },
      key: [],
      response: ['ID']
    }, options, { action: 'mmsf_json_get_posts' });

    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: o,
      success: defer.resolve,
      error: defer.reject
    });
    return defer.promise();

  };

  $.mmsfJsonGetTheTerms = function(options) {

    var defer = $.Deferred();

    var o = $.extend({
      postid: '',
      taxonomy: '',
      key: [],
      response: ['term_id']
    }, options, { action: 'mmsf_json_get_the_terms' });

    $.ajax({
      url: MMSF.endpoint,
      type: 'POST',
      dataType: 'json',
      data: o,
      success: defer.resolve,
      error: defer.reject
    });
    return defer.promise();

  };

}(window.jQuery);

// ---------------------------------------------------------------------- formCheck
!function($) {

  $.mmsfFormCheck = function(jq) {

    var bool = false;

    jq.each(function(i, v) {
      if ($(this).attr('required') == 'required' && !$(this).val().length) {
        bool = false;
        return false;
      } else {
        var ov, nv;
        if (v.type == 'checkbox' || v.type == 'radio') {
          ov = $(this).data('checked');
          nv = $(this)[0]['checked'];
        } else {
          ov = $(this).data('value');
          nv = $(this).val();
        }
        if (ov == nv) { bool = false; return true; }
                 else { bool = true; return false; }
      }
    });

    return bool;

  };

}(window.jQuery);