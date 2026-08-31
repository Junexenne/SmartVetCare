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
            background-color: #ffffff;
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
            background: #ffffff;
            height: 100vh;
            height: 100dvh;
            padding: 20px;
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
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            gap: 12px;
            margin-top: 10px;
        }

        .chat-header-area {
            flex-shrink: 0;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
        }

        .chat-header-area h2 {
            font-size: 16px;
            color: #173F81;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 4px 0;
        }

        .chat-header-area p {
            font-size: 12px;
            color: #64748b;
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
            gap: 10px;
            -webkit-overflow-scrolling: touch;
        }

        .chat-messages .message, 
        .chat-messages .chat-bubble,
        .chat-messages div[class*="bubble"],
        .chat-messages div[class*="msg"] {
            max-width: 85%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.4;
        }

        .chat-input-area-wrapper {
            flex-shrink: 0; 
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: #ffffff;
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
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 24px;
            font-size: 13px;
            outline: none;
            background: #f8fafc;
        }

        .chat-input-area button {
            background: #2F248F;
            color: #ffffff;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .chat-disclaimer {
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.3;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
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

        /* Centered Mobile View with Safe Side Spacing */
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
                padding: 10px !important; /* Naglalagay ng maliit na breathing room sa buong screen */
                width: 100vw !important;
            }

            /* Ginawa itong may margin sa gilid para nakagitna at hindi nakadikit sa right/left edges */
            .chat-container {
                margin-top: 0 !important;
                border-radius: 16px !important; /* Binabalik ang smooth rounded corners para mukhang card */
                border: 1px solid rgba(226, 232, 240, 0.8) !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
                padding: 12px 14px !important;
                flex: 1;
                width: calc(100vw - 20px) !important; /* Sakto ang laki para may pantay na espasyo sa kaliwa't kanan */
                margin-left: auto !important;
                margin-right: auto !important;
                box-sizing: border-box !important;
            }

            .chat-input-area-wrapper {
                padding: 0 2px 4px 2px;
                box-sizing: border-box;
                width: 100%;
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
            
            <div class="chat-header-area">
                <h2>
                    <i class="fa-solid fa-robot" style="color: #173F81; background: #eef4ff; padding: 8px; border-radius: 10px;"></i>
                    Smart Vet Care AI
                </h2>
                <p>Your trusted companion for instant pet care support.</p>
            </div>

            <div class="chat-messages" id="chatMessages">
                <!-- Messages load dynamically -->
            </div>

            <div class="chat-input-area-wrapper">
                <div class="chat-input-area">
                    <input type="text" id="userInput" placeholder="Type your message here..." autocomplete="off">
                    <button id="sendBtn" type="button">
                        <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
                    </button>
                </div>
                <div class="chat-disclaimer">
                    <i class="fa-solid fa-triangle-exclamation"></i> Disclaimer: AI-generated guidance only. Consult a vet.
                </div>
            </div>

        </div>

    </div>

</div>

<script>
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