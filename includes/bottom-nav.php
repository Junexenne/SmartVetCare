<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="mobile-bottom-nav" id="mobileBottomNav">
    <a href="dashboard.php" class="bottom-nav-item <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-house"></i>
        <span>Home</span>
    </a>
    <a href="my-pets.php" class="bottom-nav-item <?= ($currentPage == 'my-pets.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-paw"></i>
        <span>My Pets</span>
    </a>
    <a href="appointment.php" class="bottom-nav-item <?= ($currentPage == 'appointment.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-calendar-days"></i>
        <span>Book</span>
    </a>
    <a href="health.php" class="bottom-nav-item <?= ($currentPage == 'health.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-heart-pulse"></i>
        <span>Health</span>
    </a>
    <a href="records.php" class="bottom-nav-item <?= in_array($currentPage, ['records.php', 'trash.php']) ? 'active' : '' ?>">
        <i class="fa-solid fa-box-archive"></i>
        <span>Records</span>
    </a>
    <a href="ai-chat.php" class="bottom-nav-item <?= ($currentPage == 'ai-chat.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-robot"></i>
        <span>AI Care</span>
    </a>
</div>

<style>
    /* Naka-hide default sa desktop, lalabas lang kapag mobile view na */
    .mobile-bottom-nav {
        display: none;
    }

    @media screen and (max-width: 768px) {
        .mobile-bottom-nav {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: #ffffff;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
            border-top: 1px solid #edf2f7;
            z-index: 1040; /* Mas mababa kaysa sa sidebar para ma-cover ito kapag lumabas ang sidebar */
            justify-content: space-around;
            align-items: center;
            box-sizing: border-box;
            padding: 0 4px;
            transition: transform 0.3s ease;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #64748b;
            font-size: 10px;
            font-weight: 500;
            flex: 1;
            height: 100%;
            gap: 3px;
            transition: color 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }

        .bottom-nav-item i {
            font-size: 18px;
        }

        .bottom-nav-item.active {
            color: #4f46e5;
            font-weight: 600;
        }

        /* Class para mawawala ang bottom nav kapag aktibo ang sidebar */
        .mobile-bottom-nav.sidebar-opened {
            transform: translateY(100%);
        }

        /* Dinadagdagan ng padding sa ibaba ng main content para hindi matakpan ng bottom nav */
        .main-content {
            padding-bottom: 75px !important;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bottomNav = document.getElementById("mobileBottomNav");
        const sidebar = document.querySelector(".sidebar");
        const mobileMenuBtn = document.getElementById("mobileMenuBtn") || document.querySelector(".mobile-menu-btn");
        const overlay = document.getElementById("sidebarOverlay");

        function checkSidebarState() {
            if (sidebar && bottomNav) {
                if (sidebar.classList.contains("active")) {
                    bottomNav.classList.add("sidebar-opened");
                } else {
                    bottomNav.classList.remove("sidebar-opened");
                }
            }
        }

        if (mobileMenuBtn && sidebar) {
            mobileMenuBtn.addEventListener("click", () => {
                setTimeout(checkSidebarState, 50);
            });
        }

        if (overlay && sidebar) {
            overlay.addEventListener("click", () => {
                setTimeout(checkSidebarState, 50);
            });
        }
    });
</script>