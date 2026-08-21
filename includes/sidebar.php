<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="appSidebar">
    <div class="logo">
        <img src="/SmartVetCare/assets/images/logo.png" alt="Smart Vet Care Logo">
        <div class="logo-text">
            <h2>Smart Vet Care</h2>
            <p>Pet Owner Portal</p>
        </div>
    </div>

    <ul class="menu">
        <li class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/dashboard.php">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="<?= ($currentPage == 'my-pets.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/my-pets.php">
                <i class="fa-solid fa-paw"></i>
                <span>My Pets</span>
            </a>
        </li>

        <li class="<?= ($currentPage == 'appointment.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/appointment.php">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Book Appointment</span>
            </a>
        </li>

        <li class="<?= ($currentPage == 'health.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/health.php">
                <i class="fa-solid fa-heart-pulse"></i>
                <span>Health Records</span>
            </a>
        </li>

        <li class="<?= ($currentPage == 'ai-chat.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/ai-chat.php">
                <i class="fa-solid fa-robot"></i>
                <span>AI Assistant</span>
            </a>
        </li>

        <li class="<?= ($currentPage == 'messages.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/messages.php">
                <i class="fa-solid fa-comments"></i>
                <span>Messages</span>
            </a>
        </li>

        <li class="<?= ($currentPage == 'profile.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/profile.php">
                <i class="fa-solid fa-user"></i>
                <span>My Profile</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="#" id="logoutBtn" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-text">Logout</span>
        </a>
    </div>
</div>

<!-- Custom Logout Confirmation Modal -->
<div id="logoutModal" class="custom-modal-overlay" style="display: none;">
    <div class="custom-modal-box">
        <div class="custom-modal-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3>Log Out</h3>
        <p>Are you sure you want to log out of your account?</p>
        <div class="custom-modal-actions">
            <button id="cancelLogoutBtn" class="btn-cancel">Cancel</button>
            <button id="confirmLogoutBtn" class="btn-confirm">Yes, Logout</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Logout Modal Logic
    const logoutBtn = document.getElementById("logoutBtn");
    const logoutModal = document.getElementById("logoutModal");
    const cancelLogoutBtn = document.getElementById("cancelLogoutBtn");
    const confirmLogoutBtn = document.getElementById("confirmLogoutBtn");

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function(e) {
            e.preventDefault();
            logoutModal.style.display = "flex";
        });
    }

    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener("click", function() {
            logoutModal.style.display = "none";
        });
    }

    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener("click", function() {
            window.location.href = "/SmartVetCare/auth/logout.php";
        });
    }

    // Sidebar 10-Second Auto-Collapse Logic
    const sidebar = document.getElementById("appSidebar");
    let collapseTimer;

    function triggerCollapseTimer() {
        clearTimeout(collapseTimer);
        // Maghihintay ng 10 segundo (10000 ms) bago i-collapse kapag walang hover
        collapseTimer = setTimeout(() => {
            sidebar.classList.add("auto-collapsed");
        }, 10000);
    }

    // Kapag tinutukan ng mouse ang sidebar, alisin ang collapse at i-reset ang timer
    sidebar.addEventListener("mouseenter", function() {
        sidebar.classList.remove("auto-collapsed");
        clearTimeout(collapseTimer);
    });

    // Kapag inalis ang mouse sa sidebar, simulan na ang 10-second countdown
    sidebar.addEventListener("mouseleave", function() {
        triggerCollapseTimer();
    });

    // Simulan agad ang timer sa pag-load ng pahina
    triggerCollapseTimer();
});
</script>