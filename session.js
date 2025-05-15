document.addEventListener("DOMContentLoaded", () => {
    // Fetch user session data from header.php
    fetch('header.php')
        .then(response => response.json())
        .then(data => {
            // If user is logged in, update the UI
            if (data.isLoggedIn) {
                const userOptions = document.querySelector('.user-options');
                const loggedInState = document.querySelector('#loggedInState');
                const signInButton = document.querySelector('#signInButton');

                // Hide the Sign In / Sign Up button
                if (signInButton) {
                    signInButton.style.display = 'none';
                }

                // Show the logged-in state with the username
                if (loggedInState) {
                    loggedInState.style.display = 'block';
                    loggedInState.querySelector('.points').textContent = `Welcome, ${data.userData['username-signup']}`;
                }
            } else {
                // Show the Sign In / Sign Up button if not logged in
                const signInButton = document.querySelector('#signInButton');
                if (signInButton) {
                    signInButton.style.display = 'block';
                }
                const loggedInState = document.querySelector('#loggedInState');
                if (loggedInState) {
                    loggedInState.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error fetching user session data:', error);
        });
});
