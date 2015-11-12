!function($){

  // Edit Forms Action
  $('[data-mmsf-feemi-toggle]').on('click', function(e) {

    e.preventDefault();

    var field = $('[data-mmsf-feemi]');
    field.wrapInner('<form action="" method="post" id="add-new-vendor" />');
    var form = $('#add-new-vendor');
    field.slideDown('fast', function() {
      form.mmsfBackdrop(true);
    });

    form.on('change', function() {
      var formOK = false;
      form.find('[required]').each(function() {
        if ( !$(this).val() ) {
          formOK = false;
          return false;
        } else {
          formOK = true;
          return true;
        }
      });
      if ( formOK ) {
        $('[data-mmsf-feemi-submit]').removeAttr('disabled');
      } else {
        $('[data-mmsf-feemi-submit]').attr('disabled', 'disabled');
      }
    });

    $('[data-mmsf-feemi-cancel]').on('click', function(e) {

      e.preventDefault();

      form.mmsfBackdrop(false);
      form.children().unwrap();
      field.slideUp();

    });

  });

}(window.jQuery);