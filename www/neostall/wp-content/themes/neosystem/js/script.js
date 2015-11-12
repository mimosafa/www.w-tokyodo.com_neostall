$(function() {

  $('#neosystem-navbar li.dropdown:has(".active")').addClass('active');

  $(document).on('ready', function() {

    /**
     * Archive
     */
    if ($('#archive-main').length && NEOYATAI_POSTS) {

      var _lbl = NEOYATAI_POSTS.label,
          _itm = NEOYATAI_POSTS.items;

      _dom = mmsfArchiveTableCreate(_lbl, _itm);
      $('#archive-main').html(_dom);

    }

    /**
     * Single
     */
    if ($('#single-main').length) {

      if ( undefined !== CONTENT ) {
        $('#single-main').append($.newEl(CONTENT));
      }

      $('.mmsf-edit-toggle').on('click', function(e) {
        e.preventDefault();
        $($(this).data('target')).wrapInner($.newEl({
          'element': 'form',
          'id': 'mmsf-form',
          'action': location.href,
          'method': 'post'
        }));

        var form = $('#mmsf-form')
        .append($.newEl({
          'element': 'div',
          'class': 'clearfix',
          'id': 'mmsf-btn-group',
          'inner': {
            'element': 'div',
            'class': 'btn-group pull-right',
            'inner': [{
              'element': 'button',
              'class': 'btn btn-default btn-sm',
              'id': 'cncl',
              'type': 'button',
              'inner': 'Cancel'
            },{
              'element': 'button',
              'class': 'btn btn-default btn-sm',
              'id': 'sbmt',
              'type': 'submit',
              'disabled': 'disabled',
              'inner': 'Submit'
            }]
          }
        }))
        .mmsfBackdrop()
        ;

        form.find('[data-element]').each(function() {
          var formElement = $.newEl($(this).data('element'))
          .addClass('mmsf-replace')
          .insertAfter($(this));
          $(this).hide();
        });

        $('#cncl').on('click', function(e) {
          e.preventDefault();
          form.find('.mmsf-replace').each(function(){
            $(this).prev('span').show().end()
            .remove()
            ;
          });
          $('#mmsf-btn-group').remove();
          form.mmsfBackdrop().children().unwrap();
        });

      });

    }

  });

  /**
   * @param Array label
   * @param Array items
   */
  function mmsfArchiveTableCreate(label, items) {
    var thTr = [];
    $.each(label, function(i, v) {
      thTr[i] = {'element':'th','inner':v};
    });
    var th = {'element':'tr','inner':thTr};

    var tb = [];
    $.each(items, function(i, arr) {
      var tds = [];
      $.each(arr, function(j, v) {
        tds[j] = {'element':'td','inner':v};
      });
      tb[i] = {'element':'tr','inner':tds}
    });
    var args = {
      'element': 'table',
      'class': 'table table-hover',
      'inner': [
        {'element': 'thead','inner': th},
        {'element': 'tbody','inner': tb}
      ]
    };
    return $.newEl(args);
  }

  /**
   *
   */
  function mmsfSingleDataTableCreate(label, items) {
    var rows = [];
    $.each(items, function(i, v) {
      rows[i] = {'element':'tr','inner':[{'element':'th','inner':v['label']},{'element':'td','inner':v['data']}]};
    });
    var args = [
      {
        'element': 'h4',
        'inner': label
      },
      {
        'element': 'table',
        'class': 'table table-bordered table-hover',
        'inner': rows
      }
    ];
    return $.newEl(args);
  }

});

/**
 * Create New Element
 *
 * @param options
 * @return HTML Object
 */
!function($) {

  $.newEl = function(options) {

    var opts = $.extend({}, options);
    var o = { element: '', inner: [], class: [], attr: {}, outer: '' };
    $.each(opts, function(i, v) {
      if ('element' == i) {
        o.element = v;
        delete opts.element;
      } else if ('inner' == i) {
        o.inner = o.inner.concat(v);
        delete opts.inner;
      } else if ('class' == i) {
        o.class = o.class.concat(v);
        delete opts.class;
      } else if ('attr' == i) {
        o.attr = v;
        delete opts.attr;
      } else if ('outer' == i) {
        o.outer = v;
        delete opts.outer;
      }
    });
    o.attr = $.extend(o.attr, opts);

    if (o.element) {
      var el = $('<' + o.element + '>');
    } else {
      return false;
    }
    $.each(o.class, function(i, v) { el.addClass(v); });
    $.each(o.inner, function(i, v) {
      if (typeof v == 'object') {
        var innerOpts = $.extend({}, v);
        el.append($.newEl(innerOpts));
      } else {
        el.append(v);
      }
    });
    if (!$.isEmptyObject(o.attr)) {
      $.each(o.attr, function(i, v) {
        if (i == 'value') el.val(v);
        else el.attr(i, v);
      });
    }

    if (!o.outer) {
      return el;
    } else {
      if (typeof o.outer != 'object') {
        el.wrap(o.outer);
      } else {
        var outerOpts = $.extend({}, o.outer);
        el.wrap($.newEl(outerOpts));
      }
      return el.parent();
    }

  }

}(window.jQuery);

/**
 * Backdrop
 */
!function($){

  $.fn.mmsfBackdrop = function(options) {

    var el = this;

    var opts = $.extend({}, $.fn.mmsfBackdrop.default, options);

    var backdropObj = $('#' + opts.backdropId);

    if (!backdropObj.length) {

      var backdrop = $('<div>' + opts.inner + '</div>')
        .attr('id', opts.backdropId)
        .css({
          'position': 'fixed',
          'top': 0,
          'left': 0,
          'width': '100%',
          'height': '100%',
          'backgroundColor': opts.dropColor,
          'opacity': opts.opacity,
          'zIndex': opts.zIndex
        })
        .hide()
      ;

      $.each(el, function() {
        $(this).css({
          'position': 'relative',
          'zIndex': opts.zIndex + 1
        });
      });

      $('body').append(backdrop);

      if (opts.coverOver.length) {

        var coveredEl = new Array(opts.coverOver);
        var cover = $('<div></div>')
          .addClass(opts.coverClass)
          .css({
            'position': 'absolute',
            'top': 0,
            'left': 0,
            'width': '100%',
            'height': '100%',
            'backgroundColor': opts.dropColor,
            'opacity': opts.opacity,
          }).
          hide()
        ;
        $.each(coveredEl, function(i) {
          if ('static' == $(this).css('position')) { // need??
            $(this).css('position','relative');
          }
          cover.appendTo($(this));
        });
        $('.' + opts.coverClass).fadeIn(opts.fadeSpeed);

      }

      backdrop.fadeIn(opts.fadeSpeed);

    } else {

      backdropObj.fadeOut(opts.fadeSpeed, function() {
        $(this).remove();
        el.css({
          'position': '',
          'zIndex': ''
        });
      });
      if ($('.' + opts.coverClass).length) {
        $('.' + opts.coverClass).fadeOut(opts.fadeSpeed, function() {
          $(this).remove();
        });
      }

    }

    return this;

  };

  $.fn.mmsfBackdrop.default = {
    // backdrop
    backdropId: 'mmsf-backdrop',
    dropColor: 'white',
    opacity: '.65',
    fadeSpeed: 'normal',
    zIndex: 1000,
    inner: '',
    // cover
    coverOver: '',
    coverClass: 'mmsf-cover'
  };

}(window.jQuery);