<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Smart Vet Care | User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Sidebar Overlay para sa Mobile */
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
        /* Delivery-Style Progress Tracker Styles */
        .progress-tracker-container {
            width: 100%;
            padding: 15px 0 5px 0;
            font-family: 'Poppins', sans-serif;
        }
        .stepper {
            display: flex;
            justify-content: space-between;
            position: relative;
            max-width: 100%;
            margin: 0 auto;
        }
        .stepper::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 15%;
            right: 15%;
            height: 3px;
            background: #e2e8f0;
            z-index: 1;
        }
        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }
        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 6px auto;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        .step-text {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }
        .step.completed .step-circle {
            background: #10b981;
            color: #ffffff;
        }
        .step.completed .step-text {
            color: #10b981;
            font-weight: 600;
        }
        .step.active .step-circle {
            background: #2563EB;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }
        .step.active .step-text {
            color: #2563EB;
            font-weight: 700;
        }
        /* Responsive Breakpoint para sa Phones */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1001;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active {
                left: 0;
            }
            .sidebar-overlay.active {
                display: block;
            }
            .main-content {
                padding: 12px !important;
                width: 100% !important;
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
            .welcome-card {
                padding: 16px 20px !important;
                border-radius: 14px !important;
                margin-bottom: 8px !important;
            }
            .welcome-card h1 {
                font-size: 18px !important;
                margin-bottom: 4px !important;
            }
            .welcome-card p {
                font-size: 11.5px !important;
                line-height: 1.35 !important;
            }
            .welcome-card > div:last-child {
                width: 42px !important;
                height: 42px !important;
                font-size: 18px !important;
            }
            .vet-reminder-card {
                padding: 12px 14px !important;
                border-radius: 12px !important;
                margin-bottom: 8px !important;
            }
            .vet-reminder-icon {
                width: 36px !important;
                height: 36px !important;
                font-size: 15px !important;
                border-radius: 10px !important;
            }
            .vet-reminder-content h4 {
                font-size: 13px !important;
                margin-bottom: 2px !important;
            }
            .vet-reminder-content p {
                font-size: 11px !important;
                line-height: 1.3 !important;
            }
            .upcoming-appointment-section,
            .recent-activities-section {
                width: 100% !important;
                display: block !important;
                float: none !important;
                margin-bottom: 12px !important;
                box-sizing: border-box !important;
            }
        }
        @media (max-width: 480px) {
            .dashboard-cards {
                grid-template-columns: 1fr !important;
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
        <!-- Topbar -->
        <?php 
        if (file_exists("../includes/topbar.php")) {
            include "../includes/topbar.php"; 
        }
        ?>
        <section class="dashboard-content">
            <!-- Welcome Section -->
            <div class="welcome-card" style="background: linear-gradient(135deg, #163B7A 0%, #2563EB 50%, #38BDF8 100%); margin-bottom: 10px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; border-radius: 16px; padding: 25px 30px; box-shadow: 0 4px 15px rgba(23,63,129,0.2);">
                <div>
                    <h1 id="greeting" style="color: #ffffff; margin: 0 0 8px 0; font-size: 22px;">Good Morning! </h1>
                    <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 13.5px;">Welcome back to Smart Vet Care. Manage your pets, appointments, health records, and stay connected with your veterinarian.</p>
                </div>
                <div style="background: rgba(255, 255, 255, 0.15); width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #ffffff; flex-shrink: 0;">
                    <i class="fa-solid fa-paw"></i>
                </div>
            </div>
            <!-- Compact Clinic Operating Hours Widget -->
            <div style="background: #ffffff; border-radius: 14px; padding: 12px 16px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; border-left: 5px solid #5b21b6;">
                <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                    <div style="background: #ede9fe; color: #5b21b6; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                        <i class="fa-solid fa-kit-medical"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <h4 style="margin: 0; font-size: 13px; color: #1e293b; font-weight: 600;">Furry Friends Animal Clinic</h4>
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-top: 2px;">
                            <p style="margin: 0; font-size: 11px; color: #64748b;">M–Sat: 8:00 AM–6:00 PM | Sun: 9:00 AM–3:00 PM</p>
                            <span id="clinicStatusBadge" style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; flex-shrink: 0;">
                                <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> Open
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Seasonal Health Reminder Widget -->
            <div class="vet-reminder-card" id="seasonalReminderCard" style="margin-bottom: 10px;">
                <div class="vet-reminder-icon">
                    <i class="fa-solid fa-triangle-exclamation" id="reminderIcon"></i>
                </div>
                <div class="vet-reminder-content">
                    <h4 id="reminderTitle">Seasonal Pet Health Alert</h4>
                    <p id="reminderText">Loading health tips for your pet...</p>
                </div>
            </div>
            <!-- Statistics Grid -->
            <div class="dashboard-cards" style="margin-bottom: 10px;">
                <div class="stat-card">
                    <div class="icon-box icon-pets"><i class="fa-solid fa-paw"></i></div>
                    <div class="card-info">
                        <h3>My Pets</h3>
                        <h2 id="totalPets">0</h2>
                        <span>Registered Pets</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-box icon-appts"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="card-info">
                        <h3>Appointments</h3>
                        <h2 id="appointmentCount">0</h2>
                        <span>Upcoming Schedule</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-box icon-msgs"><i class="fa-solid fa-envelope"></i></div>
                    <div class="card-info">
                        <h3>Messages</h3>
                        <h2 id="messageCount">0</h2>
                        <span>Unread Messages</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-box icon-ai">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" viewBox="0 0 24 24">
                            <path d="M12 2c1.8 5.7 4.3 8.2 10 10-5.7 1.8-8.2 4.3-10 10-1.8-5.7-4.3-8.2-10-10 5.7-1.8 8.2-4.3 10-10z"/>
                        </svg>
                    </div>
                    <div class="card-info">
                        <h3>AI ASSISTANT</h3>
                        <h2>Ready</h2>
                        <span>Ask anything about pets</span>
                    </div>
                </div>
            </div>
            <!-- Bottom Sections -->
            <div class="upcoming-appointment-section" style="margin-bottom: 10px;">
                <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Appointment</h3>
                <div id="upcomingAppointmentContainer">
                    <p class="empty-state">You don't have any upcoming appointments.</p>
                </div>
            </div>
            <div class="recent-activities-section">
                <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Activities</h3>
                <div id="recentActivitiesContainer">
                    <p class="empty-state">No recent activities yet.</p>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- FLOATING CHATBOT WIDGET -->
<div id="smartVetChatWidget" style="position: fixed; bottom: 25px; right: 25px; z-index: 9999; font-family: 'Poppins', sans-serif;">
    <!-- Chat Toggle Button (Floating Icon) -->
    <button id="chatToggleBtn" style="background: linear-gradient(135deg, #5142f5, #173F81); color: #ffffff; border: none; width: 55px; height: 55px; border-radius: 50%; box-shadow: 0 6px 20px rgba(83, 66, 245, 0.4); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 22px; transition: transform 0.3s ease;">
       <i class="fa-solid fa-paw"></i>
    </button>

    <!-- Chat Window Box -->
    <div id="chatWindow" style="display: none; position: absolute; bottom: 70px; right: 0; width: 340px; height: 450px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid #edf2f7; flex-direction: column; overflow: hidden;">
        <!-- Chat Header -->
        <div style="background: linear-gradient(135deg, #173F81, #5142f5); color: #ffffff; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="background: rgba(255,255,255,0.2); width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-paw" style="font-size: 16px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 14px; font-weight: 600;">Smart Vet Assistant</h4>
                    <span style="font-size: 10px; opacity: 0.8;">Online | FAQs & Support</span>
                </div>
            </div>
            <button id="chatCloseBtn" style="background: transparent; border: none; color: #ffffff; font-size: 16px; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Chat Body / Messages Area -->
        <div id="chatMessages" style="flex: 1; padding: 15px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 10px;">
            <div style="background: #ffffff; padding: 10px 14px; border-radius: 12px; font-size: 12.5px; color: #1e293b; box-shadow: 0 2px 5px rgba(0,0,0,0.03); max-width: 85%; border: 1px solid #edf2f7;">
                Hello, Smart Vet Fam! How can I help you today with your pets or booking an appointment?
            </div>
        </div>

        <!-- Quick FAQ Buttons -->
        <div style="padding: 8px 12px; background: #ffffff; border-top: 1px solid #edf2f7; display: flex; gap: 6px; overflow-x: auto; white-space: nowrap;">
            <button class="faq-chip" data-question="How to book an appointment?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 5px 10px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 500;">📅 How to Book?</button>
            <button class="faq-chip" data-question="What are the clinic hours?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 5px 10px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 500;">⏰ Clinic Hours</button>
            <button class="faq-chip" data-question="How to check health records?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 5px 10px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 500;">📋 Health Records</button>
            <button class="faq-chip" data-question="What to do during an emergency?" style="background: #fee2e2; color: #991b1b; border: none; padding: 5px 10px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 500;">🚨 Emergency</button>
            <button class="faq-chip" data-question="Do you accept walk-ins?" style="background: #e0f2fe; color: #0369a1; border: none; padding: 5px 10px; border-radius: 15px; font-size: 11px; cursor: pointer; font-weight: 500;">🚶 Walk-ins & Fees</button>
        </div>

        <!-- Chat Input Footer -->
        <div style="padding: 10px; background: #ffffff; border-top: 1px solid #edf2f7; display: flex; gap: 8px;">
            <input type="text" id="chatInput" placeholder="Type your question..." style="flex: 1; padding: 8px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 12px; font-family: 'Poppins', sans-serif;">
            <button id="chatSendBtn" style="background: #173F81; color: #ffffff; border: none; width: 35px; height: 35px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-paper-plane" style="font-size: 12px;"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Mobile Hamburger Menu Toggle & Clinic Status Checker
    document.addEventListener("DOMContentLoaded", function() {
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('appSidebar'); 
        const overlay = document.getElementById('sidebarOverlay');
        if (mobileBtn && sidebar && overlay) {
            mobileBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
        
        const badge = document.getElementById('clinicStatusBadge');
        if (badge) {
            const now = new Date();
            const day = now.getDay();
            const hour = now.getHours();
            let isOpen = false;
            if (day >= 1 && day <= 6) {
                if (hour >= 8 && hour < 18) isOpen = true;
            } else if (day === 0) {
                if (hour >= 9 && hour < 15) isOpen = true;
            }
            if (!isOpen) {
                badge.style.background = "#fee2e2";
                badge.style.color = "#991b1b";
                badge.innerHTML = '<span style="width: 6px; height: 6px; background: #ef4444; border-radius: 50%; display: inline-block;"></span> Closed';
            } else {
                badge.style.background = "#dcfce7";
                badge.style.color = "#166534";
                badge.innerHTML = '<span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> Open';
            }
        }

        // Floating Chatbot Logic
        const toggleBtn = document.getElementById("chatToggleBtn");
        const chatWindow = document.getElementById("chatWindow");
        const closeBtn = document.getElementById("chatCloseBtn");
        const sendBtn = document.getElementById("chatSendBtn");
        const chatInput = document.getElementById("chatInput");
        const chatMessages = document.getElementById("chatMessages");
        const faqChips = document.querySelectorAll(".faq-chip");

        toggleBtn.addEventListener("click", () => {
            const isOpen = chatWindow.style.display === "flex";
            chatWindow.style.display = isOpen ? "none" : "flex";
            toggleBtn.style.transform = isOpen ? "scale(1)" : "scale(1.05)";
        });

        closeBtn.addEventListener("click", () => {
            chatWindow.style.display = "none";
            toggleBtn.style.transform = "scale(1)";
        });

        function appendMessage(sender, text) {
            const msgDiv = document.createElement("div");
            if (sender === 'user') {
                msgDiv.style.cssText = "background: #173F81; color: #ffffff; padding: 10px 14px; border-radius: 12px; font-size: 12.5px; margin-left: auto; max-width: 85%; word-break: break-word;";
            } else {
                msgDiv.style.cssText = "background: #ffffff; color: #1e293b; padding: 10px 14px; border-radius: 12px; font-size: 12.5px; margin-right: auto; max-width: 85%; border: 1px solid #edf2f7; word-break: break-word;";
            }
            msgDiv.textContent = text;
            chatMessages.appendChild(msgDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function handleBotResponse(question) {
            let reply = "Pasensya na po, maaari kayong sumangguni sa ating Help & Support page para sa iba pang detalye o mag-message direkta sa klinika.";
            const q = question.toLowerCase();

            if (q.includes("book") || q.includes("appointment")) {
                reply = "To book an appointment, simply go to 'Book Appointment' in the left menu, then select the date, time, and service for your pet.";
            } else if (q.includes("oras") || q.includes("time") || q.includes("clinic") || q.includes("hours")) {
                reply = "Furry Friends Animal Clinic is open from Monday to Saturday, 8:00 AM - 6:00 PM, and on Sundays from 9:00 AM - 3:00 PM.";
            } else if (q.includes("health") || q.includes("records") || q.includes("medikal")) {
                reply = "You can view the complete medical records and history of your pets in the 'Health Records' or 'Records & History' section in the sidebar.";
            } else if (q.includes("emergency")) {
                reply = "For emergencies, no appointment is needed! Please rush directly to the clinic or call us immediately so we can assist your pet right away.";
            } else if (q.includes("walk-in") || q.includes("fee") || q.includes("price") || q.includes("magkano")|| q.includes("bayad"))  {
                reply = "Yes, walk-ins are accepted, but scheduled appointments are prioritized for regular checkups. Standard consultation fees range from ₱300 to ₱500.";
            }
            setTimeout(() => {
                appendMessage('bot', reply);
            }, 500);
        }

        sendBtn.addEventListener("click", () => {
            const text = chatInput.value.trim();
            if (text) {
                appendMessage('user', text);
                chatInput.value = "";
                handleBotResponse(text);
            }
        });

        chatInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                sendBtn.click();
            }
        });

        faqChips.forEach(chip => {
            chip.addEventListener("click", () => {
                const question = chip.getAttribute("data-question");
                appendMessage('user', question);
                handleBotResponse(question);
            });
        });
    });
</script>
<script type="module" src="../assets/js/dashboard.js"></script>
</body>
</html>