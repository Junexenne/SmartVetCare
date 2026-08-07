import { auth, db } from "./firebase-config.js";

import {
    signInWithEmailAndPassword
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

import {
    collection,
    query,
    where,
    getDocs
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

const loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        try {
            // Firebase Authentication Login
            const userCredential = await signInWithEmailAndPassword(
                auth,
                email,
                password
            );

            const user = userCredential.user;

            // Hanapin ang user sa Firestore
            const q = query(
                collection(db, "users"),
                where("email", "==", user.email)
            );

            const snapshot = await getDocs(q);

            if (snapshot.empty) {
                if (typeof showToast === "function") {
                    showToast("Login Failed", "User record not found.", "error");
                } else {
                    alert("User record not found.");
                }
                return;
            }

            const userData = snapshot.docs[0].data();

            // Check kung active ang account
            if (userData.status !== "active") {
                if (typeof showToast === "function") {
                    showToast("Account Disabled", "Please contact the clinic.", "error");
                } else {
                    alert("Account Disabled: Please contact the clinic.");
                }
                return;
            }

            // Save Session
            localStorage.setItem("isLoggedIn", "true");
            localStorage.setItem("userUID", user.uid);
            localStorage.setItem("userEmail", user.email);
            localStorage.setItem("ownerId", userData.ownerId);
            localStorage.setItem("fullName", userData.fullName);
            localStorage.setItem("phone", userData.phone);
            localStorage.setItem("address", userData.address);
            localStorage.setItem("role", userData.role);

            if (typeof showToast === "function") {
                showToast(
                    "Login Successful",
                    "Welcome back " + userData.fullName + "!",
                    "success"
                );
            } else {
                alert("Login Successful! Welcome back " + userData.fullName);
            }

            setTimeout(() => {
                window.location.href = "../pages/dashboard.php";
            }, 1500);

        } catch (error) {
            console.error(error);

            if (typeof showToast === "function") {
                showToast(
                    "Login Failed",
                    error.message,
                    "error"
                );
            } else {
                alert("Login Failed: " + error.message);
            }
        }
    });
}