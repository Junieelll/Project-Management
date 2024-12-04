<?php
session_start();

include '../../config/conn.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../../auth/login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <link rel="stylesheet" href="../../styles/Member-Styles/home.css" />
  <link
    rel="stylesheet"
    href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="../../js/Member-JS/ganttChartUser.js"></script>
    <script src="../../js/Member-JS/dragUser.js" defer></script>
  <link rel="stylesheets" src="../../styles/Member-Styles/home.css"/>
</head>

<body>
  <nav>
    <div class="logo">
      <button class="sidebar-btn" id="sidebarBtn">
        <i class="fa-regular fa-bars"></i>
      </button>
      <a href="Home.html" class="logo">
        <img src="../../images/logo.png" alt="Logo" id="logo" />
      </a>
    </div>

    <div class="nav-links">
      <a href=""><img src="../../images/home.png" alt="Home" id="home" /> Home</a>
      <a href="../Home.php"><img src="../../images/project.png" alt="Project" id="project" />
        Project Management</a>
      <a href=""><img src="../../images/graduate.png" alt="Learning" id="toga" />
        Learning Hub</a>
    </div>

    <div class="search-input">
      <input type="text" name="" id="" placeholder="Search" />
      <i class="fa-solid fa-magnifying-glass search1"></i>
    </div>

    <div class="user-links">
      <a href="#" class="notifications">
        <img
          src="../../images/notif.png"
          alt="Notifications"
          id="notifications" />
      </a>
      <a href="#" class="profile">
        <img src="../../images/profile.png" alt="Profile" id="profile" />
      </a>
      <a href="#" class="message">
        <img src="../../images/message.png" alt="Message" />
      </a>
    </div>
  </nav>

  <?php include '../Sidebar.php'; ?>

  <main>
    <div class="tabs">
      <button
        class="tab-button active"
        onclick="openTab(event, 'announcement')">
        Announcement
      </button>
      <button class="tab-button" onclick="openTab(event, 'task-tracking')">
        Task Tracking
      </button>
      <button
        class="tab-button"
        onclick="openTab(event, 'progress-tracking')">
        Progress Tracking
      </button>
    </div>

    <div id="announcement" class="tab-content active">
      <div class="container">
        <div class="header">
          <h2>Announcement Board</h2>
        </div>

        <div class="announcement-body">
      
        </div>
      </div>
    </div>

    <div id="task-tracking" class="tab-content">
      <div class="project-header">
        <div class="dropdown">
          <button class="dropdown-btn projects-btn">
            DevSphere Project
            <span><i class="fa-solid fa-chevron-down"></i></span>
          </button>
          <div class="dropdown-content project-list">
            <button>Project 2</button>
            <button>Project 3</button>
            <button>Project 4</button>
          </div>
        </div>

        <div class="toolbar">
          <div class="search-input">
            <input type="text" name="" id="" placeholder="Search" />
            <i class="fa-solid fa-magnifying-glass search2"></i>
          </div>
          <div class="dropdown">
            <button class="dropdown-btn all-task">
              All Task
              <span><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="dropdown-content task-status">
              <button>BackLogs</button>
              <button>In Progress</button>
              <button>Testing</button>
              <button>Completed</button>
            </div>
          </div>
          <button class="sort-btn"><i class="fa-solid fa-sort"></i> Sort</button>
          <button id="confirm-bulk-status-change">Confirm Kanban Changes</button>
        </div>
      </div>

      <div class="project-content">
        <div class="members">
          <div class="user active">
            <i class="fa-solid fa-user"></i>
          </div>
          <div class="user">
            <i class="fa-solid fa-user"></i>
          </div>
          <div class="add-member">
            <button class="add-member-btn"><i class="fa-solid fa-plus"></i></button>
          </div>
        </div>

        <div class="cards-container">
                <div class="backlogs" id="Backlog">
                  <h5>BACKLOG</h5>
                </div>

                <div class="in-progress" id="InProgress">
                  <h5>IN PROGRESS</h5>
                </div>

                <div class="testing" id="Testing">
                  <h5>TESTING</h5>
                </div>

                <div class="finished" id="Finished">
                  <h5>FINISHED</h5>
                </div>
              </div>
          </div>
      </div>

     <!-- Modal for File Upload -->
<div class="add_filetask" id="fileUploadModal">
    <div class="modal-content">
        <span class="close-btn" id="closeModalBtn">&times;</span>
        <h2>Upload Your Files</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="hidden" id="taskIdInput" name="task_id" /> <!-- Hidden input for task_id -->
            <input type="file" name="files[]" id="fileInput" multiple required />
            <br>
            <button type="submit">Upload</button>
        </form>
    </div>
</div>


    <div id="progress-tracking" class="tab-content">
      <h2>Progress Tracking Content</h2>
      <div class="gantt">
      <div id="timeline" style="width: 900px; height: 500px;"></div>
      </div>
      <div class="piechart">
      <div id="pie" style="width: 900px; height: 500px;"></div>
      </div>
    </div>
    </div>

    <div id="imageModal" class="imageModal">
      <span id="closeModal" class="close">&times;</span>
      <button class="nav left" id="prevImage" style="display: none;"><i class="fa-regular fa-chevron-left"></i></button>
      <img src="" alt="Zoomed Image" class="image-modal-content">
      <button class="nav right" id="nextImage" style="display: none;"><i class="fa-regular fa-chevron-right"></i></button>
    </div>

    <div id="deleteModal" class="modal">
      <div class="modal-content">
        <h4>Confirm Deletion</h4>
        <p>Are you sure you want to delete this announcement?</p>
        <div class="action-btns">
          <button id="confirmDelete" class="confirm-btn">Confirm</button>
          <button id="cancelDelete" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="socials">
      <a href="https://www.facebook.com">
        <i class="fa-brands fa-facebook-f"></i>
      </a>
      <a href="https://www.twitter.com">
        <i class="fa-brands fa-twitter"></i>
      </a>
    </div>

    <div class="footer-links">
      <a href="About.php">About</a>
      <a href="Privacy.php">Privacy Policy</a>
      <a href="UserAgreement.php">User Agreement</a>
      <a href="Terms.php">Terms of Service</a>
      <a href="FAQs.php">FAQ</a>
      <a href="#help-center">Help Center</a>
    </div>

    <hr class="line">

    <p class="copyright">ⓒ 2024 DevSphere, Inc</p>
  </footer>

  <script src="../../js/Member-Js/home.js"></script>
</body>

</html>