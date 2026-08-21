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
        body { background: #f5f7ff; font-family: 'Poppins', sans-serif; margin: 0; }
        .main-container { padding: 30px; max-width: 1000px; margin: 0 auto; }
        .record-card { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .pet-header { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        .pet-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background: #ddd; }
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .badge.stable { background: #e1f5fe; color: #0288d1; }
        .badge.urgent { background: #ffebee; color: #c62828; }
        .vitals-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; }
        .vital-box { background: #f8f9ff; padding: 15px; border-radius: 10px; border: 1px solid #dbe4ff; }
        .vital-box h4 { margin: 0 0 5px 0; font-size: 13px; color: #555; }
        .vital-box p { margin: 0; font-size: 18px; font-weight: 700; color: #173F81; }
    </style>
</head>
<body>
<div class="dashboard">

    <?php include("../includes/sidebar.php"); ?>

    <div class="main-content">

        <?php include("../includes/topbar.php"); ?>
        
    <div class="main-container">
        <h1>Health Records & Monitoring</h1>
        <p>Real-time monitoring and medical status from Furry Friends Animal Clinic.</p>

        <!-- Dito ilalagay ang dynamic data mula sa Firestore -->
        <div id="healthRecordsContainer">
            <p>Loading health records...</p>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Scripts -->
    <script src="../assets/js/toast.js"></script>
    <script type="module" src="../assets/js/health-monitoring.js"></script>
    
</body>
</html>