<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Vet Care</title>
    
    <?php
    // Awtomatikong tukuyin kung ang file ay nasa loob ng 'pages/' folder o nasa root
    $isInPages = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false);
    $assetPath = $isInPages ? "../assets/" : "assets/";
    ?>

    <!-- Favicon Logo for Browser Tab -->
    <link rel="icon" type="image/png" href="<?= $assetPath ?>images/logo-transparent.png">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?= $assetPath ?>css/style.css">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
