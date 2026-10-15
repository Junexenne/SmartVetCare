<?php
// Dynamic base URL configuration para gumagana sa parehong local at live hosting
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host . "/"; 
define('BASE_URL', $base_url);
?>