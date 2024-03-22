$(document).ready(function () {
  $('#registerForm').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
      type: 'POST',
      url: 'php/register.php',
      data: formData,
      success: function (response) {
        $('#registerMessage').html(response);
      },
    });
  });
});
