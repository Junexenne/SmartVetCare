<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | Smart Vet Care</title>

    <link rel="stylesheet" href="../assets/css/register.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="register-container">

    <div class="register-card">

        <img src="../assets/images/logo.png" class="logo">

        <h1>Create Account</h1>

        <p>Join Smart Vet Care today.</p>

        <form id="registerForm">

            <div class="row">

                <input
                    type="text"
                    id="firstName"
                    placeholder="First Name"
                    required>

                <input
                    type="text"
                    id="lastName"
                    placeholder="Last Name"
                    required>

            </div>

            <input
                type="email"
                id="email"
                placeholder="Email Address"
                required>

            <input
                type="text"
                id="phone"
                placeholder="Phone Number"
                required>

            <input
                type="text"
                id="address"
                placeholder="Address"
                required>

            <input
                type="password"
                id="password"
                placeholder="Password"
                required>

            <input
                type="password"
                id="confirmPassword"
                placeholder="Confirm Password"
                required>

            <button type="submit">

                Create Account

            </button>

        </form>

        <div class="login-link">

    Already have an account?

    <a href="../auth/log-user.php">

        Login

    </a>

</div>

</div>

<script type="module" src="../assets/js/register.js"></script>

</body>

</html>