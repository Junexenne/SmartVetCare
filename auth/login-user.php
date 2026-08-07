<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Owner Login</title>
    <!-- FontAwesome para sa Icon ng Mata -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
    body {
        background: #f5f7ff;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px; /* Para sa responsive spacing sa mobile */
    }

    .login-box {
        width: 100%;
        max-width: 360px; /* Mas maliit at sakto lang sa screen */
        background: white;
        padding: 30px 25px; /* In-adjust ang padding para pantay */
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        text-align: center; /* Ipipilit nitong ipag-gitna ang lahat ng pwedeng i-align */
        box-sizing: border-box;
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
        text-align: left; /* Naka-align sa kaliwa ang label para malinis tingnan */
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
        padding: 12px 40px 12px 14px; /* May padding sa kanan para di matakpan ng mata ang password */
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
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

    .login-btn {
        width: 100%;
        border: none;
        cursor: pointer;
        padding: 12px;
        background: #173F81;
        color: white;
        font-size: 15px;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.2s;
        display: block;
        margin: 10px auto 0 auto; /* Ginagarantiyang nasa gitna ang button */
    }

    .login-btn:hover {
        background: #0f2b5c;
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
</style>

</head>

<body>

    <div class="login-container">
        <div class="login-box">

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

                <button type="submit" class="login-btn">
                    Log In
                </button>
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

    <!-- Script para sa pag-toggle ng password visibility -->
    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");

        togglePassword.addEventListener("click", function () {
            // Palitan ang type ng input mula password patungong text at vice versa
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            
            // Palitan ang icon ng mata (bukas o nakapikit)
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>

    <script src="../assets/js/toast.js"></script>
    <script type="module" src="../assets/js/login.js"></script>

</body>

</html>