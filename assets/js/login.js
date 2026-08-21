import { auth, db } from "./firebase-config.js";
import {
    signInWithEmailAndPassword,
    setPersistence,
    browserLocalPersistence,
    browserSessionPersistence
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";
import {
    collection,
    query,
    where,
    getDocs
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

const loginForm = document.getElementById("loginForm");
const emailInput = document.getElementById("email");
const rememberMeCheckbox = document.getElementById("rememberMe");

// 1. I-load ang naka-save na email kung meron man sa localStorage (Remember Me)
document.addEventListener("DOMContentLoaded", () => {
    const savedEmail = localStorage.getItem("savedUserEmail");
    if (savedEmail) {
        emailInput.value = savedEmail;
        rememberMeCheckbox.checked = true;
    }
});

// Safe helper para tawagin ang showToast galing sa toast.js
function triggerToast(title, message, type) {
    if (typeof showToast === "function") {
        showToast(title, message, type);
    } else {
        alert(`${title}: ${message}`);
    }
}

async function handleLogin() {
    const email = emailInput.value.trim();
    const password = document.getElementById("password").value;
    const rememberMe = rememberMeCheckbox.checked;

    if (!email || !password) {
        triggerToast("Validation Error", "Please fill in all fields.", "error");
        return;
    }

    try {
        // Itakda ang Firebase Persistence base sa Remember Me checkbox
        const persistenceType = rememberMe ? browserLocalPersistence : browserSessionPersistence;
        await setPersistence(auth, persistenceType);

        // Firebase Authentication Login
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        // Hanapin ang user sa Firestore
        const q = query(
            collection(db, "users"),
            where("email", "==", user.email)
        );

        const snapshot = await getDocs(q);

        if (snapshot.empty) {
            triggerToast("Login Failed", "User record not found.", "error");
            return;
        }

        const userData = snapshot.docs[0].data();

        // Check kung active ang account
        if (userData.status !== "active") {
            triggerToast("Account Disabled", "Please contact the clinic.", "error");
            return;
        }

        // I-handle ang Remember Me para sa Email input field
        if (rememberMe) {
            localStorage.setItem("savedUserEmail", email);
        } else {
            localStorage.removeItem("savedUserEmail");
        }

        // Save Session sa localStorage
        localStorage.setItem("isLoggedIn", "true");
        localStorage.setItem("userUID", user.uid);
        localStorage.setItem("userEmail", user.email);
        localStorage.setItem("ownerId", userData.ownerId);
        localStorage.setItem("fullName", userData.fullName);
        localStorage.setItem("phone", userData.phone);
        localStorage.setItem("address", userData.address);
        localStorage.setItem("role", userData.role);

        // Success Toast
        triggerToast(
            "Login Successful",
            "Welcome back " + userData.fullName + "!",
            "success"
        );

        setTimeout(() => {
            window.location.href = "../pages/dashboard.php";
        }, 1500);

    } catch (error) {
        console.error(error);

        let errorMessage = error.message;
        if (error.code === 'auth/invalid-credential' || error.code === 'auth/wrong-password' || error.code === 'auth/user-not-found') {
            errorMessage = "Invalid email or password.";
        }

        triggerToast("Login Failed", errorMessage, "error");
    }
}

if (loginForm) {
    // 1. Kapag pinindot ang Log In button o nag-submit ang form
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        await handleLogin();
    });

    // 2. Sinisigurong gagana rin kapag pinindot ang "Enter" key sa loob ng inputs
    const inputs = loginForm.querySelectorAll("input");
    inputs.forEach(input => {
        if (input.type === "checkbox") return;

        input.addEventListener("keydown", async (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                await handleLogin();
            }
        });
    });
}