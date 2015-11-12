/* ===================================================
 * jquery.mmsf.js v0.0.0
 * http://
 * =================================================== */

/**
 * Create New Element
 *
 * @param options: Object {element: String HTML tag, inner:[], class:String||Array, attr:Object[, attr:value, ...]]}
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

      el.css({
        'position': 'relative',
        'zIndex': opts.zIndex + 1
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

/**
 * Element replace
 */
!function($) {

  $.fn.mmsfElementReplace = function(options) {

    var elData = this.data();

    var defaultOptions = {
      element: elData.element,
      attr: elData.attr,
      inner: this.html()
    }

    var opts = $.extend(true, {}, $.fn.mmsfElementReplace.default, defaultOptions, options);

    var el = $(document.createElement(opts.element));
    $.each(opts.attr, function(i, v) {
      el.attr(i,v);
    });
    if (opts.inner)
      el.html(opts.inner);

    var replace;
    if (opts.wrapper || opts.before || opts.after) {
      var _wrap = document.createElement('wrap');
      el.wrap(_wrap);
      var wrapper = el.parent();
      wrapper.prepend(opts.before);
      wrapper.append(opts.after);
      if (opts.wrapper) {
        wrapper.wrapInner(opts.wrapper);
      }
      replace = wrapper.children();
    } else {
      replace = el;
    }

    this.hide().after(replace);

    return replace;

  };

  $.fn.mmsfElementReplace.default = {
    element: '',
    attr: {},
    inner: '',
    wrapper: '',
    before: '',
    after: ''
  }

  $.fn.mmsfReplaceToFormEl = function(options) {

    var elData = this.data();

    var defaultOptions = {
      element: elData.element,
      elementType: elData.type,
      name: elData.name,
      existsData: elData.exists,
      selectOptions: elData.options,
      elementClass: elData.class ? elData.class : '',
      wrapper: '',
      before: '',
      after: ''
    }

    var opts = $.extend(true, {}, defaultOptions, options);

    var formEl = $.newEl({
      element: opts.element,
      inner: function() {
        if ('select' == opts.element && opts.selectOptions) {
          var optionEl = '';
          $.each(opts.selectOptions, function(i, v) {
            optionEl += '<option value="' + i + '">' + v + '</option>';
          });
          return optionEl;
        }
      },
      class: opts.elementClass,
      type: opts.elementType,
      name: opts.name,
      value: opts.existsData,
      'data-exists': opts.existsData
    });

    if (opts.before || opts.after) {
      var _wrapper = document.createElement('wrapper');
      formEl.wrap(_wrapper);
      var wrapper = formEl.parent();
      wrapper.prepend(opts.before).end().append(opts.after);
    }

    var replace;
    if (opts.wrapper) {
      if ($('wrapper').length) {
        wrapper.wrapInner(opts.wrapper);
      } else {
        formEl.wrap(opts.wrapper);
        var wrapper = formEl.parent();
      }
      replace = wrapper.children();
    } else {
      replace = formEl;
    }

    console.log(replace);

    this.hide().after(replace);

    return replace;
  };

}(window.jQuery);