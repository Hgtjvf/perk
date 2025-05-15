document.addEventListener('DOMContentLoaded', function () {
    // Function to fetch and display user data
    function fetchUsers() {
        fetch('user-management.php', {
            method: 'POST',
        })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('userTableBody');
                tableBody.innerHTML = ''; // Clear the table body

                if (data.error) {
                    // Display an error message in the table if the backend returns an error
                    tableBody.innerHTML = `<tr><td colspan="7" style="color: red;">${data.error}</td></tr>`;
                    return;
                }

                if (data.length === 0) {
                    // Handle case with no users
                    tableBody.innerHTML = `<tr><td colspan="7">No users found.</td></tr>`;
                    return;
                }

                // Populate the table with user data
                data.forEach(user => {
                    const row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.account_status}</td>
                            <td>${user.registration_date}</td>
                            <td>${user.last_login}</td>
                            <td>
                                <a href="edit_user.php?id=${user.id}">Edit</a> |
                                <a href="delete_user.php?id=${user.id}" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                const tableBody = document.getElementById('userTableBody');
                tableBody.innerHTML = `<tr><td colspan="7" style="color: red;">Error loading data.</td></tr>`;
            });
    }

    // Fetch users when the page loads
    fetchUsers();
});
