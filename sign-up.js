document.getElementById("sign-up-form-signup").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission
    console.log("Sign-up button clicked!"); // Debugging message

    const formData = new FormData(this);
    const errorMessageContainer = document.getElementById("error-message-container-signup");
    errorMessageContainer.innerHTML = ""; // Clear previous errors

    fetch("connect.php", {
        method: "POST",
        body: formData,
    })
    .then((response) => {
        if (!response.ok) throw new Error("Network error");
        return response.json();
    })
    .then((data) => {
        if (data.success) {
            console.log("Sign-up successful!");
            window.location.href = "sign-in.html";
        } else {
            console.log("Sign-up failed:", data.errors);
            Object.entries(data.errors).forEach(([key, message]) => {
                const errorElement = document.createElement("span");
                errorElement.classList.add("error-message-signup");
                errorElement.textContent = message;
                errorMessageContainer.appendChild(errorElement);
            });
        }
    })
    .catch((error) => {
        console.error("Unexpected error:", error);
        const generalError = document.createElement("span");
        generalError.classList.add("error-message-signup");
        generalError.textContent = `An unexpected error occurred: ${error.message}`;
        errorMessageContainer.appendChild(generalError);
    });
});
