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
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
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
            background: #f1f5f9;
            border-radius: 50%;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 32px;
            border: 2px solid #e2e8f0;
        }

        .doctor-info {
            text-align: center;
            margin-bottom: 20px;
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
            margin-bottom: 16px;
        }

        .doctor-details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px;
            text-align: left;
            font-size: 13px;
            color: #475569;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }

        .doctor-details div {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .doctor-details i {
            color: #5142f5;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .book-doctor-btn {
            display: block;
            width: 100%;
            background: #163B7A;
            color: #fff;
            text-align: center;
            padding: 11px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(81, 66, 245, 0.15);
        }

        .book-doctor-btn:hover {
            background: #4333e6;
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
                    Meet our resident doctors, check their credentials, and view their clinic availability schedules.
                </p>
            </div>

            <div class="doctors-grid">

                <!-- Doctor 1 -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. Alfie Tamesis, DVM</h3>
                            <p class="role">Chief Veterinarian / Clinic Owner</p>
                        </div>
                        <div class="doctor-details">
                            <div>
                                <i class="fa-solid fa-graduation-cap"></i>
                                <span>Doctor of Veterinary Medicine, UP Los Baños</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-id-card"></i>
                                <span>License No: 12345</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-stethoscope"></i>
                                <span>Specialization:Internal Medicine & Surgery</span>
                            </div>
                            <div>
                                <i class="fa-regular fa-clock"></i>
                                <span><strong>Availability:</strong><br>Mon – Wed: 8:00 AM – 5:00 PM</span>
                            </div>
                        </div>
                    </div>
                    <a href="appointment.php?doctor=Dr.%20Alfie%20Tamesis" class="book-doctor-btn">
                        <i class="fa-solid fa-calendar-plus" style="margin-right: 6px;"></i> Book with Doc Alfie
                    </a>
                </div>

                <!-- Doctor 2 -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. James Nico Martinez</h3>
                            <p class="role">Associate Veterinarian</p>
                        </div>
                        <div class="doctor-details">
                            <div>
                                <i class="fa-solid fa-graduation-cap"></i>
                                <span>DVM, Central Luzon State University</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-id-card"></i>
                                <span>License No: 67890</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-stethoscope"></i>
                                <span>Specialization: Internal Medicine & Surgery </span>
                            </div>
                            <div>
                                <i class="fa-regular fa-clock"></i>
                                <span><strong>Availability:</strong><br>Thu – Fri: 9:00 AM – 4:00 PM</span>
                            </div>
                        </div>
                    </div>
                    <a href="appointment.php?doctor=Dr.%20James%20Nico%20Martinez" class="book-doctor-btn">
                        <i class="fa-solid fa-calendar-plus" style="margin-right: 6px;"></i> Book with Doc James
                    </a>
                </div>

                <!-- Doctor 3 -->
                <div class="doctor-card">
                    <div>
                        <div class="doctor-img-placeholder">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="doctor-info">
                            <h3>Dr. Crachzel Kyle Asistio</h3>
                            <p class="role">Emergency & Weekend Veterinarian</p>
                        </div>
                        <div class="doctor-details">
                            <div>
                                <i class="fa-solid fa-graduation-cap"></i>
                                <span>DVM, De La Salle Araneta University</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-id-card"></i>
                                <span>License No: 54321</span>
                            </div>
                            <div>
                                <i class="fa-solid fa-stethoscope"></i>
                                <span>Specialization: Emergency Care & Vaccinations</span>
                            </div>
                            <div>
                                <i class="fa-regular fa-clock"></i>
                                <span><strong>Availability:</strong><br>Saturday: 9:00 AM – 3:00 PM</span>
                            </div>
                        </div>
                    </div>
                    <a href="appointment.php?doctor=Dr.%20Crachzel%20Kyle%20Asistio" class="book-doctor-btn">
                        <i class="fa-solid fa-calendar-plus" style="margin-right: 6px;"></i> Book with Doc Crachzel
                    </a>
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