<?php
// PHP session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Smart Vet Care AI</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            height: -webkit-fill-available;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f8faff;
        }
        .dashboard {
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: row;
            box-sizing: border-box;
            overflow: hidden;
            width: 100vw;
        }
        .sidebar {
            width: 270px;
            height: 100%;
            flex-shrink: 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1100 !important;
        }
        .main-content {
            flex: 1;
            background: #f8faff;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
            min-width: 0;
            padding: 10px 20px 20px 20px !important;
            gap: 6px !important;
        }
        .topbar, header, .top-navbar {
            margin-bottom: 5px !important;
            flex-shrink: 0;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
            box-sizing: border-box;
            background: #ffffff;
            margin: 0 !important;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
            padding: 12px 18px;
            gap: 8px;
        }
        .chat-ai-header {
            flex-shrink: 0;
            background: linear-gradient(135deg, #163B7A 0%, #2563EB 50%, #38BDF8 100%);
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
            position: relative;
            z-index: 10;
        }
        .chat-ai-header-content h2 {
            font-size: 14.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 2px 0;
            color: #ffffff;
        }
        .chat-ai-header-content p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto; 
            word-break: break-word;
            overflow-wrap: break-word;
            box-sizing: border-box;
            min-height: 0;
            padding: 10px 6px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #fdfcff;
            -webkit-overflow-scrolling: touch;
        }
        .message-wrapper {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        .message-wrapper.user {
            align-items: flex-end !important;
        }
        .message-wrapper.ai {
            align-items: flex-start !important;
        }
        .message {
            padding: 12px 16px !important;
            border-radius: 18px !important;
            font-size: 13px !important;
            line-height: 1.5 !important;
            max-width: 80% !important;
            width: fit-content !important;
            box-sizing: border-box !important;
            display: inline-block !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04) !important;
            word-break: break-word !important;
        }
        .message-wrapper.user .message {
            background: linear-gradient(135deg, #2563EB, #1d4ed8) !important;
            color: #ffffff !important;
            border-bottom-right-radius: 4px !important;
        }
        .message-wrapper.ai .message {
            background: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid #e2e8f0 !important;
            border-bottom-left-radius: 4px !important;
        }
        .chat-suggestions-wrapper {
            flex-shrink: 0;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            gap: 8px;
            padding: 6px 2px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .chat-suggestions-wrapper::-webkit-scrollbar {
            display: none;
        }
        .suggestion-chip {
            background: #f1f5f9;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .suggestion-chip:hover {
            background: #e2e8f0;
            color: #2563EB;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .suggestion-chip i {
            color: #2563EB;
            font-size: 12px;
        }
        .chat-input-area-wrapper {
            flex-shrink: 0; 
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: transparent;
            border: none;
            padding: 4px 0 0 0;
        }
        .chat-input-area {
            display: flex;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            gap: 8px;
            background: transparent;
            border: none;
            padding: 0;
        }
        .chat-input-area input {
            flex: 1;
            min-width: 0;
            box-sizing: border-box;
            padding: 10px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 28px;
            font-size: 13px;
            outline: none;
            background: #f8fafc;
            color: #1e293b;
            transition: all 0.3s ease;
        }
        .chat-input-area input:focus {
            border-color: #2563EB;
            background: #ffffff;
        }
        .chat-input-area button {
            background: linear-gradient(135deg, #163B7A 0%, #2563EB 100%);
            color: #ffffff;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }
        .chat-input-area button:hover {
            opacity: 0.9;
        }
        .chat-disclaimer {
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-bottom: 0;
            text-align: center;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1050;
        }
        .sidebar-overlay.active {
            display: block !important;
        }
      @media (max-width: 768px) {
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1100 !important;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
                background: var(--sidebar-bg, #173F81) !important; 
            }
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
            }
            .main-content {
                padding: 0 !important;
                width: 100vw !important;
            }
            .chat-container {
                margin: 0 !important;
                width: 100% !important;
                border-radius: 0 !important;
                border: none !important;
                height: 100% !important;
                padding: 8px 12px calc(75px + env(safe-area-inset-bottom, 30px)) 12px !important;
            }
            .message {
                max-width: 85% !important;
            }
        }
        @keyframes blink {
            0% { opacity: .2; }
            20% { opacity: 1; }
            100% { opacity: .2; }
        }
    </style>
</head>
<body>
<?php if (file_exists("pages-header.php")) { include("pages-header.php"); } ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("../includes/topbar.php"); ?>
        <div class="chat-container">
            <div class="chat-ai-header">
                <div class="chat-ai-header-content">
                    <h2>
                        <i class="fa-solid fa-sparkles"></i>
                        Smart Vet Care AI
                    </h2>
                    <p><span style="color: #34d399;">●</span> Online | Your trusted AI companion for instant pet care support.</p>
                </div>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="message-wrapper ai">
                    <div class="message">
                        <i class="fa-solid fa-sparkles" style="color: #2563EB; margin-right: 6px;"></i> Hello, Smart Vet Fam! How can I help you today with your pets or booking an appointment?
                    </div>
                </div>
            </div>
            <!-- Quick Suggestion Pills -->
            <div class="chat-suggestions-wrapper">
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What are the common symptoms of vomiting in dogs?')">
                    <i class="fa-solid fa-dog"></i> Dog Vomiting Symptoms
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('How do I book a vet appointment online?')">
                    <i class="fa-solid fa-calendar-check"></i> Book an Appointment
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What are your clinic operating hours?')">
                    <i class="fa-solid fa-clock"></i> Clinic Hours & Schedule
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What is the best diet for a lethargic cat?')">
                    <i class="fa-solid fa-cat"></i> Cat Diet & Nutrition
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('When should I bring my pet for emergency care?')">
                    <i class="fa-solid fa-triangle-exclamation"></i> Emergency Signs
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What vaccines does my puppy need?')">
                    <i class="fa-solid fa-syringe"></i> Puppy Vaccination
                </button>
            </div>
            <div class="chat-input-area-wrapper">
                <div class="chat-input-area">
                    <input type="text" id="userInput" placeholder="Ask anything about pet care..." autocomplete="off">
                    <button id="sendBtn" type="button">
                        <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
                    </button>
                </div>
                <div class="chat-disclaimer">
                    <i class="fa-solid fa-triangle-exclamation"></i> Disclaimer: AI-generated guidance only. Consult a licensed vet for medical diagnosis.
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/bottom-nav.php"); ?>
<!-- Integrated Script para sa AI at Firestore Database -->
<script type="module" src="../assets/js/ai-chat.js"></script>

<!-- Dedicated Mobile Sidebar Direct Fix Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuBtn = document.getElementById("mobileMenuBtn");
        const sidebar = document.querySelector(".sidebar");
        const overlay = document.getElementById("sidebarOverlay");

        if (menuBtn && sidebar) {
            menuBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    sidebar.style.left = "0px";
                    if (overlay) overlay.style.display = "block";
                } else {
                    sidebar.style.left = "-270px";
                    if (overlay) overlay.style.display = "none";
                }
            });
        }

        if (overlay && sidebar) {
            overlay.addEventListener("click", function() {
                sidebar.classList.remove("active");
                sidebar.style.left = "-270px";
                overlay.style.display = "none";
            });
        }
    });
</script>
</body>
</html>