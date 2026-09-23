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

            /* 1. Stat Cards Grid & Compact Sizing */
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

            /* 2. Compact Welcome Card */
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

            /* 3. Compact Seasonal Reminder Card */
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

            /* 4. Ensure Bottom Sections Stack Properly (1-col) */
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
            <!-- Welcome Section na may Gradient at Paw Print Icon -->
            <div class="welcome-card" style="background: linear-gradient(135deg, #173F81 0%, #2563EB 50%, #38BDF8 100%); margin-bottom: 10px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; border-radius: 16px; padding: 25px 30px; box-shadow: 0 4px 15px rgba(23,63,129,0.2);">
                <div>
                    <h1 id="greeting" style="color: #ffffff; margin: 0 0 8px 0; font-size: 22px;">Good Morning! </h1>
                    <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 13.5px;">Welcome back to Smart Vet Care. Manage your pets, appointments, health records, and stay connected with your veterinarian.</p>
                </div>
                <div style="background: rgba(255, 255, 255, 0.15); width: 55px; height: 55px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #ffffff; flex-shrink: 0;">
                    <i class="fa-solid fa-paw"></i>
                </div>
            </div>

            <!-- Compact Clinic Operating Hours Widget (Updated with Vet/Medical Icon) -->
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

<script>
    // Mobile Hamburger Menu Toggle
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

        // Simple Real-Time Clinic Status Checker Script
        const badge = document.getElementById('clinicStatusBadge');
        if (badge) {
            const now = new Date();
            const day = now.getDay(); // 0 is Sunday, 6 is Saturday
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
    });
</script>
<script type="module" src="../assets/js/dashboard.js"></script>
</body>
</html>