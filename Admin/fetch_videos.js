document.addEventListener("DOMContentLoaded", () => {
  fetch("fetch_videos.php")
    .then(response => response.json())
    .then(videos => {
      const tbody = document.getElementById("video-list-body-videomanage");
      tbody.innerHTML = ""; // Clear existing content

      videos.forEach(video => {
        const row = document.createElement("tr");

        row.innerHTML = `
          <td>${video.id}</td>
          <td><video width="80" src="${video.file_path}" controls></video></td>
          <td>${video.title}</td>
          <td>Uploader Name</td> <!-- Placeholder -->
          <td>${video.category}</td>
          <td>${new Date().toLocaleDateString()}</td> <!-- Placeholder -->
          <td>0 / 0</td> <!-- Views & Likes -->
          <td>Published</td> <!-- Placeholder -->
          <td>0</td> <!-- Reward Points -->
          <td><button onclick="viewVideoDetail(${video.id})">View</button></td>
        `;

        tbody.appendChild(row);
      });
    })
    .catch(error => {
      console.error("Error fetching videos:", error);
    });
});

function viewVideoDetail(videoId) {
  // Logic to view full video detail, can be expanded
  alert(`Video Detail for ID: ${videoId}`);
}
