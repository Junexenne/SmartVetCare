<div class="topbar" style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 15px 30px; background: #ffffff; box-sizing: border-box;">
    
    <!-- Left: Sidebar Toggle Button & Search Bar with Icon -->
    <div style="display: flex; align-items: center; gap: 15px; flex: 1; max-width: 450px;">
     <!-- Search Bar -->
        <div class="top-search" style="flex: 1; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 14px;"></i>
            <input type="text" id="topSearchInput" placeholder="Search (Press Enter)..." style="width: 100%; padding: 8px 15px 8px 40px; border-radius: 20px; border: 1px solid #ddd; outline: none; font-size: 14px; font-family: 'Poppins', sans-serif; transition: all 0.3s ease;">
        </div>
    </div>

    <!-- Right: Bell & Profile -->
    <div class="top-icons" style="display: flex; align-items: center; gap: 20px;">
        
        <!-- Notification Wrapper -->
        <div class="notification-dropdown-wrapper" style="position: relative; display: inline-block;">
            <button class="notification-btn" id="notifBellBtn" style="background: #f0f0f0; border: none; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative;">
                <i class="fa-solid fa-bell" style="color: #333; font-size: 16px;"></i>
                <!-- Red Badge Counter -->
                <span id="notifBadge" style="position: absolute; top: -2px; right: -2px; background: red; color: white; font-size: 10px; padding: 2px 5px; border-radius: 50%; display: none;">0</span>
            </button>

            <!-- Dropdown Menu -->
            <div id="notifDropdown" style="display: none;">
                <div class="notif-header">
                 <h4>Notifications</h4>
                 <span id="markAllRead">Mark all as read</span>
                </div>
             <div id="notifListContainer">
            <!-- Dito papasok ang real-time items galing sa JS -->
             </div>
        </div>
        </div>

        <!-- User Profile -->
        <div class="user-profile" style="display: flex; align-items: center; gap: 10px;">
            <img src="/SmartVetCare/assets/images/default-user.png" class="profile-img" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            <div style="text-align: left;">
                <h4 style="margin: 0; font-size: 13px; color: #1e1e2d; font-weight: bold;">Pet Owner</h4>
                <span style="font-size: 11px; color: #28a745;">Online</span>
            </div>
        </div>

    </div>
</div>

<!-- 1. I-set ang currentUserId galing sa session -->
<script>
    const currentUserId = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'OWN-00004'; ?>";
</script>

<!-- 2. Smart Search, Auto-Collapse, & Hover-Expand Script -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sidebar = document.querySelector(".sidebar");
        let closeTimer = null;

        if (sidebar) {
            // Auto-collapse on load
            sidebar.classList.add("collapsed");

            // Kapag tinututukan ang sidebar (Hover)
            sidebar.addEventListener("mouseenter", () => {
                clearTimeout(closeTimer);
                sidebar.classList.remove("collapsed");
            });

            // Kapag inalis ang cursor (Mouse Leave) na may Delay
            sidebar.addEventListener("mouseleave", () => {
                clearTimeout(closeTimer);
                closeTimer = setTimeout(() => {
                    sidebar.classList.add("collapsed");
                }, 3000); 
            });
        }

        // Smart Search Functionality
        const searchInput = document.getElementById("topSearchInput");
        if (searchInput) {
            searchInput.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    const query = searchInput.value.trim().toLowerCase();
                    if (query !== "") {
                        let targetPage = "my-pets.php"; 

                        if (query.includes("appointment") || query.includes("book") || query.includes("schedule")) {
                            targetPage = "appointment.php"; 
                        } else if (query.includes("health") || query.includes("record") || query.includes("medical")) {
                            targetPage = "health.php"; 
                        } else if (query.includes("ai") || query.includes("assistant") || query.includes("chat")) {
                            targetPage = "ai-chat.php"; 
                        } else if (query.includes("message") || query.includes("inbox")) {
                            targetPage = "messages.php";
                        } else if (query.includes("profile") || query.includes("account")) {
                            targetPage = "profile.php";
                        } else {
                            targetPage = `my-pets.php?search=${encodeURIComponent(query)}`;
                        }

                        window.location.href = targetPage;
                    }
                }
            });
        }
    });
</script>

<script type="module" src="../assets/js/firebase-config.js"></script>
<script type="module" src="../assets/js/notification.js"></script>