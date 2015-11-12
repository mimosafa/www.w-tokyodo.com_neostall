!function($){

  $('[data-add-series]').on('click', function(e) {

    e.preventDefault();

    var form = $('#add-new-series');

    form.slideDown().mmsfBackdrop({
      coverOver: $('.navbar-fixed-top')
    });

    form.on('change keyup', function() {
      var formOK = false;
      form.find('[required]').each(function() {
        if ( $(this).val().length > 0 ) {
          formOK = true;
          return true;
        } else {
          formOK = false;
          return false;
        }
      });
      if ( formOK ) {
        form.find('button[type="submit"]').prop('disabled', false);
      } else {
        form.find('button[type="submit"]').prop('disabled', true);
      }
    });

    $('[data-cancel]').on('click', function(e) {

      e.preventDefault();

      form.find('input').val('').end().find(':checked').prop('checked', false);

      form.mmsfBackdrop().slideUp();

    });

  });

}(window.jQuery);