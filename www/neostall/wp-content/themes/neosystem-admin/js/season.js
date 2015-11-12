!function($) {

  $('[data-mmsf-edtbl-toggle]').on('click', function(e) {

    e.preventDefault(e);

    var table    = $('table[data-mmsf-edtbl="' + $(this).data('mmsf-edtbl-target') + '"'),
        purpose  = $(this).data('mmsf-edtbl-toggle'),
        template, form;

    if ('add' == purpose) {

      template = table.clone();
      template.find('[data-mmsf-edtbl-item]').each(function() {
        $(this).remove();
      });
      template.find('[data-mmsf-edtbl-template]').removeClass('hide').find('[data-mmsf-edtbl-key]').each(function() {
        $(this).children('input,select,textarea').attr('name',$(this).data('mmsf-edtbl-key'));
      });
      template.wrap('<form action="' + location.href + '" method="post" />');
      form = template.parent('form').hide();
      var btn = '\
      <div class="btn-group pull-right">\
      <button type="button" class="btn btn-small btn-danger" data-mmsf-edtbl-cancel>Cancel</button>\
      <button type="submit" class="btn btn-small" data-mmsf-edtbl-submit disabled="disabled">Submit</button>\
      </div>';
      form.append(btn);
      table.before(form);
      form.show().mmsfBackdrop(true);

      var cancel = form.find('[data-mmsf-edtbl-cancel]');

      cancel.click(function() {
        form.mmsfBackdrop(false);
        form.remove();
      });

    }

  });

}(window.jQuery);