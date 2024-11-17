<?php
  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: ../Views/Home.php');
  }

  include '../config/conn.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="script/firebase.js" defer type="module"></script>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
    <div class="loginContainer">
        <div class="container1">
          <img src="../images/devsphere-icon.png" alt="" id="devsphere-icon">
        </div>
    
        <div class="container2">
          <h1 id="welcome"> Welcome! </h1>
          <p id="quote"> Helping you to Learn with <span id="strong"> DevSphere </span></p>
          <button class="google" id="google-btn" onclick="">
            <img src="../images/google-icon.png" alt="" id="google-icon">
            <p class="button-desc"> Continue with <span id="strong2"> Gmail </span></p>
          </button>
        </div>
      </div>
</body>
</html>