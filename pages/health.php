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
    <title>Pet Health Records - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        .health-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e1b4b;
            font-size: 24px;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .page-header p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        .record-card { 
            background: white; 
            border-radius: 16px; 
            padding: 24px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
            margin-bottom: 20px; 
            border: 1px solid #edf2f7; 
            box-sizing: border-box; 
            word-break: break-word; 
            overflow-wrap: break-word; 
            transition: transform 0.2s ease, box-shadow 0.2s ease; 
        }
        .record-card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 10px 25px rgba(81, 66, 245, 0.08); 
        }
        .pet-header { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .pet-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background: #ddd; flex-shrink: 0; }
        .badge { padding: 6px 14px; border-radius: 30px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; }
        .badge.stable { background: #e1f5fe; color: #0288d1; border: 1px solid #b3e5fc; }
        .badge.urgent { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .vitals-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 15px; margin-top: 15px; }
        .vital-box { background: #f8fafc; padding: 18px 20px; border-radius: 12px; border: 1px solid #edf2f7; box-sizing: border-box; word-break: break-word; position: relative; }
        .vital-box h4 { margin: 0 0 8px 0; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .vital-box p { margin: 0; font-size: 20px; font-weight: 700; color: #173F81; }

        #healthRecordsContainer {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 21px !important;
                gap: 8px !important;
                white-space: nowrap !important;
            }
            .page-header h1 i {
                padding: 8px !important;
                font-size: 14px !important;
                border-radius: 10px !important;
            }
            .page-header h2 {
                font-size: 10px !important;
                gap: 8px !important;
                white-space: nowrap !important;
            }
            .record-card { padding: 15px; }
            .pet-header { gap: 12px; }
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

    <div class="main-content" style="background: #f4f7fe; min-height: 100vh; padding: 20px;">

        <?php include("../includes/topbar.php"); ?>
        
        <div class="health-container">
            <div class="page-header">
                <h1>
                    <i class="fa-solid fa-heart-pulse" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                    Health Records & Monitoring
                </h1>
                <p>Real-time monitoring and medical status from Furry Friends Animal Clinic</p>
            </div>

            <div id="healthRecordsContainer">
                <!-- Dito magloload ang dynamic health records mo -->
            </div>
        </div>
    </div>
</div>

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
</script>
<script src="../assets/js/toast.js"></script>
<script type="module" src="../assets/js/health-monitoring.js"></script>
    
</body>
</html>