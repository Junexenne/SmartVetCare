<?php
// settings.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --bg-color: #f8f9fc;
            --text-color: #5a5c69;
            --border-color: #e3e6f0;
        }
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
        .page-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        .page-header h1 {
            font-size: 24px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 6px 0;
            font-weight: 700;
        }
        .page-header p {
            margin: 0;
            color: #858796;
            font-size: 14px;
        }
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }
        @media (max-width: 992px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
        .settings-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            padding: 30px;
            border-top: 4px solid var(--primary-color);
        }
        .settings-card.support-card {
            border-top-color: #f6c23e;
        }
        .settings-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            font-size: 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            outline: none;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
        }
        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-warning {
            background-color: #f6c23e;
            color: #fff;
        }
        .btn-warning:hover { background-color: #dda20a; color: #fff; }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-primary:hover { background-color: #2e59d9; }
        .notification-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }
        .notification-item:last-of-type {
            border-bottom: none;
            margin-bottom: 15px;
        }
        .notification-info strong {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 3px;
        }
        .notification-info p {
            margin: 0;
            font-size: 12px;
            color: #858796;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: var(--success-color);
        }
        input:checked + .slider:before {
            transform: translateX(20px);
        }
        .support-box {
            background: #fffdf5;
            border: 1px dashed #f6c23e;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
            font-size: 13px;
            line-height: 1.5;
        }
        #toastNotification {
            position: fixed;
            top: 20px;
            right: 30px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.35);
            pointer-events: none;
        }
        #toastNotification.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .toast-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .toast-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content" style="background: #f4f7fe; min-height: 100vh; padding: 20px;">
        <?php include("../includes/topbar.php"); ?>
        <div class="settings-container" style="max-width: 1200px; margin: 0 auto;">
            <div class="page-header">
                <h1>
                    <i class="fa-solid fa-gear" style="background: #eef4ff; color: #4e73df; padding: 10px; border-radius: 12px;"></i>
                    Account Settings
                </h1>
                <p>Manage your account security and notification preferences.</p>
            </div>
            
            <div class="settings-grid">
                
                <!-- Notification Preferences Section -->
                <div class="settings-card">
                    <h3><i class="fa-solid fa-bell"></i> Notification Preferences</h3>
                    <p style="font-size: 13px; color: #858796; margin-bottom: 20px;">
                        Choose what updates you want to receive about your pets.
                    </p>
                    
                    <form id="preferencesForm">
                        <div class="notification-item">
                            <div class="notification-info">
                                <strong>Appointment Reminders</strong>
                                <p>Get notified 24 hours before your scheduled visit.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="prefAppointment" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notification-item">
                            <div class="notification-info">
                                <strong>Vaccination Due Alerts</strong>
                                <p>Receive SMS alerts when your pet is due for a vaccine.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="prefVaccination" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notification-item">
                            <div class="notification-info">
                                <strong>Promotions & Offers</strong>
                                <p>Occasional emails about Furry Friends Clinic discounts.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="prefPromotions">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <button type="submit" id="savePrefBtn" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="fa-solid fa-floppy-disk"></i> Save Preferences
                        </button>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="settings-card">
                    <h3><i class="fa-solid fa-lock"></i> Change Password</h3>
                    <p style="font-size: 13px; color: #858796; margin-bottom: 20px;">
                        Update your password securely using your current password or via email reset.
                    </p>
                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" id="currentPassword" class="form-control" placeholder="Enter current password" required>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" id="newPassword" class="form-control" placeholder="Enter new password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm new password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-key"></i> Update Password
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastNotification">
    <i id="toastIcon" class="fa-solid"></i>
    <span id="toastMessage"></span>
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

<script type="module">
    import { auth, db } from "../assets/js/firebase-config.js";
    import {
        collection,
        query,
        where,
        getDocs,
        doc,
        updateDoc
    } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
    import { 
        onAuthStateChanged, 
        EmailAuthProvider, 
        reauthenticateWithCredential, 
        updatePassword 
    } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

    const toast = document.getElementById("toastNotification");
    const toastMessage = document.getElementById("toastMessage");
    const toastIcon = document.getElementById("toastIcon");
    let currentUserDocId = null;

    onAuthStateChanged(auth, async (user) => {
        if (!user) return;
        try {
            const userQuery = query(collection(db, "users"), where("email", "==", user.email));
            const userSnap = await getDocs(userQuery);
            if (!userSnap.empty) {
                const userDoc = userSnap.docs[0];
                currentUserDocId = userDoc.id;
                const data = userDoc.data();
                
                if (data.preferences) {
                    document.getElementById("prefAppointment").checked = data.preferences.appointmentReminders ?? true;
                    document.getElementById("prefVaccination").checked = data.preferences.vaccinationAlerts ?? true;
                    document.getElementById("prefPromotions").checked = data.preferences.promotions ?? false;
                }
            }
        } catch (error) {
            console.error("Error loading preferences:", error);
        }
    });

    document.getElementById("preferencesForm").addEventListener("submit", async (e) => {
        e.preventDefault();
        if (!currentUserDocId) {
            showToast("User session not found.", "error");
            return;
        }
        const preferences = {
            appointmentReminders: document.getElementById("prefAppointment").checked,
            vaccinationAlerts: document.getElementById("prefVaccination").checked,
            promotions: document.getElementById("prefPromotions").checked
        };
        try {
            const userRef = doc(db, "users", currentUserDocId);
            await updateDoc(userRef, { preferences: preferences });
            showToast("Preferences successfully saved!", "success");
        } catch (error) {
            console.error("Error saving preferences:", error);
            showToast("Failed to save preferences.", "error");
        }
    });

    // Handle Password Change inside settings
    document.getElementById("changePasswordForm").addEventListener("submit", async (e) => {
        e.preventDefault();
        const currentPassword = document.getElementById("currentPassword").value;
        const newPassword = document.getElementById("newPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        if (newPassword !== confirmPassword) {
            showToast("New passwords do not match.", "error");
            return;
        }

        if (newPassword.length < 6) {
            showToast("Password must be at least 6 characters.", "error");
            return;
        }

        const user = auth.currentUser;
        if (!user) {
            showToast("No active session found.", "error");
            return;
        }

        try {
            const credential = EmailAuthProvider.credential(user.email, currentPassword);
            await reauthenticateWithCredential(user, credential);
            await updatePassword(user, newPassword);
            showToast("Password successfully updated!", "success");
            document.getElementById("changePasswordForm").reset();
        } catch (error) {
            console.error("Error updating password:", error);
            showToast(error.message || "Failed to update password.", "error");
        }
    });

    function showToast(msg, type) {
        if (!toast) return;
        toastMessage.textContent = msg;
        toast.className = '';
        toast.classList.add(type === "success" ? "toast-success" : "toast-error");
        toastIcon.className = type === "success" ? "fa-solid fa-circle-check" : "fa-solid fa-triangle-exclamation";
        toast.classList.add("show");
        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
    }
</script>
</body>
</html>