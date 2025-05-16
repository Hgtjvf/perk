  document.addEventListener('DOMContentLoaded', function () {
        function fetchUsers() {
            fetch('user-management.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('userTableBody');
                tableBody.innerHTML = '';

                if (data.error) {
                    tableBody.innerHTML = `<tr><td colspan="10" style="color: red;">${data.error}</td></tr>`;
                    return;
                }

                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="10">No users found.</td></tr>`;
                    return;
                }

                data.forEach(user => {
                    const row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.full_name}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.points}</td>
                            <td>${user.pkr}</td>
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
                document.getElementById('userTableBody').innerHTML = `<tr><td colspan="10" style="color: red;">Error loading data.</td></tr>`;
            });
        }

        fetchUsers();
    });