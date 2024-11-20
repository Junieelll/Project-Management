document.addEventListener("DOMContentLoaded", async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const announcementId = urlParams.get("announcement_id");
  
    if (!announcementId) {
      console.error("Announcement ID not found in URL.");
      return;
    }
  
    async function fetchAnnouncementDetails() {
      try {
          const response = await fetch(`../../controller/fetch_announcement_details.php?announcement_id=${announcementId}`);
          const result = await response.json();
  
          if (result.success) {
              populateAnnouncementDetails(result.announcement, result.announcement.comments);
          } else {
              console.error("Failed to fetch announcement details:", result.message);
          }
      } catch (error) {
          console.error("Error fetching announcement details:", error);
      }
  }
  
  
  
  function populateAnnouncementDetails(announcement, comments) {
    document.querySelector(".post-header img").src = announcement.profile_picture;
    document.querySelector(".post-footer img").src = announcement.profile_picture;
    document.querySelector(".post-header h5").textContent = announcement.user_name;
    document.querySelector(".post-header small").textContent = calculateTimeAgo(new Date(announcement.upload_date));
  
    document.querySelector(".post-content h5").textContent = announcement.title;
    document.querySelector(".post-content p").textContent = announcement.critical_message;
  
    const imageContainer = document.querySelector(".image-container");
    imageContainer.innerHTML = ""; 

    const maxImages = 5; 
    const numImages = Math.min(announcement.files.length, maxImages);
  
    imageContainer.className = "image-container";
  
    const layoutClass = ["one", "two", "three", "four", "five"][numImages - 1];
    if (layoutClass) {
      imageContainer.classList.add(layoutClass);
    }
  
    announcement.files.forEach((file, index) => {
      if (index < maxImages) {
          const img = document.createElement("img");
          img.src = file.file_path;
          img.alt = file.file_name;

          img.addEventListener("click", () => {
              openImageModal(file.file_path, announcement.files);
          });

          imageContainer.appendChild(img);
      }
  });
  
  if (announcement.files.length > maxImages) {
    const remainingCount = announcement.files.length - maxImages;
    const lastFile = announcement.files[maxImages];

    const extraImagesBadge = document.createElement("div");
    extraImagesBadge.className = "extra-images";
    extraImagesBadge.innerHTML = `<span>+${remainingCount}</span>`;

    extraImagesBadge.style.backgroundImage = `url('${lastFile.file_path}')`;

    imageContainer.appendChild(extraImagesBadge);

    extraImagesBadge.addEventListener("click", () => {
        openImageModal(lastFile.file_path, announcement.files);
    });
}

  
    const commentsContainer = document.querySelector(".comments");
    commentsContainer.innerHTML = ""; 
    comments.forEach((comment) => {
      const commentCard = `
        <div class="user-card">
          <img src="${comment.commenter_picture}" alt="">
          <div class="user-info">
            <div class="user-name">
              <h5>${comment.commenter_name}</h5>
              <small>${calculateTimeAgo(new Date(comment.created_date))}</small>
            </div>
            <div class="comment-content">
              <p>${comment.comment_text}</p>
            </div>
          </div>
        </div>
      `;
      commentsContainer.innerHTML += commentCard;
    });
  
    document.querySelector(".comment-qty").textContent = `${comments.length} Comments`;
  }

  let currentImages = [];
let currentImageIndex = 0;

function openImageModal(imagePath, images) {
    currentImages = images;
    currentImageIndex = images.findIndex(file => file.file_path === imagePath);

    const modal = document.getElementById("imageModal");
    const modalImg = modal.querySelector("img");
    modalImg.src = imagePath;
    modal.style.display = "block";

    updateChevronVisibility();
}

document.getElementById("closeModal").addEventListener("click", () => {
    document.getElementById("imageModal").style.display = "none";
});

document.getElementById("prevImage").addEventListener("click", () => {
    if (currentImageIndex > 0) {
        currentImageIndex--;
        updateModalImage();
    }
});

document.getElementById("nextImage").addEventListener("click", () => {
    if (currentImageIndex < currentImages.length - 1) {
        currentImageIndex++;
        updateModalImage();
    }
});

function updateModalImage() {
    const modalImg = document.getElementById("imageModal").querySelector("img");
    modalImg.src = currentImages[currentImageIndex].file_path;

    updateChevronVisibility();
}

function updateChevronVisibility() {
    const prevButton = document.getElementById("prevImage");
    const nextButton = document.getElementById("nextImage");

    if (currentImages.length === 1) {
        prevButton.style.display = "none";
        nextButton.style.display = "none";
    } else {
        prevButton.style.display = currentImageIndex === 0 ? "none" : "flex";
        nextButton.style.display = currentImageIndex === currentImages.length - 1 ? "none" : "flex";
    }
}
  
const commentInput = document.getElementById("commentInput");

commentInput.style.height = "25px";

commentInput.addEventListener("input", function () {
    this.style.height = "25px";
    
    this.style.height = `${this.scrollHeight}px`;
});
  
  function calculateTimeAgo(date) {
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    let interval = Math.floor(seconds / 31536000);
    if (interval >= 1) return `${interval} year${interval > 1 ? "s" : ""} ago`;
    interval = Math.floor(seconds / 2592000);
    if (interval >= 1) return `${interval} month${interval > 1 ? "s" : ""} ago`;
    interval = Math.floor(seconds / 86400);
    if (interval >= 1) return `${interval} day${interval > 1 ? "s" : ""} ago`;
    interval = Math.floor(seconds / 3600);
    if (interval >= 1) return `${interval} hour${interval > 1 ? "s" : ""} ago`;
    interval = Math.floor(seconds / 60);
    if (interval >= 1) return `${interval} minute${interval > 1 ? "s" : ""} ago`;
    return `${seconds} second${seconds > 1 ? "s" : ""} ago`;
  }

  const backBtn = document.querySelector('.back-btn');

  backBtn.addEventListener('click', goBack);

  function goBack() {
    history.back();
  }

  const commentButton = document.querySelector('.comment-btn');

  document.querySelector('.comment-btn').addEventListener('click', async function () {
    const commentInput = document.getElementById('commentInput');
    const content = commentInput.value.trim();
    
    if (content === "") {
        alert("Comment cannot be empty.");
        return;
    }

    try {
        const response = await fetch('../../controller/insert_comment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                content: content,
                announcement_id: announcementId
            })
        });

        const result = await response.json();
        
        if (result.success) {
            commentInput.value = "";
            commentInput.style.height = "25px";
            fetchAnnouncementDetails();
            
            console.log("Comment added successfully!");
        } else {
            alert("Failed to add comment: " + result.message);
        }
    } catch (error) {
        console.error("Error inserting comment:", error);
    }
});

commentInput.addEventListener('keydown', function (event) {
  if (event.key === 'Enter' && !event.shiftKey) { // Check if Enter is pressed without Shift
      event.preventDefault(); // Prevent newline creation
      commentButton.click(); // Trigger the button click
  }
});


fetchAnnouncementDetails();
});