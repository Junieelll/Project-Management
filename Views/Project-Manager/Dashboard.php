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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard and Manage Users</title>
  <link rel="stylesheet" href="../../styles/dashboard.css">
  <link
    rel="stylesheet"
    href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <script type="text/javascript" src="../../js/Project-Manager-JS/dashboardadmin.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="../../js/Project-Manager-JS/dashboardchart.js"></script>
</head>
<body>
<nav>
    <div class="logo">
      <button class="sidebar-btn" id="sidebarBtn">
        <i class="fa-regular fa-bars"></i>
      </button>
      <a href="../Home.php" class="logo">
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
  <div class="dashboard">
    <!-- Dashboard Header -->
    <header class="dashboard-header">
      <h1>Dashboard</h1>
      <a id="hrefproj">Go To Project</a>
    </header>

    

    <!-- Dashboard Stats -->
    <div class="stats">
      <div class="stat-item">
        <h2>Total Task</h2>
        <p>6</p>
      </div>
      <div class="stat-item">
        <h2>Task In-Progress</h2>
        <p>21</p>
      </div>
      <div class="stat-item">
        <h2>Task Finished</h2>
        <p>2</p>
      </div>
      <div class="stat-item">
        <h2>Total User</h2>
        <p>12</p>
      </div>
    </div>

    <!-- Dashboard Details -->
    <div class="tasks-overview">
      <div class="tasks">
        <h3>Tasks</h3>
        <ul>
        </ul>
      </div>

      <div class="status">
        <h3>Project Status</h3>
        <div class="chart">
        <div id="piechart" style="width: 500px; height: 250px;"></div>
        </div>
      </div>
    </div>

    <!-- Manage Users Section -->
    <div class="manage-users">
      <header class="header">
        <h2>Manage Users</h2>
      </header>

      <div class="users-container">
        <div class="user-card owner">
          <div class="badge">Owner</div>
          <div class="avatar">W</div>
          <h3>WorkDo</h3>
          <p>company@example.com</p>
          <div class="user-stats">
            <span>9 Tasks</span>
          </div>
        </div>

        <div class="user-card">
          <div class="badge">Member</div>
          <div class="avatar">
            <img src="https://via.placeholder.com/50" alt="User">
          </div>
          <h3>Alex</h3>
          <p>alex@example.com</p>
        </div>



        <!-- Add more user cards as needed -->
      </div>
    </div>
  </div>
</body>
</html>
