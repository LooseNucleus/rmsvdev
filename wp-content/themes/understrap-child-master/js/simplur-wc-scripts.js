jQuery(document).ready(function($) {
  var hash = window.location.hash;
  hash && $('ul.nav a[href="' + hash + '"]').tab('show');

  $('.nav-pills a').click(function (e) {
    $(this).tab('show');
  });


//the following gets called after every tab change
  $('.nav-pills a').on('shown.bs.tab', function () {
  $('html, body').animate({
    scrollTop: $('#product-tabs').offset().top - 100
  }, 1000);

  window.location.hash = '';
});
});
