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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 500;
            color: #334155;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            position: relative;
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
        .calendar-day.fully-booked {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            cursor: not-allowed !important;
            opacity: 0.8;
        }
        .calendar-day.fully-booked::after {
            content: 'Full';
            font-size: 8px;
            font-weight: 700;
            display: block;
            color: #dc2626;
            line-height: 1;
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
        .form-group select, .form-group textarea {
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
        .form-group select:focus, .form-group textarea:focus {
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
                <p>Select your pet, preferred doctor, schedule, and service.</p>
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
                        <!-- Choose a Doctor Section -->
                        <div class="form-group">
                            <label><i class="fa-solid fa-user-doctor" style="color: #5142f5;"></i> Choose Doctor & Schedule</label>
                            <select id="assignedDoctor" required>
                                <option value="" disabled selected>Select a veterinarian</option>
                                <option value="Dr. Alfie Tamesis">Dr. Alfie Tamesis (Mon, Sun)</option>
                                <option value="Dr. JK">Dr. JK (Mon–Thu, Sat–Sun)</option>
                                <option value="Dr. Edmond">Dr. Edmond (Tue, Thu, Sat)</option>
                                <option value="Dr. Vitz">Dr. Vitz (Friday)</option>
                            </select>
                        </div>
                        <h3 style="color: #1e1b4b; font-size: 16px; margin-bottom: 4px;">
                            <i class="fa-solid fa-calendar-days" style="color: #163B7A;"></i>
                            Appointment Date & Time
                        </h3>
                        <p class="calendar-note" style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                            Choose your preferred appointment date and schedule (Clinic hours: 10:00 AM – 7:00 PM). Note: Calendar dates disabled if the doctor is not scheduled or fully booked (max 10 appointments/day).
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
                                            <p style="color: #888; font-size: 12px; text-align: center;">Select a doctor and date first.</p>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-done" id="donePickerBtn">Confirm Schedule</button>
                                </div>
                            </div>
                        </div>
                        <!-- Payment & GCash / PayMaya Section -->
                        <div style="margin-top: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                            <h4 style="margin: 0 0 8px 0; color: #1e1b4b; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-wallet" style="color: #5142f5;"></i> Reservation Payment Details
                            </h4>
                            <p style="font-size: 12.5px; color: #64748b; margin: 0 0 10px 0;" id="paymentInstructionText">
                                Please send your reservation fee via GCash or PayMaya using the clinic account numbers below. Note: Status will remain <strong>Pending / Waiting for Approval</strong> upon submission.
                            </p>
                            <div style="display: flex; gap: 12px; margin-bottom: 12px; font-size: 13px;">
                                <div style="background: #eef2ff; padding: 8px 12px; border-radius: 8px; flex: 1; border: 1px solid #c7d2fe;">
                                    <strong>GCash:</strong> 0991-572-3015 (Smart Vet Care)
                                </div>
                                <div style="background: #f0fdf4; padding: 8px 12px; border-radius: 8px; flex: 1; border: 1px solid #bbf7d0;">
                                    <strong>PayMaya:</strong> 0991-572-3015 (Smart Vet Care)
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 12.5px;">GCash / PayMaya Reference Number</label>
                                <input type="text" id="referenceNumberInput" inputmode="numeric" pattern="[0-9]*" placeholder="Enter transaction reference number" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; outline: none; font-family: 'Poppins', sans-serif;">
                            </div>
                        </div>
                    </div>
                                        <div class="appointment-right">
                    <div class="form-group">
                        <label><i class="fa-solid fa-paw" style="color: #5142f5;"></i> Registered Pet(s) <small style="color: #64748b;">(Select one or more)</small></label>
                        <div id="petsCheckboxContainer" class="multi-checkbox-box">
                            <!-- HINDI NA IPAPAKITA ANG "No registered pets found..." LALABAS LANG AY YUNG CHECKBOX SA LABAS -->
                        </div>
                        <!-- DAGDAG PARA SA MGA FIRST-TIME USERS / WALA PANG NAKAREHISTRONG PET -->
                        <div style="margin-top: 10px; border-top: 1px dashed #cbd5e1; padding-top: 10px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #4f46e5; cursor: pointer;">
                                <input type="checkbox" id="toggleNewPetInput" style="cursor: pointer; width: 16px; height: 16px;">
                                <span>First time here? Register a new pet for this booking</span>
                            </label>
                            <!-- Hidden input fields na lilitaw lang kapag kinlik ang checkbox -->
                            <div id="newPetFieldsContainer" style="display: none; margin-top: 10px; background: #f1f5f9; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; flex-direction: column; gap: 10px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 500; color: #334155; margin-bottom: 3px;">Pet Name</label>
                                    <input type="text" id="newPetNameInput" placeholder="e.g., Brownie" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-family: inherit; box-sizing: border-box; outline: none;">
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <div style="flex: 1;">
                                        <label style="display: block; font-size: 12px; font-weight: 500; color: #334155; margin-bottom: 3px;">Pet Type / Species</label>
                                        <select id="newPetSpeciesInput" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-family: inherit; background: #fff; outline: none;">
                                            <option value="Dog">Dog</option>
                                            <option value="Cat">Cat</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div style="flex: 1;">
                                        <label style="display: block; font-size: 12px; font-weight: 500; color: #334155; margin-bottom: 3px;">Breed (Optional)</label>
                                        <input type="text" id="newPetBreedInput" placeholder="e.g., Shih Tzu / Pusakal" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-family: inherit; box-sizing: border-box; outline: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fa-solid fa-stethoscope" style="color: #5142f5;"></i> Service(s) <small style="color: #64748b;">(Select services)</small></label>
                        <div id="servicesCheckboxContainer" class="multi-checkbox-box" style="max-height: 200px;">
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Deworming"> Deworming (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Wellness - Anti Rabies"> Anti Rabies (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" data-in1="true" value="Wellness - Vaccination (2in1)"> 2in1 Vaccine (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" data-in1="true" value="Wellness - Vaccination (4in1 Cat)"> 4in1 Vaccine Cat (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" data-in1="true" value="Wellness - Vaccination (4in1 Dog)"> 4in1 Vaccine Dog (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" data-in1="true" value="Wellness - Vaccination (5in1)"> 5in1 Vaccine (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" data-in1="true" value="Wellness - Vaccination (6in1)"> 6in1 Vaccine (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" data-in1="true" value="Wellness - Vaccination (7in1)"> 7in1 Vaccine (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" data-in1="true" value="Wellness - Vaccination (8in1)"> 8in1 Vaccine (Expected Duration: ~5 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - CBC"> Laboratories - CBC (Expected Duration: ~15 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Chemistry"> Laboratories - Chemistry (Expected Duration: ~15 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Microscopy"> Laboratories - Microscopy (Expected Duration: ~15 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Ultrasound"> Laboratories - Ultrasound (Expected Duration: ~20 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Laboratories - Laser Therapy"> Laboratories - Laser Therapy (Expected Duration: ~20 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Check up"> Check up (Expected Duration: ~15 mins)</label>
                            <label class="multi-checkbox-item"><input type="checkbox" name="selectedServices" value="Minor Surgeries"> Minor Surgeries (Expected Duration: ~45 mins)</label>
                        </div>
                    </div>
                    <div class="form-group" style="background: #fdf4ff; padding: 10px 14px; border-radius: 8px; border: 1px solid #f5d0fe;">
                        <label style="margin: 0; font-weight: 600; color: #86198f; font-size: 13px;">Standard Reservation Fee: <span id="totalFeeDisplay" style="color: #701a75; font-size: 15px;">₱150</span></label>
                        <small style="color: #a21caf; font-size: 11.5px; display: block; margin-top: 2px;">Note: A fixed ₱150 reservation fee is required per booking and will be deducted from your total bill upon arrival at the clinic.</small>
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
<!-- IMPORTANT NOTICE POPUP MODAL (WARNING ALERT STYLING) -->
<div id="importantNoticeModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); z-index: 99999; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #ffffff; border-radius: 18px; width: 100%; max-width: 460px; box-shadow: 0 25px 50px -12px rgba(217, 119, 6, 0.25); border: 1px solid #fef3c7; overflow: hidden; font-family: 'Poppins', sans-serif;">
        
        <!-- Header Banner na may Gradient Warning Alert -->
        <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); padding: 20px 24px; border-bottom: 2px solid #fde68a; display: flex; align-items: center; gap: 14px;">
            <div style="background: #fef3c7; border: 2px solid #f59e0b; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 22px; color: #d97706;"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 17px; color: #78350f; font-weight: 700; line-height: 1.2;">Important Advisory & Policies</h3>
                <span style="font-size: 11.5px; color: #b45309; font-weight: 500;">Please read carefully before proceeding</span>
            </div>
        </div>
        <!-- Content Body -->
        <div style="padding: 22px 24px 18px 24px; font-size: 13px; color: #334155; line-height: 1.6;">
            
            <div style="display: flex; gap: 12px; margin-bottom: 12px; background: #fffbeb; padding: 12px; border-radius: 10px; border-left: 4px solid #f59e0b;">
                <span style="font-weight: 700; color: #d97706; font-size: 14px;">1.</span>
                <p style="margin: 0; font-size: 12.5px; color: #451a03;">
                    <strong>Schedule Changes & Delays:</strong> Appointment times may adjust if a prior procedure takes longer than expected or due to emergency cases. You will be notified for a reschedule or refund if necessary.
                </p>
            </div>
            <div style="display: flex; gap: 12px; margin-bottom: 12px; background: #fffbeb; padding: 12px; border-radius: 10px; border-left: 4px solid #f59e0b;">
                <span style="font-weight: 700; color: #d97706; font-size: 14px;">2.</span>
                <p style="margin: 0; font-size: 12.5px; color: #451a03;">
                    <strong>Late Policy:</strong> Being late by <strong style="color: #dc2626;">20 minutes</strong> from your appointment time results in automatic cancellation.
                </p>
            </div>
            <div style="display: flex; gap: 12px; margin-bottom: 16px; background: #fffbeb; padding: 12px; border-radius: 10px; border-left: 4px solid #f59e0b;">
                <span style="font-weight: 700; color: #d97706; font-size: 14px;">3.</span>
                <p style="margin: 0; font-size: 12.5px; color: #451a03;">
                    <strong>Reservation Fee:</strong> The reservation fee paid via GCash/PayMaya is required for booking and will be deducted upon your arrival at the clinic.
                </p>
            </div>
        </div>
        <!-- Footer / Action Button -->
        <div style="padding: 0 24px 22px 24px;">
            <button type="button" id="agreeNoticeBtn" style="width: 100%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3); transition: all 0.2s ease;">
                <i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> Yes, I Agree & Understand
            </button>
        </div>
    </div>
</div>
<!-- RESCHEDULE MODAL -->
<div id="rescheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: flex-start; padding-top: 30px; overflow-y: auto;">
    <div style="background: #fff; padding: 25px; border-radius: 16px; width: 100%; max-width: 540px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); font-family: 'Poppins', sans-serif; margin-bottom: 40px;">
        <h3 style="margin-bottom: 12px; color: #1e1b4b; display: flex; align-items: center; gap: 8px; font-size: 16px;">
            <i class="fa-solid fa-calendar-pen" style="color: #5142f5;"></i> Reschedule Appointment
        </h3>
        <input type="hidden" id="rescheduleApptId">
        <input type="hidden" id="rescheduleDate">
        <input type="hidden" id="rescheduleTime">
        
        <div style="margin-bottom: 12px;">
            <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 5px; color: #475569;">Select Doctor</label>
            <select id="rescheduleDoctor" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: 'Poppins', sans-serif; outline: none; font-size: 13px;">
                <option value="Dr. Alfie Tamesis">Dr. Alfie Tamesis (Mon, Sun)</option>
                <option value="Dr. Jan Karlo Fanito">Dr. Jan Karlo Fanito (Mon–Thu, Sat–Sun)</option>
                <option value="Dr. Edmond">Dr. Edmond Batan (Tue, Thu, Sat)</option>
                <option value="Dr. Vitz">Dr. Vitz (Friday)</option>
            </select>
        </div>
        <!-- Inline Kalendaryo at Time Slots sa loob ng Modal para hindi natatakpan -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; margin-bottom: 12px;">
            <label style="display: block; font-size: 13px; font-weight: 600; color: #1e1b4b; margin-bottom: 8px;">Select New Date & Time Slot</label>
            
            <div style="display: flex; gap: 15px;">
                <!-- Calendar Side -->
                <div style="flex: 1.3;">
                    <div class="calendar-nav" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                        <button type="button" id="reschedPrevMonthBtn" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; width: 28px; height: 28px; cursor: pointer;"><i class="fa-solid fa-chevron-left"></i></button>
                        <div style="display: flex; gap: 4px;">
                            <select id="reschedMonthSelect" style="padding: 4px 6px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; background: #fff;"></select>
                            <select id="reschedYearSelect" style="padding: 4px 6px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; background: #fff;"></select>
                        </div>
                        <button type="button" id="reschedNextMonthBtn" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; width: 28px; height: 28px; cursor: pointer;"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; text-align: center; margin-bottom: 6px;">
                        <div style="font-size: 10px; font-weight: 600; color: #94a3b8;">Su</div>
                        <div style="font-size: 10px; font-weight: 600; color: #94a3b8;">Mo</div>
                        <div style="font-size: 10px; font-weight: 600; color: #94a3b8;">Tu</div>
                        <div style="font-size: 10px; font-weight: 600; color: #94a3b8;">We</div>
                        <div style="font-size: 10px; font-weight: 600; color: #94a3b8;">Th</div>
                        <div style="font-size: 10px; font-weight: 600; color: #94a3b8;">Fr</div>
                        <div style="font-size: 10px; font-weight: 600; color: #94a3b8;">Sa</div>
                    </div>
                    <div id="reschedCalendarDaysGrid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 3px; text-align: center;"></div>
                </div>
                <!-- Time Slots Side -->
                <div style="flex: 1; border-left: 1px solid #e2e8f0; padding-left: 12px; display: flex; flex-direction: column;">
                    <label style="display: block; font-size: 11.5px; font-weight: 600; color: #475569; margin-bottom: 6px;">Available Time Slots</label>
                    <div id="reschedPickerSlotsContainer" style="display: flex; flex-direction: column; gap: 5px; max-height: 190px; overflow-y: auto;">
                        <p style="color: #888; font-size: 11px; text-align: center; margin-top: 20px;">Select a date first.</p>
                    </div>
                </div>
            </div>
            <div id="reschedHolidayNotice" style="display: none; background: #fff5f5; border-left: 3px solid #e53e3e; padding: 6px 10px; border-radius: 0 6px 6px 0; margin-top: 10px; font-size: 11.5px; color: #9b2c2c;"></div>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
            <button type="button" id="closeModalBtn" style="padding: 8px 16px; background: #e2e8f0; color: #334155; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 13px;">Cancel</button>
            <button type="button" id="confirmRescheduleBtn" style="padding: 8px 16px; background: #5142f5; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 13px;">Save New Schedule</button>
        </div>
    </div>
</div>
<?php include("../includes/bottom-nav.php"); ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Script para sa Important Notice Popup Modal
        const importantNoticeModal = document.getElementById('importantNoticeModal');
        const agreeNoticeBtn = document.getElementById('agreeNoticeBtn');
        if (agreeNoticeBtn && importantNoticeModal) {
            agreeNoticeBtn.addEventListener('click', () => {
                importantNoticeModal.style.display = 'none';
            });
        }
        const toggleBtn = document.getElementById('datetimeToggleBtn');
        const popup = document.getElementById('datetimePopup');
        const selectedDateText = document.getElementById('selectedDateText');
        const selectedTimeText = document.getElementById('selectedTimeText');
        const hiddenDateInput = document.getElementById('appointmentDate');
        const hiddenTimeInput = document.getElementById('appointmentTime');
        const pickerSlotsContainer = document.getElementById('pickerSlotsContainer');
        const holidayNoticeBanner = document.getElementById('holidayNoticeBanner');
        const holidayNoticeText = document.getElementById('holidayNoticeText');
        const assignedDoctorSelect = document.getElementById('assignedDoctor');
        
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
        function isDoctorAvailableOnDay(dateObj, doctorName) {
            const dayOfWeek = dateObj.getDay(); 
            if (!doctorName) return false;
            
            if (doctorName === "Dr. Alfie Tamesis") {
                return (dayOfWeek === 1 || dayOfWeek === 0); 
            } else if (doctorName === "Dr. JK") {
                return (dayOfWeek >= 1 && dayOfWeek <= 4) || (dayOfWeek === 6 || dayOfWeek === 0); 
            } else if (doctorName === "Dr. Edmond") {
                return (dayOfWeek === 2 || dayOfWeek === 4 || dayOfWeek === 6); 
            } else if (doctorName === "Dr. Vitz") {
                return (dayOfWeek === 5); 
            }
            return true;
        }
        // Variable para itago ang bilang ng bookings kada petsa mula sa database
        window.dateBookingCounts = {};
        async function fetchDateBookingCounts() {
            try {
                // Dynamic import ng firebase functions mula sa appointment.js o inline query
                const q = (await import("https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js")).query;
                const collection = (await import("https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js")).collection;
                const getDocs = (await import("https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js")).getDocs;
                const dbInstance = (await import("./firebase-config.js")).db;
                const snapshot = await getDocs(collection(dbInstance, "appointments_v2"));
                const counts = {};
                snapshot.forEach(docSnap => {
                    const data = docSnap.data();
                    const status = (data.status || "").toLowerCase().trim();
                    // Bilangin lamang ang mga active o pending/approved appointments na hindi cancelled/archived
                    if (data.isArchived !== true && status !== "archived" && status !== "cancelled" && status !== "trash" && data.date) {
                        counts[data.date] = (counts[data.date] || 0) + 1;
                    }
                });
                window.dateBookingCounts = counts;
                renderCalendarDays();
            } catch (err) {
                console.error("Error fetching booking counts:", err);
            }
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
            
            const activeDoctor = assignedDoctorSelect ? assignedDoctorSelect.value : "";
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
                
                const formattedMonth = String(selectedMonth + 1).padStart(2, '0');
                const formattedDay = String(d).padStart(2, '0');
                const dateStr = `${selectedYear}-${formattedMonth}-${formattedDay}`;
                const bookingCount = window.dateBookingCounts ? (window.dateBookingCounts[dateStr] || 0) : 0;
                const isFullyBooked = bookingCount >= 10;
                let isDisabled = false;
                let holidayName = getHolidayName(currentCellDate);
                let isHoliday = holidayName !== null;
                let docAvailable = activeDoctor ? isDoctorAvailableOnDay(currentCellDate, activeDoctor) : true;
                if (currentCellDate < today || isHoliday || !docAvailable || isFullyBooked) {
                    isDisabled = true;
                }
                
                if (isDisabled) {
                    dayEl.classList.add('disabled', 'muted');
                    if (isFullyBooked) {
                        dayEl.classList.add('fully-booked');
                    } else {
                        dayEl.style.backgroundColor = isHoliday ? '#fee2e2' : '#f1f5f9';
                        dayEl.style.color = isHoliday ? '#991b1b' : '#cbd5e1';
                        dayEl.style.cursor = 'not-allowed';
                        dayEl.style.opacity = '0.7';
                    }
                } else {
                    if (d === selectedDay) {
                        dayEl.classList.add('selected');
                    }
                }
                dayEl.addEventListener('click', () => {
                    if (isDisabled) return;
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
        if (assignedDoctorSelect) {
            assignedDoctorSelect.addEventListener('change', () => {
                renderCalendarDays();
                loadPickerTimeSlots();
            });
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
            const activeDoctor = assignedDoctorSelect ? assignedDoctorSelect.value : "";
            if (!activeDoctor) {
                pickerSlotsContainer.innerHTML = '<p style="color: #888; font-size: 12px; text-align: center;">Please select a doctor first.</p>';
                return;
            }
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
                if (!assignedDoctorSelect.value) {
                    alert("Please select a doctor first.");
                    return;
                }
                popup.classList.toggle('active');
                fetchDateBookingCounts(); // Refresh booking limits when opened
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
            fetchDateBookingCounts();
            loadPickerTimeSlots();
        }
        updatePickerUI();
        fetchDateBookingCounts();
        // --- SCRIPT PARA SA IN-1 VACCINE MUTUAL EXCLUSION ---
        const in1Checkboxes = document.querySelectorAll('input[name="selectedServices"][data-in1="true"]');
        in1Checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                if (cb.checked) {
                    // I-disable ang iba pang in-1 checkboxes kapag may napili na isa
                    in1Checkboxes.forEach(otherCb => {
                        if (otherCb !== cb) {
                            otherCb.checked = false;
                            otherCb.disabled = true;
                            otherCb.closest('label').style.opacity = '0.5';
                        }
                    });
                } else {
                    // I-enable ulit lahat kapag na-uncheck
                    in1Checkboxes.forEach(otherCb => {
                        otherCb.disabled = false;
                        otherCb.closest('label').style.opacity = '1';
                    });
                }
            });
        });
        
        const totalFeeDisplay = document.getElementById('totalFeeDisplay');
        if (totalFeeDisplay) {
            totalFeeDisplay.textContent = '₱150';
        }
    });
</script>
<script type="module" src="../assets/js/appointment.js"></script>
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