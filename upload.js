document.getElementById('form-upload').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this); // Create FormData object from the form
    const progressBar = document.getElementById('progress-fill-upload');
    const uploadPercentage = document.getElementById('upload-percentage-upload');

    // Display progress bar
    document.querySelector('.upload-progress-upload').style.display = 'block';

    const xhr = new XMLHttpRequest();

    // Update progress bar
    xhr.upload.addEventListener('progress', function (e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            uploadPercentage.textContent = percentComplete + '%';
        }
    });

    // Handle response from PHP
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);

                if (response.success) {
                    alert('Upload successful!');
                    document.getElementById('form-upload').reset(); // Clear form
                    progressBar.style.width = '0%';
                    uploadPercentage.textContent = '0%';
                } else {
                    alert('Error: ' + response.message);
                }
            } catch (e) {
                alert('Unexpected server response. Please try again.');
            }
        } else {
            alert('Error during upload. Please try again.');
        }
    };

    // Handle error
    xhr.onerror = function () {
        alert('Upload failed. Please check your connection and try again.');
    };

    xhr.open('POST', 'upload_video.php', true); // PHP backend script
    xhr.send(formData); // Send form data to PHP
});
