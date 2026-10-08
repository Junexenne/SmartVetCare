import { auth, db } from "./firebase-config.js";
import {
    signInWithEmailAndPassword,
    setPersistence,
    browserLocalPersistence,
    browserSessionPersistence,
    signOut
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
            <h3 style="color: #1e1b4b; margin-bottom: 8px; font-size: 18px; font-weight: 700;">Two-Factor Authentication</h3>
            <p id="otpSubtitle" style="color: #64748b; font-size: 13px; margin-bottom: 20px; line-height: 1.4;">Select verification method (Testing Mode).</p>
            
            <div id="otpSelectMethod" style="margin-bottom: 20px; display: flex; gap: 10px;">
                <button type="button" id="sendEmailOtpBtn" style="flex: 1; padding: 10px; background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px;"><i class="fa-solid fa-envelope"></i> Via Email</button>
                <button type="button" id="sendPhoneOtpBtn" style="flex: 1; padding: 10px; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px;"><i class="fa-solid fa-phone"></i> Via SMS</button>
            </div>
            <div id="otpInputContainer" style="display: none; margin-bottom: 20px;">
                <div id="givenOtpDisplay" style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 8px; border-radius: 6px; font-size: 12px; color: #475569; margin-bottom: 12px;"></div>
                <input type="text" id="otpCodeInput" maxlength="6" placeholder="Enter 6-digit code" style="width: 100%; padding: 12px; text-align: center; font-size: 20px; letter-spacing: 4px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; margin-bottom: 10px;">
                <button type="button" id="verifyOtpBtn" style="width: 100%; padding: 12px; background: #5142f5; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Verify Code</button>
            </div>
            <button type="button" id="closeOtpModalBtn" style="background: none; border: none; color: #94a3b8; font-size: 12px; cursor: pointer; text-decoration: underline;">Cancel / Back to Login</button>
        </div>
    `;
    document.body.appendChild(otpModal);
}

let generatedOtp = "123456"; 
let tempUserData = null;
let tempUserObject = null;

document.addEventListener("DOMContentLoaded", () => {
    const savedEmail = localStorage.getItem("savedUserEmail");
    if (savedEmail) {
        emailInput.value = savedEmail;
        rememberMeCheckbox.checked = true;
    }
    document.getElementById("closeOtpModalBtn").addEventListener("click", () => {
        otpModal.style.display = "none";
        if (auth.currentUser) signOut(auth);
    });
    document.getElementById("sendEmailOtpBtn").addEventListener("click", () => {
        showGivenOtpUI("Email");
    });
    document.getElementById("sendPhoneOtpBtn").addEventListener("click", () => {
        showGivenOtpUI("SMS / Phone");
    });
    document.getElementById("verifyOtpBtn").addEventListener("click", () => {
        verifyOtpProcess();
    });
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
    const rememberMe = rememberMeCheckbox.checked;
    if (!email || !password) {
        triggerToast("Validation Error", "Please fill in all fields.", "error");
        return;
    }
    try {
        const persistenceType = rememberMe ? browserLocalPersistence : browserSessionPersistence;
        await setPersistence(auth, persistenceType);
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;
        const q = query(
            collection(db, "users"),
            where("email", "==", user.email)
        );
        const snapshot = await getDocs(q);
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
        
        document.getElementById("otpSubtitle").innerHTML = `Choose verification method for <b>${userData.fullName}</b>:`;
        document.getElementById("otpSelectMethod").style.display = "flex";
        document.getElementById("otpInputContainer").style.display = "none";
        otpModal.style.display = "flex";
    } catch (error) {
        console.error(error);
        let errorMessage = error.message;
        if (error.code === 'auth/invalid-credential' || error.code === 'auth/wrong-password' || error.code === 'auth/user-not-found') {
            errorMessage = "Invalid email or password.";
        }
        triggerToast("Login Failed", errorMessage, "error");
    }
}

function showGivenOtpUI(methodName) {
    document.getElementById("otpSelectMethod").style.display = "none";
    document.getElementById("otpInputContainer").style.display = "block";
    document.getElementById("givenOtpDisplay").innerHTML = `<b>[Given OTP Test Mode]</b><br>Your ${methodName} code is: <span style="color: #5142f5; font-weight: bold; font-size: 14px;">${generatedOtp}</span>`;
}

async function verifyOtpProcess() {
    const enteredOtp = document.getElementById("otpCodeInput").value.trim();
    if (!enteredOtp || enteredOtp.length !== 6) {
        triggerToast("Invalid Code", "Please enter the 6-digit OTP code.", "error");
        return;
    }
    if (enteredOtp === generatedOtp) {
        otpModal.style.display = "none";
        triggerToast("Verification Successful", "Access granted!", "success");
        
        const activeUserId = tempUserData.ownerId || tempUserData.userDocId;

        localStorage.setItem("isLoggedIn", "true");
        localStorage.setItem("userUID", tempUserObject.uid);
        localStorage.setItem("userEmail", tempUserObject.email);
        localStorage.setItem("ownerId", activeUserId);
        localStorage.setItem("fullName", tempUserData.fullName);
        localStorage.setItem("phone", tempUserData.phone);
        localStorage.setItem("address", tempUserData.address);
        localStorage.setItem("role", tempUserData.role);

        // Ipasa ang ID sa session_handler.php para mag-set ang PHP Session
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
    } else {
        triggerToast("Verification Failed", `Incorrect code. (Hint: Use ${generatedOtp})`, "error");
    }
}

if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        await handleLogin();
    });
}