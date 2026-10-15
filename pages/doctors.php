<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Veterinarians - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
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
        .doctors-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }
        .doctor-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid #edf2f7;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .doctor-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(81, 66, 245, 0.08);
        }
        .doctor-img-placeholder {
            width: 90px;
            height: 90px;
            background: #eef4ff;
            border-radius: 50%;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid #c7d2fe;
            color: #5142f5;
            font-size: 38px;
        }
        .doctor-info {
            text-align: center;
            margin-bottom: 10px;
        }
        .doctor-info h3 {
            color: #1e1b4b;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .doctor-info p.role {
            color: #5142f5;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 0;
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
            }
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
            }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content" style="background: #f4f7fe; min-height: 100vh; padding: 20px;">
        <?php include("../includes/topbar.php"); ?>
        <div class="doctors-container">
            <div class="doctors-header" style="margin-bottom: 25px;">
                <h1 style="display: flex; align-items: center; gap: 10px; color: #1e1b4b; font-size: 24px; margin-bottom: 6px;">
                    <i class="fa-solid fa-user-doctor" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                    Our Veterinarians
                </h1>
                <p style="color: #64748b; font-size: 13px;">
                    Meet our resident veterinarians at Furry Friends Animal Clinic.
                </p>
            </div>
            <div class="doctors-grid">
                <!-- Doctor 1: Dr. Alfie G. Tamesis -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. Alfie G. Tamesis</h3>
                            <p class="role">Resident Veterinarian</p>
                        </div>
                    </div>
                </div>
                <!-- Doctor 2: Dr. Edmond Batan -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. Edmond Batan</h3>
                            <p class="role">Resident Veterinarian</p>
                        </div>
                    </div>
                </div>
                <!-- Doctor 3: Dr. Jovanito Ellema -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. Jovanito Ellema</h3>
                            <p class="role">Resident Veterinarian</p>
                        </div>
                    </div>
                </div>
                <!-- Doctor 4: Dr. Jan Karlo Fanito -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. Jan Karlo Fanito</h3>
                            <p class="role">Resident Veterinarian</p>
                        </div>
                    </div>
                </div>
                <!-- Doctor 5: Dr. Senr Cruz -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. Senr Cruz</h3>
                            <p class="role">Resident Veterinarian</p>
                        </div>
                    </div>
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
</body>
</html>