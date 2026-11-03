<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Owner Login</title>
    <!-- FontAwesome para sa Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/images/logo-transparent.png">
    <style>
        * {
            box-sizing: border-box;
        }
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
        }
        .login-box {
            width: 100%;
            max-width: 370px;
            background: white;
            padding: 20px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        .login-box h1 {
            color: #173F81;
            font-size: 26px;
            margin-top: 0;
            margin-bottom: 8px;
            text-align: center;
        }
        .login-box p {
            color: #777;
            font-size: 13px;
            margin-bottom: 25px;
            text-align: center;
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
        .toggle-password:hover {
            color: #173F81;
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
        .login-btn:hover {
            background: #0f2b5c;
        }
        .google-btn {
            width: 100% !important;
            border: 1px solid #ddd !important;
            cursor: pointer !important;
            padding: 12px !important;
            background: #ffffff !important;
            color: #333 !important;
            font-size: 15px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: background 0.2s !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            margin: 12px auto 0 auto !important;
        }
        .google-btn:hover {
            background: #f8f9fa !important;
            border-color: #ccc !important;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 18px 0;
            color: #aaa;
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #eee;
        }
        .divider::before {
            margin-right: .75em;
        }
        .divider::after {
            margin-left: .75em;
        }
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
        .links a:hover {
            text-decoration: underline;
        }
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
        .register-info strong {
            color: #2F248F;
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 12px;
            }
            .login-box {
                padding: 25px 20px;
                border-radius: 12px;
            }
            .login-box h1 {
                font-size: 22px;
            }
        }
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
            <!-- Clinic Logo -->
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
                <button type="submit" class="login-btn">
                    Log In
                </button>
            </form>

            <div class="divider">OR</div>

             <!-- Google Login Button -->
            <button type="button" id="googleLoginBtn" class="google-btn" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px 16px; border: 1px solid #dadce0; border-radius: 10px; background: #fff; cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 500; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: background 0.2s;">
                <!-- Google Official Multi-color Icon -->
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.13 0-5.78-2.11-6.73-4.96H1.19v3.14C3.18 21.31 7.23 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.27 14.24c-.25-.72-.38-1.49-.38-2.24s.13-1.52.38-2.24V6.62H1.19C.43 8.15 0 9.89 0 12s.43 3.85 1.19 5.38l4.08-3.14z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.23 0 3.18 2.69 1.19 6.62l4.08 3.14c.95-2.85 3.6-4.96 6.73-4.96z"/>
                </svg>
                <span>Sign in with <span style="font-weight: 600;"><span style="color: #4285F4;">G</span><span style="color: #DB4437;">o</span><span style="color: #F4B400;">o</span><span style="color: #4285F4;">g</span><span style="color: #0F9D58;">l</span><span style="color: #EA4335;">e</span></span></span>
            </button>

            <div class="links">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
            <div class="register-info">
                <p>
                    <strong>Don't have an account?</strong><br>
                    You can register your own account <a href="register.php" style="color: #173F81; font-weight: 600; text-decoration: underline;">here</a> or visit Furry Friends Animal Clinic.
                </p>
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
    <!-- Script para sa pag-toggle ng password visibility -->
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
    <!-- Toast at Login Scripts -->
    <script src="../assets/js/toast.js"></script>
    <script type="module" src="../assets/js/login.js?v=totp20261021"></script>
</body>
</html>