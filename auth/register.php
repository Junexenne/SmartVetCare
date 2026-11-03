<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Smart Vet Care</title>
    <!-- FontAwesome para sa Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/images/logo-transparent.png">
    <style>
        * { box-sizing: border-box; }
        body {
            background: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.65)), url('../assets/images/clinic-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            padding: 20px 0;
        }
        .login-container { 
            min-height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 16px; 
            width: 100%; 
        }
        .login-box { 
            width: 100%; 
            max-width: 370px; 
            background: white; 
            padding: 10px 24px; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); 
            text-align: center; 
        }
        .login-box h1 { 
            color: #173F81; 
            font-size: 22px; 
            margin-top: 0; 
            margin-bottom: 4px; 
        }
        .login-box p { 
            color: #777; 
            font-size: 12px; 
            margin-bottom: 16px; 
        }
        .form-group { 
            margin-bottom: 12px; 
            text-align: left; 
        }
        .form-group label { 
            display: block; 
            margin-bottom: 4px; 
            font-weight: 600; 
            font-size: 12.5px; 
            color: #333; 
        }
        .form-group input, .form-group select { 
            width: 100%; 
            padding: 9px 12px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            font-size: 13.5px; 
            outline: none; 
            font-family: 'Poppins', sans-serif; 
        }
        .form-group input:focus, .form-group select:focus { 
            border-color: #173F81; 
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .password-wrapper input {
            padding-right: 38px;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 15px;
        }
        .toggle-password:hover {
            color: #173F81;
        }
        .login-btn { 
            width: 100%; 
            border: none; 
            cursor: pointer; 
            padding: 11px; 
            background: #173F81; 
            color: white; 
            font-size: 14.5px; 
            border-radius: 8px; 
            font-weight: 600; 
            margin-top: 8px; 
            transition: background 0.2s; 
        }
        .login-btn:hover { 
            background: #0f2b5c; 
        }
        .links { 
            margin-top: 14px; 
            text-align: center; 
        }
        .links a { 
            text-decoration: none; 
            color: #2F248F; 
            font-size: 12.5px; 
            font-weight: 500; 
        }
        .links a:hover { 
            text-decoration: underline; 
        }

        /* Toast Notification UI Styles (Top-Right) */
        .toast {
            visibility: hidden;
            position: fixed !important;
            top: 30px !important;
            right: 30px !important;
            bottom: auto !important;
            left: auto !important;
            z-index: 99999 !important;
            background: #ffffff;
            min-width: 280px;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-left: 5px solid #173F81;
            opacity: 0;
            transition: all 0.3s ease-in-out;
            transform: translateY(-20px);
        }
        .toast.show {
            visibility: visible !important;
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        .toast-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        #toastIcon {
            font-size: 20px;
        }
        .toast.success #toastIcon {
            color: #10B981;
        }
        .toast.error #toastIcon {
            color: #EF4444;
        }
        .toast.success {
            border-left-color: #10B981;
        }
        .toast.error {
            border-left-color: #EF4444;
        }
        .toast-text h4 {
            margin: 0 0 3px 0;
            font-size: 14px;
            color: #173F81;
            font-weight: 700;
        }
        .toast-text p {
            margin: 0;
            font-size: 12.5px;
            color: #666;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div style="text-align: center; margin-bottom: 12px;">
                <img src="../assets/images/logo-login.png" alt="Smart Vet Logo" style="width: 70px; height: 70px; object-fit: contain;">
            </div>
            <h1>Create Account</h1>
            <p>Register a new Smart Vet Care account with your complete details.</p>
            <form id="registerForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="fullName" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="regEmail" required placeholder="Enter your email address">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="regPassword" required placeholder="At least 6 characters">
                        <i class="fa-solid fa-eye toggle-password" id="toggleRegPassword"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Phone / Contact Number</label>
                    <input type="text" id="phone" required placeholder="e.g., 09987654321">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" id="address" required placeholder="e.g., Caloocan City">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select id="gender" required>
                        <option value="" disabled selected>Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <button type="submit" id="submitBtn" class="login-btn">Register Account</button>
            </form>
            <div class="links">
                <a href="login-user.php">Already have an account? Log In</a>
            </div>
        </div>
    </div>

    <!-- Toast Notification Element -->
    <div id="toast" class="toast">
        <div class="toast-content">
            <i id="toastIcon" class="fa-solid fa-circle-check"></i>
            <div class="toast-text">
                <h4 id="toastTitle">Title</h4>
                <p id="toastMessage">Message goes here</p>
            </div>
        </div>
    </div>

    <!-- Toast Script -->
    <script src="../assets/js/toast.js"></script>

    <!-- Toggle Password Visibility -->
    <script>
        const toggleRegPassword = document.getElementById("toggleRegPassword");
        const regPasswordInput = document.getElementById("regPassword");
        if (toggleRegPassword && regPasswordInput) {
            toggleRegPassword.addEventListener("click", function () {
                const type = regPasswordInput.getAttribute("type") === "password" ? "text" : "password";
                regPasswordInput.setAttribute("type", type);
                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            });
        }

        function showLocalToast(title, message, type = 'error') {
            if (typeof window.showToast === 'function') {
                window.showToast(title, message, type);
            } else {
                alert(`${title}: ${message}`);
            }
        }
    </script>

    <!-- Firebase Registration Module -->
    <script type="module">
        import { auth, db } from "../assets/js/firebase-config.js";
        import { createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";
        import { doc, setDoc, collection, getDocs } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

        const registerForm = document.getElementById("registerForm");
        const submitBtn = document.getElementById("submitBtn");

        if (registerForm) {
            registerForm.addEventListener("submit", async (e) => {
                e.preventDefault();

                const fullName = document.getElementById("fullName").value.trim();
                const email = document.getElementById("regEmail").value.trim();
                const password = document.getElementById("regPassword").value;
                const phone = document.getElementById("phone").value.trim();
                const address = document.getElementById("address").value.trim();
                const gender = document.getElementById("gender").value;

                if (!fullName || !email || !password || !phone || !address || !gender) {
                    showLocalToast("Registration Error", "Please fill in all fields.", "error");
                    return;
                }

                try {
                    submitBtn.disabled = true;
                    submitBtn.textContent = "Registering...";

                    // 1. Gumawa ng user sa Firebase Authentication
                    const userCredential = await createUserWithEmailAndPassword(auth, email, password);
                    const user = userCredential.user;

                    // 2. Kumuha ng bilang ng mga kasalukuyang users para sa sunod-sunod na ownerId (OWN-XXXXX / OWNER-XXXXX)
                    const usersSnap = await getDocs(collection(db, "users"));
                    const count = usersSnap.size + 1;
                    const newOwnerId = "OWN-" + String(count).padStart(5, '0');

                    // 3. Piliin ang angkop na default profile image batay sa gender
                    let profileImage = "../assets/images/male-owner.png";
                    if (gender === "Female") {
                        profileImage = "../assets/images/female-owner.png";
                    }

                    // 4. I-save ang kumpletong record sa Firestore 'users' collection gamit ang UID ng narehistrong user
                    await setDoc(doc(db, "users", user.uid), {
                        authUid: user.uid,
                        fullName: fullName,
                        email: email,
                        phone: phone,
                        contactNumber: phone,
                        address: address,
                        gender: gender,
                        ownerId: newOwnerId,
                        role: "Pet Owner",
                        status: "active",
                        profileImage: profileImage,
                        createdAt: new Date(),
                        updatedAt: new Date()
                    });

                    // 5. Ipakita ang Success Toast Notification
                    showLocalToast("Success", "Account registered successfully! Redirecting to login...", "success");

                    // 6. Mag-redirect sa login page pagkatapos ng 2 segundo
                    setTimeout(() => {
                        window.location.href = "login-user.php";
                    }, 2000);

                } catch (err) {
                    console.error("Registration error:", err);
                    let errMsg = err.message;
                    if (err.code === "auth/email-already-in-use") {
                        errMsg = "The email address is already in use by another account.";
                    } else if (err.code === "auth/weak-password") {
                        errMsg = "The password must be at least 6 characters long.";
                    }
                    showLocalToast("Registration Failed", errMsg, "error");
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = "Register Account";
                }
            });
        }
    </script>
</body>
</html>