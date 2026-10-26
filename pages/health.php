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
    <title>Pet Health Records - Smart Vet Care</title>
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
        .health-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        /* Puti at bilugang Card gaya ng sa Profile Page */
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
        .record-card { 
            background: white; 
            border-radius: 16px; 
            padding: 20px; 
            box-shadow: 0 2px 12px rgba(0,0,0,0.02); 
            margin-bottom: 16px; 
            border: 1px solid #edf2f7; 
            box-sizing: border-box; 
            word-break: break-word; 
            overflow-wrap: break-word; 
            transition: transform 0.2s ease, box-shadow 0.2s ease; 
        }
        .record-card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(81, 66, 245, 0.06); 
        }
        .pet-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
        .pet-avatar { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; background: #ddd; flex-shrink: 0; }
        .badge { padding: 6px 14px; border-radius: 30px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; }
        .badge.stable { background: #e1f5fe; color: #0288d1; border: 1px solid #b3e5fc; }
        .badge.urgent { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .vitals-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 12px; }
        .vital-box { background: #f8fafc; padding: 14px 16px; border-radius: 12px; border: 1px solid #edf2f7; box-sizing: border-box; word-break: break-word; position: relative; }
        .vital-box h4 { margin: 0 0 6px 0; font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .vital-box p { margin: 0; font-size: 18px; font-weight: 700; color: #173F81; }
        #healthRecordsContainer {
            display: flex;
            flex-direction: column;
            gap: 16px;
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
            .record-card { padding: 14px; }
            .vitals-grid { grid-template-columns: 1fr; }
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
        
        <div class="health-container">
            <!-- Header Card (Gaya ng sa Profile Card) -->
            <div class="profile-style-card page-header">
                <h1>
                    <i class="fa-solid fa-heart-pulse" style="background: #eef4ff; padding: 10px; border-radius: 12px;color: #5142f5;"></i>
                    Health Records & Monitoring
                </h1>
                <p>Medical status and updates recorded during your clinic visits at Furry Friends Animal Clinic</p>
            </div>

        
            <div class="profile-style-card">
                <div id="healthRecordsContainer">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/bottom-nav.php"); ?>

<!-- Toast Container -->
<div id="toast-container"></div>

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
document.addEventListener("DOMContentLoaded", function() {
        const menuBtn = document.getElementById("mobileMenuBtn");
        const sidebar = document.querySelector(".sidebar");
        const overlay = document.getElementById("sidebarOverlay");

        if (menuBtn && sidebar) {
            menuBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    sidebar.style.left = "0px";
                    if (overlay) overlay.style.display = "block";
                } else {
                    sidebar.style.left = "-270px";
                    if (overlay) overlay.style.display = "none";
                }
            });
        }

        if (overlay && sidebar) {
            overlay.addEventListener("click", function() {
                sidebar.classList.remove("active");
                sidebar.style.left = "-270px";
                overlay.style.display = "none";
            });
        }
    });
</script>
<script src="../assets/js/toast.js"></script>
<script type="module" src="../assets/js/health-monitoring.js"></script>
    
</body>
</html>