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
    <title>KuaGO AI Support | Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo-transparent.png">
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
            padding: 10px 20px 8px 20px !important;
            gap: 6px !important;
        }
        .topbar, header, .top-navbar {
            margin-bottom: 4px !important;
            flex-shrink: 0;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            height: 100%;
            min-height: 0;
            overflow: hidden;
            box-sizing: border-box;
            background: #ffffff;
            margin: 0 !important;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
            padding: 12px 18px 8px 18px;
            gap: 8px;
        }
        .chat-ai-header {
            flex-shrink: 0;
            background: linear-gradient(135deg, #163B7A 0%, #2563EB 50%, #38BDF8 100%);
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
            position: relative;
            z-index: 10;
        }
        .chat-ai-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .kuago-avatar-header {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffffff;
            padding: 2px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .kuago-avatar-header img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .online-dot {
            width: 9px;
            height: 9px;
            background: #10B981;
            border: 2px solid #ffffff;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
        }
        .chat-ai-header-content h2 {
            font-size: 14.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0 0 2px 0;
            color: #ffffff;
        }
        .chat-ai-header-content p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
        }
        /* Mobile KuaGO Welcome Card */
        .kuago-welcome-card {
            background: linear-gradient(135deg, #173F81 0%, #1e40af 100%);
            border-radius: 14px;
            padding: 12px 16px;
            color: #ffffff;
            margin-bottom: 2px;
            box-shadow: 0 3px 10px rgba(23, 63, 129, 0.15);
            flex-shrink: 0;
        }
        .kuago-welcome-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .kuago-title-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .kuago-card-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .kuago-card-icon img {
            width: 100%;
            height: 100%;
            border-radius: 4px;
            object-fit: cover;
        }
        .kuago-welcome-top h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
        }
        .ai-support-badge {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            letter-spacing: 0.5px;
        }
        .kuago-welcome-card p {
            margin: 0;
            font-size: 12px;
            line-height: 1.4;
            color: rgba(255, 255, 255, 0.9);
        }
        /* Action Buttons Bar */
        .kuago-action-buttons-bar {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 2px 2px 4px 2px;
            flex-shrink: 0;
            scrollbar-width: none;
        }
        .kuago-action-buttons-bar::-webkit-scrollbar {
            display: none;
        }
        .kuago-action-pill {
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #e2e8f0;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .kuago-action-pill:hover {
            background: #f0f7ff;
            color: #2563EB;
            border-color: #bfdbfe;
            transform: translateY(-1px);
        }
        /* Chat Messages Area - Expanded to take maximum height */
        .chat-messages {
            flex: 1;
            overflow-y: auto; 
            word-break: break-word;
            overflow-wrap: break-word;
            box-sizing: border-box;
            min-height: 0;
            padding: 8px 4px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #fdfcff;
            border-radius: 10px;
            border: 1px solid #f1f5f9;
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
            padding: 10px 14px !important;
            border-radius: 16px !important;
            font-size: 12.5px !important;
            line-height: 1.45 !important;
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
            gap: 6px;
            padding: 2px 2px;
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
            padding: 6px 12px;
            border-radius: 18px;
            font-size: 11.5px;
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
            font-size: 11px;
        }
        .chat-input-area-wrapper {
            flex-shrink: 0; 
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: transparent;
            border: none;
            padding: 2px 0 0 0;
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
            padding: 9px 15px;
            border: 1.5px solid #cbd5e1;
            border-radius: 28px;
            font-size: 12.5px;
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
            width: 36px;
            height: 36px;
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
            font-size: 9.5px;
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
                padding: 8px 8px calc(40px + env(safe-area-inset-bottom, 10px)) 10px !important;
            }
            .message {
                max-width: 85% !important;
            }
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
            <!-- Header Banner with Avatar Image -->
            <div class="chat-ai-header">
                <div class="chat-ai-header-left">
                    <div class="kuago-avatar-header">
                        <img src="../assets/images/KuaGO.png" alt="KuaGO Logo" onerror="this.src='../assets/images/logo-transparent.png'">
                        <div class="online-dot"></div>
                    </div>
                    <div class="chat-ai-header-content">
                        <h2>
                            KuaGO AI Support  ✧₊⁺
                            <i class="fa-solid fa-sparkles" style="font-size: 13px;"></i>
                        </h2>
                        <p>Smart Vet Care • Internet required</p>
                    </div>
                </div>
            </div>
            <!-- Welcome Card -->
            <div class="kuago-welcome-card">
                <div class="kuago-welcome-top">
                    <div class="kuago-title-group">
                        <div class="kuago-card-icon">
                            <img src="../assets/images/KuaGO.png" alt="KuaGO Avatar" onerror="this.src='../assets/images/logo-transparent.png'">
                        </div>
                        <h3>KuaGO</h3>
                    </div>
                    <span class="ai-support-badge">AI SUPPORT</span>
                </div>
                <p>KuaGO is ready to help! Ask about your pets, clinic services, or appointments at Furry Friends Animal Clinic.</p>
            </div>
            
        
            <!-- Chat Messages (Expanded to fill vertical space) -->
            <div class="chat-messages" id="chatMessages"></div>
            
            <!-- Quick Suggestion Pills -->
            <div class="chat-suggestions-wrapper">
             <button class="kuago-action-pill" type="button" onclick="sendSuggestion('I want to book an appointment with KuaGO.')">
                    <i class="fa-solid fa-calendar-plus" style="color: #2563EB;"></i> Book in Chat
                </button>
                <button class="kuago-action-pill" type="button" onclick="sendSuggestion('What are the emergency contact details and urgent warning signs?')">
                    <i class="fa-solid fa-phone-volume" style="color: #2563EB;"></i> Emergency Contact
                </button>
                <button class="kuago-action-pill" type="button" onclick="window.location.href='appointment.php'">
                    <i class="fa-solid fa-calendar-days" style="color: #2563EB;"></i> My Bookings
                </button>   
               <button class="suggestion-chip" type="button" onclick="sendSuggestion('What are the common symptoms of vomiting in dogs?')">
                    <i class="fa-solid fa-dog"></i> Dog Vomiting Symptoms
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
            <!-- Chat Input Area at the bottom -->
            <div class="chat-input-area-wrapper">
                <div class="chat-input-area">
                    <input type="text" id="userInput" placeholder="Ask KuaGO about your pet..." autocomplete="off">
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
<script type="module" src="../assets/js/ai-chat.js"></script>
<!-- ROBUST EVENT DELEGATION SCRIPT PARA SA BURGER BUTTON AT SIDEBAR -->
<script>
    document.addEventListener("click", function(e) {
        const menuBtn = e.target.closest("#mobileMenuBtn, #menuBtn, .mobile-menu-btn, .burger-btn, .fa-bars");
        const sidebar = document.querySelector(".sidebar") || document.getElementById("appSidebar");
        const overlay = document.getElementById("sidebarOverlay") || document.querySelector(".sidebar-overlay");

        if (menuBtn && sidebar) {
            e.stopPropagation();
            e.preventDefault();
            
            sidebar.classList.toggle("active");
            
            if (sidebar.classList.contains("active")) {
                sidebar.style.setProperty("left", "0px", "important");
                if (overlay) {
                    overlay.classList.add("active");
                    overlay.style.setProperty("display", "block", "important");
                }
            } else {
                sidebar.style.setProperty("left", "-270px", "important");
                if (overlay) {
                    overlay.classList.remove("active");
                    overlay.style.setProperty("display", "none", "important");
                }
            }
            return;
        }

        if (overlay && overlay.contains(e.target)) {
            if (sidebar) {
                sidebar.classList.remove("active");
                sidebar.style.setProperty("left", "-270px", "important");
            }
            overlay.classList.remove("active");
            overlay.style.setProperty("display", "none", "important");
        }
    }); 
</script>
</body>
</html>