function openTab(event, tabName) {
  var tabContents = document.querySelectorAll(".tab-content");
  tabContents.forEach((content) => content.classList.remove("active"));

  var tabButtons = document.querySelectorAll(".tab-button");
  tabButtons.forEach((button) => button.classList.remove("active"));

  document.getElementById(tabName).classList.add("active");
  event.currentTarget.classList.add("active");
}

document
  .querySelector(".dropdown-btn.projects-btn")
  .addEventListener("click", function () {
    this.parentElement.classList.toggle("active");
  });

document
  .querySelector(".dropdown-btn.all-task")
  .addEventListener("click", function () {
    this.parentElement.classList.toggle("active");
  });

const announcementBody = document.querySelector(".announcement-body");

announcementBody.addEventListener("click", function (event) {
  const clickedOptionButton = event.target.closest(".option-btn");
  const clickedOptionContainer = event.target.closest(".option-container");

  if (clickedOptionButton) {
    const optionContainer = clickedOptionButton.nextElementSibling;

    document.querySelectorAll(".option-container").forEach((container) => {
      if (container !== optionContainer) {
        container.style.display = "none";
      }
    });

    optionContainer.style.display =
      optionContainer.style.display === "flex" ? "none" : "flex";
  } else if (!clickedOptionContainer) {
    document.querySelectorAll(".option-container").forEach((container) => {
      container.style.display = "none";
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const postAnnouncementModal = document.getElementById(
    "postAnnouncementModal"
  );
  const postBtn = document.querySelector(".post-btn");
  const closeBtn = document.querySelector(".close-btn");

  postBtn.addEventListener("click", function () {
    postAnnouncementModal.classList.add("show");
  });

  closeBtn.addEventListener("click", function () {
    postAnnouncementModal.classList.remove("show");
  });

  const urlParams = new URLSearchParams(window.location.search);
  const projectId = urlParams.get("project_id");
  const announcementForm = document.getElementById("postAnnouncementForm");

  const fileInput = document.getElementById("upload_file");
  const filePreview = document.getElementById("filePreview");
  const filesArray = [];

  fileInput.addEventListener("change", (event) => {
    const files = event.target.files;

    Array.from(files).forEach((file, index) => {
      filesArray.push(file);

      const previewItem = document.createElement("div");
      previewItem.classList.add("preview-item");

      previewItem.innerHTML = `
              <div class="icon">
                  <i class="fa-solid fa-image"></i>
              </div>
              <p>${file.name}</p>
              <button type="button" class="remove-btn" data-index="${
                filesArray.length - 1
              }">
                  <i class="fa-solid fa-circle-xmark"></i>
              </button>
          `;

      filePreview.appendChild(previewItem);

      previewItem
        .querySelector(".remove-btn")
        .addEventListener("click", (e) => {
          const fileIndex = e.target.closest("button").dataset.index;
          filesArray.splice(fileIndex, 1);
          previewItem.remove();
        });
    });

    fileInput.value = "";
  });

  announcementForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    if (!projectId) {
      showToast("Project ID not found in the URL.");
      return;
    }

    const message = document.querySelector("#message").value.trim();

    const formData = new FormData();
    formData.append("project_id", projectId);
    formData.append("title", document.getElementById("project-title").value);
    formData.append("message", message);

    filesArray.forEach((file) => {
      formData.append("files[]", file);
    });

    try {
      const response = await fetch("../../controller/post_announcement.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        postAnnouncementModal.classList.remove('show');
        showToast(
          '<i class="fa-light fa-circle-check"></i> Announcement posted successfully!',
          "success"
        );
        announcementForm.reset();
        filePreview.innerHTML = "";
        filesArray.length = 0;
        fetchAnnouncementsForProject(projectId);
      } else {
        showToast(
          `<i class="fa-light fa-triangle-exclamation"></i> '${result.message}'`,
          "error"
        );
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });

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

  let currentImageIndex = 0;
  let currentImages = [];
  let currentAnnouncementId = null;

  fetchAnnouncementsForProject(projectId);

  if (!projectId) {
    console.error("Project ID not found in URL.");
    showToast("Project ID is missing.");
    window.location.href = '../../views/Home.php';
  }

  async function fetchAnnouncementsForProject(projectId) {
    try {
      const response = await fetch(
        `../../controller/fetch_announcements.php?project_id=${projectId}`
      );
      const result = await response.json();

      if (result.success) {
        renderAnnouncements(result.data);
      } else {
        console.error("Failed to fetch announcements:", result.message);
      }
    } catch (error) {
      console.error("Error fetching announcements:", error);
    }
  }

  function calculateTimeAgo(date) {
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    let interval = Math.floor(seconds / 31536000);

    if (interval >= 1) return interval + ' year' + (interval === 1 ? '' : 's') + ' ago';
    interval = Math.floor(seconds / 2592000);
    if (interval >= 1) return interval + ' month' + (interval === 1 ? '' : 's') + ' ago';
    interval = Math.floor(seconds / 86400);
    if (interval >= 1) return interval + ' day' + (interval === 1 ? '' : 's') + ' ago';
    interval = Math.floor(seconds / 3600);
    if (interval >= 1) return interval + ' hour' + (interval === 1 ? '' : 's') + ' ago';
    interval = Math.floor(seconds / 60);
    if (interval >= 1) return interval + ' minute' + (interval === 1 ? '' : 's') + ' ago';
    return Math.floor(seconds) + ' second' + (seconds === 1 ? '' : 's') + ' ago';
}

  function renderAnnouncements(announcements) {
    const announcementBody = document.querySelector(".announcement-body");
    announcementBody.innerHTML = "";

    announcements.forEach((announcement) => {
      const card = document.createElement("div");
      card.classList.add("card");

      card.dataset.announcementId = announcement.announcement_id;
      const postedTime = calculateTimeAgo(new Date(announcement.upload_date));

      const cardContent = `
        <div class="card-header">
              <div class="user-info">
                <img src="${announcement.profile_picture}" alt="${announcement.name}'s profile picture">
                <div class="name">
                  <h5>${announcement.name}</h5>
                  <small>${postedTime}</small>
                </div>
              </div>
              <button class="option-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
              <div class="option-container">
                  <button class="edit-btn"><i class="fa-light fa-pen-to-square"></i> Edit</button>
                  <button class="delete-btn"><i class="fa-light fa-trash"></i> Delete</button>
              </div>
        </div>
          <div class="card-body">
              <div class="title">
                  <h5>${announcement.title}</h5>
                  <p>${announcement.critical_message}</p>
              </div>
          </div>
      `;
      card.innerHTML = cardContent;

    card.addEventListener("click", function (event) {
      if (!event.target.closest(".option-btn") && !event.target.closest(".option-container")) {
        const announcementId = card.dataset.announcementId;
        window.location.href = `Announcement-Details.php?announcement_id=${announcementId}`;
      }
    });

    // event listener ng delete delete
      const deleteBtn = card.querySelector(".delete-btn");
      deleteBtn.addEventListener("click", () => showDeleteModal(announcement.announcement_id));

      // event listener ng edit button
      const editBtn = card.querySelector(".edit-btn");
      editBtn.addEventListener("click", () => openEditModal(announcement));

      const imageContainer = document.createElement("div");
      imageContainer.classList.add("image-container");

      const files = announcement.files;
      files.slice(0, 4).forEach((file) => {
        const img = document.createElement("img");
        img.src = file.file_path;
        img.alt = file.file_name;
        img.addEventListener("click", () =>{
          event.stopPropagation();
         openImageModal(file.file_path, files) 
        });
        imageContainer.appendChild(img);
      });

      if (files.length > 4) {
        const remainingCount = files.length - 4;

        const lastFile = files[4];
        const overlayDiv = document.createElement("div");
        overlayDiv.classList.add("file-overlay");
        overlayDiv.style.backgroundImage = `url('${lastFile.file_path}')`;
        overlayDiv.innerHTML = `<span>+${remainingCount}</span>`;

        overlayDiv.addEventListener("click", (event) =>{
          event.stopPropagation();
          openImageModal(lastFile.file_path, files)
        });
        imageContainer.appendChild(overlayDiv);
      }

      card.appendChild(imageContainer);
      announcementBody.appendChild(card);
    });
  }

  function openEditModal(announcement) {
    const editModal = document.getElementById("editAnnouncementModal");
    const titleInput = document.getElementById("edit-project-title");
    const messageTextarea = document.getElementById("edit-message");
    const filePreview = document.getElementById("editFilePreview");
  
    titleInput.value = announcement.title;
    messageTextarea.value = announcement.critical_message;
  
    let hiddenIdField = document.getElementById("edit-announcement-id");
    if (!hiddenIdField) {
      hiddenIdField = document.createElement("input");
      hiddenIdField.type = "hidden";
      hiddenIdField.id = "edit-announcement-id";
      hiddenIdField.name = "announcement_id";
      document.getElementById("editAnnouncementForm").appendChild(hiddenIdField);
    }
    hiddenIdField.value = announcement.announcement_id;
  
    filePreview.innerHTML = "";
  
    if (announcement.files) {
      announcement.files.forEach((file) => {
        const previewItem = document.createElement("div");
        previewItem.classList.add("preview-item");
        previewItem.innerHTML = `
          <div class="icon">
                  <i class="fa-solid fa-image"></i>
              </div>
              <p>${file.file_name}</p>
              <button type="button" class="remove-btn">
                  <i class="fa-solid fa-circle-xmark"></i>
            </button>
        `;

        const removeBtn = previewItem.querySelector(".remove-btn");
            removeBtn.addEventListener("click", () => removeFile(file.file_id, previewItem));


        filePreview.appendChild(previewItem);
      });
    }
  
    editModal.classList.add("show");
  }

  const editModal = document.getElementById("editAnnouncementModal");
  const cancelBtn = editModal.querySelector(".cancel-btn");


  cancelBtn.addEventListener("click", function () {
    editModal.classList.remove("show");
  });

  function removeFile(fileId, previewItem) {
    if (confirm("Are you sure you want to remove this file?")) {
        fetch(`../../controller/delete-file.php`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ file_id: fileId }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    previewItem.remove();

                } else {
                    showToast("Failed to delete the file. Please try again.", 'error');
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while deleting the file.");
            });
    }
}

  document.getElementById("editAnnouncementForm").addEventListener("submit", async (event) => {
    event.preventDefault();
  
    const title = document.getElementById("edit-project-title").value.trim();
    const message = document.getElementById("edit-message").value.trim();
    const announcementId = document.getElementById("edit-announcement-id").value;
  
    const formData = new FormData();
    formData.append("title", title);
    formData.append("message", message);
    formData.append("announcement_id", announcementId);
  
    try {
      const response = await fetch("../../controller/update_announcement.php", {
        method: "POST",
        body: formData,
      });
  
      const result = await response.json();
  
      if (result.success) {
        showToast("Announcement updated successfully!", "success");
        document.getElementById("editAnnouncementModal").classList.remove("show");
        fetchAnnouncementsForProject(projectId); 
      } else {
        showToast(result.message, "error");
      }
    } catch (error) {
      console.error("Error updating announcement:", error);
    }
  });
  

  function showDeleteModal(announcementId) {
    console.log("showDeleteModal called with ID:", announcementId); 
    currentAnnouncementId = announcementId;
    const modal = document.getElementById("deleteModal");
    modal.classList.add('show');
  }
  
  function hideDeleteModal() {
    const modal = document.getElementById("deleteModal");
    modal.classList.remove('show');
    currentAnnouncementId = null;
  }
  
  document.getElementById("confirmDelete").addEventListener("click", async () => {
    console.log("Confirm button clicked");
    if (currentAnnouncementId) {
      console.log("Current Announcement ID:", currentAnnouncementId);
      await deleteAnnouncement(currentAnnouncementId);
      hideDeleteModal();
      fetchAnnouncementsForProject(projectId); // Ensure `projectId` is defined
    }
  });
  
  
  document.getElementById("cancelDelete").addEventListener("click", () => {
    hideDeleteModal();
  });
  
  async function deleteAnnouncement(announcementId) {
  
    try {
      const response = await fetch(`../../controller/delete_announcement.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: announcementId }),
      });
  
      const result = await response.json();
  
      if (result.success) {
        showToast("Announcement deleted successfully", 'success');
      } else {
        showModal("Failed to delete announcement", 'error');
      }
    } catch (error) {
      console.error("Error during fetch:", error);
    }
  }
  
  function openImageModal(imagePath, images) {
    currentImages = images;
    currentImageIndex = images.findIndex(
      (file) => file.file_path === imagePath
    );

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
      prevButton.style.display = currentImageIndex === 0 ? "none" : "block";
      nextButton.style.display =
        currentImageIndex === currentImages.length - 1 ? "none" : "block";
    }
  }
});


