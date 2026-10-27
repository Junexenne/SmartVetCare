<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="appSidebar">
    <!-- Logo at Text Section -->
    <div style="text-align: center; margin-bottom: 10px; flex-shrink: 0;">
        <img src="../assets/images/logo-transparent.png" alt="Clinic Logo" style="width: 42px; height: 42px; object-fit: contain; filter: brightness(0) invert(1);">
        
        <div class="sidebar-brand-text">
            <h3 style="color: #ffffff; font-size: 11.5px; margin-top: 3px; margin-bottom: 1px;">Smart Vet Care</h3>
            <p style="color: #cbd5e1; font-size: 9px; margin: 0;">Pet Owner Portal</p>
        </div>
    </div>
    <ul class="menu">
        <li class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
            <a href="dashboard.php">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'my-pets.php') ? 'active' : '' ?>">
            <a href="my-pets.php">
                <i class="fa-solid fa-paw"></i>
                <span>My Pets</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'appointment.php') ? 'active' : '' ?>">
            <a href="appointment.php">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Book Appointment</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'health.php') ? 'active' : '' ?>">
            <a href="health.php">
                <i class="fa-solid fa-heart-pulse"></i>
                <span>Health Records</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'ai-chat.php') ? 'active' : '' ?>">
            <a href="ai-chat.php">
                <i class="fa-solid fa-gem"></i>
                <span>KuaGO</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'messages.php') ? 'active' : '' ?>">
            <a href="messages.php">
                <i class="fa-solid fa-comments"></i>
                <span>Messages</span>
            </a>
        </li>
         
        <!-- Our Doctors Tab -->
        <li class="<?= ($currentPage == 'doctors.php') ? 'active' : '' ?>">
            <a href="doctors.php">
                <i class="fa-solid fa-user-doctor"></i>
                <span>Our Doctors</span>
            </a>
        </li>
        <!-- Notification -->
        <li class="<?= ($currentPage == 'notifications.php') ? 'active' : ''; ?>">
            <a href="notifications.php">
                <i class="fa-solid fa-bell"></i>
                <span>Notifications</span>
            </a>
        </li>
        <!-- Records & History Link -->
        <li class="<?= in_array($currentPage, ['records.php', 'trash.php', 'archived-appointments.php', 'archived-pets.php']) ? 'active' : '' ?>">
            <a href="records.php">
                <i class="fa-solid fa-box-archive"></i>
                <span>Records & History</span>
            </a>
        </li>
        <!-- Help & Support Tab sa Pinakababa na may Divider -->
        <li class="sidebar-help-item <?= ($currentPage == 'help.php') ? 'active' : '' ?>">
            <a href="help.php">
                <i class="fa-solid fa-circle-question"></i>
                <span>Help & Support</span>
            </a>
        </li>
    </ul>
</div>

<style>
    .sidebar {
        overflow-y: auto !important; /* Ginawang scrollable para hindi maputol kapag maliit ang screen */
        overflow-x: hidden !important;
        display: flex;
        flex-direction: column;
        height: 100vh;
        box-sizing: border-box;
        padding: 8px 6px !important;
        transition: width 0.3s ease;
        z-index: 1050;
        scrollbar-width: none; /* Tinanggal ang scrollbar para malinis tingnan sa mobile */
    }
    .sidebar::-webkit-scrollbar {
        display: none;
    }
    .sidebar .logo {
        padding: 2px !important;
        margin-bottom: 2px !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 2px;
    }
    .sidebar .logo img {
        width: 36px !important;
        height: 36px !important;
        object-fit: contain;
    }
    .sidebar .logo-text h2 {
        font-size: 12px !important;
        margin: 0 !important;
        font-weight: 600;
        white-space: nowrap;
    }
    .sidebar .logo-text p {
        font-size: 11px !important;
        margin: 0 !important;
        opacity: 0.8;
        white-space: nowrap;
    }
    .sidebar .menu {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .sidebar .menu li a {
        padding: 6px 8px !important; /* Mas compact padding para magkasya lahat */
        font-size: 13px !important; 
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
        border-radius: 6px;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .sidebar .menu li a span {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-block !important;
        color: inherit !important;
    }
    .sidebar .menu li a i {
        font-size: 11.5px !important;
        margin-right: 8px !important;
        width: 14px;
        text-align: center;
        flex-shrink: 0;
    }
    .sidebar .menu li.active a,
    .sidebar .menu li a:active,
    .sidebar .menu li a:focus {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }
    .sidebar .menu li.active a span,
    .sidebar .menu li.active a i {
        color: #ffffff !important;
    }
    .sidebar-help-item {
        margin-top: auto !important; /* Sinisigurong laging nakadikit sa pinakababa */
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        padding-top: 4px;
    }
    .sidebar.auto-collapsed {
        overflow: hidden !important;
    }
    .sidebar.auto-collapsed .logo-text,
    .sidebar.auto-collapsed .menu li a span {
        opacity: 0 !important;
        pointer-events: none !important;
        visibility: hidden !important;
    }
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -260px;
            top: 0;
            width: 260px;
            height: 100vh;
            transition: left 0.3s ease;
            background-color: #4f46e5;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        .sidebar.active {
            left: 0;
        }
        .sidebar.auto-collapsed .logo-text,
        .sidebar.auto-collapsed .menu li a span {
            opacity: 1 !important;
            pointer-events: auto !important;
            visibility: visible !important;
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("appSidebar");
    let collapseTimer;
    const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
    if (isCollapsed && window.innerWidth > 768) {
        sidebar.classList.add("auto-collapsed");
        document.body.classList.add("sidebar-is-collapsed");
    }
    function triggerCollapseTimer() {
        clearTimeout(collapseTimer);
        collapseTimer = setTimeout(() => {
            if (window.innerWidth > 768) {
                sidebar.classList.add("auto-collapsed");
                document.body.classList.add("sidebar-is-collapsed");
                localStorage.setItem("sidebarCollapsed", "true");
            }
        }, 10000);
    }
    if (sidebar) {
        sidebar.addEventListener("mouseenter", function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove("auto-collapsed");
                document.body.classList.remove("sidebar-is-collapsed");
                localStorage.setItem("sidebarCollapsed", "false");
                clearTimeout(collapseTimer);
            }
        });
        sidebar.addEventListener("mouseleave", function() {
            if (window.innerWidth > 768) {
                triggerCollapseTimer();
            }
        });
    }
    if (!sidebar.classList.contains("auto-collapsed") && window.innerWidth > 768) {
        triggerCollapseTimer();
    }
});
</script>