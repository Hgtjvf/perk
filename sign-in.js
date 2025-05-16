document.addEventListener('DOMContentLoaded', () => {
    // Toggle password visibility
    const togglePassword = document.getElementById('toggle-password-signin');
    const passwordField = document.getElementById('password-signin');

    togglePassword.addEventListener('click', () => {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
    });
});

function handleFormSubmit(event) {
    event.preventDefault(); // Prevent the default form submission

    const emailOrUsername = document.getElementById('email-username-signin').value;
    const password = document.getElementById('password-signin').value;
    const rememberMe = document.getElementById('remember-me-signin').checked;

    // Clear any previous errors
    document.getElementById('email-username-error-signin').textContent = '';
    document.getElementById('password-error-signin').textContent = '';
    document.getElementById('general-error-signin').textContent = '';

    // Perform client-side validation (simple check)
    if (!emailOrUsername || !password) {
        document.getElementById('general-error-signin').textContent = 'Please fill in all fields.';
        return;
    }

    // Create a FormData object to send to PHP (login.php)
    const formData = new FormData();
    formData.append('email-username-signin', emailOrUsername);
    formData.append('password-signin', password);
    formData.append('remember-me-signin', rememberMe ? 'on' : 'off');

    // Send the form data via Fetch API to login.php
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to dashboard or other page after successful login
            window.location.href = 'index.html'; // Adjust to your desired page
        } else {
            // Display errors from the server response
            if (data.errors['email_or_username']) {
                document.getElementById('email-username-error-signin').textContent = data.errors['email_or_username'];
            }
            if (data.errors['password']) {
                document.getElementById('password-error-signin').textContent = data.errors['password'];
            }
            if (data.errors['general']) {
                document.getElementById('general-error-signin').textContent = data.errors['general'];
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('general-error-signin').textContent = 'An error occurred. Please try again later.';
    });
}
