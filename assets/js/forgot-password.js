import { auth } from "./firebase-config.js";

import {
    sendPasswordResetEmail
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

const forgotForm = document.getElementById("forgotForm");

if (forgotForm) {

    forgotForm.addEventListener("submit", async (e) => {

        e.preventDefault();

        const email = document.getElementById("email").value.trim();

        try {

            await sendPasswordResetEmail(auth, email);

            showToast(
                "Reset Link Sent",
                "Please check your email for password reset instructions.",
                "success"
            );

            forgotForm.reset();

        } catch (error) {

            showToast(
                "Request Failed",
                error.message,
                "error"
            );

        }

    });

}