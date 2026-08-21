<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment - Smart Vet Care</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="dashboard">

    <?php include("../includes/sidebar.php"); ?>

    <div class="main-content" style="background: #f4f7fe; min-height: 100vh; padding: 20px;">

        <?php include("../includes/topbar.php"); ?>

        <div class="appointment-container" style="max-width: 1200px; margin: 0 auto;">

            <div class="appointment-header" style="margin-bottom: 25px;">
                <h1>
                    <i class="fa-solid fa-calendar-check" style="background: #eef4ff; padding: 10px; border-radius: 12px;"></i>
                    Appointment
                </h1>
                <p>Select your pet, preferred schedule, and service.</p>
            </div>

            <div id="alertBox"></div>

            <div class="appointment-card">

                <div class="appointment-left">
                    <h3>
                        <i class="fa-solid fa-calendar-days"></i>
                        Appointment Date
                    </h3>
                    <p class="calendar-note">
                        Choose your preferred appointment date.
                    </p>
                    <input type="date" id="appointmentDate" class="appointment-date">
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
                            <option value="Dr. Alfie Tamasis">Dr. Alfie Tamasis</option>
                            <option value="Dr. James Nico Martinez">Dr. James Nico Martinez</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Available Time</label>
                        <div id="timeSlots" class="time-slots">
                            <p style="color: #888; font-size: 13px; grid-column: span 2;">Please select a date first.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Reason / Notes</label>
                        <textarea id="notes" placeholder="Describe your pet's condition..."></textarea>
                    </div>

                    <button id="bookAppointmentBtn" class="book-btn">
                        <i class="fa-solid fa-calendar-plus"></i>
                        Book Appointment
                    </button>

                </div>
            </div>

            <div class="user-appointments-section">
                <h3>
                    <i class="fa-solid fa-calendar-check"></i>
                    Your Booked Appointments
                </h3>

                <div id="userAppointmentsContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                    <p style="color: #777; font-size: 13px;">Loading your appointments...</p>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="module" src="../assets/js/appointment.js"></script>

</body>
</html>