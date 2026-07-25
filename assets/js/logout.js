import { auth } from "./firebase-config.js";

import {
    signOut
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

const logoutBtn = document.getElementById("logoutBtn");

if(logoutBtn){

    logoutBtn.addEventListener("click", async()=>{

        await signOut(auth);

        localStorage.removeItem("isLoggedIn");
        localStorage.removeItem("userEmail");
        localStorage.removeItem("userUID");

        window.location.href="../auth/login-user.php";

    });

}