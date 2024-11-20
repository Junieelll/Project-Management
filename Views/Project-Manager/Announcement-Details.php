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
  <link rel="stylesheet" href="../../styles/Project-Manager-Styles/announcement-details.css" />
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
        <img src="../../images/logo.png" alt="Logo" id="logo" />
      </a>
    </div>

    <div class="nav-links">
      <a href=""><img src="../../images/home.png" alt="Home" id="home" /> Home</a>
      <a href=""><img src="../../images/project.png" alt="Project" id="project" />
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
        <img src="../../images/message.png" alt="Message" id="message" />
      </a>
    </div>
  </nav>

  <?php include '../Sidebar.php'; ?>

  <main>
      <div class="post-container">
        <div class="post-header">
        <button class="back-btn"><i class="fa-regular fa-arrow-left"></i></button>
          <img src="" alt="">
          <div class="user-info">
            <h5>Name</h5>
            <small>1 hour ago</small>
          </div>
        </div>
        <div class="post-body">
          <div class="post-content">
            <h5>Title</h5>
            <p>Message</p>
          </div>
          <div class="image-container">

          </div>
          <p class="comment-qty"></p>
        </div>
        <div class="post-footer">
          <img src="" alt="">
          <div class="text-input">
            <textarea name="" id="commentInput" placeholder="Write a comment..."></textarea>
            <button class="comment-btn"><i class="fa-solid fa-paper-plane-top"></i></button>
          </div>  
        </div>
        <div class="comments">
            
        </div>
      </div>

      <div id="imageModal" class="imageModal">
      <span id="closeModal" class="close">&times;</span>
      <button class="nav left" id="prevImage" style="display: none;"><i class="fa-regular fa-chevron-left"></i></button>
      <img src="" alt="Zoomed Image" class="image-modal-content">
      <button class="nav right" id="nextImage" style="display: none;"><i class="fa-regular fa-chevron-right"></i></button>
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

  <script src="../../js/Project-Manager-JS/announcement-details.js"></script>
</body>

</html>