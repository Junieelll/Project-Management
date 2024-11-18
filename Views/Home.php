<?php
session_start();

include '../config/conn.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <link rel="stylesheet" href="../styles/home.css" />
  <link
    rel="stylesheet"
    href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />


</head>

<body>
  <nav>
    <div class="logo">
      <button class="sidebar-btn" id="sidebarBtn">
        <i class="fa-regular fa-bars"></i>
      </button>
      <a href="Home.html" class="logo">
        <img src="../images/logo.png" alt="Logo" id="logo" />
      </a>
    </div>

    <div class="nav-links">
      <a href=""><img src="../images/home.png" alt="Home" id="home" /> Home</a>
      <a href=""><img src="../images/project.png" alt="Project" id="project" />
        Project Management</a>
      <a href=""><img src="../images/graduate.png" alt="Learning" id="toga" />
        Learning Hub</a>
    </div>

    <div class="search-input">
      <input type="text" name="" id="" placeholder="Search" />
      <i class="fa-solid fa-magnifying-glass search1"></i>
    </div>

    <div class="user-links">
      <a href="#" class="notifications">
        <img
          src="../images/notif.png"
          alt="Notifications"
          id="notifications" />
      </a>
      <a href="#" class="profile">
        <img src="../images/profile.png" alt="Profile" id="profile" />
      </a>
      <a href="#" class="message">
        <img src="../images/message.png" alt="Message" id="message" />
      </a>
    </div>
  </nav>

  <?php include 'Sidebar.php'; ?>

  <main>
    <div class="projects-list">
      <p class="noProj" style="display: none;">No Projects Yet</p>
      <button class="add-project" id="addProjectBtn"><i class="fa-solid fa-circle-plus"></i></button>
    </div>

    <div id="deleteModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Delete Project</h4>
        <span class="close"><i class="bi bi-x"></i></span>
      </div>
      <p>Once you delete this project, it cannot be undone.</p>
      <div class="modal-btns">
        <button id="cancelDelete" class="cancel-btn">Cancel</button>
        <button id="confirmDelete" class="confirm-btn">Yes, Delete</button>
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

  <script src="../js/home.js"></script>
</body>

</html>