$(document).ready(function () {
  $('#loginForm').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
      type: 'POST',
      url: 'php/login.php',
      data: formData,
      success: function (response) {
        console.log('respon : ', response);
        console.log('trim : ', response.trim());
        $('#loginMessage').html(response);
        if (response.trim() == 'Login successful') {
          console.log('Redirecting to profile  page..');
          var username = $('#username').val().trim();
          localStorage.setItem('username', username); // Store username in localStorage
          window.location.href = '../profile.html'; // Redirect to profile page
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX Error:', error); // Log AJAX error
      },
    });
  });
});
