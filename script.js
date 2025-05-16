// Placeholder for dynamic content, to be replaced with backend data


           document.getElementById("year").textContent = new Date().getFullYear();



function handleFormSubmit(event) {
    event.preventDefault(); // Prevent page reload on form submit
    
    var email = document.getElementById("email-username-signin").value;
    var password = document.getElementById("password-signin").value;
    var rememberMe = document.getElementById("remember-me-signin").checked;

    // Create a FormData object to send data via AJAX
    var formData = new FormData();
    formData.append('email-username', email);
    formData.append('password', password);
    formData.append('remember-me', rememberMe ? 1 : 0);

    // Create an AJAX request to send the form data to login.php
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'login.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Handle the response (e.g., show a success message or redirect the user)
            var response = xhr.responseText;
            if (response === 'Success') {
                // Redirect to the dashboard or home page if login is successful
                window.location.href = 'dashboard.html';
            } else {
                // Display an error message
                document.getElementById('general-error-signin').textContent = 'Invalid email or password';
            }
        } else {
            document.getElementById('general-error-signin').textContent = 'Error with the request. Please try again.';
        }
    };

    xhr.send(formData);
}

// for Subscription input


  document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('subscribe-form');
  const message = document.getElementById('subscribe-message');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    message.style.display = 'none';
    message.textContent = '';

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) throw new Error('Network response was not ok');

      const data = await response.json();

      message.style.color = data.success ? 'green' : 'red';
      message.textContent = data.message;
      message.style.display = 'block';

      if (data.success) {
        form.reset();
      }
    } catch (error) {
      message.style.color = 'red';
      message.textContent = 'Subscription failed. Please try again later.';
      message.style.display = 'block';
    }
  });
});
