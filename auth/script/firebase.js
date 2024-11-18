import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

const firebaseConfig = {
  apiKey: "AIzaSyAcS-SFf8tDGBSQdlfrHZf4ZjhyDKf89vA",
  authDomain: "devsphereprojman.firebaseapp.com",
  databaseURL: "https://devsphereprojman-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "devsphereprojman",
  storageBucket: "devsphereprojman.firebasestorage.app",
  messagingSenderId: "859316508211",
  appId: "1:859316508211:web:c3f70fcf7c598a3b16b546"
};


const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

const provider = new GoogleAuthProvider();
const googleLogin = document.getElementById("google-btn");

provider.setCustomParameters({
    prompt: 'select_account'
});

googleLogin.addEventListener("click", function () {
    signInWithPopup(auth, provider)
      .then((result) => {
        const user = result.user;
        const userId = user.uid; 
  
        fetch("../auth/verify_user.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ userId: userId }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.exists) {
              window.location.href = "../Views/Home.php";
            } else {
              window.location.href = "../Views/CreateAccount.php";
            }
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      })
      .catch((error) => {
        console.error("Authentication error:", error);
      });
  });
  