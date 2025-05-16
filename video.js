const videoPlayer = document.getElementById("videoPlayer");
const nextButton = document.getElementById("nextButton");
const noAdMessage = document.getElementById("noAdMessage");
const goHomeButton = document.getElementById("goHomeButton");

let currentVideo = null;
let adPlaying = false;

// To avoid autoplay errors, mute the video initially
videoPlayer.muted = true;

// Load and play next main video
async function loadNextVideo() {
  adPlaying = false;
  nextButton.style.display = "none";
  noAdMessage.style.display = "none";
  goHomeButton.style.display = "none";

  videoPlayer.controls = true;
  videoPlayer.src = "";
  videoPlayer.load();

  try {
    const res = await fetch("get_next_video.php");
    if (!res.ok) throw new Error("Network response was not ok");
    const data = await res.json();

    if (data && data.file_path) {
      currentVideo = data;
      videoPlayer.src = data.file_path;
      videoPlayer.load();

      // Try to play video, catch error if autoplay blocked
      videoPlayer.play().catch(() => {
        // Show next button so user can click to start
        nextButton.style.display = "inline-block";
      });

      // Unmute after user interaction
      videoPlayer.muted = false;
    } else {
      alert("No video found.");
    }
  } catch (error) {
    alert("Error loading video: " + error.message);
  }
}

// Load and play ad after main video ends
async function loadAd() {
  adPlaying = true;
  nextButton.style.display = "none";
  noAdMessage.style.display = "none";
  goHomeButton.style.display = "none";

  videoPlayer.controls = false; // disable controls for unskippable ad
  videoPlayer.src = "";
  videoPlayer.load();

  try {
    const res = await fetch("get_next_ad.php");
    if (!res.ok) throw new Error("Network response was not ok");
    const data = await res.json();

    if (data && data.file_path) {
      videoPlayer.src = data.file_path;
      videoPlayer.load();
      videoPlayer.play().catch(() => {
        // If autoplay blocked, user must interact
        alert("Please interact with the page to play the ad.");
      });
    } else {
      // No ad found, show message + button
      noAdMessage.style.display = "block";
      goHomeButton.style.display = "inline-block";
      videoPlayer.src = "";
      videoPlayer.load();
    }
  } catch (error) {
    alert("Error loading ad: " + error.message);
  }
}

// Add points after ad completes
async function addPoints() {
  if (!currentVideo) return;

  try {
    await fetch("add_points.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        viewerPoints: 2,
        creatorPoints: 1,
        creatorId: currentVideo.creator_id,
        videoId: currentVideo.video_id,
      }),
    });
  } catch (err) {
    console.error("Error adding points:", err);
  }
}

// When video ends, either play ad or show next button
videoPlayer.addEventListener("ended", () => {
  if (!adPlaying) {
    loadAd();
  } else {
    addPoints();
    nextButton.style.display = "inline-block";
  }
});

// Click next button to load next main video
nextButton.addEventListener("click", () => {
  loadNextVideo();
});

// Click go home button to redirect to home page
goHomeButton.addEventListener("click", () => {
  window.location.href = "index.html";
});

// Prevent seeking for both video and ad
let lastAllowedTime = 0;
videoPlayer.addEventListener("timeupdate", () => {
  if (!videoPlayer.seeking) {
    lastAllowedTime = videoPlayer.currentTime;
  }
});
videoPlayer.addEventListener("seeking", () => {
  if (Math.abs(videoPlayer.currentTime - lastAllowedTime) > 0.01) {
    videoPlayer.currentTime = lastAllowedTime;
  }
});

// Initial load: wait for user interaction to enable sound/playback
document.addEventListener("click", () => {
  loadNextVideo();
}, { once: true });
