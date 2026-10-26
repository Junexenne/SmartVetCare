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
        <!-- Right: Real-time Clock, Calendar Dropdown, Report Flag, Bell & Profile Dropdown -->
        <div class="top-icons">
            
            <!-- Real-time Clock Display -->
            <div id="liveClock" class="live-clock-badge">
                <i class="fa-regular fa-clock"></i>
                <span id="clockText">Loading time...</span>
            </div>
            <!-- Report a Problem Flag Button -->
            <button class="topbar-icon-btn report-flag-btn" type="button" onclick="openReportProblemModal()" title="Report a Problem">
                <i class="fa-solid fa-flag"></i>
            </button>
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
                       <span id="markAllAsReadBtn" style="cursor: pointer;">Mark all as read</span>
                    </div>
                    <div id="notifListContainer" class="dropdown-list-container notif-list">
                       <div class="notif-empty-state">
                            <i class="fa-regular fa-bell-slash"></i>
                            <p>No notifications yet</p>
                       </div>
                    </div>
                    <div class="dropdown-footer-link">
                        <a href="notifications.php">View All Notifications &rarr;</a>
                    </div>
                </div>
            </div>
            <!-- User Profile Dropdown Menu -->
            <div class="profile-dropdown-container" id="profileDropdownContainer">
                <div class="user-profile" id="profileTrigger">
                    <div class="profile-initial-avatar" id="topbarInitialAvatarContainer" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        <span id="topbarInitialAvatar">U</span>
                        <img id="topbarProfileImg" src="" alt="Profile" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    </div>
                    <div class="user-profile-info">
                        <h4 id="topbarUserName">Pet Owner</h4>
                        <span>Online</span>
                    </div>
                    <i class="fa-solid fa-chevron-down profile-dropdown-arrow"></i>
                </div>
                <!-- Profile Dropdown Box List -->
                <div class="dropdown-box profile-dropdown-box" id="profileDropdownMenu">
                    <a href="profile.php" class="profile-dropdown-item">
                        <i class="fa-solid fa-user"></i> My Profile
                    </a>
                    <a href="settings.php" class="profile-dropdown-item">
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
<!-- REPORT A PROBLEM MODAL (SMART VET CARE UI THEME) -->
<div id="reportProblemModal" class="report-modal-overlay" style="display: none;">
    <div class="report-modal-card">
        <button class="report-modal-close" id="closeReportModalBtn" type="button">&times;</button>
        
        <div class="report-header">
            <div class="report-icon-box">
                <i class="fa-solid fa-flag"></i>
            </div>
            <div>
                <h2>Report a problem</h2>
                <p>Something broken, confusing or missing? Tell us and we'll look into it.</p>
            </div>
        </div>
        <form id="reportProblemForm">
            <!-- What is it about? -->
            <div class="report-section">
                <label class="report-label">What is it about?</label>
                <div class="report-chips-grid">
                    <button type="button" class="report-chip active" data-category="Something isn't working">
                        <i class="fa-solid fa-bug"></i> Something isn't working
                    </button>
                    <button type="button" class="report-chip" data-category="Wrong or missing info">
                        <i class="fa-solid fa-circle-info"></i> Wrong or missing info
                    </button>
                    <button type="button" class="report-chip" data-category="Account & sign-in">
                        <i class="fa-solid fa-circle-user"></i> Account & sign-in
                    </button>
                    <button type="button" class="report-chip" data-category="Suggestion">
                        <i class="fa-solid fa-lightbulb"></i> Suggestion
                    </button>
                    <button type="button" class="report-chip" data-category="Other">
                        <i class="fa-solid fa-ellipsis"></i> Other
                    </button>
                </div>
                <input type="hidden" id="selectedCategory" value="Something isn't working">
            </div>
            <!-- What happened? -->
            <div class="report-section">
                <label class="report-label">What happened?</label>
                <div class="textarea-wrapper">
                    <textarea id="problemDescription" maxlength="1000" rows="4" placeholder="Example: The appointment didn't load after I tapped the book button." required></textarea>
                    <span class="char-count" id="charCount">0/1000</span>
                </div>
            </div>
            <!-- Email (Optional) -->
            <div class="report-section">
                <label class="report-label">Email <small>(optional, so we can reply)</small></label>
                <input type="email" id="userReportEmail" placeholder="you@example.com" class="report-input">
            </div>
            <!-- Submit Button -->
            <button type="submit" id="submitReportBtn" class="report-submit-btn">
                <i class="fa-solid fa-paper-plane"></i> Submit Report
            </button>
        </form>
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
<!-- SweetAlert2 CDN for Modern UI Alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
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
        position: relative;
    }
    .topbar-icon-btn:hover,
    .notification-btn:hover {
        background: #173F81;
        color: #ffffff;
        border-color: #173F81;
        transform: translateY(-1px);
    }
    .report-flag-btn:hover {
        background: #fee2e2 !important;
        color: #dc2626 !important;
        border-color: #fca5a5 !important;
    }
    #notifBadge {
        position: absolute;
        top: -3px;
        right: -3px;
        background: #e53e3e;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 50%;
        display: none;
        font-weight: 700;
        box-shadow: 0 2px 5px rgba(229, 62, 62, 0.4);
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
        width: 320px;
    }
    .notif-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        gap: 10px;
        align-items: flex-start;
        text-decoration: none;
        transition: background 0.15s ease;
        cursor: pointer;
    }
    .notif-item.unread {
        background: #f0f7ff;
    }
    .notif-item:hover {
        background: #f8fafc;
    }
    .notif-icon-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .notif-item.approved .notif-icon-circle {
        background: #dcfce7;
        color: #15803d;
    }
    .notif-item.rejected .notif-icon-circle {
        background: #fee2e2;
        color: #dc2626;
    }
    .notif-content {
        flex: 1;
    }
    .notif-title {
        font-size: 12px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 2px 0;
    }
    .notif-body {
        font-size: 11px;
        color: #64748b;
        margin: 0 0 4px 0;
        line-height: 1.3;
    }
    .notif-time {
        font-size: 9.5px;
        color: #94a3b8;
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
        max-height: 280px;
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
    .dropdown-footer-link {
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
    .user-profile {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
        padding-left: 8px;
        border-left: 1px solid #e2e8f0;
    }
    .profile-initial-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #173F81;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 600;
        border: 2px solid #173F81;
        box-shadow: 0 2px 6px rgba(23, 63, 129, 0.15);
        flex-shrink: 0;
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
    .report-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 15px;
        box-sizing: border-box;
    }
    .report-modal-card {
        background: #ffffff;
        color: #1e293b;
        width: 100%;
        max-width: 480px;
        border-radius: 18px;
        padding: 24px;
        position: relative;
        box-shadow: 0 20px 40px rgba(23, 63, 129, 0.12);
        border: 1px solid #e2e8f0;
        font-family: 'Poppins', sans-serif;
        max-height: 90vh;
        overflow-y: auto;
    }
    .report-modal-close {
        position: absolute;
        top: 18px;
        right: 18px;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .report-modal-close:hover {
        background: #fee2e2;
        color: #dc2626;
    }
    .report-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
    }
    .report-icon-box {
        background: #e0edff;
        color: #173F81;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .report-header h2 {
        font-size: 18px;
        margin: 0 0 4px 0;
        font-weight: 700;
        color: #173F81;
    }
    .report-header p {
        font-size: 11.5px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }
    .report-section {
        margin-bottom: 16px;
    }
    .report-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }
    .report-chips-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .report-chip {
        background: #f8fafc;
        color: #475569;
        border: 1.5px solid #e2e8f0;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        font-family: 'Poppins', sans-serif;
    }
    .report-chip:hover {
        border-color: #173F81;
        color: #173F81;
        background: #f1f5f9;
    }
    .report-chip.active {
        background: #173F81;
        border-color: #173F81;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(23, 63, 129, 0.2);
    }
    .textarea-wrapper {
        position: relative;
    }
    textarea#problemDescription {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        color: #1e293b;
        font-size: 12px;
        outline: none;
        box-sizing: border-box;
        resize: none;
        font-family: 'Poppins', sans-serif;
    }
    textarea#problemDescription:focus {
        border-color: #173F81;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(23, 63, 129, 0.1);
    }
    .char-count {
        position: absolute;
        bottom: 8px;
        right: 12px;
        font-size: 10px;
        color: #94a3b8;
    }
    .report-input {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        color: #1e293b;
        font-size: 12px;
        outline: none;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }
    .report-input:focus {
        border-color: #173F81;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(23, 63, 129, 0.1);
    }
    .report-submit-btn {
        width: 100%;
        background: #173F81;
        color: #ffffff;
        border: none;
        padding: 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 10px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(23, 63, 129, 0.2);
    }
    .report-submit-btn:hover {
        background: #0f2b5a;
        transform: translateY(-1px);
    }
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
        .profile-initial-avatar {
            width: 32px;
            height: 32px;
            font-size: 13px;
            border-width: 1.5px;
        }
        .dropdown-box {
            right: -20px !important;
            width: 250px !important;
        }
        .notif-box {
            right: -30px !important;
            width: 280px !important;
        }
        .profile-dropdown-box {
            right: 0 !important;
            width: 180px !important;
        }
    }
</style>
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { 
        getFirestore, 
        collection, 
        query, 
        where, 
        onSnapshot, 
        getDocs, 
        doc, 
        updateDoc,
        addDoc,
        serverTimestamp 
    } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
    
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
    window.openReportProblemModal = () => {
        const modal = document.getElementById("reportProblemModal");
        if (modal) modal.style.display = "flex";
    };

    // UNIVERSAL SIDEBAR BURGER TOGGLE FUNCTION
    function initSidebarToggle() {
        const menuBtn = document.getElementById("mobileMenuBtn") || 
                        document.getElementById("menuBtn") || 
                        document.querySelector(".mobile-menu-btn") || 
                        document.querySelector(".burger-btn") || 
                        document.querySelector(".menu-toggle");
        
        const sidebar = document.querySelector(".sidebar") || 
                        document.querySelector("aside") || 
                        document.querySelector(".sidebar-wrapper");
        
        const sidebarOverlay = document.getElementById("sidebarOverlay") || 
                               document.querySelector(".sidebar-overlay");

        if (menuBtn && sidebar) {
            menuBtn.onclick = (e) => {
                e.stopPropagation();
                sidebar.classList.toggle("active");
                if (sidebarOverlay) {
                    sidebarOverlay.style.display = sidebar.classList.contains("active") ? "block" : "none";
                }
            };
        }

        if (sidebarOverlay && sidebar) {
            sidebarOverlay.onclick = () => {
                sidebar.classList.remove("active");
                sidebarOverlay.style.display = "none";
            };
        }
    }

    document.addEventListener("DOMContentLoaded", async () => {
        // Run universal toggle setup immediately
        initSidebarToggle();

        const reportModal = document.getElementById("reportProblemModal");
        const closeReportBtn = document.getElementById("closeReportModalBtn");
        const chips = document.querySelectorAll(".report-chip");
        const categoryInput = document.getElementById("selectedCategory");
        const textarea = document.getElementById("problemDescription");
        const charCount = document.getElementById("charCount");
        const reportForm = document.getElementById("reportProblemForm");

        if (closeReportBtn) {
            closeReportBtn.addEventListener("click", () => {
                if (reportModal) reportModal.style.display = "none";
            });
        }
        chips.forEach(chip => {
            chip.addEventListener("click", () => {
                chips.forEach(c => c.classList.remove("active"));
                chip.classList.add("active");
                if (categoryInput) {
                    categoryInput.value = chip.getAttribute("data-category");
                }
            });
        });
        if (textarea && charCount) {
            textarea.addEventListener("input", () => {
                charCount.textContent = `${textarea.value.length}/1000`;
            });
        }
        if (reportForm) {
            reportForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const category = categoryInput.value;
                const description = textarea.value.trim();
                const email = document.getElementById("userReportEmail").value.trim();
                const currentOwnerId = localStorage.getItem("ownerId") || localStorage.getItem("userUID") || "ANONYMOUS";
                const submitBtn = document.getElementById("submitReportBtn");
                const originalBtnText = submitBtn.innerHTML;
                try {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Submitting...`;
                    await addDoc(collection(db, "system_reports"), {
                        userId: currentOwnerId,
                        category: category,
                        description: description,
                        email: email || "Not provided",
                        status: "Pending",
                        createdAt: serverTimestamp()
                    });
                    Swal.fire({
                        title: 'Report Submitted!',
                        text: 'Thank you for helping us improve Smart Vet Care.',
                        icon: 'success',
                        confirmButtonColor: '#173F81',
                        confirmButtonText: 'OK'
                    });
                    reportForm.reset();
                    charCount.textContent = "0/1000";
                    if (reportModal) reportModal.style.display = "none";
                } catch (error) {
                    console.error("Error submitting problem report:", error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to submit report. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#e53e3e'
                    });
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        }
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
            const reportModalCard = document.querySelector(".report-modal-card");
            if (reportModal && reportModal.style.display === "flex" && reportModalCard && !reportModalCard.contains(e.target) && !e.target.closest('.report-flag-btn')) {
                reportModal.style.display = "none";
            }
        });
        const searchInput = document.getElementById("topSearchInput");
        const searchDropdown = document.getElementById("liveSearchResults");
        if (searchInput && searchDropdown) {
            let debounceTimer;
            searchInput.addEventListener("input", function() {
                const queryVal = this.value.trim().toLowerCase();
                clearTimeout(debounceTimer);
                if (queryVal.length < 2) {
                    searchDropdown.style.display = "none";
                    searchDropdown.innerHTML = "";
                    return;
                }
                debounceTimer = setTimeout(async () => {
                    try {
                        let matchedResults = [];
                        try {
                            const petsSnapshot = await getDocs(collection(db, "pets"));
                            petsSnapshot.forEach((docSnap) => {
                                const data = docSnap.data();
                                const petName = (data.petName || data.name || "").toLowerCase();
                                const breed = (data.breed || "").toLowerCase();
                                const owner = (data.ownerName || "").toLowerCase();
                                if (petName.includes(queryVal) || breed.includes(queryVal) || owner.includes(queryVal)) {
                                    matchedResults.push({
                                        title: data.petName || data.name || "Unnamed Pet",
                                        description: `Breed: ${data.breed || 'N/A'} - Owner: ${data.ownerName || 'N/A'}`,
                                        type: "Pet",
                                        url: "my-pets.php"
                                    });
                                }
                            });
                        } catch (e) {}
                        try {
                            const apptSnapshot = await getDocs(collection(db, "appointments"));
                            apptSnapshot.forEach((docSnap) => {
                                const data = docSnap.data();
                                const service = (data.service || data.reason || "").toLowerCase();
                                const clientName = (data.clientName || data.ownerName || "").toLowerCase();
                                const petName = (data.petName || "").toLowerCase();
                                if (service.includes(queryVal) || clientName.includes(queryVal) || petName.includes(queryVal)) {
                                    matchedResults.push({
                                        title: `Appointment: ${data.service || data.reason || 'Checkup'}`,
                                        description: `Pet: ${data.petName || 'N/A'} | Date: ${data.date || 'N/A'}`,
                                        type: "Appointment",
                                        url: "appointment.php"
                                    });
                                }
                            });
                        } catch (e) {}
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
                            searchDropdown.innerHTML = `<p class="live-search-no-result">No results found for "${queryVal}"</p>`;
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
                window.location.href = "../auth/logout.php";
            });
        }
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
        const currentUserId = localStorage.getItem("ownerId") || localStorage.getItem("userUID");
        const userEmail = localStorage.getItem("userEmail");
        const savedFullName = localStorage.getItem("fullName");
        const topbarUserName = document.getElementById("topbarUserName");
        const topbarInitialAvatar = document.getElementById("topbarInitialAvatar");
        const topbarProfileImg = document.getElementById("topbarProfileImg");
        if (savedFullName) {
            if (topbarUserName) topbarUserName.textContent = savedFullName;
        }
        function setTopbarAvatar(fullName, gender) {
            const normalizedGender = (gender || "").toLowerCase();
            let displayImage = "";
            if (normalizedGender === "male") {
                displayImage = "../assets/images/male-owner.png";
            } else if (normalizedGender === "female") {
                displayImage = "../assets/images/female-owner.png";
            }
            if (displayImage !== "") {
                if (topbarProfileImg) {
                    topbarProfileImg.src = displayImage;
                    topbarProfileImg.style.display = 'block';
                }
                if (topbarInitialAvatar) topbarInitialAvatar.style.display = 'none';
            } else {
                if (topbarProfileImg) topbarProfileImg.style.display = 'none';
                if (topbarInitialAvatar) {
                    topbarInitialAvatar.textContent = fullName ? fullName.charAt(0).toUpperCase() : "U";
                    topbarInitialAvatar.style.display = 'block';
                }
            }
        }
        setTopbarAvatar(savedFullName, localStorage.getItem("gender"));
        let currentOwnerId = currentUserId;
        if (currentUserId) {
            try {
                let q = query(collection(db, "users"), where("ownerId", "==", currentUserId));
                let snapshot = await getDocs(q);
                if (snapshot.empty && userEmail) {
                    q = query(collection(db, "users"), where("email", "==", userEmail));
                    snapshot = await getDocs(q);
                }
                if (!snapshot.empty) {
                    const userData = snapshot.docs[0].data();
                    currentOwnerId = userData.ownerId || currentUserId;
                    if (userData.fullName && topbarUserName) {
                        topbarUserName.textContent = userData.fullName;
                        localStorage.setItem("fullName", userData.fullName);
                    }
                    if (userData.gender) {
                        localStorage.setItem("gender", userData.gender);
                    }
                    setTopbarAvatar(userData.fullName || savedFullName, userData.gender);
                }
            } catch (err) {
                console.error("Error fetching topbar user profile:", err);
            }
        }
        
        // NOTIFICATION SYSTEM
        if (currentOwnerId) {
            const notifBadge = document.getElementById("notifBadge");
            const notifCountBadge = document.getElementById("notifCountBadge");
            const notifListContainer = document.getElementById("notifListContainer");
            let activeNotifications = [];
            
            function renderAllNotifications() {
                activeNotifications.sort((a, b) => {
                    const timeA = a.createdAt?.toDate ? a.createdAt.toDate() : new Date(a.createdAt || 0);
                    const timeB = b.createdAt?.toDate ? b.createdAt.toDate() : new Date(b.createdAt || 0);
                    return timeB - timeA;
                });
                let unreadCount = activeNotifications.filter(n => !n.read).length;
                if (notifBadge) {
                    if (unreadCount > 0) {
                        notifBadge.textContent = unreadCount;
                        notifBadge.style.display = "block";
                    } else {
                        notifBadge.style.display = "none";
                    }
                }
                if (notifCountBadge) {
                    notifCountBadge.textContent = unreadCount;
                }
                if (notifListContainer) {
                    if (activeNotifications.length === 0) {
                        notifListContainer.innerHTML = `
                            <div class="notif-empty-state">
                                <i class="fa-regular fa-bell-slash"></i>
                                <p>No notifications yet</p>
                            </div>
                        `;
                        return;
                    }
                    let html = "";
                    activeNotifications.slice(0, 5).forEach(item => {
                        const isUnread = !item.read ? "unread" : "";
                        const type = (item.type || item.sourceType || "").toLowerCase();
                        
                        let iconClass = "fa-bell";
                        let itemClass = "";
                        
                        if (type.includes("appointment") || type.includes("confirmed") || type.includes("approved")) {
                            iconClass = "fa-calendar-check";
                            itemClass = "approved";
                        } else if (type.includes("rejected") || type.includes("rejection") || type.includes("cancelled")) {
                            iconClass = "fa-triangle-exclamation";
                            itemClass = "rejected";
                        } else if (type.includes("vaccine") || type.includes("vaccination")) {
                            iconClass = "fa-syringe";
                        } else if (type.includes("medication")) {
                            iconClass = "fa-pills";
                        } else if (type.includes("announcement")) {
                            iconClass = "fa-bullhorn";
                        }
                        let timeAgo = "Just now";
                        if (item.createdAt) {
                            const date = item.createdAt.toDate ? item.createdAt.toDate() : new Date(item.createdAt);
                            timeAgo = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        }
                        html += `
                            <a href="${item.link || 'notifications.php'}" class="notif-item ${isUnread} ${itemClass}" data-id="${item.id}" data-type="${item.sourceType || 'notification'}">
                                <div class="notif-icon-circle">
                                    <i class="fa-solid ${iconClass}"></i>
                                </div>
                                <div class="notif-content">
                                    <p class="notif-title">${item.title || "Clinic Notification"}</p>
                                    <p class="notif-body">${item.message || item.description || ""}</p>
                                    <span class="notif-time">${timeAgo}</span>
                                </div>
                            </a>
                        `;
                    });
                    notifListContainer.innerHTML = html;
                    document.querySelectorAll(".notif-item").forEach(itemEl => {
                        itemEl.addEventListener("click", async () => {
                            const notifId = itemEl.getAttribute("data-id");
                            const sourceType = itemEl.getAttribute("data-type");
                            if (notifId && sourceType === "notification") {
                                try {
                                    await updateDoc(doc(db, "notifications", notifId), { read: true });
                                } catch (err) {
                                    console.error("Error updating notification status:", err);
                                }
                            }
                        });
                    });
                }
            }
            const notifQuery = query(collection(db, "notifications"), where("ownerId", "==", currentOwnerId));
            onSnapshot(notifQuery, (snapshot) => {
                activeNotifications = activeNotifications.filter(n => n.sourceType !== "notification");
                snapshot.forEach((docSnap) => {
                    const data = docSnap.data();
                    activeNotifications.push({ 
                        id: docSnap.id, 
                        sourceType: "notification",
                        ...data 
                    });
                });
                renderAllNotifications();
            });
            const apptQuery = query(collection(db, "appointments_v2"), where("ownerId", "==", currentOwnerId));
            onSnapshot(apptQuery, (snapshot) => {
                activeNotifications = activeNotifications.filter(n => n.sourceType !== "appointment");
                let upcoming = [];
                snapshot.forEach((docSnap) => {
                    const data = docSnap.data();
                    const status = (data.status || "").toLowerCase();
                    
                    if (["confirmed", "approved", "in progress", "completed"].includes(status)) {
                        activeNotifications.push({
                            id: "appt_" + docSnap.id,
                            sourceType: "appointment",
                            type: "appointment",
                            title: `Appointment ${status.charAt(0).toUpperCase() + status.slice(1)}!`,
                            message: `Your appointment for ${data.service || 'checkup'} (Pet: ${data.petName || 'N/A'}) is scheduled on ${data.date || ''}.`,
                            read: false,
                            createdAt: data.createdAt || new Date(),
                            link: 'appointment.php'
                        });
                    } else if (["rejected", "declined", "cancelled"].includes(status)) {
                        activeNotifications.push({
                            id: "appt_rej_" + docSnap.id,
                            sourceType: "appointment",
                            type: "rejected",
                            title: `Appointment Request ${status.charAt(0).toUpperCase() + status.slice(1)}`,
                            message: `Sorry, your request for ${data.service || 'checkup'} on ${data.date || ''} could not be accommodated.`,
                            read: false,
                            createdAt: data.createdAt || new Date(),
                            link: 'appointment.php'
                        });
                    }
                    if (status !== "cancelled" && status !== "rejected" && status !== "declined" && !data.isArchived) {
                        upcoming.push({ id: docSnap.id, ...data });
                    }
                });
                renderAllNotifications();
                if (topbarAppointmentsList) {
                    if (upcoming.length === 0) {
                        topbarAppointmentsList.innerHTML = `<p class="empty-state-text">No upcoming appointments.</p>`;
                    } else {
                        let html = "";
                        upcoming.slice(0, 3).forEach(item => {
                            html += `
                                <div style="padding: 8px 10px; background: #f8fafc; border-radius: 8px; border: 1.5px solid #e2e8f0; font-size: 11px; margin-bottom: 4px;">
                                    <strong style="color: #1e1b4b; display: block;">${item.service || "Appointment"}</strong>
                                    <span style="color: #64748b;"><i class="fa-solid fa-paw"></i> ${item.petName || "Pet"} | ${item.date || ""} (${item.timeSlot || ""})</span>
                                </div>
                            `;
                        });
                        topbarAppointmentsList.innerHTML = html;
                    }
                }
            });
            const petsQuery = query(collection(db, "pets"), where("ownerId", "==", currentOwnerId));
            onSnapshot(petsQuery, async (petsSnap) => {
                let petIds = [];
                petsSnap.forEach(p => {
                    const d = p.data();
                    if (d.petId) petIds.push(d.petId);
                });
                if (petIds.length > 0) {
                    getDocs(collection(db, "vaccination_records")).then(vSnap => {
                        activeNotifications = activeNotifications.filter(n => n.sourceType !== "vaccination");
                        vSnap.forEach(vDoc => {
                            const vData = vDoc.data();
                            if (petIds.includes(vData.petId)) {
                                activeNotifications.push({
                                    id: "vac_" + vDoc.id,
                                    sourceType: "vaccination",
                                    type: "vaccination",
                                    title: "Vaccination Reminder",
                                    message: `Vaccine (${vData.vaccineName || 'Shot'}) for pet ID ${vData.petId} is due on ${vData.nextDueDate || 'soon'}.`,
                                    read: false,
                                    createdAt: new Date(),
                                    link: 'my-pet.php'
                                });
                            }
                        });
                        renderAllNotifications();
                    }).catch(err => console.error("Vaccine fetch error:", err));
                    getDocs(collection(db, "health_records")).then(hSnap => {
                        activeNotifications = activeNotifications.filter(n => n.sourceType !== "medication");
                        hSnap.forEach(hDoc => {
                            const hData = hDoc.data();
                            if (petIds.includes(hData.petId) && hData.medication) {
                                activeNotifications.push({
                                    id: "med_" + hDoc.id,
                                    sourceType: "medication",
                                    type: "medication",
                                    title: "Medication Schedule",
                                    message: `Prescribed medication: ${hData.medication} for your pet.`,
                                    read: false,
                                    createdAt: hData.createdAt || new Date(),
                                    link: 'health.php'
                                });
                            }
                        });
                        renderAllNotifications();
                    }).catch(err => console.error("Health records fetch error:", err));
                }
            });
            const announceQuery = query(collection(db, "announcements"));
            onSnapshot(announceQuery, (snapshot) => {
                activeNotifications = activeNotifications.filter(n => n.sourceType !== "announcement");
                snapshot.forEach((docSnap) => {
                    const data = docSnap.data();
                    activeNotifications.push({
                        id: "ann_" + docSnap.id,
                        sourceType: "announcement",
                        type: "announcement",
                        title: data.title || "Clinic Announcement",
                        message: data.content || data.message || "",
                        read: false,
                        createdAt: data.createdAt || new Date(),
                        link: 'dashboard.php'
                    });
                });
                renderAllNotifications();
            });
            const markAllBtn = document.getElementById("markAllAsReadBtn");
            if (markAllBtn) {
                markAllBtn.addEventListener("click", async () => {
                    try {
                        const unreadQuery = query(collection(db, "notifications"), where("ownerId", "==", currentOwnerId), where("read", "==", false));
                        const unreadSnap = await getDocs(unreadQuery);
                        unreadSnap.forEach(async (docSnap) => {
                            await updateDoc(doc(db, "notifications", docSnap.id), { read: true });
                        });
                        activeNotifications.forEach(n => n.read = true);
                        renderAllNotifications();
                    } catch (err) {
                        console.error("Error marking all as read:", err);
                    }
                });
            }
        }
    });
</script>