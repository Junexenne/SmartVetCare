<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Account</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{

    background:#f5f7ff;
    font-family:Poppins,sans-serif;

}

.register-container{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    padding:50px 20px;

}

.register-box{

    width:500px;

    background:#fff;

    border-radius:20px;

    padding:40px;

    box-shadow:0 10px 30px rgba(0,0,0,.12);

}

.register-box h1{

    color:#173F81;

    text-align:center;

    margin-bottom:10px;

}

.register-box p{

    text-align:center;

    color:#666;

    margin-bottom:30px;

}

.form-group{

    margin-bottom:18px;

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

    box-sizing:border-box;

}

.register-btn{

    width:100%;

    padding:15px;

    border:none;

    border-radius:10px;

    background:#2F248F;

    color:#fff;

    font-size:16px;

    cursor:pointer;

    margin-top:10px;

}

.register-btn:hover{

    background:#241b73;

}

.login-link{

    text-align:center;

    margin-top:20px;

}

.login-link a{

    color:#2F248F;

    text-decoration:none;

    font-weight:600;

}

</style>

</head>

<body>

<div class="register-container">

<div class="register-box">

<h1>Create Account</h1>

<p>Join Smart Vet Care today.</p>

<form id="registerForm">

<div class="form-group">

<label>Full Name</label>

<input
type="text"
id="fullName"
required>

</div>

<div class="form-group">

<label>Email Address</label>

<input
type="email"
id="email"
required>

</div>

<div class="form-group">

<label>Phone Number</label>

<input
type="text"
id="phone"
required>

</div>

<div class="form-group">

<label>Password</label>

<input
type="password"
id="password"
required>

</div>

<div class="form-group">

<label>Confirm Password</label>

<input
type="password"
id="confirmPassword"
required>

</div>

<button
type="submit"
class="register-btn">

Create Account

</button>

</form>

<div class="login-link">

Already have an account?

<a href="login-user.php">

Login

</a>

</div>

</div>

</div>
<script src="../assets/js/toast.js"></script>

<script type="module" src="../assets/js/register.js"></script>
<script type="module" src="../assets/js/register.js"></script>

</body>

</html>