import { auth, db } from "./firebase-config.js";

import {
    createUserWithEmailAndPassword
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

import {
    doc,
    setDoc,
    serverTimestamp
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

const registerForm = document.getElementById("registerForm");

if (registerForm) {

    registerForm.addEventListener("submit", async (e) => {

        e.preventDefault();

        const firstName = document.getElementById("firstName").value.trim();
        const lastName = document.getElementById("lastName").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const address = document.getElementById("address").value.trim();

        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        if (password !== confirmPassword) {

            showToast(
                "Password Error",
                "Passwords do not match.",
                "error"
            );

            return;

        }

        try {

            const userCredential = await createUserWithEmailAndPassword(
                auth,
                email,
                password
            );

            const user = userCredential.user;

           const names = fullName.trim().split(" ");

const firstName = names.shift() || "";
const lastName = names.join(" ");

await setDoc(doc(db, "users", user.uid), {

    firstName: firstName,
    lastName: lastName,
    email: email,
    phone: phone,
    address: "",
    profileImage: "",
    createdAt: serverTimestamp()

});

            showToast(
                "Account Created",
                "You can now login to your account.",
                "success"
            );

            registerForm.reset();

            setTimeout(() => {

                window.location.href = "../index.php";

            }, 1500);

        }

        catch (error) {

            showToast(
                "Registration Failed",
                error.message,
                "error"
            );

        }

    });

}