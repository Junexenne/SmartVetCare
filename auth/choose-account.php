<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Choose Account</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{

    background:#f5f7ff;
    font-family:Poppins,sans-serif;

}

.choose-container{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

}

.choose-box{

    width:900px;

    background:white;

    border-radius:20px;

    padding:50px;

    box-shadow:0 10px 35px rgba(0,0,0,.12);

}

.choose-title{

    text-align:center;

    color:#173F81;

    margin-bottom:40px;

}

.choose-wrapper{

    display:flex;

    gap:30px;

}

.account-card{

    flex:1;

    text-align:center;

    padding:40px 30px;

    border:2px solid #E5E5E5;

    border-radius:18px;

    transition:.3s;

}

.account-card:hover{

    border-color:#173F81;

    transform:translateY(-8px);

}

.account-icon{

    font-size:70px;

    margin-bottom:20px;

}

.account-card h2{

    color:#173F81;

}

.account-card p{

    color:#777;

    margin:20px 0;

}

.account-card a{

    display:inline-block;

    padding:14px 30px;

    background:#173F81;

    color:white;

    text-decoration:none;

    border-radius:10px;

}

.account-card a:hover{

    background:#102d61;

}

</style>

</head>

<body>

<div class="choose-container">

<div class="choose-box">

<h1 class="choose-title">

Choose Your Account

</h1>

<div class="choose-wrapper">

<div class="account-card">

<div class="account-icon">

🐶

</div>

<h2>Pet Owner</h2>

<p>

Book appointments, manage pets, receive reminders and access medical records.

</p>

<a href="login-user.php">

Continue

</a>

</div>

<div class="account-card">

<div class="account-icon">

👨‍⚕️

</div>

<h2>Clinic Staff</h2>

<p>

Manage appointments, pet records, vaccinations and clinic operations.

</p>

<a href="login-admin.php">

Continue

</a>

</div>

</div>

</div>

</div>

</body>

</html>