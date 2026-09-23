<div class="topbar-wrapper-main">
    <div class="topbar">
        
        <!-- Left: Burger Menu Button + Search Bar with Live Dropdown -->
        <div class="topbar-left-group">
            <button class="mobile-menu-btn" id="mobileMenuBtn" type="button" title="Toggle Menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="top-search-wrapper">
                <form action="" method="GET" class="top-search" onsubmit="return false;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" id="topSearchInput" placeholder="Search pets, appointments, health records..." autocomplete="off">
                </form>
                <!-- Live Search Results Dropdown Box -->
                <div id="liveSearchResults" class="live-search-dropdown" style="display: none;"></div>
            </div>
        </div>

        <!-- Right: Real-time Clock, Calendar Dropdown, Bell & Profile Dropdown -->
        <div class="top-icons">
            
            <!-- Real-time Clock Display -->
            <div id="liveClock" class="live-clock-badge">
                <i class="fa-regular fa-clock"></i>
                <span id="clockText">Loading time...</span>
            </div>

            <!-- Calendar Dropdown Wrapper -->
            <div class="calendar-dropdown-wrapper">
                <button class="topbar-icon-btn" id="calendarBtn" type="button">
                    <i class="fa-regular fa-calendar-days"></i>
                </button>

                <!-- Interactive Calendar & Upcoming Appointments Popup Box -->
                <div id="calendarDropdown" class="dropdown-box">
                    <div class="dropdown-header">
                        <h4><i class="fa-solid fa-calendar-check" style="margin-right: 8px;"></i> Calendar & Schedules</h4>
                        <span id="currentMonthYear"></span>
                    </div>

                    <!-- Mini Calendar Grid View -->
                    <div style="padding: 12px 15px; border-bottom: 1px solid #edf2f7;">
                        <div class="mini-calendar-weekdays">
                            <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                        </div>
                        <div id="miniCalendarDays" class="mini-calendar-grid">
                            <!-- JS generated days here -->
                        </div>
                    </div>

                    <!-- Upcoming Appointments Section -->
                    <div style="padding: 12px 15px;">
                        <p class="dropdown-section-title">Upcoming Appointments</p>
                        <div id="topbarAppointmentsList" class="dropdown-list-container">
                            <p class="empty-state-text">No upcoming appointments.</p>
                        </div>
                    </div>
                    <div class="dropdown-footer-link">
                        <a href="appointment.php">View Full Appointments &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Notification Wrapper -->
            <div class="notification-dropdown-wrapper">
                <button class="notification-btn" id="notifBellBtn" type="button">
                    <i class="fa-solid fa-bell"></i>
                    <span id="notifBadge">0</span>
                </button>
                
                <!-- Modern & Clean Notification Popup Box -->
                <div id="notifDropdown" class="dropdown-box notif-box">
                    <div class="notif-header">
                       <div style="display: flex; align-items: center; gap: 8px;">
                            <h4>Notifications</h4>
                            <span id="notifCountBadge">0</span>
                       </div>
                       <span id="markAllAsReadBtn">Mark all as read</span>
                    </div>

                    <div id="notifListContainer" class="dropdown-list-container notif-list">
                       <div class="notif-empty-state">
                            <i class="fa-regular fa-bell-slash"></i>
                            <p>No notifications yet</p>
                       </div>
                    </div>

                    <div class="dropdown-footer-text">
                        <span>Smart Vet Care System</span>
                    </div>
                </div>
            </div>

            <!-- User Profile Dropdown Menu -->
            <div class="profile-dropdown-container" id="profileDropdownContainer">
                <div class="user-profile" id="profileTrigger">
                    <img src="../assets/images/default-user.png" class="profile-img" id="topbarProfileImg" alt="Profile Image">
                    <div class="user-profile-info">
                        <h4 id="topbarUserName">Pet Owner</h4>
                        <span>Online</span>
                    </div>
                    <i class="fa-solid fa-chevron-down profile-dropdown-arrow"></i>
                </div>

                <!-- Profile Dropdown Box List -->
                <div class="dropdown-box profile-dropdown-box" id="profileDropdownMenu">
                    <a href="/SmartVetCare/pages/profile.php" class="profile-dropdown-item">
                        <i class="fa-solid fa-user"></i> My Profile
                    </a>
                    <a href="/SmartVetCare/pages/settings.php" class="profile-dropdown-item">
                        <i class="fa-solid fa-gear"></i> Settings
                    </a>
                    <div class="profile-dropdown-divider"></div>
                    <a href="#" id="topbarLogoutBtn" class="profile-dropdown-item logout-link-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

        </div>
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
            <button id="cancelLogoutBtn" class="btn-cancel" type="button">Cancel</button>
            <button id="confirmLogoutBtn" class="btn-confirm" type="button">Yes, Logout</button>
        </div>
    </div>
</div>

<style>
    /* =====================================
        TOPBAR RESPONSIVE & MODERN CSS STYLING
        ===================================== */
    .topbar-wrapper-main {
        width: 100%;
        box-sizing: border-box;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 12px 20px;
        background: #ffffff;
        box-sizing: border-box;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border-radius: 16px;
        border: 1px solid rgba(23, 63, 129, 0.05);
        position: relative;
        margin-bottom: 25px;
        gap: 15px;
    }

    .topbar-left-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        max-width: 420px;
    }

    .mobile-menu-btn {
        display: none;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        color: #173F81;
        font-size: 20px;
        cursor: pointer;
        padding: 4px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
    }

    .top-search-wrapper {
        display: flex;
        align-items: center;
        flex: 1;
        position: relative;
    }

    .top-search {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
        margin: 0;
    }

    .top-search i {
        position: absolute;
        left: 12px;
        color: #a0aec0;
        font-size: 13px;
        pointer-events: none;
    }

    .top-search input {
        width: 100%;
        height: 38px;
        padding: 0 12px 0 36px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #1e293b;
        outline: none;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.25s ease;
    }

    .top-search input:focus {
        border-color: #173F81;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(23, 63, 129, 0.1);
    }

    /* Live Search Dropdown Styles */
    .live-search-dropdown {
        position: absolute;
        top: 45px;
        left: 0;
        width: 100%;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: 1px solid #edf2f7;
        z-index: 1000;
        max-height: 250px;
        overflow-y: auto;
        font-family: 'Poppins', sans-serif;
    }

    .live-search-item {
        padding: 10px 15px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        transition: background 0.15s ease;
    }

    .live-search-item:last-child {
        border-bottom: none;
    }

    .live-search-item:hover {
        background: #f8fafc;
    }

    .live-search-item .item-title {
        font-size: 12.5px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .live-search-item .item-sub {
        font-size: 11px;
        color: #64748b;
        margin: 0;
    }

    .live-search-no-result {
        padding: 12px;
        text-align: center;
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
    }

    .top-icons {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .live-clock-badge {
        font-size: 12px;
        color: #4a5568;
        font-weight: 500;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 5px;
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 8px;
        border: 1px solid #edf2f7;
    }

    .calendar-dropdown-wrapper,
    .notification-dropdown-wrapper,
    .profile-dropdown-container {
        position: relative;
        display: inline-block;
    }

    .topbar-icon-btn,
    .notification-btn {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #475569;
        font-size: 14px;
    }

    .topbar-icon-btn:hover,
    .notification-btn:hover {
        background: #173F81;
        color: #ffffff;
        border-color: #173F81;
        transform: translateY(-1px);
    }

    #notifBadge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #e53e3e;
        color: white;
        font-size: 9px;
        padding: 2px 5px;
        border-radius: 50%;
        display: none;
        font-weight: 600;
    }

    .dropdown-box {
        display: none;
        position: absolute;
        right: 0;
        top: 45px;
        width: 280px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        border: 1px solid #edf2f7;
        z-index: 1000;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
    }

    .notif-box {
        width: 300px;
    }

    .profile-dropdown-box {
        width: 200px;
        padding: 6px 0;
    }

    .profile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        color: #4a5568;
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 500;
        transition: background 0.15s, color 0.15s;
    }

    .profile-dropdown-item i {
        font-size: 13px;
        color: #718096;
        width: 16px;
        text-align: center;
    }

    .profile-dropdown-item:hover {
        background: #f8fafc;
        color: #173F81;
    }

    .profile-dropdown-item:hover i {
        color: #173F81;
    }

    .profile-dropdown-divider {
        height: 1px;
        background: #edf2f7;
        margin: 4px 0;
    }

    .logout-link-item {
        color: #e53e3e !important;
    }

    .logout-link-item i {
        color: #e53e3e !important;
    }

    .logout-link-item:hover {
        background: #fff5f5 !important;
        color: #c53030 !important;
    }

    .profile-dropdown-arrow {
        font-size: 10px;
        color: #718096;
        margin-left: 4px;
        transition: transform 0.2s ease;
    }

    .user-profile.active .profile-dropdown-arrow {
        transform: rotate(180deg);
    }

    .dropdown-header {
        background: #173F81;
        color: white;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-header h4 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
    }

    .notif-header {
        background: #ffffff;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #edf2f7;
    }

    .notif-header h4 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: #1e1e2d;
    }

    #notifCountBadge {
        background: #edf2f7;
        color: #4a5568;
        font-size: 10px;
        padding: 1px 6px;
        border-radius: 10px;
        font-weight: 500;
    }

    #markAllAsReadBtn {
        font-size: 11px;
        color: #173F81;
        cursor: pointer;
        font-weight: 500;
    }

    .mini-calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-size: 10px;
        font-weight: 600;
        color: #a0aec0;
        margin-bottom: 4px;
    }

    .mini-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-size: 11px;
        gap: 2px;
    }

    .dropdown-section-title {
        margin: 0 0 6px 0;
        font-size: 10px;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .dropdown-list-container {
        max-height: 140px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .notif-list {
        max-height: 250px;
        background: #ffffff;
    }

    .empty-state-text {
        text-align: center;
        color: #a0aec0;
        font-size: 11px;
        margin: 8px 0;
    }

    .notif-empty-state {
        text-align: center;
        padding: 20px;
        color: #a0aec0;
    }

    .notif-empty-state i {
        font-size: 20px;
        margin-bottom: 6px;
        color: #cbd5e0;
    }

    .notif-empty-state p {
        margin: 0;
        font-size: 12px;
        font-weight: 500;
    }

    .dropdown-footer-link,
    .dropdown-footer-text {
        background: #f8f9fa;
        padding: 8px;
        text-align: center;
        border-top: 1px solid #edf2f7;
    }

    .dropdown-footer-link a {
        color: #173F81;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
    }

    .dropdown-footer-text span {
        font-size: 10px;
        color: #a0aec0;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
        padding-left: 8px;
        border-left: 1px solid #e2e8f0;
    }

    .profile-img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #173F81;
        box-shadow: 0 2px 6px rgba(23, 63, 129, 0.15);
    }

    .user-profile-info {
        text-align: left;
    }

    .user-profile-info h4 {
        margin: 0 0 1px 0;
        font-size: 12px;
        color: #1e1e2d;
        font-weight: 700;
    }

    .user-profile-info span {
        font-size: 10px;
        font-weight: 500;
        color: #28a745;
    }

    /* Custom Logout Modal CSS */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .custom-modal-box {
        background: #fff;
        padding: 25px;
        border-radius: 14px;
        width: 320px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        font-family: 'Poppins', sans-serif;
    }
    .custom-modal-icon {
        font-size: 35px;
        color: #e53e3e;
        margin-bottom: 10px;
    }
    .custom-modal-box h3 {
        margin: 0 0 8px 0;
        font-size: 16px;
        color: #1e1e2d;
    }
    .custom-modal-box p {
        margin: 0 0 20px 0;
        font-size: 12.5px;
        color: #718096;
    }
    .custom-modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    .btn-cancel, .btn-confirm {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
    }
    .btn-cancel {
        background: #edf2f7;
        color: #4a5568;
    }
    .btn-confirm {
        background: #e53e3e;
        color: #fff;
    }

    /* --- MOBILE RESPONSIVE MEDIA QUERY --- */
    @media screen and (max-width: 768px) {
        .topbar {
            padding: 8px 10px;
            gap: 8px;
            margin-bottom: 12px;
            border-radius: 12px;
        }

        .mobile-menu-btn {
            display: inline-flex;
        }

        .topbar-left-group {
            max-width: none;
            flex: 1;
        }

        .top-search input {
            height: 34px;
            font-size: 12px;
        }

        .live-clock-badge {
            display: none;
        }

        .top-icons {
            gap: 6px;
        }

        .topbar-icon-btn,
        .notification-btn {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .user-profile {
            border-left: none;
            padding-left: 0;
            background: #f8f9fa;
            padding: 2px;
            border-radius: 50%;
            border: none;
        }

        .user-profile-info,
        .profile-dropdown-arrow {
            display: none; 
        }

        .profile-img {
            width: 32px;
            height: 32px;
            border-width: 1.5px;
        }

        .dropdown-box {
            right: -20px !important;
            width: 250px !important;
        }

        .notif-box {
            right: -30px !important;
            width: 260px !important;
        }

        .profile-dropdown-box {
            right: 0 !important;
            width: 180px !important;
        }
    }
</style>

<!-- Firebase App & Firestore CDN SDKs -->
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { getFirestore, collection, getDocs } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

    const firebaseConfig = {
        apiKey: "AIzaSyBwHmTjg_rT-bU0NL1c71f5qkonf7H7eNM",
        authDomain: "furryfriendsanimalclinic-13da3.firebaseapp.com",
        projectId: "furryfriendsanimalclinic-13da3",
        storageBucket: "furryfriendsanimalclinic-13da3.firebasestorage.app",
        messagingSenderId: "214577366989",
        appId: "1:214577366989:web:fa96249b2a0321f3f52684",
        measurementId: "G-RZEKK3R3B2"
    };

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    document.addEventListener("DOMContentLoaded", () => {
        // Real-time Clock
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'short', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const clockEl = document.getElementById("clockText");
            if (clockEl) {
                clockEl.textContent = now.toLocaleDateString('en-US', options);
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Mobile Burger Button / Sidebar Toggle Handler
        const menuBtn = document.getElementById("menuBtn") || document.querySelector(".burger-btn") || document.querySelector(".menu-toggle");
        const sidebar = document.querySelector(".sidebar") || document.querySelector("aside");
        const sidebarOverlay = document.getElementById("sidebarOverlay") || document.querySelector(".sidebar-overlay");

        if (menuBtn && sidebar) {
            menuBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                sidebar.classList.toggle("active");
                if (sidebarOverlay) {
                    sidebarOverlay.style.display = sidebar.classList.contains("active") ? "block" : "none";
                }
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener("click", () => {
                if (sidebar) sidebar.classList.remove("active");
                sidebarOverlay.style.display = "none";
            });
        }

        // Dropdown Toggles (Calendar, Notification, Profile Menu)
        const calendarBtn = document.getElementById("calendarBtn");
        const calendarDropdown = document.getElementById("calendarDropdown");
        const notifBellBtn = document.getElementById("notifBellBtn");
        const notifDropdown = document.getElementById("notifDropdown");
        const profileTrigger = document.getElementById("profileTrigger");
        const profileDropdownMenu = document.getElementById("profileDropdownMenu");

        if (calendarBtn && calendarDropdown) {
            calendarBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                if (notifDropdown) notifDropdown.style.display = "none";
                if (profileDropdownMenu) profileDropdownMenu.style.display = "none";
                calendarDropdown.style.display = calendarDropdown.style.display === "block" ? "none" : "block";
            });
        }

        if (notifBellBtn && notifDropdown) {
            notifBellBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                if (calendarDropdown) calendarDropdown.style.display = "none";
                if (profileDropdownMenu) profileDropdownMenu.style.display = "none";
                notifDropdown.style.display = notifDropdown.style.display === "block" ? "none" : "block";
            });
        }

        if (profileTrigger && profileDropdownMenu) {
            profileTrigger.addEventListener("click", (e) => {
                e.stopPropagation();
                if (calendarDropdown) calendarDropdown.style.display = "none";
                if (notifDropdown) notifDropdown.style.display = "none";
                const isOpen = profileDropdownMenu.style.display === "block";
                profileDropdownMenu.style.display = isOpen ? "none" : "block";
                profileTrigger.classList.toggle("active", !isOpen);
            });
        }

        document.addEventListener("click", (e) => {
            if (calendarDropdown && !calendarDropdown.contains(e.target) && calendarBtn && !calendarBtn.contains(e.target)) {
                calendarDropdown.style.display = "none";
            }
            if (notifDropdown && !notifDropdown.contains(e.target) && notifBellBtn && !notifBellBtn.contains(e.target)) {
                notifDropdown.style.display = "none";
            }
            if (profileDropdownMenu && !profileDropdownMenu.contains(e.target) && profileTrigger && !profileTrigger.contains(e.target)) {
                profileDropdownMenu.style.display = "none";
                profileTrigger.classList.remove("active");
            }
        });

        // Live Search Bar Logic (Case-Insensitive & Multi-Field Query)
        const searchInput = document.getElementById("topSearchInput");
        const searchDropdown = document.getElementById("liveSearchResults");

        if (searchInput && searchDropdown) {
            let debounceTimer;

            searchInput.addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    searchDropdown.style.display = "none";
                    searchDropdown.innerHTML = "";
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    try {
                        let matchedResults = [];

                        // 1. Query 'pets' collection
                        try {
                            const petsSnapshot = await getDocs(collection(db, "pets"));
                            petsSnapshot.forEach((doc) => {
                                const data = doc.data();
                                const petName = (data.petName || data.name || "").toLowerCase();
                                const breed = (data.breed || "").toLowerCase();
                                const owner = (data.ownerName || "").toLowerCase();
                                
                                if (petName.includes(query) || breed.includes(query) || owner.includes(query)) {
                                    matchedResults.push({
                                        title: data.petName || data.name || "Unnamed Pet",
                                        description: `Breed: ${data.breed || 'N/A'} - Owner: ${data.ownerName || 'N/A'}`,
                                        type: "Pet",
                                        url: "my-pets.php"
                                    });
                                }
                            });
                        } catch (e) { console.log("Pets collection error or empty"); }

                        // 2. Query 'appointments' collection
                        try {
                            const apptSnapshot = await getDocs(collection(db, "appointments"));
                            apptSnapshot.forEach((doc) => {
                                const data = doc.data();
                                const service = (data.service || data.reason || "").toLowerCase();
                                const clientName = (data.clientName || data.ownerName || "").toLowerCase();
                                const petName = (data.petName || "").toLowerCase();
                                
                                if (service.includes(query) || clientName.includes(query) || petName.includes(query)) {
                                    matchedResults.push({
                                        title: `Appointment: ${data.service || data.reason || 'Checkup'}`,
                                        description: `Pet: ${data.petName || 'N/A'} | Date: ${data.date || 'N/A'}`,
                                        type: "Appointment",
                                        url: "appointment.php"
                                    });
                                }
                            });
                        } catch (e) { console.log("Appointments collection error or empty"); }

                        // 3. Query 'health_records' collection
                        try {
                            const healthSnapshot = await getDocs(collection(db, "health_records"));
                            healthSnapshot.forEach((doc) => {
                                const data = doc.data();
                                const petName = (data.petName || data.name || "").toLowerCase();
                                const healthInfo = (data.diagnosis || data.symptoms || data.chiefComplaint || data["Chief Complaint"] || "").toLowerCase();
                                
                                if (petName.includes(query) || healthInfo.includes(query)) {
                                    matchedResults.push({
                                        title: `Health Record: ${data.petName || data.name || 'Pet'}`,
                                        description: `Details: ${data.diagnosis || data.symptoms || data.chiefComplaint || data["Chief Complaint"] || 'General Checkup'}`,
                                        type: "Health",
                                        url: "health.php"
                                    });
                                }
                            });
                        } catch (e) { console.log("Health records collection error or empty"); }

                        // Render results
                        if (matchedResults.length > 0) {
                            let html = "";
                            matchedResults.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="live-search-item">
                                        <div>
                                            <p class="item-title">${item.title}</p>
                                            <p class="item-sub">${item.description}</p>
                                        </div>
                                        <span style="font-size: 10px; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: 600;">${item.type}</span>
                                    </a>
                                `;
                            });
                            searchDropdown.innerHTML = html;
                            searchDropdown.style.display = "block";
                        } else {
                            searchDropdown.innerHTML = `<p class="live-search-no-result">No results found for "${query}"</p>`;
                            searchDropdown.style.display = "block";
                        }
                    } catch (error) {
                        console.error("Firebase Search Error: ", error);
                    }
                }, 300);
            });

            document.addEventListener("click", function(e) {
                if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                    searchDropdown.style.display = "none";
                }
            });
        }

        // Logout Modal Logic
        const topbarLogoutBtn = document.getElementById("topbarLogoutBtn");
        const logoutModal = document.getElementById("logoutModal");
        const cancelLogoutBtn = document.getElementById("cancelLogoutBtn");
        const confirmLogoutBtn = document.getElementById("confirmLogoutBtn");

        if (topbarLogoutBtn) {
            topbarLogoutBtn.addEventListener("click", function(e) {
                e.preventDefault();
                if (profileDropdownMenu) profileDropdownMenu.style.display = "none";
                if (logoutModal) logoutModal.style.display = "flex";
            });
        }

        if (cancelLogoutBtn) {
            cancelLogoutBtn.addEventListener("click", function() {
                if (logoutModal) logoutModal.style.display = "none";
            });
        }

        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener("click", function() {
                window.location.href = "/SmartVetCare/auth/logout.php";
            });
        }

        // Mini Calendar generation
        const now = new Date();
        const monthYearEl = document.getElementById("currentMonthYear");
        if (monthYearEl) {
            monthYearEl.textContent = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        }

        const daysContainer = document.getElementById("miniCalendarDays");
        if (daysContainer) {
            const year = now.getFullYear();
            const month = now.getMonth();
            const firstDayIndex = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();
            const todayDate = now.getDate();

            let daysHtml = "";
            for (let i = 0; i < firstDayIndex; i++) {
                daysHtml += `<span></span>`;
            }
            for (let d = 1; d <= totalDays; d++) {
                const isToday = (d === todayDate);
                const style = isToday 
                    ? `background: #173F81; color: white; border-radius: 50%; font-weight: 600; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; margin: auto;` 
                    : `color: #4a5568; padding: 2px 0;`;
                daysHtml += `<span style="${style}">${d}</span>`;
            }
            daysContainer.innerHTML = daysHtml;
        }

        // Sync User Name from localStorage
        const savedFullName = localStorage.getItem("fullName");
        const topbarUserName = document.getElementById("topbarUserName");
        if (savedFullName && topbarUserName) {
            topbarUserName.textContent = savedFullName;
        }
    });
</script>