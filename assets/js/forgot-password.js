import { auth, db } from "./firebase-config.js";
import { sendPasswordResetEmail } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";
import { collection, query, where, getDocs } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

const forgotForm = document.getElementById("forgotForm");

if (forgotForm) {
    forgotForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const inputEmail = document.getElementById("email").value.trim();
        
        try {
            // 1. Search Firestore if the input matches a linked personalEmail
            const usersRef = collection(db, "users");
            const q = query(usersRef, where("personalEmail", "==", inputEmail));
            const snapshot = await getDocs(q);

            let targetAuthEmail = inputEmail;

            if (!snapshot.empty) {
                // If found, grab the official clinic email linked to it
                const userData = snapshot.docs[0].data();
                targetAuthEmail = userData.email; 
            }

            // 2. Send the password reset email via Firebase Auth
            await sendPasswordResetEmail(auth, targetAuthEmail);
            
            if (typeof showToast === "function") {
                showToast(
                    "Reset Link Sent",
                    "Please check your personal email for password reset instructions.",
                    "success"
                );
            }
            forgotForm.reset();
        } catch (error) {
            let errorMsg = error.message;
            if (error.code === 'auth/user-not-found') {
                errorMsg = "No registered account found with this email.";
            }
            if (typeof showToast === "function") {
                showToast("Request Failed", errorMsg, "error");
            }
        }
    });
}