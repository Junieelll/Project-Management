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
  

// import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
// import { 
//   getAuth, 
//   signInWithPopup, 
//   GoogleAuthProvider, 
//   setPersistence, 
//   browserSessionPersistence 
// } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
// import { getDatabase, ref, set } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-database.js";

// const firebaseConfig = {
//   apiKey: "AIzaSyCZMDHBfIYKhdczG3IKbImrkBapx6tDk3s",
//   authDomain: "communityhub-eb32f.firebaseapp.com",
//   databaseURL: "https://communityhub-eb32f-default-rtdb.firebaseio.com",
//   projectId: "communityhub-eb32f",
//   storageBucket: "communityhub-eb32f.firebasestorage.app",
//   messagingSenderId: "820902248808",
//   appId: "1:820902248808:web:27462ec4620b7adb0716dd",
//   measurementId: "G-W4TK6V23ZD"
// };

// // Initialize Firebase app, authentication, and database
// const app = initializeApp(firebaseConfig);
// const auth = getAuth(app);
// const db = getDatabase(app);

// const provider = new GoogleAuthProvider();
// provider.setCustomParameters({ prompt: 'select_account' });

// const googleLoginButton = document.getElementById("google-btn");
// const googleAlternativeButton = document.getElementById("google");

// // Generic function to handle login
// function handleGoogleLogin() {
//   setPersistence(auth, browserSessionPersistence)
//     .then(() => signInWithPopup(auth, provider))
//     .then(async (result) => {
//       const user = result.user;
//       const userId = user.uid;
//       const name = user.displayName;
//       const email = user.email;
//       const profilePicture = user.photoURL;

//       // Store user data in Realtime Database
//       await set(ref(db, `Users/${userId}/info`), {
//         email,
//         username: name,
//         photo: profilePicture,
//       }).catch((error) => console.error('Error saving user data:', error));

//       // Call backend to check if the user exists in your system
//       fetch("../auth/verify_user.php", {
//         method: "POST",
//         headers: { "Content-Type": "application/json" },
//         body: JSON.stringify({ userId }),
//       })
//         .then((response) => response.json())
//         .then((data) => {
//           if (data.exists) {
//             onLoginSuccess(user);
//             window.location.href = "../Views/Home.php";
//           } else {
//             window.location.href = `CreateAccount.php?userId=${encodeURIComponent(userId)}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&profilePicture=${encodeURIComponent(profilePicture)}`;
//           }
//         })
//         .catch((error) => console.error("Error:", error));
//     })
//     .catch((error) => console.error("Authentication error:", error.message));
// }

// // Handle successful login actions
// function onLoginSuccess(user) {
//   sessionStorage.setItem('userId', user.uid);
//   sessionStorage.setItem('userName', user.displayName);
//   sessionStorage.setItem('userPhoto', user.photoURL);
// }

// // Attach login handlers to both buttons
// if (googleLoginButton) {
//   googleLoginButton.addEventListener("click", handleGoogleLogin);
// }

// if (googleAlternativeButton) {
//   googleAlternativeButton.addEventListener("click", handleGoogleLogin);
// }
