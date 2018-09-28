
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}
Drupal.behaviors.ajax_list = {
  attach: function (context, settings) {
    $ = jQuery;
    $('.pager .pager__item a',context).click(function(e) {
      e.preventDefault();
      var form = $(this).closest('form');
      var page = getParameterByName('page',$(this).attr('href'));
      $('input[name="page"]',form).val(page).trigger('change');
    });

  }
};
