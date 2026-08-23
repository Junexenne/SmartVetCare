<div class="topbar" style="display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 15px 30px; background: #ffffff; box-sizing: border-box; box-shadow: 0 2px 10px rgba(0,0,0,0.02); position: relative;">
    
    <!-- Left: Search Bar Only -->
    <div style="display: flex; align-items: center; flex: 1; max-width: 400px;">
        <div class="top-search" style="flex: 1; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 14px;"></i>
            <input type="text" id="topSearchInput" placeholder="Search (Press Enter)..." style="width: 100%; padding: 8px 15px 8px 40px; border-radius: 20px; border: 1px solid #ddd; outline: none; font-size: 14px; font-family: 'Poppins', sans-serif; transition: all 0.3s ease;">
        </div>
    </div>

    <!-- Right: Real-time Clock, Calendar Dropdown, Bell & Profile -->
    <div class="top-icons" style="display: flex; align-items: center; gap: 15px;">
        
        <!-- Real-time Clock Display (Katabi na ng Calendar) -->
        <div id="liveClock" style="font-size: 13px; color: #4a5568; font-weight: 500; white-space: nowrap; display: flex; align-items: center; gap: 6px; background: #f8f9fa; padding: 6px 12px; border-radius: 8px; border: 1px solid #edf2f7;">
            <i class="fa-regular fa-clock" style="color: #5142f5;"></i>
            <span id="clockText">Loading time...</span>
        </div>

        <!-- Calendar Dropdown Wrapper -->
        <div class="calendar-dropdown-wrapper" style="position: relative; display: inline-block;">
            <button class="topbar-icon-btn" id="calendarBtn" style="background: #f0f0f0; border: none; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s;">
                <i class="fa-regular fa-calendar-days" style="color: #333; font-size: 16px;"></i>
            </button>

            <!-- Interactive Calendar & Upcoming Appointments Popup Box -->
            <div id="calendarDropdown" style="display: none; position: absolute; right: 0; top: 48px; width: 320px; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); border: 1px solid #edf2f7; z-index: 1000; overflow: hidden; font-family: 'Poppins', sans-serif;">
                <div style="background: #5142f5; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="margin: 0; font-size: 15px; font-weight: 600;"><i class="fa-solid fa-calendar-check" style="margin-right: 8px;"></i> Calendar & Schedules</h4>
                    <span id="currentMonthYear" style="font-size: 12px; opacity: 0.9;"></span>
                </div>

                <!-- Mini Calendar Grid View -->
                <div style="padding: 12px 15px; border-bottom: 1px solid #edf2f7;">
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: 11px; font-weight: 600; color: #a0aec0; margin-bottom: 6px;">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>
                    <div id="miniCalendarDays" style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: 12px; gap: 2px;">
                        <!-- JS generated days here -->
                    </div>
                </div>

                <!-- Upcoming Appointments Section -->
                <div style="padding: 12px 15px;">
                    <p style="margin: 0 0 8px 0; font-size: 11px; font-weight: 600; color: #718096; text-transform: uppercase; letter-spacing: 0.5px;">Upcoming Appointments</p>
                    <div id="topbarAppointmentsList" style="max-height: 150px; overflow-y: auto; display: flex; flex-direction: column; gap: 6px;">
                        <p style="text-align: center; color: #a0aec0; font-size: 12px; margin: 10px 0;">No upcoming appointments.</p>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 10px; text-align: center; border-top: 1px solid #edf2f7;">
                    <a href="appointment.php" style="color: #5142f5; font-size: 12px; font-weight: 600; text-decoration: none;">View Full Appointments &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Notification Wrapper -->
        <div class="notification-dropdown-wrapper" style="position: relative; display: inline-block;">
            <button class="notification-btn" id="notifBellBtn" style="background: #f0f0f0; border: none; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative;">
                <i class="fa-solid fa-bell" style="color: #333; font-size: 16px;"></i>
                <span id="notifBadge" style="position: absolute; top: -2px; right: -2px; background: red; color: white; font-size: 10px; padding: 2px 5px; border-radius: 50%; display: none;">0</span>
            </button>
            <div id="notifDropdown" style="display: none;">
                <div class="notif-header">
                   <h4>Notifications</h4>
                   <span id="markAllAsReadBtn">Mark all as read</span>
                </div>
               <div id="notifListContainer"></div>
            </div>
        </div>

        <!-- User Profile -->
        <div class="user-profile" style="display: flex; align-items: center; gap: 10px;">
            <img src="../assets/images/default-user.png" class="profile-img" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            <div style="text-align: left;">
                <h4 style="margin: 0; font-size: 13px; color: #1e1e2d; font-weight: bold;">Pet Owner</h4>
                <span style="font-size: 11px; color: #28a745;">Online</span>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
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

        if (calendarBtn && calendarDropdown) {
            calendarBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                calendarDropdown.style.display = calendarDropdown.style.display === "block" ? "none" : "block";
            });

            document.addEventListener("click", (e) => {
                if (!calendarDropdown.contains(e.target) && !calendarBtn.contains(e.target)) {
                    calendarDropdown.style.display = "none";
                }
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
                    ? `background: #5142f5; color: white; border-radius: 50%; font-weight: 600; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin: auto;` 
                    : `color: #4a5568; padding: 3px 0;`;
                daysHtml += `<span style="${style}">${d}</span>`;
            }
            daysContainer.innerHTML = daysHtml;
        }
    });
</script>