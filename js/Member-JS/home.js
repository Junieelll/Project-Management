function openTab(event, tabName) {
    var tabContents = document.querySelectorAll(".tab-content");
    tabContents.forEach(content => content.classList.remove("active"));
  
    var tabButtons = document.querySelectorAll(".tab-button");
    tabButtons.forEach(button => button.classList.remove("active"));
  
    document.getElementById(tabName).classList.add("active");
    event.currentTarget.classList.add("active");
  }

  document.querySelector('.dropdown-btn.projects-btn').addEventListener('click', function () {
    this.parentElement.classList.toggle('active');
});

document.querySelector('.dropdown-btn.all-task').addEventListener('click', function () {
    this.parentElement.classList.toggle('active');
});

document.addEventListener("DOMContentLoaded", async () => {
  const urlParams = new URLSearchParams(window.location.search);
  const ProjectId = urlParams.get("project_id");

  if (!ProjectId) {
    console.error("Project ID not found in URL.");
    window.location.href = '';
    return;
  }
  
  async function fetchAnnouncementDetails() {
    try {
        const response = await fetch(`../../controller/fetch_member_announcement.php?project_id=${ProjectId}`);
        const result = await response.json();

        if (result.success) {
            const announcement = result.announcements;
            const loggedInUser = result.logged_in_user; 
            populateAnnouncementDetails(announcement, loggedInUser);
        } else {
            console.error("Failed to fetch announcement details:", result.message);
        }
    } catch (error) {
        console.error("Error fetching announcement details:", error);
    }
}

function populateAnnouncementDetails(announcements, loggedInUser) {
    const announcementBody = document.querySelector(".announcement-body");

    if (!announcements || announcements.length === 0) {
        announcementBody.innerHTML = `<p>No announcements available at the moment.</p>`;
        return;
    }

    // Clear the existing content to prevent duplication
    announcementBody.innerHTML = "";

    announcements.forEach((announcement) => {
        const postContainer = document.createElement("div");
        postContainer.className = "post-container";
        postContainer.innerHTML = `
            <input type="hidden" value="${announcement.announcement_id}" id="announcementId">
            <div class="post-header">
                <img src="${announcement.profile_picture}" alt="">
                <div class="user-info">
                    <h5>${announcement.user_name}</h5>
                    <small>${calculateTimeAgo(new Date(announcement.upload_date))}</small>
                </div>
            </div>
            <div class="post-body">
                <div class="post-content">
                    <h5>${announcement.title}</h5>
                    <p>${announcement.critical_message}</p>
                </div>
                <div class="image-container"></div>
                <p class="comment-qty">${announcement.comments.length} Comments</p>
            </div>
            <div class="post-footer">
                <img src="${loggedInUser.profile_picture}" alt="">
                <div class="text-input">
                    <textarea id="commentInput-${announcement.announcement_id}" placeholder="Write a comment..."></textarea>
                    <button class="comment-btn" data-announcement-id="${announcement.announcement_id}">
                        <i class="fa-solid fa-paper-plane-top"></i>
                    </button>
                </div>
            </div>
            <div class="comments"></div>
        `;

        // Append the post container to the body
        announcementBody.appendChild(postContainer);

        // Handle images dynamically
        const imageContainer = postContainer.querySelector(".image-container");
        if (announcement.files && announcement.files.length > 0) {
            const maxImages = 5;
            const numImages = Math.min(announcement.files.length, maxImages);

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

                extraImagesBadge.addEventListener("click", () => {
                    openImageModal(lastFile.file_path, announcement.files);
                });

                imageContainer.appendChild(extraImagesBadge);
            }
        }

        // Handle comments dynamically
        const commentsContainer = postContainer.querySelector(".comments");
        if (announcement.comments && announcement.comments.length > 0) {
            announcement.comments.forEach((comment) => {
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
        }

        // Add event listener for comment submission
        const commentButton = postContainer.querySelector(".comment-btn");
        const commentInput = postContainer.querySelector(`#commentInput-${announcement.announcement_id}`);

        commentButton.addEventListener("click", async function () {
            const content = commentInput.value.trim();
            const announcementId = announcement.announcement_id;

            if (content === "") {
                showToast("Comment cannot be empty.");
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
                    fetchAnnouncementDetails();
                } else {
                    showToast("Failed to add comment: " + result.message);
                }
            } catch (error) {
                console.error("Error inserting comment:", error);
            }
        });

        commentInput.addEventListener("keydown", function (event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                commentButton.click();
            }
        });

        commentInput.style.height = "25px";

        commentInput.addEventListener("input", function () {

            this.style.height = "25px"; 
            this.style.height = `${this.scrollHeight}px`; 
        });
    });
}

  
  function showToast(message, type = "info") {
    const toast = document.getElementById("toast");
    toast.innerHTML = message;

    toast.className = "toast show";
    if (type === "error") {
      toast.classList.add("toast-error");
    } else if (type === "success") {
      toast.classList.add("toast-success");
    } else {
      toast.classList.add("toast-info");
    }

    setTimeout(() => {
      toast.className = toast.className.replace("show", "");
    }, 3000);
  }


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



fetchAnnouncementDetails();

});