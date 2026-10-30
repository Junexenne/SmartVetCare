<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$logged_in_user = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : (isset($_SESSION['name']) ? $_SESSION['name'] : (isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Smart Vet Care | User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo-transparent.png">
    <style>
        body {
            background-color: #f4f7fe;
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
        .main-content {
            padding: 16px !important;
            box-sizing: border-box !important;
            background: #f4f7fe;
            min-height: 100vh;
        }
        .health-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .health-progress-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #edf2f7;
            margin-bottom: 12px;
        }
        .health-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .health-progress-header h3 {
            margin: 0;
            font-size: 16px;
            color: #1e1b4b;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .health-progress-header a {
            font-size: 13px;
            color: #5142f5;
            text-decoration: none;
            font-weight: 600;
        }
        .health-progress-header a:hover {
            text-decoration: underline;
        }
        .progress-tracker-container {
            width: 100%;
            padding: 10px 0;
            font-family: 'Poppins', sans-serif;
        }
        .stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            max-width: 100%;
            margin: 0 auto;
        }
        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 6px 10px;
            border-radius: 12px;
            transition: background 0.3s ease;
        }
        .step.completed,
        .step.active {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
        }
        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .step-text {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
        }
        .step.completed .step-circle,
        .step.active .step-circle {
            background: #2a1290;
            border-color: #2a1290;
            color: #ffffff;
        }
        .step.completed .step-text,
        .step.active .step-text {
            color: #166534;
            font-weight: 700;
        }
        .step-line {
            flex: 1;
            height: 3px;
            background: #e2e8f0;
            margin: 0 8px;
            margin-bottom: 18px;
            border-radius: 2px;
            transition: background 0.3s ease;
        }
        .step-line.active {
            background: #2a1290;
        }
        .speech-bubble {
            background: #ffffff; 
            color: #1e293b; 
            border-radius: 16px; 
            padding: 12px 14px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.06); 
            position: relative;
        }
        .speech-bubble::after {
            content: "";
            position: absolute;
            left: -8px;
            top: 20px;
            border-width: 6px 8px 6px 0;
            border-style: solid;
            border-color: transparent #ffffff transparent transparent;
            display: block;
        }
        .welcome-card {
            padding: 20px !important;
            border-radius: 24px !important;
            margin-bottom: 12px !important;
            height: auto !important;
            min-height: auto !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            box-sizing: border-box !important;
        }
        .welcome-card-disclaimer {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            margin-top: 20px !important;
            font-size: 11px !important;
            line-height: 1.4 !important;
            color: rgba(255, 255, 255, 0.85) !important;
            text-align: left !important;
            border-top: 1px solid rgba(255, 255, 255, 0.25) !important;
            padding-top: 10px !important;
            clear: both !important;
            display: block !important;
            white-space: normal !important;
            word-break: normal !important;
            overflow: visible !important;
        }
        @keyframes tandangGentleJump {
            0% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-4px) scale(1.01); }
            100% { transform: translateY(0) scale(1); }
        }
        .tandang-bouncing {
            animation: tandangGentleJump 2.2s ease-in-out infinite;
        }
        .item-close-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 13px;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px;
            transition: all 0.2s ease;
            margin-left: 6px;
        }
        .item-close-btn:hover {
            background: #fee2e2;
            color: #dc2626;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 12px !important;
                padding-bottom: 85px !important;
            }
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1100 !important;
                background: var(--sidebar-bg, #173F81) !important; 
            }
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
            }
            div.dashboard-cards,
            .dashboard-cards {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                grid-template-rows: auto auto !important;
                gap: 8px !important;
                width: 100% !important;
                box-sizing: border-box !important;
                margin-bottom: 8px !important;
            }
            div.dashboard-cards .stat-card,
            .stat-card {
                width: 100% !important;
                box-sizing: border-box !important;
                padding: 10px 12px !important;
                border-radius: 12px !important;
            }
            .stat-card .icon-box {
                width: 36px !important;
                height: 36px !important;
                font-size: 14px !important;
                border-radius: 10px !important;
            }
            .stat-card h3 {
                font-size: 11px !important;
                margin: 0 !important;
            }
            .stat-card h2 {
                font-size: 18px !important;
                margin: 2px 0 !important;
            }
            .stat-card span {
                font-size: 10px !important;
            }
            .welcome-card h1 {
                font-size: 18px !important;
                margin-bottom: 2px !important;
                line-height: 1.25 !important;
            }
            .welcome-card p#subGreeting {
                font-size: 11.5px !important;
                line-height: 1.3 !important;
                margin-bottom: 14px !important;
            }
            .speech-bubble {
                padding: 10px 12px !important;
                border-radius: 14px !important;
            }
            .speech-bubble h4 {
                font-size: 10px !important;
                margin-bottom: 2px !important;
            }
            .speech-bubble p {
                font-size: 10px !important;
                line-height: 1.35 !important;
                margin-bottom: 4px !important;
            }
            #tandangContextBadge {
                font-size: 8.5px !important;
                margin-top: 3px !important;
            }
            .welcome-card-disclaimer {
                font-size: 9.5px !important;
                margin-top: 14px !important;
                padding-top: 8px !important;
            }
            .health-progress-card,
            .recent-activities-section {
                width: 100% !important;
                display: block !important;
                float: none !important;
                margin-bottom: 8px !important;
                box-sizing: border-box !important;
            }
        }
        @media (max-width: 480px) {
            .dashboard-cards {
                grid-template-columns: 1fr !important;
            }
            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
            .step-text {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php 
    if (file_exists("../includes/sidebar.php")) {
        include "../includes/sidebar.php"; 
    }
    ?>
    <div class="main-content">
        <?php 
        if (file_exists("../includes/topbar.php")) {
            include "../includes/topbar.php"; 
        }
        ?>
        
        <div class="health-container">
            <section class="dashboard-content" style="padding: 0; margin: 0; width: 100%;">
                
                <!-- UNIFIED TANDANG & WELCOME CARD -->
                <div class="welcome-card" style="background: linear-gradient(135deg, #00386B 0%, #173F81 50%, #38BDF8 100%); color: #ffffff; box-shadow: 0 8px 25px rgba(23,63,129,0.25); position: relative; overflow: hidden;">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                            <span style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px; opacity: 0.9; display: flex; align-items: center; gap: 5px;">
                                <i class="fa-solid fa-sparkles" style="color: #fbbf24;"></i>˙✦ SMART VET CARE · MEET TANDANG 
                            </span>
                            <button id="refreshTandangBtn" style="background: transparent; border: none; color: white; cursor: pointer; font-size: 13px; opacity: 0.85; transition: transform 0.2s;" title="Refresh Tip">
                                <i class="fa-solid fa-rotate-right" id="refreshIcon"></i>
                            </button>
                        </div>
                        <h1 id="greeting" style="color: #ffffff; margin: 0 0 2px 0; font-size: 18px; font-weight: 700;">Welcome back, <?php echo htmlspecialchars($logged_in_user); ?>!</h1>
                        <p id="subGreeting" style="color: rgba(255,255,255,0.9); margin: 0 0 16px 0; font-size: 11px;">Good morning! Your AI pet buddy has a tip for you.</p>
                        
                        <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                            <div style="flex-shrink: 0; width: 110px; text-align: center;">
                                <img id="tandangImage" src="../assets/images/t1.png" alt="Tandang AI Pet Buddy" class="tandang-bouncing" style="width: 100px; height: auto; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.25)); display: block; margin: 0 auto;">
                            </div>
                            <div class="speech-bubble" style="flex: 1; min-width: 0;">
                                <h4 style="margin: 0 0 3px 0; font-size: 10.5px; font-weight: 700; color: #1e293b; letter-spacing: 0.3px;">TANDANG</h4>
                                <span id="tandangMessageText" style="display: block; margin: 0; font-size: 13px; line-height: 1.35; color: #334155;">
                                    Kukurukuku! Loading weather and pet reminders for you...
                                </span>
                                <div id="tandangContextBadge" style="margin-top: 4px; font-size: 10px; color: #64748b; font-weight: 500;">
                                    AI reminder · Weather-informed
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="welcome-card-disclaimer">
                        General pet-care guidance only. Consult your veterinarian for health concerns.
                    </span>
                </div>
                <div style="background: #ffffff; border-radius: 14px; padding: 12px 16px; margin-bottom: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #5b21b6;">
                    <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                        <div style="background: #ede9fe; color: #5b21b6; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa-solid fa-kit-medical"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <h4 style="margin: 0; font-size: 13px; color: #1e293b; font-weight: 600;">Furry Friends Animal Clinic</h4>
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-top: 2px;">
                                <p style="margin: 0; font-size: 11px; color: #64748b;">Monday - Sunday: 10:00 AM - 07:00 PM</p>
                                <span id="clinicStatusBadge" style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; flex-shrink: 0;">
                                    <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> Open
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- CLICKABLE STATS CARDS (MY PETS, APPOINTMENTS, MESSAGES, AI ASSISTANT) -->
                <div class="dashboard-cards" style="margin-bottom: 12px;">
                    <div class="stat-card" onclick="window.location.href='../pages/my-pets.php'" style="cursor: pointer;">
                        <div class="icon-box icon-pets"><i class="fa-solid fa-paw"></i></div>
                        <div class="card-info">
                            <h3>My Pets</h3>
                            <h2 id="totalPets">0</h2>
                            <span>Registered Pets</span>
                        </div>
                    </div>
                    <div class="stat-card" onclick="window.location.href='../pages/appointment.php'" style="cursor: pointer;">
                        <div class="icon-box icon-appts"><i class="fa-solid fa-calendar-days"></i></div>
                        <div class="card-info">
                            <h3>Appointments</h3>
                            <h2 id="appointmentCount">0</h2>
                            <span>Upcoming Schedule</span>
                        </div>
                    </div>
                    <div class="stat-card" onclick="window.location.href='../pages/messages.php'" style="cursor: pointer;">
                        <div class="icon-box icon-msgs"><i class="fa-solid fa-envelope"></i></div>
                        <div class="card-info">
                            <h3>Messages</h3>
                            <h2 id="messageCount">0</h2>
                            <span>Unread Messages</span>
                        </div>
                    </div>
                    <div class="stat-card" id="aiAssistantCardTrigger" onclick="window.location.href='../pages/ai-chat.php'" style="cursor: pointer;">
                    <div class="icon-box icon-ai">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" viewBox="0 0 24 24">
                            <path d="M12 2c1.8 5.7 4.3 8.2 10 10-5.7 1.8-8.2 4.3-10 10-1.8-5.7-4.3-8.2-10-10 5.7-1.8 8.2-4.3 10-10z"/>
                        </svg>
                    </div>
                    <div class="card-info">
                        <h3>KuaGO Ai Assistant</h3>
                        <h2>Ready</h2>
                        <span>Ask anything about pets</span>
                    </div>
                </div>
                </div>
                
                <div class="health-progress-card" style="margin-bottom: 12px;">
                    <div class="health-progress-header">
                        <h3><i class="fa-solid fa-calendar-check" style="color: #5142f5;"></i> Appointment Status Tracking</h3>
                        <a href="../pages/appointment.php">View Appointments</a>
                    </div>
                    <div style="background: #eef2ff; border-left: 4px solid #5142f5; padding: 8px 12px; border-radius: 6px; font-size: 11.5px; color: #1e3a8a; margin-bottom: 12px;">
                        <i class="fa-solid fa-circle-info" style="margin-right: 5px;"></i> <strong>Note:</strong> Once an appointment status is marked as <strong>Completed</strong>, it will automatically disappear from this tracker within 24 hours.
                    </div>
                    <div id="dashboardAppointmentTrackerContainer">
                        <p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">Loading appointment status tracking...</p>
                    </div>
                </div>
                <div class="health-progress-card" style="margin-bottom: 12px;">
                    <div class="health-progress-header">
                        <h3><i class="fa-solid fa-kit-medical" style="color: #5142f5;"></i> Health Monitoring Progress Tracker</h3>
                        <a href="../pages/health.php">View Health</a>
                    </div>
                    <div style="background: #eef2ff; border-left: 4px solid #5142f5; padding: 8px 12px; border-radius: 6px; font-size: 11.5px; color: #1e3a8a; margin-bottom: 12px;">
                        <i class="fa-solid fa-circle-info" style="margin-right: 5px;"></i> <strong>Note:</strong> Once a pet's status is marked as <strong>Discharged</strong>, it will automatically disappear from this tracker within 24 hours.
                    </div>
                    <div id="dashboardHealthContainer">
                        <p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">Loading health monitoring records...</p>
                    </div>
                </div>
                <div class="recent-activities-section" style="margin-bottom: 0;">
                    <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Activities</h3>
                    <div id="recentActivitiesContainer">
                        <p class="empty-state">No recent activities yet.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- FIXED CHAT WIDGET BUTTON -->
<div id="smartVetChatWidget" style="position: fixed; bottom: 75px; right: 20px; z-index: 9999; font-family: 'Poppins', sans-serif;">
    <button id="chatToggleBtn" style="background: linear-gradient(135deg, #5142f5, #173F81); color: #ffffff; border: none; width: 55px; height: 55px; border-radius: 50%; box-shadow: 0 6px 20px rgba(83, 66, 245, 0.4); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 22px; transition: transform 0.2s ease;">
       <i class="fa-solid fa-paw"></i>
    </button>
    <div id="chatWindow" style="display: none; position: absolute; bottom: 70px; right: 0; width: 340px; height: 450px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid #edf2f7; flex-direction: column; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #173F81, #5142f5); color: #ffffff; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="background: rgba(255,255,255,0.2); width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-paw" style="font-size: 16px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 14px; font-weight: 600;">Smart Vet Assistant</h4>
                    <span style="font-size: 10px; opacity: 0.8;">Online | Powered by Gemini AI</span>
                </div>
            </div>
            <button id="chatCloseBtn" style="background: transparent; border: none; color: #ffffff; font-size: 16px; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div id="chatMessages" style="flex: 1; padding: 15px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 10px;">
            <div style="background: #ffffff; padding: 10px 14px; border-radius: 12px; font-size: 12.5px; color: #1e293b; box-shadow: 0 2px 5px rgba(0,0,0,0.03); max-width: 85%; border: 1px solid #edf2f7;">
                Hello, Smart Vet Fam! How can I help you today with your pets or website features?
            </div>
        </div>
        <!-- Pinalawak na Suggestion Chips -->
        <div style="padding: 8px 12px; background: #ffffff; border-top: 1px solid #edf2f7; display: flex; gap: 6px; overflow-x: auto; white-space: nowrap;">
            <button class="faq-chip" data-question="How to book an appointment?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 6px 12px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 600;">📅 How to Book?</button>
            <button class="faq-chip" data-question="What are the clinic hours?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 6px 12px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 600;">⏰ Clinic Hours</button>
            <button class="faq-chip" data-question="Where can I see clinical records?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 6px 12px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 600;">📁 Clinical Records</button>
            <button class="faq-chip" data-question="How to use KuaGO AI?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 6px 12px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 600;">🦉 Use KuaGO AI</button>
            <button class="faq-chip" data-question="How to change my password?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 6px 12px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 600;">🔑 Change Password</button>
            <button class="faq-chip" data-question="How to report a problem or send feedback?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 6px 12px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 600;">💬 Send Feedback</button>
            <button class="faq-chip" data-question="What to do during an emergency?" style="background: #fee2e2; color: #991b1b; border: none; padding: 6px 12px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 600;">🚨 Emergency</button>
        </div>
        <div style="padding: 10px; background: #ffffff; border-top: 1px solid #edf2f7; display: flex; gap: 8px;">
            <input type="text" id="chatInput" placeholder="Type your question..." style="flex: 1; padding: 8px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 12px; font-family: 'Poppins', sans-serif;">
            <button id="chatSendBtn" style="background: #173F81; color: #ffffff; border: none; width: 35px; height: 35px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-paper-plane" style="font-size: 12px;"></i>
            </button>
        </div>
    </div>
</div>
<?php include("../includes/bottom-nav.php"); ?>
<!-- TANDANG TEMPERATURE CHECKER & GROQ AI SCRIPT -->
<script>
    const GROQ_API_KEY_TANDANG = "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"; 
    let weatherDataCache = { temp: 30.7, city: "Caloocan", isRainy: false };
    const cityCoordinates = {
        "caloocan": { lat: 14.650, lon: 120.967 },
        "valenzuela": { lat: 14.7006, lon: 120.9830 },
        "quezon city": { lat: 14.6760, lon: 121.0437 },
        "manila": { lat: 14.5995, lon: 120.9842 },
        "malabon": { lat: 14.6681, lon: 120.9567 },
        "navotas": { lat: 14.6675, lon: 120.9431 }
    };

    let userPetsCache = [];

    // 10 iba't ibang pet care reminders sa English
    const englishRemindersPool = [
        "please make sure {PETS} drink plenty of fresh water to stay hydrated throughout the day!",
        "rain or wet weather is approaching. Keep {PETS} safe and avoid letting them walk through floodwaters to prevent leptospirosis!",
        "always check {PETS} for ticks and fleas after outdoor walks in the park.",
        "keep toxic human foods like chocolate, onions, and grapes strictly away from {PETS}.",
        "schedule regular veterinary check-ups and keep {PETS} updated on their annual vaccinations.",
        "brush {PETS}'s coat regularly and check their ears weekly to prevent dirt build-up and infections.",
        "maintain good dental hygiene for {PETS} by brushing their teeth or giving them dental chew treats.",
        "ensure {PETS} get balanced daily nutrition and measured food portions to maintain a healthy weight.",
        "provide safe indoor toys and interactive games to keep {PETS} physically active and mentally sharp.",
        "protect {PETS}'s paws by avoiding hot asphalt walks during peak midday sun hours."
    ];
    let reminderPoolIndex = 0;

    function initTandangAnimation() {
        const tandangImg = document.getElementById("tandangImage");
        if (!tandangImg) return;
        const frames = [
            "../assets/images/t1.png",
            "../assets/images/t2.png",
            "../assets/images/t3.png",
            "../assets/images/t4.png"
        ];
        let currentFrameIndex = 0;
        setInterval(() => {
            currentFrameIndex = (currentFrameIndex + 1) % frames.length;
            tandangImg.src = frames[currentFrameIndex];
        }, 1200); 
    }

    async function fetchDynamicWeather() {
        try {
            let userCity = localStorage.getItem("userAddress") || "Caloocan";
            userCity = userCity.split(',')[0].trim();
            weatherDataCache.city = userCity;
            let key = userCity.toLowerCase().trim();
            let coords = cityCoordinates[key] || { lat: 14.650, lon: 120.967 };
            
            const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${coords.lat}&longitude=${coords.lon}&current=temperature_2m,precipitation&timezone=Asia%2FManila`);
            const data = await res.json();
            if (data && data.current) {
                weatherDataCache.temp = data.current.temperature_2m;
                weatherDataCache.isRainy = data.current.precipitation > 0;
            }
        } catch (err) {
            console.error("Weather fetch error:", err);
        }
    }

    function formatPetNames(pets) {
        if (!pets || pets.length === 0) return "your pets";
        if (pets.length === 1) return pets[0];
        if (pets.length === 2) return `${pets[0]} and ${pets[1]}`;
        return pets.slice(0, -1).join(", ") + `, and ${pets[pets.length - 1]}`;
    }

    async function updateTandangDisplay() {
        const msgEl = document.getElementById("tandangMessageText");
        const badgeEl = document.getElementById("tandangContextBadge");
        const subGreetingEl = document.getElementById("subGreeting");
        
        const currentHour = new Date().getHours();
        let timeGreet = "Good morning";
        if (currentHour >= 12 && currentHour < 18) {
            timeGreet = "Good afternoon";
        } else if (currentHour >= 18) {
            timeGreet = "Good evening";
        }
        if (subGreetingEl) {
            subGreetingEl.textContent = `${timeGreet}! Your AI pet buddy has a tip for you.`;
        }

        const petsStr = formatPetNames(userPetsCache);

        // Piliin ang susunod na reminder mula sa 10 options
        reminderPoolIndex = (reminderPoolIndex + 1) % englishRemindersPool.length;
        let baseReminder = englishRemindersPool[reminderPoolIndex];
        baseReminder = baseReminder.replace(/{PETS}/g, petsStr);

        // Kung mainit (>=31°C) o maulan, i-override para sa weather safety, kung hindi ay sundin ang pool tips
        let tipSentence = "";
        if (weatherDataCache.temp >= 31) {
            tipSentence = `Kukurukuku! On this ${weatherDataCache.temp} °C day in ${weatherDataCache.city}, please make sure ${petsStr} drink plenty of fresh water to avoid heat exhaustion!`;
        } else if (weatherDataCache.isRainy || weatherDataCache.temp < 27) {
            tipSentence = `Kukurukuku! On this ${weatherDataCache.temp} °C rainy day in ${weatherDataCache.city}, keep ${petsStr} safe indoors and avoid floodwaters to prevent leptospirosis!`;
        } else {
            tipSentence = `Kukurukuku! On this ${weatherDataCache.temp} °C day in ${weatherDataCache.city}, ${baseReminder}`;
        }

        if (msgEl) {
            msgEl.textContent = tipSentence;
        }
        if (badgeEl) {
            badgeEl.textContent = `AI reminder · Weather-informed · ${weatherDataCache.city}`;
        }

        // Optional Groq API call
        try {
            const response = await fetch("https://api.groq.com/openai/v1/chat/completions", {
                method: "POST",
                headers: {
                    "Authorization": `Bearer ${GROQ_API_KEY_TANDANG}`,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    model: "llama-3.3-70b-versatile",
                    messages: [
                        { role: "system", content: "You are Tandang, a friendly pet care AI assistant. Write a short single-sentence message in English stating the temperature and city once, mentioning the pet names, and giving a weather-appropriate or general care tip." },
                        { role: "user", content: `Current temperature is ${weatherDataCache.temp} °C in ${weatherDataCache.city}. Pet names are ${petsStr}.` }
                    ],
                    max_tokens: 70
                })
            });
            if (response.ok) {
                const data = await response.json();
                if (data.choices && data.choices[0] && data.choices[0].message) {
                    const aiText = data.choices[0].message.content.trim();
                    if (msgEl) msgEl.textContent = aiText;
                }
            }
        } catch (e) {
            console.log("Using localized fallback.");
        }
    }

    window.addEventListener("userAddressLoaded", async (e) => {
        await fetchDynamicWeather();
        updateTandangDisplay();
    });

    document.addEventListener("DOMContentLoaded", async function() {
        initTandangAnimation();
        await fetchDynamicWeather();
        updateTandangDisplay();
        
        // Magbabago ang reminder text every 15 seconds
        setInterval(async () => {
            await fetchDynamicWeather();
            updateTandangDisplay();
        }, 15000);

        const refreshBtn = document.getElementById("refreshTandangBtn");
        if (refreshBtn) {
            refreshBtn.addEventListener("click", async () => {
                const icon = document.getElementById("refreshIcon");
                if (icon) {
                    icon.style.transform = "rotate(360deg)";
                    icon.style.transition = "transform 0.5s ease";
                    setTimeout(() => { icon.style.transform = "rotate(0deg)"; }, 500);
                }
                await fetchDynamicWeather();
                updateTandangDisplay();
            });
        }
    });
</script>
<!-- ROBUST EVENT DELEGATION SCRIPT PARA SA SIDEBAR -->
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
<!-- GEMINI AI CHATBOT SCRIPT -->
<script type="module">
    import { GoogleGenerativeAI } from "https://esm.run/@google/generative-ai";
    const GEMINI_API_KEY = "AQ.Ab8RN6Jme3HtnoqIAbhJcXQLflMqkotZa1qr04O81v7w-cg-fg"; 
    const genAI = new GoogleGenerativeAI(GEMINI_API_KEY);
    const model = genAI.getGenerativeModel({ 
        model: "gemini-1.5-flash",
        systemInstruction: `You are the friendly AI Assistant for Furry Friends Animal Clinic in Smart Vet Care. Always respond politely and clearly in English.`
    });
    
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById("chatToggleBtn");
        const chatWindow = document.getElementById("chatWindow");
        const closeBtn = document.getElementById("chatCloseBtn");
        const sendBtn = document.getElementById("chatSendBtn");
        const chatInput = document.getElementById("chatInput");
        const chatMessages = document.getElementById("chatMessages");
        const faqChips = document.querySelectorAll(".faq-chip");
        const cardTrigger = document.getElementById("aiAssistantCardTrigger");
        
        function toggleChat() {
            if (!chatWindow) return;
            const isFlex = window.getComputedStyle(chatWindow).display === "flex";
            chatWindow.style.display = isFlex ? "none" : "flex";
        }
        if (toggleBtn) {
            toggleBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                toggleChat();
            });
        }
        
        if (closeBtn && chatWindow) {
            closeBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                chatWindow.style.display = "none";
            });
        }
        
        function appendMessage(sender, text) {
            const msgDiv = document.createElement("div");
            if (sender === 'user') {
                msgDiv.style.cssText = "background: #173F81; color: #ffffff; padding: 10px 14px; border-radius: 12px; font-size: 12.5px; margin-left: auto; max-width: 85%; word-break: break-word;";
            } else {
                msgDiv.style.cssText = "background: #ffffff; color: #1e293b; padding: 10px 14px; border-radius: 12px; font-size: 12.5px; margin-right: auto; max-width: 85%; border: 1px solid #edf2f7; word-break: break-word; white-space: pre-line;";
            }
            msgDiv.textContent = text;
            chatMessages.appendChild(msgDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return msgDiv;
        }
        
        async function handleBotResponse(question) {
            const loadingMsg = appendMessage('bot', "Thinking...");
            const lowerQ = question.toLowerCase().trim();
            if (lowerQ.includes('password') || lowerQ.includes('change pass') || lowerQ.includes('reset pass')) {
                loadingMsg.textContent = `To change your account password:\n\n1. Click on the arrow down icon beside your profile picture at the top-right corner of the screen.\n2. Click "Settings" from the dropdown menu.\n3. Update your password details and save your changes!`;
                return;
            }
            if (lowerQ.includes('kuago') || lowerQ.includes('ai support') || lowerQ.includes('explain ai')) {
                loadingMsg.textContent = `To use Explain with Kuago:\n\n1. Go to "Records & History" from the sidebar menu.\n2. Open any clinical record entry.\n3. Click the "Explain with KuaGO" button to generate a simplified medical explanation.\n\nNote: Please note that KuaGO is an AI assistant. Always consult a licensed veterinarian for official medical diagnosis and prescriptions.`;
                return;
            }
            if (lowerQ.includes('clinical record') || lowerQ.includes('where are records') || lowerQ.includes('archive') || lowerQ.includes('trash')) {
                loadingMsg.textContent = `To view all your pet's clinical records, archived files, and trash:\n\n1. Go to "Records & History" in the sidebar menu.\n2. Make sure to change the year dropdown filter to "All Years" so all historical records are displayed.\n3. Switch between tabs to view Clinical Records, Archived Appointments, Archived Pets, or Trash!`;
                return;
            }
            if (lowerQ.includes('report') || lowerQ.includes('feedback') || lowerQ.includes('problem') || lowerQ.includes('issue') || lowerQ.includes('flag')) {
                loadingMsg.textContent = `To report a problem or submit feedback:\n\nOption 1: Go to the "Help & Support" tab in the sidebar menu, scroll down to the bottom, and fill out the "Send Feedback" form.\n\nOption 2: Click the "Flag" icon located at the top-bar (beside the date and time display) to quickly report an issue!`;
                return;
            }
            if (lowerQ.includes('book') || lowerQ.includes('appointment') || lowerQ.includes('sched')) {
                loadingMsg.textContent = `To book an appointment on our website:\n\n1. Click on "Book Appointment" in the sidebar menu.\n2. Select your registered pet.\n3. Choose your preferred service, date, and available time slot.\n4. Enter any specific notes or reasons for the visit.\n5. Click "Submit Appointment" to confirm!`;
                return;
            }
            if (lowerQ.includes('hour') || lowerQ.includes('time') || lowerQ.includes('open') || lowerQ.includes('oras') || lowerQ.includes('araw') || lowerQ.includes('schedule')) {
                loadingMsg.textContent = `Furry Friends Animal Clinic is OPEN everyday from 10:00 AM to 7:00 PM.\n\nLocation: Bagong Barrio, Caloocan (Infront of 7-11, beside Lourdes Church).\nContact us at: 0925-8897811 (Sun).`;
                return;
            }
            if (lowerQ.includes('emergency') || lowerQ.includes('urgent')) {
                loadingMsg.textContent = `For emergencies during clinic hours (10:00 AM - 7:00 PM):\nNo need to book an appointment! Please bring your pet directly to our clinic immediately or call us at 0925-8897811.\n\nIf it is beyond clinic hours, please bring your pet immediately to the nearest 24-hour emergency animal clinic!`;
                return;
            }
            try {
                const promptInEnglish = `${question} (Please respond clearly and politely in English for Furry Friends Animal Clinic website user)`;
                const result = await model.generateContent(promptInEnglish);
                const response = await result.response;
                const textReply = response.text();
                loadingMsg.textContent = textReply ? textReply : `For immediate assistance, please visit our clinic or contact us at 0925-8897811.`;
            } catch (error) {
                loadingMsg.textContent = `We are currently offline and attending to a patient. If beyond clinic hours and it is a medical emergency, please bring your pet to the nearest 24hr animal clinic.\n\nFor immediate response during clinic hours, kindly call or text us at 0925-8897811.\nOpen everyday 10am - 7pm.`;
            }
        }
        
        if (sendBtn && chatInput) {
            sendBtn.addEventListener("click", () => {
                const text = chatInput.value.trim();
                if (text) {
                    appendMessage('user', text);
                    chatInput.value = "";
                    handleBotResponse(text);
                }
            });
            chatInput.addEventListener("keypress", (e) => {
                if (e.key === "Enter") sendBtn.click();
            });
        }
        
        faqChips.forEach(chip => {
            chip.addEventListener("click", () => {
                const question = chip.getAttribute("data-question");
                appendMessage('user', question);
                handleBotResponse(question);
            });
        });
    });
</script>

<!-- FIREBASE CONNECTOR PARA SA PET NAMES AT ADDRESS NG USER -->
<script type="module">
    import { auth, db } from "./firebase-config.js";
    import { collection, query, where, getDocs } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
    import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

    onAuthStateChanged(auth, async (user) => {
        if (!user) return;
        try {
            const userQuery = query(collection(db, "users"), where("email", "==", user.email));
            const userSnap = await getDocs(userQuery);
            if (!userSnap.empty) {
                const userData = userSnap.docs[0].data();
                const ownerId = userData.ownerId;
                if (userData.address) {
                    localStorage.setItem("userAddress", userData.address);
                    window.dispatchEvent(new CustomEvent("userAddressLoaded", { detail: userData.address }));
                }
                if (ownerId) {
                    const petsQuery = query(collection(db, "pets"), where("ownerId", "==", ownerId));
                    const petsSnap = await getDocs(petsQuery);
                    let names = [];
                    petsSnap.forEach(doc => {
                        const pData = doc.data();
                        if (pData.petName) names.push(pData.petName);
                    });
                    if (names.length > 0) {
                        window.userPetsCache = names;
                        if (typeof updateTandangDisplay === "function") {
                            await fetchDynamicWeather();
                            updateTandangDisplay();
                        }
                    }
                }
            }
        } catch (err) {
            console.error("Error fetching user pets/address:", err);
        }
    });
</script>
<script type="module" src="../assets/js/dashboard.js"></script>
</body>
</html>