document.addEventListener("DOMContentLoaded", () => {
  fetch("fetch_videos.php")
    .then(response => response.json())
    .then(data => {
      console.log("Video data: ", data);
      const tableBody = document.getElementById("video-list-body-videomanage");
      tableBody.innerHTML = ""; // Clear previous content

      data.forEach(video => {
        const row = document.createElement("tr");

        row.innerHTML = `
          <td>${video.video_id}</td>
          <td>
            <video width="100" controls muted>
              <source src="${video.file_path}" type="video/mp4" />
              Your browser does not support the video tag.
            </video>
          </td>
          <td>${video.title}</td>
          <td>${video.uploader}</td>
          <td>${video.category}</td>
          <td>${video.upload_date}</td>
          <td>${video.views} views / ${video.likes} likes</td>
          <td>${video.status}</td>
          <td>${video.reward_points}</td>
          <td><button type="button">Manage</button></td>
        `;

        tableBody.appendChild(row);
      });
    })
    .catch(error => {
      console.error("Error fetching video data:", error);
    });
});
