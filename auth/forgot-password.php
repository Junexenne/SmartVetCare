<?php
session_start();
$userId = $_SESSION['user_id'] ?? 'OWN-00004';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Forgot Password - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
            width: 100%;
        }
        .login-box {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        .login-box h1 {
            color: #173F81;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .login-box p {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 13px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #f8fafc;
        }
        .form-group input:focus {
            border-color: #173F81;
            background: #ffffff;
        }
        .note-box {
            background: #eef2ff;
            border-left: 3px solid #2563EB;
            padding: 12px;
            font-size: 12px;
            color: #1e293b;
            text-align: left;
            border-radius: 6px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .login-btn {
            width: 100% !important;
            border: none !important;
            cursor: pointer !important;
            padding: 12px !important;
            background: #2F248F !important;
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
            background: #1e1b4b !important;
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

        /* MAGANDANG MODERN TOAST STYLING */
        .toast {
            visibility: hidden;
            position: fixed !important;
            top: 25px !important;
            right: 25px !important;
            z-index: 99999 !important;
            background: #ffffff !important;
            min-width: 300px;
            max-width: 380px;
            padding: 14px 18px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), 0 4px 10px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #10B981 !important;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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
            font-size: 22px;
            flex-shrink: 0;
        }
        .toast.success #toastIcon {
            color: #10B981 !important;
        }
        .toast.error #toastIcon {
            color: #EF4444 !important;
        }
        .toast.success {
            border-left-color: #10B981 !important;
        }
        .toast.error {
            border-left-color: #EF4444 !important;
        }
        .toast-text h4 {
            margin: 0 0 2px 0 !important;
            font-size: 14px !important;
            color: #0f172a !important;
            font-weight: 700 !important;
        }
        .toast-text p {
            margin: 0 !important;
            font-size: 12px !important;
            color: #475569 !important;
            text-align: left !important;
            line-height: 1.4 !important;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div style="text-align: center; margin-bottom: 15px;">
                <img src="../assets/images/logo-login.png" alt="Smart Vet Logo" style="width: 70px; height: 70px; object-fit: contain;">
            </div>
            <h1>Forgot Password</h1>
            <p>Enter your personal email to receive password reset instructions.</p>
            
            <div class="note-box">
                <i class="fa-solid fa-circle-info" style="color: #2563EB; margin-right: 4px;"></i>
                <strong>Note:</strong> Please use your <strong>personal email address</strong> (such as Gmail or Yahoo)
            </div>

            <form id="forgotForm">
                <div class="form-group">
                    <label>Personal Email Address</label>
                    <input type="email" id="email" placeholder="e.g., name@gmail.com" required>
                </div>
                <button type="submit" class="login-btn">
                    Send Reset Link
                </button>
            </form>

            <div class="links">
                <a href="login-user.php">&larr; Back to Login</a>
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

    <script src="../assets/js/toast.js"></script>
    <script type="module" src="../assets/js/forgot-password.js"></script>
</body>
</html>