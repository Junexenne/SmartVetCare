<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pet Owner Login</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{

    background:#f5f7ff;
    font-family:Poppins,sans-serif;

}

.login-container{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

}

.login-box{

    width:420px;

    background:white;

    padding:40px;

    border-radius:20px;

    box-shadow:0 10px 30px rgba(0,0,0,.15);

}

.login-box h1{

    color:#173F81;

    text-align:center;

    margin-bottom:10px;

}

.login-box p{

    text-align:center;

    color:#777;

    margin-bottom:30px;

}

.form-group{

    margin-bottom:20px;

}

.form-group label{

    display:block;

    margin-bottom:8px;

    font-weight:600;

}

.form-group input{

    width:100%;

    padding:14px;

    border:1px solid #ddd;

    border-radius:10px;

    font-size:15px;

}

.login-btn{

    width:100%;

    border:none;

    cursor:pointer;

}

.links{

    display:flex;

    justify-content:space-between;

    margin-top:20px;

}

.links a{

    text-decoration:none;

    color:#2F248F;

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

<input
type="email"
id="email"
required>

</div>

<div class="form-group">

<label>Password</label>

<input
type="password"
id="password"
required>

</div>

<button
type="submit"
class="login-btn">

Log In

</button>

</form>

<div class="links">

<a href="register.php">

Create Account

</a>

<a href="forgot-password.php">

Forgot Password?

</a>

</div>

</div>

</div>

<script src="../assets/js/toast.js"></script>

<script type="module" src="../assets/js/login.js"></script>

</body>

</html>