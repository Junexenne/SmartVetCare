<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Vet Care AI</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="dashboard">

    <?php include("../includes/sidebar.php"); ?>

    <div class="main-content" style="background: #f4f7fe; min-height: 100vh; padding: 20px; display: flex; flex-direction: column;">

        <?php include("../includes/topbar.php"); ?>

        <!-- Chat Main Container -->
        <div class="chat-container">
            
            <div class="chat-header-area">
                <h2>
                    <i class="fa-solid fa-robot" style="color: #173F81; background: #eef4ff; padding: 10px; border-radius: 12px;"></i>
                    Smart Vet Care AI
                </h2>
                <p>Your trusted companion for instant pet care support and guidance.</p>
            </div>

            <!-- Messages Area -->
            <div class="chat-messages" id="chatMessages">
                <!-- Dito magloload ang messages -->
            </div>

            <!-- Input & Disclaimer Area -->
            <div class="chat-input-area-wrapper">
                <div class="chat-input-area">
                    <input type="text" id="userInput" placeholder="Ask anything about your pet's health, food, or symptoms..." autocomplete="off">
                    <button id="sendBtn" type="button">
                        <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
                    </button>
                </div>
                <div class="chat-disclaimer">
                    <i class="fa-solid fa-triangle-exclamation"></i> Disclaimer: Smart Vet Care AI responses are AI-generated for guidance only. Always consult a licensed veterinarian for proper diagnosis.
                </div>
            </div>

        </div>

    </div>

</div>

<script type="module" src="../assets/js/ai-chat.js"></script>

</body>
</html>