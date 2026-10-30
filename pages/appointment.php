<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Appointment - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo-transparent.png">
    <style>
        body {
            background-color: #f4f7fe;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1050;
        }
        .sidebar-overlay.active {
            display: block !important;
        }
        .main-content {
            padding: 16px !important;
            box-sizing: border-box !important;
            background: #f4f7fe;
            min-height: 100vh;
        }
        .appointment-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .profile-style-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #edf2f7;
            box-sizing: border-box;
        }
        .appointment-header {
            padding: 16px 20px 10px 20px !important;
            margin-bottom: 0 !important;
        }
        .appointment-header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e1b4b;
            font-size: 22px;
            margin: 0 0 2px 0;
            font-weight: 700;
        }
        .appointment-header p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
            line-height: 1.4;
        }
        .emergency-banner {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-left: 4px solid #f56565;
            padding: 10px 16px;
            border-radius: 12px;
            color: #9b2c2c;
            font-size: 12.5px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: -4px;
        }
        .appointment-card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }
        .appointment-left {
            position: relative;
            z-index: 50;
        }
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
        .calendar-day:hover:not(.disabled) {
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
        .picker-time-slot.disabled {
            background-color: #f1f5f9 !important;
            color: #cbd5e1 !important;
            border-color: #e2e8f0 !important;
            cursor: not-allowed !important;
            opacity: 0.5;
            pointer-events: none;
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
        .form-group {
            margin-bottom: 16px;
            width: 100%;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #475569;
        }
        .form-group textarea {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
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
        .form-group textarea:focus {
            border-color: #5142f5;
            box-shadow: 0 0 0 3px rgba(81, 66, 245, 0.1);
        }
        .multi-checkbox-box {
            max-height: 160px;
            overflow-y: auto;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 12px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .multi-checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #1e1b4b;
            cursor: pointer;
            user-select: none;
        }
        .multi-checkbox-item input[type="checkbox"] {
            accent-color: #5142f5;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        @media (max-width: 992px) {
            .appointment-card-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 12px !important;
            }
            .profile-style-card {
                padding: 18px;
                border-radius: 18px;
            }
            .appointment-header {
                padding: 14px 16px !important;
            }
            .appointment-header h1 {
                font-size: 18px !important;
            }
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1100 !important;
                background: var(--sidebar-bg, #173F81) !important; 
            }
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
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
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("../includes/topbar.php"); ?>
        <div class="appointment-container">
            <div class="profile-style-card appointment-header">
                <h1>
                    <i class="fa-solid fa-calendar-check" style="background: #eef4ff; padding: 10px; border-radius: 12px;color: #5142f5;"></i>
                    Appointment
                </h1>
                <p>Select your pet, preferred schedule, and service.</p>
            </div>
            <div class="emergency-banner">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 16px; color: #e53e3e; margin-top: 2px;"></i>
                <div>
                    <strong>Holiday & Emergency Advisory:</strong> The clinic is closed during holidays for regular appointments. However, we can still accommodate <strong>emergency cases</strong> on these days no appointment is needed for emergencies! You can proceed directly to the clinic or call us immediately at 0991-572-3015.
                </div>
            </div>
            
            <div id="alertBox" style="display: none; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 500;"></div>
            <div class="profile-style-card">
                <div class="appointment-card-grid">
                    <div class="appointment-left">
                        <h3 style="color: #1e1b4b; font-size: 16px; margin-bottom: 4px;">
                            <i class="fa-solid fa-calendar-days" style="color: #163B7A;"></i>
                            Appointment Date & Time
                        </h3>
                        <p class="calendar-note" style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                            Choose your preferred appointment date and schedule (Clinic hours: 10:00 AM – 7:00 PM).
                        </p>
                        
                        <div id="holidayNoticeBanner" style="display: none; border-left: 4px solid #e53e3e; background: #fff5f5; padding: 10px 14px; border-radius: 0 8px 8px 0; margin-bottom: 12px;">
                            <small style="text-transform: uppercase; font-size: 10px; font-weight: 700; color: #e53e3e; display: block;">Holiday Notice</small>
                            <h4 id="holidayNoticeText" style="margin: 2px 0 0 0; font-size: 13.5px; color: #9b2c2c; font-weight: 600;">-</h4>
                        </div>
                        <div class="custom-datetime-wrapper">
                            <div class="datetime-display-box" id="datetimeToggleBtn">
                                <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 13px; font-weight: 500; overflow: hidden;">
                                    <i class="fa-regular fa-calendar" style="color: #163B7A; font-size: 15px;"></i>
                                    <span id="selectedDateText">Select Date</span>
                                    <span style="color: #cbd5e1;">|</span>
                                    <i class="fa-regular fa-clock" style="color: #163B7A; font-size: 15px;"></i>
                                    <span id="selectedTimeText">--:-- --</span>
                                </div>
                                <i class="fa-regular fa-calendar-days" style="color: #163B7A; font-size: 16px; flex-shrink: 0;"></i>
                            </div>
                            <input type="hidden" id="appointmentDate">
                            <input type="hidden" id="appointmentTime">
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
                            <label><i class="fa-solid fa-paw" style="color: #5142f5;"></i> Registered Pet(s) <small style="color: #64748b;">(Select one or more)</small></label>
                            <div id="petsCheckboxContainer" class="multi-checkbox-box">
                                <span style="font-size: 12px; color: #888;">Loading your pets...</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-stethoscope" style="color: #5142f5;"></i> Service(s) <small style="color: #64748b;">(Select one or more)</small></label>
                            <div id="servicesCheckboxContainer" class="multi-checkbox-box" style="max-height: 180px;">
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Deworming"> Deworming</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Anti Rabies"> Anti Rabies</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Vaccination (2in1)"> 2in1 Vaccine</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Vaccination (4in1 Cat)"> 4in1 Vaccine (Cat)</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Vaccination (4in1 Dog)"> 4in1 Vaccine (Dog)</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Vaccination (5in1)"> 5in1 Vaccine</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Vaccination (6in1)"> 6in1 Vaccine</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Vaccination (7in1)"> 7in1 Vaccine</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Vaccination (8in1)"> 8in1 Vaccine</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - CBC"> Laboratories - CBC</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Chemistry"> Laboratories - Chemistry</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Microscopy"> Laboratories - Microscopy</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Ultrasound"> Laboratories - Ultrasound</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Laser Therapy"> Laboratories - Laser Therapy</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Check up"> Check up</label>
                                <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Minor Surgeries"> Minor Surgeries</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Reason / Notes</label>
                            <textarea id="notes" placeholder="Describe your pet's condition or consultation details..." style="resize: vertical; min-height: 90px;"></textarea>
                        </div>
                        <button id="bookAppointmentBtn" class="book-btn" style="width: 100%; background: #5142f5; color: #fff; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s, box-shadow 0.2s;">
                            <i class="fa-solid fa-calendar-plus"></i>
                            Book Appointment
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="profile-style-card">
                <h3 style="color: #1e1b4b; font-size: 16px; display: flex; align-items: center; gap: 8px; margin: 0 0 12px 0;">
                    <i class="fa-solid fa-calendar-check" style="color: #5142f5;"></i>
                    Your Booked Appointments
                </h3>
                <div id="userAppointmentsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                    <p style="color: #777; font-size: 13px;">Loading your appointments...</p>
                </div>
            </div>
        </div>
    </div>
</div>
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
<?php include("../includes/bottom-nav.php"); ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggleBtn = document.getElementById('datetimeToggleBtn');
        const popup = document.getElementById('datetimePopup');
        const selectedDateText = document.getElementById('selectedDateText');
        const selectedTimeText = document.getElementById('selectedTimeText');
        const hiddenDateInput = document.getElementById('appointmentDate');
        const hiddenTimeInput = document.getElementById('appointmentTime');
        const pickerSlotsContainer = document.getElementById('pickerSlotsContainer');
        const holidayNoticeBanner = document.getElementById('holidayNoticeBanner');
        const holidayNoticeText = document.getElementById('holidayNoticeText');
        
        let currentDate = new Date();
        let selectedYear = currentDate.getFullYear();
        let selectedMonth = currentDate.getMonth();
        let selectedDay = currentDate.getDate();
        let selectedTimeValue = "";
        
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');
        
        if (monthSelect && yearSelect) {
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
        }
        
        function getHolidayName(dateObj) {
            const year = dateObj.getFullYear();
            const month = dateObj.getMonth();
            const day = dateObj.getDate();
            
            const specificHolidays = {
                "0-1": "New Year's Day",
                "3-9": "Araw ng Kagitingan",
                "4-1": "Labor Day",
                "5-12": "Independence Day",
                "7-21": "Ninoy Aquino Day",
                "10-1": "All Saints' Day (Undas)",
                "10-2": "All Souls' Day",
                "10-30": "Bonifacio Day",
                "11-8": "Feast of the Immaculate Conception",
                "11-24": "Christmas Eve",
                "11-25": "Christmas Day",
                "11-30": "Rizal Day",
                "11-31": "New Year's Eve"
            };
            const key = `${month}-${day}`;
            if (specificHolidays[key]) return specificHolidays[key];
            if (month === 7) {
                const lastDayAugust = new Date(year, 7, 31);
                let lastMondayAugust = 31 - ((lastDayAugust.getDay() + 6) % 7);
                if (day === lastMondayAugust) return "National Heroes Day";
            }
            if (month === 1 && day === 25) return "EDSA People Power Revolution Anniversary";
            return null;
        }
        function renderCalendarDays() {
            const grid = document.getElementById('calendarDaysGrid');
            if (!grid) return;
            grid.innerHTML = '';
            const firstDayIndex = new Date(selectedYear, selectedMonth, 1).getDay();
            const totalDays = new Date(selectedYear, selectedMonth + 1, 0).getDate();
            const prevTotalDays = new Date(selectedYear, selectedMonth, 0).getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
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
                const currentCellDate = new Date(selectedYear, selectedMonth, d);
                currentCellDate.setHours(0, 0, 0, 0);
                
                let isDisabled = false;
                let holidayName = getHolidayName(currentCellDate);
                let isHoliday = holidayName !== null;
                if (currentCellDate < today || isHoliday) {
                    isDisabled = true;
                }
                
                if (isDisabled) {
                    dayEl.classList.add('disabled', 'muted');
                    dayEl.style.backgroundColor = isHoliday ? '#fee2e2' : '#f1f5f9';
                    dayEl.style.color = isHoliday ? '#991b1b' : '#cbd5e1';
                    dayEl.style.cursor = 'not-allowed';
                    dayEl.style.opacity = '0.7';
                } else {
                    if (d === selectedDay) {
                        dayEl.classList.add('selected');
                    }
                }
                dayEl.addEventListener('click', () => {
                    selectedDay = d;
                    renderCalendarDays();
                    loadPickerTimeSlots();
                    
                    const clickedDateObj = new Date(selectedYear, selectedMonth, d);
                    const nameOfHoliday = getHolidayName(clickedDateObj);
                    if (nameOfHoliday) {
                        if (holidayNoticeBanner && holidayNoticeText) {
                            holidayNoticeText.textContent = `${nameOfHoliday} (${clickedDateObj.toLocaleDateString()})`;
                            holidayNoticeBanner.style.display = "block";
                        }
                    } else {
                        if (holidayNoticeBanner) {
                            holidayNoticeBanner.style.display = "none";
                        }
                    }
                });
                grid.appendChild(dayEl);
            }
        }
        function isTimePassed(slotTimeStr, selectedDateObj) {
            const now = new Date();
            const checkDate = new Date(selectedDateObj);
            checkDate.setHours(0, 0, 0, 0);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (checkDate > today) return false;
            if (checkDate < today) return true;
            const timePart = slotTimeStr.split('-')[0].trim();
            const match = timePart.match(/(\d+):(\d+)\s*(AM|PM)/i);
            if (!match) return false;
            let hours = parseInt(match[1], 10);
            const minutes = parseInt(match[2], 10);
            const ampm = match[3].toUpperCase();
            if (ampm === "PM" && hours < 12) hours += 12;
            if (ampm === "AM" && hours === 12) hours = 0;
            const slotDate = new Date();
            slotDate.setHours(hours, minutes, 0, 0);
            return now > slotDate;
        }
        function loadPickerTimeSlots() {
            if (!pickerSlotsContainer) return;
            pickerSlotsContainer.innerHTML = '<p style="color: #888; font-size: 12px; text-align: center;">Loading slots...</p>';
            let standardSlots = [
                { time: "10:00 AM", isBreak: false },
                { time: "11:30 AM", isBreak: false },
                { time: "12:00 PM - 01:00 PM (Break Time)", isBreak: true },
                { time: "01:00 PM", isBreak: false },
                { time: "02:30 PM", isBreak: false },
                { time: "04:00 PM", isBreak: false },
                { time: "05:30 PM", isBreak: false }
            ];
            const selectedDateObj = new Date(selectedYear, selectedMonth, selectedDay);
            const holidayTitle = getHolidayName(selectedDateObj);
            const isHolidayDate = holidayTitle !== null;
            setTimeout(() => {
                pickerSlotsContainer.innerHTML = '';
                const doneBtn = document.getElementById('donePickerBtn');
                if (isHolidayDate) {
                    if (doneBtn) doneBtn.style.display = 'none';
                    pickerSlotsContainer.innerHTML = `<div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; font-size: 12px; text-align: center; font-weight: 500;">Clinic is Closed (${holidayTitle}). Walk-in emergencies only.</div>`;
                    return;
                } else {
                    if (doneBtn) doneBtn.style.display = 'block';
                }
                standardSlots.forEach(slotObj => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'picker-time-slot';
                    slotDiv.textContent = slotObj.time;
                    const hasPassed = isTimePassed(slotObj.time, selectedDateObj);
                    if (slotObj.isBreak) {
                        slotDiv.classList.add('disabled');
                        slotDiv.style.backgroundColor = '#fee2e2';
                        slotDiv.style.color = '#991b1b';
                        slotDiv.style.borderColor = '#fca5a5';
                        slotDiv.style.fontSize = '11px';
                    } else if (hasPassed) {
                        slotDiv.classList.add('disabled');
                    } else {
                        if (selectedTimeValue === slotObj.time) {
                            slotDiv.classList.add('selected');
                        }
                        slotDiv.addEventListener('click', () => {
                            if (slotDiv.classList.contains('disabled') || slotDiv.classList.contains('booked')) return;
                            document.querySelectorAll('.picker-time-slot').forEach(el => el.classList.remove('selected'));
                            slotDiv.classList.add('selected');
                            selectedTimeValue = slotObj.time;
                        });
                    }
                    pickerSlotsContainer.appendChild(slotDiv);
                });
            }, 150);
        }
        if (toggleBtn && popup) {
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
        }
        if (monthSelect) {
            monthSelect.addEventListener('change', (e) => {
                selectedMonth = parseInt(e.target.value);
                updatePickerUI();
            });
        }
        if (yearSelect) {
            yearSelect.addEventListener('change', (e) => {
                selectedYear = parseInt(e.target.value);
                updatePickerUI();
            });
        }
        const prevBtn = document.getElementById('prevMonthBtn');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                selectedMonth--;
                if (selectedMonth < 0) {
                    selectedMonth = 11;
                    selectedYear--;
                }
                updatePickerUI();
            });
        }
        const nextBtn = document.getElementById('nextMonthBtn');
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                selectedMonth++;
                if (selectedMonth > 11) {
                    selectedMonth = 0;
                    selectedYear++;
                }
                updatePickerUI();
            });
        }
        const doneBtn = document.getElementById('donePickerBtn');
        if (doneBtn) {
            doneBtn.addEventListener('click', () => {
                const selectedDateObjCheck = new Date(selectedYear, selectedMonth, selectedDay);
                const holidayCheckName = getHolidayName(selectedDateObjCheck);
                if (holidayCheckName) {
                    alert(`Cannot select appointments on holidays (${holidayCheckName}). The clinic is closed for regular appointments.`);
                    return;
                }
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
        }
        function updatePickerUI() {
            if (monthSelect) monthSelect.value = selectedMonth;
            if (yearSelect) yearSelect.value = selectedYear;
            renderCalendarDays();
            loadPickerTimeSlots();
        }
        updatePickerUI();
    });
</script>
<script type="module" src="../assets/js/appointment.js"></script>

<!-- ROBUST EVENT DELEGATION SCRIPT PARA SA SIDEBAR -->
<script>
    document.addEventListener("click", function(e) {
        const menuBtn = e.target.closest("#mobileMenuBtn, #menuBtn, .mobile-menu-btn, .burger-btn, .fa-bars");
        const sidebar = document.querySelector(".sidebar") || document.getElementById("appSidebar");
        const overlay = document.getElementById("sidebarOverlay") || document.querySelector(".sidebar-overlay");
        if (menuBtn && sidebar) {
            e.stopPropagation();
            e.preventDefault();
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebar.style.setProperty("left", "0px", "important");
                if (overlay) {
                    overlay.classList.add("active");
                    overlay.style.setProperty("display", "block", "important");
                }
            } else {
                sidebar.style.setProperty("left", "-270px", "important");
                if (overlay) {
                    overlay.classList.remove("active");
                    overlay.style.setProperty("display", "none", "important");
                }
            }
            return;
        }
        if (overlay && overlay.contains(e.target)) {
            if (sidebar) {
                sidebar.classList.remove("active");
                sidebar.style.setProperty("left", "-270px", "important");
            }
            overlay.classList.remove("active");
            overlay.style.setProperty("display", "none", "important");
        }
    });
</script>
</body>
</html>