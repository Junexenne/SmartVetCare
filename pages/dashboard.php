<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Vet Care | User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="dashboard">
    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">
        <?php include "../includes/topbar.php"; ?>

        <section class="dashboard-content">
            <!-- Welcome Section -->
            <div class="welcome-card">
                <h1 id="greeting">Good Morning! 👋</h1>
                <p>Welcome back to Smart Vet Care. Manage your pets, appointments, health records, and stay connected with your veterinarian.</p>
            </div>

            <!-- Statistics Grid -->
            <div class="dashboard-cards">
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
            <div class="upcoming-appointment-section">
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

<script type="module" src="../assets/js/dashboard.js"></script>
</body>
</html>