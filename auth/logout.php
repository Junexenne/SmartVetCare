<?php
session_start();
session_unset();
session_destroy();
header("Location: /SmartVetCare/auth/login-user.php");
exit();
?>