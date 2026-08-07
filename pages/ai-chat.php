<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Smart Vet Care AI</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<head>
    <body>
    <div class="dashboard">

    <?php include("../includes/sidebar.php"); ?>

    <div class="main-content">

        <?php include("../includes/topbar.php"); ?>

   <div class="chat-container">
    <div class="chat-header-area">
        <h2>Smart Vet Care AI</h2>
        <p>Smart VetCare AI — your trusted companion for instant pet care support</p>
    </div>

    <div class="chat-messages" id="chatMessages">
        <!-- Dito magloload ang messages -->
    </div>

    <div class="chat-input-area-wrapper">
    <div class="chat-input-area">
        <input type="text" id="userInput" placeholder="Enter your question about your pet..." autocomplete="off">
        <button id="sendBtn" type="button">
            <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
        </button>
    </div>
    <div class="chat-disclaimer">
        Disclaimer: Smart Vet Care Ai responses are AI-generated and are designed to provide information and guidance. It is important to verify with a veterinarian to ensure proper care for your pet.
    </div>
</div>

<script type="module" src="../assets/js/ai-chat.js"></script>
    </body>
</head>
