import { auth, db } from "./firebase-config.js";
import {
    signInWithEmailAndPassword,
    setPersistence,
    browserLocalPersistence,
    browserSessionPersistence,
    signOut,
    sendEmailVerification
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

let otpModal = document.getElementById("otpModal");
if (!otpModal) {
    otpModal = document.createElement("div");
    otpModal.id = "otpModal";
    otpModal.style.cssText = "display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; font-family: 'Poppins', sans-serif;";
    otpModal.innerHTML = `
        <div style="background: #fff; padding: 30px; border-radius: 16px; width: 380px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); text-align: center;">
            <div style="font-size: 32px; color: #5142f5; margin-bottom: 12px;"><i class="fa-solid fa-shield-halved"></i></div>
            <h3 style="color: #1e1b4b; margin-bottom: 8px; font-size: 18px; font-weight: 700;">Two-Factor Verification</h3>
            <p id="otpSubtitle" style="color: #64748b; font-size: 13px; margin-bottom: 20px; line-height: 1.4;">We have sent an email verification link to your address.</p>
            
            <div id="otpDestinationContainer" style="margin-bottom: 20px;">
                <p style="font-size: 13px; color: #334155; margin-bottom: 15px;">Please check your inbox and click the verification link sent by Firebase to proceed.</p>
                <button type="button" id="checkVerificationBtn" style="width: 100%; padding: 12px; background: #5142f5; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; margin-bottom: 10px;">I Have Verified My Email</button>
                <button type="button" id="resendEmailBtn" style="width: 100%; padding: 10px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">Resend Verification Email</button>
            </div>

            <button type="button" id="closeOtpModalBtn" style="background: none; border: none; color: #94a3b8; font-size: 12px; cursor: pointer; text-decoration: underline;">Cancel / Back to Login</button>
        </div>
    `;
    document.body.appendChild(otpModal);
}

let tempUserData = null;
let tempUserObject = null;

document.addEventListener("DOMContentLoaded", () => {
    const savedEmail = localStorage.getItem("savedUserEmail");
    if (savedEmail && emailInput) {
        emailInput.value = savedEmail;
        if (rememberMeCheckbox) rememberMeCheckbox.checked = true;
    }

    const closeBtn = document.getElementById("closeOtpModalBtn");
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            otpModal.style.display = "none";
            if (auth.currentUser) signOut(auth);
        });
    }

    const checkVerificationBtn = document.getElementById("checkVerificationBtn");
    if (checkVerificationBtn) {
        checkVerificationBtn.addEventListener("click", async () => {
            if (tempUserObject) {
                await tempUserObject.reload();
                if (tempUserObject.emailVerified) {
                    finalizeSuccessfulLogin();
                } else {
                    triggerToast("Not Verified Yet", "Please click the verification link in your email first.", "error");
                }
            }
        });
    }

    const resendEmailBtn = document.getElementById("resendEmailBtn");
    if (resendEmailBtn) {
        resendEmailBtn.addEventListener("click", async () => {
            if (tempUserObject) {
                try {
                    const actionCodeSettings = {
                        url: 'http://localhost/SmartVetCare/auth/login-user.php?verified=true',
                        handleCodeInApp: true
                    };
                    await sendEmailVerification(tempUserObject, actionCodeSettings);
                    triggerToast("Email Sent", "Verification email sent successfully!", "success");
                } catch (err) {
                    triggerToast("Error", "Too many requests. Please try again later.", "error");
                }
            }
        });
    }
});

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
    const rememberMe = rememberMeCheckbox ? rememberMeCheckbox.checked : false;

    if (!email || !password) {
        triggerToast("Validation Error", "Please fill in all fields.", "error");
        return;
    }

    try {
        const persistenceType = rememberMe ? browserLocalPersistence : browserSessionPersistence;
        await setPersistence(auth, persistenceType);
        
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        proceedAfterAuth(userCredential.user, rememberMe, email);

    } catch (error) {
        console.error("Login process error:", error);
        let errorMessage = error.message;
        if (error.code === 'auth/invalid-credential' || error.code === 'auth/wrong-password' || error.code === 'auth/user-not-found') {
            errorMessage = "Invalid email or password.";
        }
        triggerToast("Login Failed", errorMessage, "error");
    }
}

async function proceedAfterAuth(user, rememberMe, email) {
    const usersRef = collection(db, "users");
    let q = query(usersRef, where("email", "==", user.email));
    let snapshot = await getDocs(q);
    if (snapshot.empty) {
        q = query(usersRef, where("personalEmail", "==", user.email));
        snapshot = await getDocs(q);
    }
    if (snapshot.empty) {
        triggerToast("Login Failed", "User record not found.", "error");
        await signOut(auth);
        return;
    }
    const userData = snapshot.docs[0].data();
    const userDocId = snapshot.docs[0].id;

    if (userData.status !== "active") {
        triggerToast("Account Disabled", "Please contact the clinic.", "error");
        await signOut(auth);
        return;
    }

    if (rememberMe) {
        localStorage.setItem("savedUserEmail", email);
    } else {
        localStorage.removeItem("savedUserEmail");
    }

    tempUserData = { userDocId, ...userData };
    tempUserObject = user;

    try {
        if (!user.emailVerified) {
            const actionCodeSettings = {
                url: 'http://localhost/SmartVetCare/auth/login-user.php?verified=true',
                handleCodeInApp: true
            };
            await sendEmailVerification(user, actionCodeSettings);
        }
    } catch (err) {
        console.log("Email verification already sent or rate limited.");
    }

    const subTitleElement = document.getElementById("otpSubtitle");
    if (subTitleElement) {
        subTitleElement.innerHTML = `Verification link sent to <b>${user.email}</b>.`;
    }
    
    otpModal.style.display = "flex";
}

async function finalizeSuccessfulLogin() {
    otpModal.style.display = "none";
    triggerToast("Verification Successful", "Access granted!", "success");
    
    const activeUserId = tempUserData.ownerId || tempUserData.userDocId;
    localStorage.setItem("isLoggedIn", "true");
    localStorage.setItem("userUID", tempUserObject.uid);
    localStorage.setItem("userEmail", tempUserObject.email);
    localStorage.setItem("ownerId", activeUserId);
    localStorage.setItem("fullName", tempUserData.fullName || "");
    localStorage.setItem("phone", tempUserData.phone || "");
    localStorage.setItem("address", tempUserData.address || "");
    localStorage.setItem("role", tempUserData.role || "");

    try {
        await fetch('../auth/session_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: activeUserId,
                email: tempUserObject.email,
                role: tempUserData.role
            })
        });
    } catch (err) {
        console.error("Failed to set session handler:", err);
    }

    setTimeout(() => {
        window.location.href = "../pages/dashboard.php";
    }, 1200);
}

if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        await handleLogin();
    });
}