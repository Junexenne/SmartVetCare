<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment - Smart Vet Care</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
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

        /* Responsive Layout & Grid Adjustments */
        .appointment-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .appointment-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
            background: #ffffff;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .appointment-left {
            position: relative;
            z-index: 50;
        }

        /* Custom Interactive DateTime Picker Styles */
        .custom-datetime-wrapper {
            position: relative;
            width: 100%;
        }

        .datetime-display-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }

        .datetime-display-box:hover {
            border-color: #5142f5;
            box-shadow: 0 4px 12px rgba(81, 66, 245, 0.08);
        }

        .datetime-popup {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            border: 1px solid #edf2f7;
            z-index: 9999;
            width: 500px;
            max-width: 100%;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        .datetime-popup.active {
            display: flex;
            gap: 20px;
        }

        .picker-calendar-side {
            flex: 1.2;
        }

        .picker-time-side {
            flex: 1;
            border-left: 1px solid #f1f5f9;
            padding-left: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .calendar-nav select {
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #1e1b4b;
            background: #f8fafc;
            outline: none;
        }

        .calendar-nav button {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            cursor: pointer;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .calendar-nav button:hover {
            background: #5142f5;
            color: #fff;
            border-color: #5142f5;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            text-align: center;
        }

        .calendar-weekday {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            padding-bottom: 6px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 500;
            color: #334155;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .calendar-day:hover {
            background: #f1f5f9;
        }

        .calendar-day.muted {
            color: #cbd5e1;
        }

        .calendar-day.selected {
            background: #5142f5 !important;
            color: #ffffff !important;
            font-weight: 600;
        }

        /* Styling para sa mga Time Slots */
        .picker-slots-container {
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-height: 220px;
            overflow-y: auto;
            margin-bottom: 12px;
            padding-right: 4px;
        }

        .picker-time-slot {
            padding: 8px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .picker-time-slot:hover:not(.disabled):not(.booked) {
            background: #eef2ff;
            border-color: #5142f5;
            color: #5142f5;
        }

        .picker-time-slot.selected {
            background: #5142f5 !important;
            color: #ffffff !important;
            border-color: #5142f5 !important;
        }

        .btn-done {
            background: #5142f5;
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 9px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-done:hover {
            background: #4333e6;
        }

        /* Form Controls Interactive UI */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #475569;
        }

        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: #1e1b4b;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: #5142f5;
            box-shadow: 0 0 0 3px rgba(81, 66, 245, 0.1);
        }

        /* Media Queries para sa Responsive Design */
        @media (max-width: 992px) {
            .appointment-card {
                grid-template-columns: 1fr;
                gap: 20px;
            }
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

            .appointment-card {
                padding-bottom: 120px !important;
            }

            .appointment-left {
                position: relative;
                z-index: 5 !important;
            }

            .datetime-popup.active {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 88vw !important;
                max-width: 320px !important;
                max-height: 75vh !important;
                padding: 14px !important;
                overflow-y: auto !important;
                flex-direction: column !important;
                background: #ffffff !important;
                z-index: 9999 !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35) !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 14px !important;
            }

            .calendar-day {
                font-size: 11px !important;
                height: 28px !important;
            }

            .calendar-nav button {
                width: 28px !important;
                height: 28px !important;
            }

            .calendar-nav select {
                padding: 4px 6px !important;
                font-size: 12px !important;
            }

            .picker-slots-container {
                max-height: 130px !important;
            }

            .picker-time-slot {
                padding: 6px 10px !important;
                font-size: 12px !important;
            }

            .picker-time-side {
                border-left: none;
                border-top: 1px solid #f1f5f9;
                padding-left: 0;
                padding-top: 12px;
                margin-top: 10px;
            }

            /* Compact 2-column grid adjustment para sa booked appointments sa mobile */
            #userAppointmentsContainer {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
            }
            #userAppointmentsContainer > div {
                padding: 8px !important;
                border-radius: 12px !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }
            #userAppointmentsContainer h4 {
                font-size: 11px !important;
                margin-bottom: 3px !important;
                word-break: break-word !important;
            }
            #userAppointmentsContainer p, 
            #userAppointmentsContainer span {
                font-size: 10px !important;
                line-height: 1.2 !important;
                margin: 2px 0 !important;
                word-break: break-word !important;
            }
            #userAppointmentsContainer button {
                padding: 4px 6px !important;
                font-size: 9px !important;
                border-radius: 6px !important;
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

        <div class="appointment-container">

            <div class="appointment-header" style="margin-bottom: 25px;">
                <h1 style="display: flex; align-items: center; gap: 10px; color: #1e1b4b !important; font-size: 24px; margin-bottom: 6px;">
                    <i class="fa-solid fa-calendar-check" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                    Appointment
                </h1>
                <p style="color: #64748b; font-size: 13px;">Select your pet, preferred schedule, and service.</p>
            </div>

            <div id="alertBox"></div>

            <div class="appointment-card">

                <div class="appointment-left">
                    <h3 style="color: #1e1b4b; font-size: 16px; margin-bottom: 4px;">
                        <i class="fa-solid fa-calendar-days" style="color: #5142f5;"></i>
                        Appointment Date & Time
                    </h3>
                    <p class="calendar-note" style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                        Choose your preferred appointment date and schedule.
                    </p>

                    <!-- Custom Interactive Date & Time Picker UI Container -->
                    <div class="custom-datetime-wrapper">
                        <div class="datetime-display-box" id="datetimeToggleBtn">
                            <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 13px; font-weight: 500; overflow: hidden;">
                                <i class="fa-regular fa-calendar" style="color: #5142f5; font-size: 15px;"></i>
                                <span id="selectedDateText">Select Date</span>
                                <span style="color: #cbd5e1;">|</span>
                                <i class="fa-regular fa-clock" style="color: #5142f5; font-size: 15px;"></i>
                                <span id="selectedTimeText">--:-- --</span>
                            </div>
                            <i class="fa-regular fa-calendar-days" style="color: #5142f5; font-size: 16px; flex-shrink: 0;"></i>
                        </div>

                        <!-- Hidden inputs para mag-sync sa backend/existing JS -->
                        <input type="hidden" id="appointmentDate">
                        <input type="hidden" id="appointmentTime">

                        <!-- Popup Box -->
                        <div class="datetime-popup" id="datetimePopup">
                            <div class="picker-calendar-side">
                                <div class="calendar-nav">
                                    <button type="button" id="prevMonthBtn"><i class="fa-solid fa-chevron-left"></i></button>
                                    <div style="display: flex; gap: 6px;">
                                        <select id="monthSelect"></select>
                                        <select id="yearSelect"></select>
                                    </div>
                                    <button type="button" id="nextMonthBtn"><i class="fa-solid fa-chevron-right"></i></button>
                                </div>
                                <div class="calendar-grid" style="margin-bottom: 8px;">
                                    <div class="calendar-weekday">Su</div>
                                    <div class="calendar-weekday">Mo</div>
                                    <div class="calendar-weekday">Tu</div>
                                    <div class="calendar-weekday">We</div>
                                    <div class="calendar-weekday">Th</div>
                                    <div class="calendar-weekday">Fr</div>
                                    <div class="calendar-weekday">Sa</div>
                                </div>
                                <div class="calendar-grid" id="calendarDaysGrid"></div>
                            </div>

                            <div class="picker-time-side">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 8px;">Available Time Slots</label>
                                    <div class="picker-slots-container" id="pickerSlotsContainer">
                                        <p style="color: #888; font-size: 12px; text-align: center; padding: 10px 0;">Select a date first.</p>
                                    </div>
                                </div>

                                <button type="button" class="btn-done" id="donePickerBtn">Confirm Schedule</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="appointment-right">

                    <div class="form-group">
                        <label>Registered Pet</label>
                        <select id="petSelect">
                            <option value="">Loading pets...</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Service</label>
                        <select id="service">
                            <option value="General Check Up">General Check Up</option>
                            <option value="Vaccination">Vaccination</option>
                            <option value="Surgery">Surgery</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Doctor</label>
                        <select id="doctor">
                            <option value="">Select Doctor</option>
                            <option value="Dr. Alfie Tamesis">Dr. Alfie Tamesis</option>
                            <option value="Dr. Crachzel Kyle Asistio">Dr. Crachzel Kyle Asistio</option>
                            <option value="Dr. James Nico Martinez">Dr. James Nico Martinez</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Reason / Notes</label>
                        <textarea id="notes" placeholder="Describe your pet's condition..." style="resize: vertical; min-height: 90px;"></textarea>
                    </div>

                    <button id="bookAppointmentBtn" class="book-btn" style="width: 100%; background: #5142f5; color: #fff; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s, box-shadow 0.2s;">
                        <i class="fa-solid fa-calendar-plus"></i>
                        Book Appointment
                    </button>

                </div>
            </div>

            <div class="user-appointments-section" style="margin-top: 30px;">
                <h3 style="color: #1e1b4b; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-calendar-check" style="color: #5142f5;"></i>
                    Your Booked Appointments
                </h3>

                <div id="userAppointmentsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px; margin-top: 15px;">
                    <p style="color: #777; font-size: 13px;">Loading your appointments...</p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #fff; padding: 25px; border-radius: 16px; width: 100%; max-width: 450px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 15px; color: #1e1b4b; display: flex; align-items: center; gap: 8px; font-size: 16px;">
            <i class="fa-solid fa-calendar-pen" style="color: #5142f5;"></i> Reschedule Appointment
        </h3>
        
        <input type="hidden" id="rescheduleApptId">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 5px; color: #475569;">New Date</label>
            <input type="date" id="rescheduleDate" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: 'Poppins', sans-serif; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 5px; color: #475569;">Available Time Slots</label>
            <div id="rescheduleTimeSlots" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 5px;">
                <p style="color: #888; font-size: 13px;">Please select a new date first.</p>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" id="closeModalBtn" style="padding: 8px 16px; background: #e2e8f0; color: #334155; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">Cancel</button>
            <button type="button" id="confirmRescheduleBtn" style="padding: 8px 16px; background: #5142f5; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">Save New Schedule</button>
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

        // Auto-select doctor from URL parameter (e.g., appointment.php?doctor=Dr.%20Alfie%20Tamesis)
        const urlParams = new URLSearchParams(window.location.search);
        const doctorParam = urlParams.get('doctor');
        if (doctorParam) {
            const doctorSelect = document.getElementById('doctor');
            if (doctorSelect) {
                doctorSelect.value = doctorParam;
                if (!doctorSelect.value) {
                    for (let option of doctorSelect.options) {
                        if (option.textContent.toLowerCase().includes(doctorParam.toLowerCase())) {
                            doctorSelect.value = option.value;
                            break;
                        }
                    }
                }
            }
        }

        // Custom Interactive DateTime Picker Logic
        const toggleBtn = document.getElementById('datetimeToggleBtn');
        const popup = document.getElementById('datetimePopup');
        const selectedDateText = document.getElementById('selectedDateText');
        const selectedTimeText = document.getElementById('selectedTimeText');
        const hiddenDateInput = document.getElementById('appointmentDate');
        const hiddenTimeInput = document.getElementById('appointmentTime');
        const pickerSlotsContainer = document.getElementById('pickerSlotsContainer');

        let currentDate = new Date();
        let selectedYear = currentDate.getFullYear();
        let selectedMonth = currentDate.getMonth();
        let selectedDay = currentDate.getDate();
        let selectedTimeValue = "";

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');

        monthNames.forEach((m, idx) => {
            const opt = document.createElement('option');
            opt.value = idx;
            opt.textContent = m;
            monthSelect.appendChild(opt);
        });

        for (let y = selectedYear - 2; y <= selectedYear + 5; y++) {
            const opt = document.createElement('option');
            opt.value = y;
            opt.textContent = y;
            yearSelect.appendChild(opt);
        }

        function updatePickerUI() {
            monthSelect.value = selectedMonth;
            yearSelect.value = selectedYear;
            renderCalendarDays();
            loadPickerTimeSlots();
        }

        function renderCalendarDays() {
            const grid = document.getElementById('calendarDaysGrid');
            grid.innerHTML = '';

            const firstDayIndex = new Date(selectedYear, selectedMonth, 1).getDay();
            const totalDays = new Date(selectedYear, selectedMonth + 1, 0).getDate();
            const prevTotalDays = new Date(selectedYear, selectedMonth, 0).getDate();

            for (let i = firstDayIndex; i > 0; i--) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day muted';
                dayEl.textContent = prevTotalDays - i + 1;
                grid.appendChild(dayEl);
            }

            for (let d = 1; d <= totalDays; d++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day';
                dayEl.textContent = d;

                if (d === selectedDay) {
                    dayEl.classList.add('selected');
                }

                dayEl.addEventListener('click', () => {
                    selectedDay = d;
                    renderCalendarDays();
                    loadPickerTimeSlots();
                });

                grid.appendChild(dayEl);
            }
        }

        function loadPickerTimeSlots() {
            const formattedMonth = String(selectedMonth + 1).padStart(2, '0');
            const formattedDay = String(selectedDay).padStart(2, '0');
            const dateStr = `${selectedYear}-${formattedMonth}-${formattedDay}`;

            pickerSlotsContainer.innerHTML = '<p style="color: #888; font-size: 12px; text-align: center;">Loading slots...</p>';

            const standardSlots = ["09:00 AM", "10:30 AM", "01:00 PM", "02:30 PM", "04:00 PM"];
            
            setTimeout(() => {
                pickerSlotsContainer.innerHTML = '';
                
                standardSlots.forEach(time => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'picker-time-slot';
                    slotDiv.textContent = time;

                    if (selectedTimeValue === time) {
                        slotDiv.classList.add('selected');
                    }

                    slotDiv.addEventListener('click', () => {
                        if (slotDiv.classList.contains('disabled') || slotDiv.classList.contains('booked')) return;
                        
                        document.querySelectorAll('.picker-time-slot').forEach(el => el.classList.remove('selected'));
                        slotDiv.classList.add('selected');
                        selectedTimeValue = time;
                    });

                    pickerSlotsContainer.appendChild(slotDiv);
                });
            }, 150);
        }

        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            popup.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!popup.contains(e.target) && !toggleBtn.contains(e.target)) {
                popup.classList.remove('active');
            }
        });

        popup.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        monthSelect.addEventListener('change', (e) => {
            selectedMonth = parseInt(e.target.value);
            updatePickerUI();
        });

        yearSelect.addEventListener('change', (e) => {
            selectedYear = parseInt(e.target.value);
            updatePickerUI();
        });

        document.getElementById('prevMonthBtn').addEventListener('click', () => {
            selectedMonth--;
            if (selectedMonth < 0) {
                selectedMonth = 11;
                selectedYear--;
            }
            updatePickerUI();
        });

        document.getElementById('nextMonthBtn').addEventListener('click', () => {
            selectedMonth++;
            if (selectedMonth > 11) {
                selectedMonth = 0;
                selectedYear++;
            }
            updatePickerUI();
        });

        document.getElementById('donePickerBtn').addEventListener('click', () => {
            if (!selectedTimeValue) {
                alert("Please select an available time slot.");
                return;
            }

            const formattedMonth = String(selectedMonth + 1).padStart(2, '0');
            const formattedDay = String(selectedDay).padStart(2, '0');
            const dateStr = `${selectedYear}-${formattedMonth}-${formattedDay}`;

            hiddenDateInput.value = dateStr;
            hiddenTimeInput.value = selectedTimeValue;

            selectedDateText.textContent = `${monthNames[selectedMonth].substring(0,3)} ${formattedDay}, ${selectedYear}`;
            selectedTimeText.textContent = selectedTimeValue;

            popup.classList.remove('active');

            hiddenDateInput.dispatchEvent(new Event('change'));
        });

        updatePickerUI();
    });
</script>
<script type="module" src="../assets/js/appointment.js"></script>

</body>
</html>