<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Owner Login - Smart Vet Care</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
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
        max-width: 400px; /* Baguhin ito mula 500px patungong 400px o 380px para mas pumuputol/sumikip ang lapad */
        background: white;
        padding: 20px 25px; /* Pwede ring bawasan ang panloob na espasyo kung gusto mo */
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
}
        .login-box h1 {
            color: #173F81;
            font-size: 26px;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .login-box p {
            color: #777;
            font-size: 13px;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .form-group input {
            width: 100%;
            padding: 12px 40px 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }
        .form-group input:focus {
            border-color: #173F81;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
            font-size: 16px;
        }
        .remember-me-group {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            text-align: left;
            font-size: 13px;
        }
        .remember-me-group input {
            margin-right: 8px;
            cursor: pointer;
        }
        .remember-me-group label {
            color: #555;
            cursor: pointer;
            user-select: none;
            margin-bottom: 0;
            font-weight: normal;
        }
        .login-btn {
            width: 100% !important;
            border: none !important;
            cursor: pointer !important;
            padding: 12px !important;
            background: #173F81 !important;
            color: white !important;
            font-size: 15px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: background 0.2s !important;
            display: flex !important;            
            align-items: center !important;    
            justify-content: center !important; 
            text-align: center !important;
            margin: 10px auto 0 auto !important;
        }
        .login-btn:hover { background: #0f2b5c; }
        .links {
            margin-top: 15px;
            text-align: center;
        }
        .links a {
            text-decoration: none;
            color: #2F248F;
            font-size: 13px;
            font-weight: 500;
        }
        .links a:hover { text-decoration: underline; }
        .register-info {
            margin-top: 20px;
            text-align: center;
            background: #f8f9ff;
            border: 1px solid #dbe4ff;
            border-radius: 10px;
            padding: 12px;
        }
        .register-info p {
            margin: 0;
            color: #555;
            font-size: 12px;
            line-height: 1.5;
        }
        .register-info strong { color: #2F248F; }
        .toast {
            visibility: hidden;
            position: fixed !important;
            top: 30px !important;
            right: 30px !important;
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
        #toastIcon { font-size: 20px; }
        .toast.success #toastIcon { color: #10B981; }
        .toast.error #toastIcon { color: #EF4444; }
        .toast.success { border-left-color: #10B981; }
        .toast.error { border-left-color: #EF4444; }
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
            <div style="text-align: center; margin-bottom: 15px;">
                <img src="../assets/images/logo-login.png" alt="Smart Vet Logo" style="width: 80px; height: 80px; object-fit: contain;">
            </div>
            <h1>Welcome Back</h1>
            <p>Login to your Smart Vet Care account.</p>
            <form id="loginForm">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" required>
                        <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                </div>
                <div class="remember-me-group">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember me</label>
                </div>
                <button type="submit" class="login-btn">Log In</button>
            </form>
            <div class="links">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
            <div class="register-info">
                <p>
                    <strong>Don't have an account?</strong><br>
                    Please visit Furry Friends Animal Clinic.<br>
                    Our clinic staff will create your account for you.
                </p>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">
        <div class="toast-content">
            <i id="toastIcon" class="fa-solid fa-circle-check"></i>
            <div class="toast-text">
                <h4 id="toastTitle">Title</h4>
                <p id="toastMessage">Message goes here</p>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener("click", function () {
                const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                passwordInput.setAttribute("type", type);
                this.classList.toggle("fa-eye");
                this.classList.toggle("fa-eye-slash");
            });
        }
    </script>
    <script src="../assets/js/toast.js"></script>
    <script type="module" src="../assets/js/login.js"></script>
</body>
</html>