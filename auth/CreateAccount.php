<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="../styles/create-acc.css" />
    <link
      rel="stylesheet"
      href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
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
        <a href=""
          ><img src="../images/home.png" alt="Home" id="home" /> Home</a
        >
        <a href=""
          ><img src="../images/project.png" alt="Project" id="project" />
          Project Management</a
        >
        <a href=""
          ><img src="../images/graduate.png" alt="Learning" id="toga" />
          Learning Hub</a
        >
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
            id="notifications"
          />
        </a>
        <a href="#" class="profile">
          <img src="../images/profile.png" alt="Profile" id="profile" />
        </a>
        <a href="#" class="message">
          <img src="../images/message.png" alt="Message" id="message" />
        </a>
      </div>
    </nav>


    <main>
      <div class="wrapper">
        <form id="createAccForm">
          <h3>Register</h3>
          <p>You've successfully authenticated with Google. Please fill in your details below and click the Register button to finish logging in.</p>
          <div class="name-input">
          <i class="fa-light fa-user icon"></i>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
          </div>
          <div class="email-input">
          <i class="fa-light fa-envelope icon"></i>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required readonly>
          </div>
          <input type="hidden" id="userId" name="userId">
          <input type="hidden" id="profilePicture" name="profilePicture">
          <button type="submit" id="createBtn">Create Account</button>
        </form>
      </div>
    </main>
      
    <footer>
      <div class="socials">
          <a href="https://www.facebook.com">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
          <a href="https://www.twitter.com" >
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
    <script>
        function getQueryParams() {
            const params = {};
            window.location.search
                .substring(1)
                .split("&")
                .forEach((param) => {
                    const [key, value] = param.split("=");
                    params[decodeURIComponent(key)] = decodeURIComponent(value || "");
                });
            return params;
        }

        const queryParams = getQueryParams();
        document.getElementById("name").value = queryParams.name || "";
        document.getElementById("email").value = queryParams.email || "";
        document.getElementById("userId").value = queryParams.userId || "";
        document.getElementById("profilePicture").value = queryParams.profilePicture || "";

        document.getElementById("createAccForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const userId = document.getElementById("userId").value;
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const profilePicture = document.getElementById("profilePicture").value;

    fetch("../controller/create_user.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ userId, name, email, profilePicture }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                window.location.href = "../Views/Home.php";
            } else {
                alert("Error creating account: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
});

    </script>
  </body>
</html>
