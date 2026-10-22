<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Our Veterinarians - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f7fe;
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
        .main-content {
            padding: 16px !important;
            box-sizing: border-box !important;
            background: #f4f7fe;
            min-height: 100vh;
        }
        .doctors-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 5px; /* Tamang espasyo o gap sa pagitan ng Header Box at Doctors Grid */
        }
        /* Puti at bilugang Card para sa Header */
        .profile-style-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #edf2f7;
            box-sizing: border-box;
        }
        .page-header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e1b4b;
            font-size: 22px;
            margin: 0 0 6px 0;
            font-weight: 700;
        }
        .page-header p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
            line-height: 1.4;
        }
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px; /* Gap sa pagitan ng mga doctor card */
        }
        /* Individual White Card para sa bawat Doktor katulad ng sa larawan */
        .doctor-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #edf2f7;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .doctor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(81, 66, 245, 0.08);
        }
        .doctor-img-container {
            width: 90px;
            height: 90px;
            background: #ffffff;
            border-radius: 50%;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 3px solid #e0e7ff;
            box-shadow: 0 4px 12px rgba(81, 66, 245, 0.12);
        }
        .doctor-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .doctor-info {
            text-align: center;
            width: 100%;
        }
        .doctor-info h3 {
            color: #1e1b4b;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .role-badge {
            color: #5142f5;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }
        /* Responsive Fixes para Mobile */
        @media (max-width: 768px) {
            .main-content {
                padding: 12px !important;
            }
            .profile-style-card {
                padding: 18px;
                border-radius: 18px;
            }
            .page-header h1 {
                font-size: 18px !important;
                gap: 8px !important;
            }
            .page-header p {
                font-size: 12px !important;
            }
            .doctor-card {
                padding: 24px 16px;
            }
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1001;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active { left: 0 !important; }
            .sidebar-overlay.active { display: block !important; }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("../includes/topbar.php"); ?>
        
        <div class="doctors-container">
            <!-- Header Card (Magkapareho ng margin sa health.php) -->
            <div class="profile-style-card page-header">
                <h1>
                    <i class="fa-solid fa-user-doctor" style="background: #eef4ff; padding: 10px; border-radius: 12px;color: #5142f5;"></i>
                    Our Veterinarians
                </h1>
                <p>Meet our resident veterinarians at Furry Friends Animal Clinic</p>
            </div>

            <!-- Doctors Grid Container (Naka-hiwalay ang bawat Doctor Card na may saktong Gap) -->
            <div class="doctors-grid">
                <!-- Doctor 1 -->
                <div class="doctor-card">
                    <div class="doctor-img-container">
                        <img src="../assets/images/adminPicture.png" alt="Dr. Alfie G. Tamesis">
                    </div>
                    <div class="doctor-info">
                        <h3>Dr. Alfie G. Tamesis</h3>
                        <span class="role-badge">Resident Veterinarian</span>
                    </div>
                </div>
                <!-- Doctor 2 -->
                <div class="doctor-card">
                    <div class="doctor-img-container">
                        <img src="../assets/images/adminPicture.png" alt="Dr. Edmond Batan">
                    </div>
                    <div class="doctor-info">
                        <h3>Dr. Edmond Batan</h3>
                        <span class="role-badge">Resident Veterinarian</span>
                    </div>
                </div>
                <!-- Doctor 3 -->
                <div class="doctor-card">
                    <div class="doctor-img-container">
                        <img src="../assets/images/adminPicture.png" alt="Dr. Jovanito Ellema">
                    </div>
                    <div class="doctor-info">
                        <h3>Dr. Jovanito Ellema</h3>
                        <span class="role-badge">Resident Veterinarian</span>
                    </div>
                </div>
                <!-- Doctor 4 -->
                <div class="doctor-card">
                    <div class="doctor-img-container">
                        <img src="../assets/images/adminPicture.png" alt="Dr. Jan Karlo Fanito">
                    </div>
                    <div class="doctor-info">
                        <h3>Dr. Jan Karlo Fanito</h3>
                        <span class="role-badge">Resident Veterinarian</span>
                    </div>
                </div>
                <!-- Doctor 5 -->
                <div class="doctor-card">
                    <div class="doctor-img-container">
                        <img src="../assets/images/adminPicture.png" alt="Dr. Senr Cruz">
                    </div>
                    <div class="doctor-info">
                        <h3>Dr. Senr Cruz</h3>
                        <span class="role-badge">Resident Veterinarian</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
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
</body>
</html>