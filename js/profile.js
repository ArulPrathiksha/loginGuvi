$(document).ready(function () {
  var username = localStorage.getItem('username');
  if (!username) {
    // Redirect to login page if username not found
    window.location.href = 'login.html';
  }

  // Fetch user profile data
  $.ajax({
    type: 'GET',
    url: 'php/profile.php',
    data: { username: username },
    success: function (response) {
      try {
        var profile = JSON.parse(response);
        if (profile) {
          populateProfile(profile);
        } else {
          displayProfileNotFound();
        }
      } catch (error) {
        console.error('Error parsing profile data:', error);
        displayProfileNotFound();
      }
    },
    error: function (xhr, status, error) {
      console.error('Failed to fetch user profile:', error);
      displayProfileNotFound();
    },
  });

  // Update profile form submission
  $('#profileUpdateForm').submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    formData += '&username=' + username;
    $.ajax({
      type: 'POST',
      url: 'php/profile.php',
      data: formData,
      success: function (response) {
        console.log(response);
        $('#updateMessage').text(response);
        // Refresh profile data after successful update
        $.ajax({
          type: 'GET',
          url: 'php/profile.php',
          data: { username: username },
          success: function (response) {
            try {
              var profile = JSON.parse(response);
              if (profile) {
                populateProfile(profile);
              } else {
                displayProfileNotFound();
              }
            } catch (error) {
              console.error('Error parsing profile data:', error);
              displayProfileNotFound();
            }
          },
          error: function (xhr, status, error) {
            console.error('Failed to fetch updated user profile:', error);
          },
        });
      },
      error: function (xhr, status, error) {
        console.error('Failed to update profile:', error);
      },
    });
  });

  function populateProfile(profile) {
    var profileInfo = document.getElementById('profileInfo');

    // Create paragraph elements for each profile detail
    var usernamePara = document.createElement('p');
    usernamePara.textContent = 'Username: ' + profile.username;

    var emailPara = document.createElement('p');
    emailPara.textContent = 'Email: ' + profile.email;

    var agePara = document.createElement('p');
    agePara.textContent = 'Age: ' + profile.age;

    var contactPara = document.createElement('p');
    contactPara.textContent = 'Contact: ' + profile.contact;

    // Clear any existing content in profileInfo
    profileInfo.innerHTML = '';

    // Append created paragraph elements to profileInfo
    profileInfo.appendChild(usernamePara);
    profileInfo.appendChild(emailPara);
    profileInfo.appendChild(agePara);
    profileInfo.appendChild(contactPara);
  }

  function displayProfileNotFound() {
    var profileInfo = document.getElementById('profileInfo');
    profileInfo.innerHTML = ''; // Clear existing content

    var message = document.createElement('p');
    message.textContent = 'User profile not found';

    profileInfo.appendChild(message);
  }

  // Show/hide profile update form
  $('#updateProfileBtn').click(function () {
    $('#updateProfileForm').toggle();
  });

  // Logout functionality
  $('#logoutBtn').click(function () {
    localStorage.removeItem('username');
    window.location.href = 'login.html'; // Redirect to login page after logout
  });
});
