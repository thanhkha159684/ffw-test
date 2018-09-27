var MTL = {};

MTL.menu = function() {
  $('.sidebar-toggle').click(function(){
    var menuOpen =  $.cookie('sidebar_menu_open');
    if (!menuOpen || menuOpen == 1) {
      $.cookie('sidebar_menu_open', 0, { expires: 1, path: '/' });
      $('body').addClass('sidebar-collapse');
    } else {
      $.cookie('sidebar_menu_open', 1, { expires: 1, path: '/' });
      $('body').removeClass('sidebar-collapse');
    }
  });
  $('.sidebar-menu a span').unbind('click').click(function(){
    window.location.href = $(this).closest('a').attr('href');
  });

}


$(document).ready(function(){
  MTL.menu();
  // TRANSFER FORM
  if ($('.unlimit').not('.hide').length == 1) {
      $('#edit-clients-remove-user').hide();
  }

});

Drupal.behaviors.base_theme = {
  attach: function (context, settings) {
    $ = jQuery;

    // Process datepicker
    $('input[type="date"]',context).each(function() {
      $(this).hide(0);
      $(this).next('.datepicker').remove();
      $(this).after('<input class="datepicker '+$(this).attr('class')+'">');
      var date = $(this).val().split("-");
      if (date.length ==3) {
        $(this).next('.datepicker').val(date[2]+'/'+date[1]+'/'+date[0]);
      }
      $(this).next('.datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
    });
    $('.datepicker',context).change(function(){
      var dateReg = /^\d{2}([./-])\d{2}\1\d{4}$/;
      if (!$(this).val().match(dateReg)) {
        alert(Drupal.t("Invalid date format"));
      } else {
        var date = $(this).val().split("/");
        if (date.length ==3) {
          $(this).prev('input[type="date"]').val(date[2]+'-'+date[1]+'-'+date[0]);
        }
      }
    });

    //Process form no-edit
    $('form.no-edit .form-control',context).each(function(){
      $(this).attr('disabled',true);
    })

  }
}
