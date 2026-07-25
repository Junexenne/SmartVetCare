import { auth } from "./firebase-config.js";

import {
    signInWithEmailAndPassword
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

const loginForm = document.getElementById("loginForm");

if(loginForm){

    loginForm.addEventListener("submit", async(e)=>{

        e.preventDefault();

        const email=document.getElementById("email").value.trim();

        const password=document.getElementById("password").value;

        try{

            await signInWithEmailAndPassword(
                auth,
                email,
                password
            );

           // Save login session
localStorage.setItem("isLoggedIn", "true");
localStorage.setItem("userEmail", auth.currentUser.email);
localStorage.setItem("userUID", auth.currentUser.uid);

showToast(
    "Login Successful",
    "Welcome back to Smart Vet Care!",
    "success"
);

setTimeout(() => {

    window.location.href = "../pages/dashboard.php";

}, 1500);

        }catch(error){

            showToast(error.message, "error");

        }

    });

}