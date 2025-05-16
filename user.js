document.addEventListener("DOMContentLoaded", function () {
  // Load user profile data
  fetch('get_user_data.php')
    .then(res => res.json())
    .then(user => {
      if (user.error) {
        console.error(user.error);
        return;
      }

      document.getElementById("username").textContent = user.username || "N/A";
      document.getElementById("email").textContent = user.email || "N/A";
      document.getElementById("status").textContent = user.account_status || "N/A";
      document.getElementById("points").textContent = user.points !== undefined ? user.points : "N/A";
      document.getElementById("joinDate").textContent = user.join_date || "N/A";
      document.getElementById("lastActive").textContent = user.last_active || "N/A";
    })
    .catch(err => {
      console.error('Error loading user data:', err);
    });

  // You can add your videos fetching logic here (like you had before) if needed.


    // Load videos
    fetch('get_user_videos.php')
        .then(res => res.json())
        .then(data => {
            const uploadedSection = document.querySelector('.videos-uploaded-userpage');
            if (!uploadedSection) {
                console.error('No .videos-uploaded-userpage container found');
                return;
            }

            const noUploadedMsg = uploadedSection.querySelector('.no-activity-userpage');
            if (data.length > 0) {
                if (noUploadedMsg) noUploadedMsg.style.display = 'none';

                data.forEach(video => {
                    const videoHTML = `
                        <div class="video-upload-item-userpage">
                            <img src="${video.file_path}" alt="Video Thumbnail" class="thumbnail-userpage">
                            <div class="video-details-userpage">
                                <a href="${video.file_path}" class="video-title-userpage">${video.title}</a>
                                <div class="video-stats-userpage">
                                    <span class="views-userpage">N/A</span>
                                    <span class="likes-userpage">N/A</span>
                                    <span class="comments-userpage">N/A</span>
                                </div>
                            </div>
                        </div>
                    `;
                    uploadedSection.insertAdjacentHTML('beforeend', videoHTML);
                });
            } else {
                // Show "No videos" message if no videos
                if (noUploadedMsg) noUploadedMsg.style.display = 'block';
                else uploadedSection.innerHTML = '<p>No videos uploaded yet.</p>';
            }
        })
        .catch(err => {
            console.error('Error loading videos:', err);
        });
});
