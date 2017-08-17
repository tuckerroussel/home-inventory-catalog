$(document).ready(function() {
  $('[data-open-details]').click(function (e) {
    e.preventDefault();
    if ($(this).attr('data-open-details') == "open") {
      $(this).removeClass('is-active');
      $(this).next().removeClass('is-active');
      $(this).attr('data-open-details', '');
    } else {
      $('table.table-expand tbody tr').removeClass('is-active').attr('data-open-details', '');
      $(this).next().addClass('is-active');
      $(this).addClass('is-active');
      $(this).attr('data-open-details', 'open');
    }
  });
});
