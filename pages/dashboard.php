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
            <!-- Welcome Section (Ginawang mas dikit: margin-bottom: 10px) -->
            <div class="welcome-card" style="margin-bottom: 10px;">
                <h1 id="greeting">Good Morning! </h1>
                <p>Welcome back to Smart Vet Care. Manage your pets, appointments, health records, and stay connected with your veterinarian.</p>
            </div>

            <!-- Clinic Operating Hours Widget (Ginawang mas dikit: margin-bottom: 10px) -->
            <div style="background: #ffffff; border-radius: 14px; padding: 15px 20px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; border-left: 5px solid #5b21b6;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: #ede9fe; color: #5b21b6; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 14px; color: #1e293b; font-weight: 600;">Furry Friends Animal Clinic Hours</h4>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #64748b;">Monday – Saturday: 8:00 AM – 6:00 PM | Sunday: 9:00 AM – 3:00 PM</p>
                    </div>
                </div>
                <div id="clinicStatusBadge" style="background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                    <span style="width: 7px; height: 7px; background: #22c55e; border-radius: 50%; display: inline-block;"></span> Clinic is Open Today
                </div>
            </div>

            <!-- Seasonal Health Reminder Widget (Ginawang mas dikit: margin-bottom: 10px) -->
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
                    <div class="icon-box icon-ai"><i class="fa-solid fa-robot"></i></div>
                    <div class="card-info">
                        <h3>AI Assistant</h3>
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
    document.addEventListener("DOMContentLoaded", () => {
        const badge = document.getElementById('clinicStatusBadge');
        const now = new Date();
        const day = now.getDay(); // 0 is Sunday, 6 is Saturday
        const hour = now.getHours();

        // Mon-Sat: 8 to 18, Sun: 9 to 15
        let isOpen = false;
        if (day >= 1 && day <= 6) {
            if (hour >= 8 && hour < 18) isOpen = true;
        } else if (day === 0) {
            if (hour >= 9 && hour < 15) isOpen = true;
        }

        if (!isOpen) {
            badge.style.background = "#fee2e2";
            badge.style.color = "#991b1b";
            badge.innerHTML = '<span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: inline-block;"></span> Clinic is Closed Now';
        }
    });
</script>
<script type="module" src="../assets/js/dashboard.js"></script>
</body>
</html>