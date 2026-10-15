import { auth } from "./firebase-config.js";
import { signOut } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

// Ginagamit ang document level listener para masigurong gagana kahit dynamic o galing sa PHP include ang sidebar
document.addEventListener("click", async (e) => {
    const logoutBtn = e.target.closest("#logoutBtn") || e.target.closest(".logout-btn");

    if (logoutBtn) {
        e.preventDefault(); // Pigilan ang default link action ng '#'

        // Magpakita ng confirmation prompt
        const isConfirmed = confirm("Are you sure you want to logout?");

        if (isConfirmed) {
            try {
                await signOut(auth);

                localStorage.removeItem("isLoggedIn");
                localStorage.removeItem("userEmail");
                localStorage.removeItem("userUID");

                window.location.href = "../auth/login-user.php";
            } catch (error) {
                console.error("Error signing out: ", error);
                alert("May problemang naganap habang nagla-log out. Pakisubukan ulit.");
            }
        }
    }
});