<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="appSidebar">
    <!-- Logo at Text Section -->
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
        
        <!-- Records & History Link -->
        <li class="<?= in_array($currentPage, ['records.php', 'trash.php', 'archived-appointments.php', 'archived-pets.php']) ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/records.php">
                <i class="fa-solid fa-box-archive"></i>
                <span>Records & History</span>
            </a>
        </li>

        <!-- Help & Feedback Tab sa Pinakababa na may Divider -->
        <li class="sidebar-help-item <?= ($currentPage == 'help.php') ? 'active' : '' ?>">
            <a href="/SmartVetCare/pages/help.php">
                <i class="fa-solid fa-circle-question"></i>
                <span>Help & Feedback</span>
            </a>
        </li>
    </ul>
</div>

<style>
    /* Fixed height, no scrollbar, saktong sakop ang buong screen */
    .sidebar {
        overflow: hidden !important;
        display: flex;
        flex-direction: column;
        height: 100vh;
        box-sizing: border-box;
        padding: 10px 8px !important;
    }

    /* Logo Styling */
    .sidebar .logo {
        padding: 4px 4px 6px 4px !important;
        margin-bottom: 2px !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 4px;
    }
    .sidebar .logo img {
        width: 44px !important;
        height: 44px !important;
        object-fit: contain;
    }
    .sidebar .logo-text h2 {
        font-size: 13.5px !important;
        margin: 0 !important;
        font-weight: 600;
        white-space: nowrap;
    }
    .sidebar .logo-text p {
        font-size: 10.5px !important;
        margin: 0 !important;
        opacity: 0.8;
        white-space: nowrap;
    }

    /* Menu List na naka-stretch para sakupin ang natitirang espasyo sa baba */
    .sidebar .menu {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between; 
    }

    .sidebar .menu li a {
        padding: 10px 12px !important; /* Mas malaking padding para mapuno ang space nang maayos */
        font-size: 13px !important;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
        border-radius: 6px;
        transition: background 0.2s;
        white-space: nowrap;
    }

    /* Siguraduhing laging kita ang text */
    .sidebar .menu li a span {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-block !important;
        color: inherit !important;
    }

    .sidebar .menu li a i {
        font-size: 14px !important;
        margin-right: 12px !important;
        width: 18px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Active/Highlight State Design */
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

    /* Help & Feedback sa pinakababa kasama ang divider */
    .sidebar-help-item {
        margin-top: auto !important;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        padding-top: 6px;
    }

    /* Auto-collapse fixes para sa desktop */
    .sidebar.auto-collapsed {
        overflow: hidden !important;
    }
    .sidebar.auto-collapsed .logo-text,
    .sidebar.auto-collapsed .menu li a span {
        opacity: 0;
        pointer-events: none;
        visibility: hidden;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 10-Second Auto-Collapse Logic para sa Laptop/Desktop
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
        }, 10000); // 10 seconds
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