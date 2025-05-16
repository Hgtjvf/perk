document.getElementById('contact-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);

    fetch('contact_form.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            const messageContainer = document.getElementById('response-message');
            messageContainer.textContent = data.message;
            messageContainer.style.color = data.success ? 'green' : 'red';
            messageContainer.style.marginTop = '10px';

            if (data.success) {
                this.reset(); // Clear the form
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const messageContainer = document.getElementById('response-message');
            messageContainer.textContent = 'An error occurred while submitting the form.';
            messageContainer.style.color = 'red';
        });
});
