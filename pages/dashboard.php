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
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

    <div class="dashboard">

        <?php include "../includes/sidebar.php"; ?>

        <div class="main-content">

            <?php include "../includes/topbar.php"; ?>

            <section class="dashboard-content">

                <!-- Welcome -->

                <div class="welcome-card">

                    <div class="welcome-text">

                        <h1 id="greeting">Good Morning! 👋</h1>

                        <p>
                            Welcome back to Smart Vet Care.
                            Manage your pets, appointments,
                            health records, and stay connected
                            with your veterinarian.
                        </p>

                    </div>

                </div>

                <!-- Statistics -->

                <div class="stat-card">
                    <div style="background: #eef2ff; color: #5142f5; min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px;">
                        <i class="fa-solid fa-paw"></i>
                    </div>
                    <div>

                        <div class="card-info">

                            <h3>My Pets</h3>

                            <h2 id="totalPets">0</h2>

                            <span>Registered Pets</span>

                        </div>

                    </div>

                    <div class="stat-card">
                        <div style="background: #e6fffa; color: #319795; min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px;">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <span style="font-size: 12px; color: #718096; font-weight: 600; text-transform: uppercase;">Appointments</span>
                            <h3 id="appointmentCount" style="margin: 2px 0; font-size: 22px; color: #2d3748;">0</h3>
                            <p style="margin: 0; font-size: 12px; color: #a0aec0;">Upcoming Schedule</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div style="background: #fffaf0; color: #dd6b20; min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px;">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <span style="font-size: 12px; color: #718096; font-weight: 600; text-transform: uppercase;">Messages</span>
                            <h3 id="messageCount" style="margin: 2px 0; font-size: 22px; color: #2d3748;">0</h3>
                            <p style="margin: 0; font-size: 12px; color: #a0aec0;">Unread Messages</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div style="background: #f7fafc; color: #805ad5; min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px;">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div>
                            <span style="font-size: 12px; color: #718096; font-weight: 600; text-transform: uppercase;">AI Assistant</span>
                            <h3 style="margin: 2px 0; font-size: 22px; color: #2d3748;">Ready</h3>
                            <p style="margin: 0; font-size: 12px; color: #a0aec0;">Ask anything about pets</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom -->
                <div class="upcoming-appointment-section">
                    <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Appointment</h3>
                    <!-- Dito dapat pumasok ang JavaScript card -->
                    <div id="upcomingAppointmentContainer">
                        <p style="text-align: center; color: #888;">You don't have any upcoming appointments.</p>
                    </div>
                </div>

                <!-- Recent Activities Section -->
                <div class="recent-activities-section">
                    <h3><i class="fa-solid fa-clock-rotate-left" style="color: #173F81;"></i> Recent Activities</h3>
                    <div id="recentActivitiesContainer">
                        <p style="text-align: center; color: #888; padding: 20px; margin: 0;">No recent activities yet.</p>
                    </div>

                </div>

        </div>

        </section>

    </div>

    </div>

    <script type="module" src="../assets/js/dashboard.js"></script>
</body>

</html>