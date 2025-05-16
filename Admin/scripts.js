// Dummy data for user and video lists
const users = [
    { id: 1, username: "john_doe", email: "john@example.com", status: "active", points: 1200, role: "viewer", signUpDate: "2023-01-15", lastActive: "2023-05-01" },
    { id: 2, username: "jane_smith", email: "jane@example.com", status: "suspended", points: 800, role: "creator", signUpDate: "2022-12-05", lastActive: "2023-04-30" },
    { id: 3, username: "user_three", email: "user3@example.com", status: "active", points: 600, role: "admin", signUpDate: "2023-02-10", lastActive: "2023-05-02" }
];

// Populate quick stats
function populateQuickStats() {
    document.getElementById("total-users-usermanage").textContent = users.length;
    document.getElementById("active-users-usermanage").textContent = users.filter(u => u.status === "active").length;
    document.getElementById("banned-users-usermanage").textContent = users.filter(u => u.status === "suspended").length;
    document.getElementById("new-signups-usermanage").textContent = users.filter(u => {
        const signUpDate = new Date(u.signUpDate);
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        return signUpDate > thirtyDaysAgo;
    }).length;
}

// Populate user table
function populateUserTable() {
    const tableBody = document.getElementById("user-table-body-usermanage");
    tableBody.innerHTML = users.map(user => `
        <tr>
            <td>${user.id}</td>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td>${user.status}</td>
            <td>${user.points}</td>
            <td>${user.role}</td>
            <td>${user.signUpDate}</td>
            <td>${user.lastActive}</td>
            <td>
                <button onclick="editUser(${user.id})">Edit</button>
                <button onclick="deleteUser(${user.id})">Delete</button>
            </td>
        </tr>
    `).join("");
}

// Initialize engagement chart
function initEngagementChart() {
    const ctx = document.getElementById('engagement-chart-usermanage').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Views', 'Likes', 'Comments'],
            datasets: [{
                label: 'User Engagement',
                data: [120, 80, 45], // Example data
                backgroundColor: ['#ADD8E6', '#90EE90', '#FFDAB9']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

// Edit user function
function editUser(userId) {
    alert(`Edit user with ID: ${userId}`);
    // Add your edit logic here
}

// Delete user function
function deleteUser(userId) {
    if (confirm(`Are you sure you want to delete user with ID: ${userId}?`)) {
        // Add your delete logic here
        alert(`User with ID: ${userId} deleted.`);
    }
}

// Initialize Page
function initPage() {
    populateQuickStats();
    populateUserTable();
    initEngagementChart();
}

// Run initialization on page load
window.onload = initPage;
