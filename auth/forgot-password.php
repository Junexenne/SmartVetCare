<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Forgot Password</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{

    background:#f5f7ff;
    font-family:Poppins,sans-serif;

}

.forgot-container{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

}

.forgot-box{

    width:420px;

    background:#fff;

    padding:40px;

    border-radius:20px;

    box-shadow:0 10px 30px rgba(0,0,0,.15);

}

.forgot-box h1{

    text-align:center;

    color:#173F81;

}

.forgot-box p{

    text-align:center;

    color:#777;

    margin-bottom:25px;

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

    box-sizing:border-box;

}

.reset-btn{

    width:100%;

    padding:15px;

    border:none;

    border-radius:10px;

    background:#173F81;

    color:#fff;

    cursor:pointer;

    font-size:16px;

}

.back-login{

    text-align:center;

    margin-top:20px;

}

.back-login a{

    color:#173F81;

    text-decoration:none;

}

</style>

</head>

<body>

<div class="forgot-container">

<div class="forgot-box">

<h1>Forgot Password</h1>

<p>

Enter your email address and we'll send you a password reset link.

</p>

<form id="forgotForm">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
id="email"
required>

</div>

<button
type="submit"
class="reset-btn">

Send Reset Link

</button>

</form>

<div class="back-login">

<a href="login-user.php">

← Back to Login

</a>

</div>

</div>

</div>

<script src="../assets/js/toast.js"></script>

<script type="module" src="../assets/js/forgot-password.js"></script>

</body>

</html>