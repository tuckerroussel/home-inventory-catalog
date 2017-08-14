$(document).ready(function() {
  $('[data-open="photo-modal"]').click(function(e) {
    $("#photo-modal").attr('data-url', $(this).attr('data-url'));
  });

  $('[data-reveal]#photo-modal').on('open.zf.reveal', function() {
    $(this).find('.photo-modal-content').html('<img src="' + $(this).attr('data-url') + '" />');
  });
});
