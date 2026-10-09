<?php
// PHP session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Smart Vet Care AI</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
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
        }
        /* Desktop Main Content Style */
        .main-content {
            flex: 1;
            background: #f8fafc;
            height: 100vh;
            height: 100dvh;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            overflow: hidden;
            min-width: 0;
        }
        
        .chat-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0; 
            box-sizing: border-box;
            overflow: hidden;
            background: #ffffff;
            border-radius: 16px;
            padding: 10px 18px 8px 18px; /* Binabaan ang bottom padding para sumagad pababa */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            gap: 8px;
            margin-top: 2px;
            margin-bottom: 2px;
        }

        /* Gradient AI Header */
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
            padding: 4px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            -webkit-overflow-scrolling: touch;
        }
        .chat-messages .message, 
        .chat-messages .chat-bubble,
        .chat-messages div[class*="bubble"],
        .chat-messages div[class*="msg"] {
            max-width: 85%;
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 13px;
            line-height: 1.4;
        }

        /* Suggestion Pills Styling */
        .chat-suggestions-wrapper {
            flex-shrink: 0;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            gap: 6px;
            padding: 2px 0;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .chat-suggestions-wrapper::-webkit-scrollbar {
            height: 4px;
        }
        .chat-suggestions-wrapper::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }
        .suggestion-chip {
            background: #f0f4ff;
            color: #173F81;
            border: 1px solid #d2e3fc;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .suggestion-chip:hover {
            background: #e2ecff;
            border-color: #bad2fc;
            transform: translateY(-1px);
        }
        .suggestion-chip i {
            color: #2563EB;
            font-size: 11px;
        }

        .chat-input-area-wrapper {
            flex-shrink: 0; 
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            padding-top: 6px;
        }
        .chat-input-area {
            display: flex;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            gap: 8px;
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
            border-radius: 50%;
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
            font-size: 8.5px;
            color: #94a3b8;
            line-height: 1.1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-bottom: 0;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1001;
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
                padding: 6px !important;
                width: 100vw !important;
            }
            .chat-container {
                margin-top: 0 !important;
                border-radius: 14px !important;
                padding: 8px 10px !important;
            }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("../includes/topbar.php"); ?>
        <div class="chat-container">
            
            <!-- AI Header -->
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
                <!-- Default AI Welcome Bubble -->
                <div class="ai-welcome-bubble" style="background: #f1f5f9; color: #1e293b; padding: 10px 14px; border-radius: 12px; max-width: 85%; font-size: 13px; line-height: 1.4; border-bottom-left-radius: 4px;">
                    <i class="fa-solid fa-sparkles" style="color: #2563EB; margin-right: 6px;"></i> Hello, Smart Vet Fam! How can I help you today with your pets or booking an appointment?
                </div>
            </div>

            <!-- Quick Suggestion Pills -->
            <div class="chat-suggestions-wrapper">
                <button class="suggestion-chip" onclick="sendSuggestion('What are the common symptoms of vomiting in dogs?')">
                    <i class="fa-solid fa-dog"></i> Dog Vomiting Symptoms
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('How do I book a vet appointment online?')">
                    <i class="fa-solid fa-calendar-check"></i> Book an Appointment
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('What are your clinic operating hours?')">
                    <i class="fa-solid fa-clock"></i> Clinic Hours & Schedule
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('What is the best diet for a lethargic cat?')">
                    <i class="fa-solid fa-cat"></i> Cat Diet & Nutrition
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('When should I bring my pet for emergency care?')">
                    <i class="fa-solid fa-triangle-exclamation"></i> Emergency Signs
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('What vaccines does my puppy need?')">
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

<script>
    function sendSuggestion(text) {
        const inputField = document.getElementById('userInput');
        const sendButton = document.getElementById('sendBtn');
        if (inputField) {
            inputField.value = text;
            if (sendButton) {
                sendButton.click();
            }
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('.sidebar'); 
        const overlay = document.getElementById('sidebarOverlay');
        if (mobileBtn && sidebar && overlay) {
            mobileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
    });
</script>
<script type="module" src="../assets/js/ai-chat.js"></script>
</body>
</html>