fetch("connect.php", {
    method: "POST",
    body: formData,
})
.then((response) => {
    if (!response.ok) throw new Error("Network error");
    return response.json();
})
