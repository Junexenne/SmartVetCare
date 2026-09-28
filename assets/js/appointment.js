import { auth, db } from "./firebase-config.js";
import {
    collection,
    query,
    where,
    getDocs,
    addDoc,
    doc,
    updateDoc,
    Timestamp
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import {
    onAuthStateChanged
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

document.addEventListener("DOMContentLoaded", () => {
    const petSelect = document.getElementById("petSelect");
    const appointmentDateInput = document.getElementById("appointmentDate");
    const appointmentTimeInput = document.getElementById("appointmentTime");
    const timeSlotsContainer = document.getElementById("pickerSlotsContainer");
    
    const serviceSelect = document.getElementById("service");
    const doctorSelect = document.getElementById("doctor");
    const notesInput = document.getElementById("notes");
    const bookBtn = document.getElementById("bookAppointmentBtn");
    const alertBox = document.getElementById("alertBox");

    // Modal elements (Reschedule)
    const rescheduleModal = document.getElementById("rescheduleModal");
    const rescheduleApptIdInput = document.getElementById("rescheduleApptId");
    const rescheduleDateInput = document.getElementById("rescheduleDate");
    const rescheduleTimeSlotsContainer = document.getElementById("rescheduleTimeSlots");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const confirmRescheduleBtn = document.getElementById("confirmRescheduleBtn");

    // Calendar Picker UI Elements (Custom Popover)
    const calendarGrid = document.getElementById("calendarGrid"); // I-adjust kung iba ID ng grid mo
    const currentMonthYearLabel = document.getElementById("currentMonthYear");
    const prevMonthBtn = document.getElementById("prevMonthBtn");
    const nextMonthBtn = document.getElementById("nextMonthBtn");
    const dateInputDisplay = document.getElementById("selectedDateText"); // o kung saan ipinapakita ang text

    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth(); // 0-indexed

    // Dynamic Custom Cancel Modal Creation
    let cancelModal = document.getElementById("cancelModal");
    if (!cancelModal) {
        cancelModal = document.createElement("div");
        cancelModal.id = "cancelModal";
        cancelModal.style.cssText = "display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;";
        cancelModal.innerHTML = `
            <div style="background: #fff; padding: 25px; border-radius: 14px; width: 360px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; font-family: inherit;">
                <div style="font-size: 36px; color: #dc2626; margin-bottom: 10px;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <h3 style="color: #1e1b4b; margin-bottom: 8px; font-size: 18px; font-weight: 700;">Cancel Appointment</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 20px; line-height: 1.5;">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
                <input type="hidden" id="cancelApptIdTarget">
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button id="closeCancelModalBtn" style="flex: 1; padding: 10px; border: 1px solid #cbd5e1; background: #fff; color: #334155; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">No, Keep It</button>
                    <button id="confirmCancelBtn" style="flex: 1; padding: 10px; border: none; background: #dc2626; color: #fff; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">Yes, Cancel</button>
                </div>
            </div>
        `;
        document.body.appendChild(cancelModal);
    }

    const cancelApptIdTarget = document.getElementById("cancelApptIdTarget");
    const closeCancelModalBtn = document.getElementById("closeCancelModalBtn");
    const confirmCancelBtn = document.getElementById("confirmCancelBtn");

    let selectedTimeSlot = null;
    let selectedRescheduleTimeSlot = null;
    let currentOwnerId = null;

    // Dynamic Doctor & Date Validation Helper (Real-time `new Date()`)
    function isDateAllowedForDoctor(year, month, day, doctorName) {
        const targetDate = new Date(year, month, day);
        const now = new Date();
        const todayMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        
        // 1. I-check kung lumipas na ang date kumpara sa tunay na ngayon
        if (targetDate < todayMidnight) {
            return false;
        }

        // 2. I-check ang availability ni Dr. Alfie Tamesis (Monday to Wednesday)
        if (doctorName && doctorName.toLowerCase().includes("alfie")) {
            const dayOfWeek = targetDate.getDay(); // 0 = Sun, 1 = Mon, ..., 6 = Sat
            if (dayOfWeek < 1 || dayOfWeek > 3) {
                return false; // Kapag Thu (4), Fri (5), Sat (6), o Sun (0), i-disable
            }
        }
        
        return true;
    }

    // Render Custom Calendar Grid (Kung gumagamit ka ng custom grid rendering)
    function renderCustomCalendar() {
        if (!calendarGrid) return;
        calendarGrid.innerHTML = "";

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        if (currentMonthYearLabel) {
            currentMonthYearLabel.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        }

        const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay();
        const totalDaysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const selectedDoctor = doctorSelect ? doctorSelect.value : "";

        // Empty cells para sa offset
        for (let i = 0; i < firstDayIndex; i++) {
            const emptyCell = document.createElement("div");
            calendarGrid.appendChild(emptyCell);
        }

        // Days loop
        for (let day = 1; day <= totalDaysInMonth; day++) {
            const dayCell = document.createElement("div");
            dayCell.textContent = day;
            dayCell.className = "calendar-day-item"; // I-match sa CSS mo

            const allowed = isDateAllowedForDoctor(currentYear, currentMonth, day, selectedDoctor);
            const formattedDateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            if (!allowed) {
                dayCell.classList.add("disabled", "grayed-out");
                dayCell.style.cssText = "background: #f1f5f9; color: #94a3b8; cursor: not-allowed; opacity: 0.6; padding: 8px; text-align: center; border-radius: 8px;";
            } else {
                dayCell.style.cssText = "cursor: pointer; background: #fff; color: #334155; padding: 8px; text-align: center; border-radius: 8px; transition: 0.2s;";
                dayCell.addEventListener("click", () => {
                    document.querySelectorAll(".calendar-day-item.selected").forEach(el => el.classList.remove("selected"));
                    dayCell.classList.add("selected");
                    if (appointmentDateInput) {
                        appointmentDateInput.value = formattedDateStr;
                        appointmentDateInput.dispatchEvent(new Event('change'));
                    }
                    if (dateInputDisplay) {
                        dateInputDisplay.textContent = formattedDateStr;
                    }
                });
            }

            calendarGrid.appendChild(dayCell);
        }
    }

    if (doctorSelect) {
        doctorSelect.addEventListener("change", () => {
            renderCustomCalendar();
            // Kung may existing selected date na, i-check din kung valid pa sa bagong doctor
            if (appointmentDateInput && appointmentDateInput.value) {
                const parts = appointmentDateInput.value.split('-');
                if (parts.length === 3) {
                    const y = parseInt(parts[0], 10);
                    const m = parseInt(parts, 10) - 1;
                    const d = parseInt(parts, 10);
                    if (!isDateAllowedForDoctor(y, m, d, doctorSelect.value)) {
                        appointmentDateInput.value = "";
                        if (timeSlotsContainer) timeSlotsContainer.innerHTML = `<p style="color: #dc2626; font-size: 13px;">Selected date is not available for this doctor. Please pick another date.</p>`;
                    }
                }
            }
        });
    }

    if (prevMonthBtn) {
        prevMonthBtn.addEventListener("click", () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCustomCalendar();
        });
    }

    if (nextMonthBtn) {
        nextMonthBtn.addEventListener("click", () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCustomCalendar();
        });
    }

    // Initial render call kung active ang custom calendar
    renderCustomCalendar();

    // 1. Authentication and loading user pets/appointments
    onAuthStateChanged(auth, async (user) => {
        if (!user) {
            showAlert("Please log in to book an appointment.", "error");
            return;
        }

        try {
            const userQuery = query(
                collection(db, "users"),
                where("email", "==", user.email)
            );
            const userSnap = await getDocs(userQuery);

            if (!userSnap.empty) {
                currentOwnerId = userSnap.docs[0].data().ownerId;
                loadUserPets(currentOwnerId);
                loadUserAppointments(currentOwnerId);
            } else {
                showAlert("User profile not found in database.", "error");
            }
        } catch (error) {
            console.error("Error fetching user session:", error);
            showAlert("Failed to load user information.", "error");
        }
    });

    // 2. Fetch registered pets for dropdown
    async function loadUserPets(ownerId) {
        try {
            const petsQuery = query(
                collection(db, "pets"),
                where("ownerId", "==", ownerId)
            );
            const petsSnap = await getDocs(petsQuery);

            petSelect.innerHTML = '<option value="">Select your pet</option>';

            if (petsSnap.empty) {
                petSelect.innerHTML = '<option value="">No registered pets found</option>';
                return;
            }

            petsSnap.forEach((docSnap) => {
                const petData = docSnap.data();
                const option = document.createElement("option");
                option.value = petData.petName || docSnap.id;
                option.textContent = petData.petName || 'Unnamed Pet';
                petSelect.appendChild(option);
            });
        } catch (error) {
            console.error("Error loading pets:", error);
            showAlert("Failed to load your pets.", "error");
        }
    }

    // 3. Generate Time Slots (Booking Form - Sync sa hidden/custom UI input)
    const availableTimes = ["09:00 AM", "10:30 AM", "01:00 PM", "02:30 PM", "04:00 PM"];
    
    if (appointmentDateInput) {
        appointmentDateInput.addEventListener("change", async () => {
            const selectedDate = appointmentDateInput.value;
            if (timeSlotsContainer) timeSlotsContainer.innerHTML = "";
            selectedTimeSlot = null;

            if (!selectedDate) {
                if (timeSlotsContainer) timeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Please select a date first.</p>`;
                return;
            }

            let bookedTimes = [];
            try {
                const apptQuery = query(
                    collection(db, "appointments"),
                    where("date", "==", selectedDate)
                );
                const apptSnap = await getDocs(apptQuery);
                
                apptSnap.forEach((docSnap) => {
                    const data = docSnap.data();
                    const st = (data.status || "").toLowerCase().trim();
                    if (st !== "cancelled" && st !== "archived" && st !== "trash") {
                        if (data.timeSlot) {
                            bookedTimes.push(data.timeSlot.trim());
                        }
                    }
                });
            } catch (error) {
                console.error("Error checking date availability:", error);
            }

            if (!timeSlotsContainer) return;

            availableTimes.forEach((time) => {
                const slotBtn = document.createElement("div");
                slotBtn.textContent = time;
                slotBtn.className = "picker-time-slot";

                const isBooked = bookedTimes.includes(time);

                if (isBooked) {
                    slotBtn.classList.add("booked", "disabled");
                    slotBtn.style.cssText = "padding: 8px 12px; background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 8px; font-size: 13px; font-weight: 500; text-align: center; cursor: not-allowed; opacity: 0.8;";
                    slotBtn.textContent = `${time} (Booked)`;
                } else {
                    slotBtn.addEventListener("click", () => {
                        if (slotBtn.classList.contains("disabled") || slotBtn.classList.contains("booked")) return;
                        
                        document.querySelectorAll(".picker-time-slot").forEach(b => {
                            b.classList.remove("selected");
                            b.style.background = "#f8fafc";
                            b.style.color = "#334155";
                            b.style.borderColor = "#e2e8f0";
                        });
                        slotBtn.classList.add("selected");
                        slotBtn.style.background = "#5142f5";
                        slotBtn.style.color = "#fff";
                        slotBtn.style.borderColor = "#5142f5";
                        
                        selectedTimeSlot = time;
                        if (appointmentTimeInput) appointmentTimeInput.value = time;
                    });
                }

                timeSlotsContainer.appendChild(slotBtn);
            });
        });
    }

    // Date formatting helper
    function parseDateString(dateStr) {
        if (!dateStr) return new Date(0);
        if (dateStr.includes('/')) {
            const parts = dateStr.split('/');
            if (parts.length === 3) {
                return new Date(`${parts}-${parts}-${parts[0]}`);
            }
        }
        return new Date(dateStr);
    }

    // 4. Fetch and Display User's Booked Appointments
    async function loadUserAppointments(ownerId) {
        const container = document.getElementById("userAppointmentsContainer");
        if (!container) return;

        try {
            const q = query(
                collection(db, "appointments"),
                where("ownerId", "==", ownerId)
            );
            const snapshot = await getDocs(q);

            container.innerHTML = "";

            if (snapshot.empty) {
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #64748b;">
                        <p>You have no booked appointments yet.</p>
                    </div>
                `;
                return;
            }

            let appointmentsList = [];
            snapshot.forEach((docSnap) => {
                const apptData = docSnap.data();
                const status = (apptData.status || "").toLowerCase().trim();
                
                if (status !== "archived" && status !== "trash" && status !== "cancelled") {
                    appointmentsList.push({ id: docSnap.id, ...apptData });
                }
            });

            if (appointmentsList.length === 0) {
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #64748b;">
                        <p>You have no active or completed appointments.</p>
                    </div>
                `;
                return;
            }

            appointmentsList.sort((a, b) => {
                const dateA = parseDateString(a.date);
                const dateB = parseDateString(b.date);
                return dateB - dateA; 
            });

            let groupedAppointments = {};
            appointmentsList.forEach(appt => {
                const d = parseDateString(appt.date);
                const monthYear = d.toLocaleString('en-US', { month: 'long', year: 'numeric' });
                
                if (!groupedAppointments[monthYear]) {
                    groupedAppointments[monthYear] = [];
                }
                groupedAppointments[monthYear].push(appt);
            });

            for (const [monthYear, appts] of Object.entries(groupedAppointments)) {
                container.insertAdjacentHTML("beforeend", `
                    <div style="grid-column: 1 / -1; margin-top: 15px; margin-bottom: 5px; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px;">
                        <h3 style="color: #4f46e5; font-size: 18px; font-weight: 700;"><i class="fa-regular fa-calendar-days"></i> For the Month of ${monthYear}</h3>
                    </div>
                `);

                appts.forEach((appt) => {
                    let statusBg = "#fef3c7";
                    let statusColor = "#d97706";

                    const currentStatus = (appt.status || "Pending").trim();
                    const statusLower = currentStatus.toLowerCase();

                    if (statusLower === "completed") {
                        statusBg = "#E1FDF4";
                        statusColor = "#065F46";
                    } else if (statusLower === "confirmed" || statusLower === "approved") {
                        statusBg = "#dcfce7";
                        statusColor = "#16a34a";
                    }

                    const isUrgent = appt.service === "Surgery" || (appt.notes && (appt.notes.toLowerCase().includes("emergency") || appt.notes.toLowerCase().includes("parvo")));
                    const canModify = statusLower === "pending" || statusLower === "confirmed" || statusLower === "approved";
                    const isCompleted = statusLower === "completed";

                    container.insertAdjacentHTML("beforeend", `
                        <div style="background: #f8fafc; border: 1px solid ${isUrgent ? '#f87171' : '#e2e8f0'}; border-radius: 14px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <span style="background: ${statusBg}; color: ${statusColor}; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        ${currentStatus}
                                    </span>
                                    <small style="color: #64748b; font-size: 12px;"><i class="fa-solid fa-paw"></i> ${appt.petName || "Unnamed Pet"}</small>
                                </div>
                                
                                ${isUrgent ? '<div style="margin-bottom: 8px;"><span style="background: #fee2e2; color: #b91c1c; padding: 3px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; text-transform: uppercase; display: inline-block;"><i class="fa-solid fa-triangle-exclamation"></i> Urgent / Emergency</span></div>' : ''}

                                <h4 style="color: #1e1b4b; font-size: 16px; margin-bottom: 8px;">${appt.service}</h4>
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-user-doctor" style="width: 18px; color: #5142f5;"></i> ${appt.doctor || "Doctor not specified"}</p>
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-calendar-days" style="width: 18px; color: #5142f5;"></i> ${appt.date} (${appt.timeSlot || "Time not set"})</p>
                                ${appt.notes ? `<p style="color: #64748b; font-size: 12px; margin-top: 8px; font-style: italic;">Note: "${appt.notes}"</p>` : ""}
                            </div>

                            ${canModify ? `
                                <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; justify-content: flex-end; gap: 8px;">
                                    <button class="cancel-appt-btn" data-id="${appt.id}" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                        <i class="fa-solid fa-ban"></i> Cancel
                                    </button>
                                    <button class="reschedule-trigger-btn" data-id="${appt.id}" style="background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                        <i class="fa-solid fa-calendar-pen"></i> Reschedule
                                    </button>
                                </div>
                            ` : ''}

                            ${isCompleted ? `
                                <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; justify-content: flex-end; gap: 8px;">
                                    <button class="archive-appt-btn" data-id="${appt.id}" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                        <i class="fa-solid fa-box-archive"></i> Archive
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    `);
                });
            }

            document.querySelectorAll(".reschedule-trigger-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    const apptId = btn.getAttribute("data-id");
                    openRescheduleModal(apptId);
                });
            });

            document.querySelectorAll(".cancel-appt-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    const apptId = btn.getAttribute("data-id");
                    cancelApptIdTarget.value = apptId;
                    cancelModal.style.display = "flex";
                });
            });

            document.querySelectorAll(".archive-appt-btn").forEach(btn => {
                btn.addEventListener("click", async () => {
                    const apptId = btn.getAttribute("data-id");
                    if (!apptId) return;

                    btn.disabled = true;
                    btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Archiving...`;

                    try {
                        const apptRef = doc(db, "appointments", apptId);
                        await updateDoc(apptRef, {
                            status: "Archived"
                        });
                        showAlert("Appointment moved to archive successfully.", "success");
                        loadUserAppointments(currentOwnerId);
                    } catch (error) {
                        console.error("Error archiving appointment:", error);
                        showAlert("Failed to archive appointment.", "error");
                        btn.disabled = false;
                        btn.innerHTML = `<i class="fa-solid fa-box-archive"></i> Archive`;
                    }
                });
            });

        } catch (error) {
            console.error("Error loading appointments:", error);
            container.innerHTML = `<p style="color: #dc2626; font-size: 14px;">Failed to load your appointments.</p>`;
        }
    }

    if (closeCancelModalBtn) {
        closeCancelModalBtn.addEventListener("click", () => {
            cancelModal.style.display = "none";
        });
    }

    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener("click", async () => {
            const apptId = cancelApptIdTarget.value;
            if (!apptId) return;

            confirmCancelBtn.disabled = true;
            confirmCancelBtn.textContent = "Cancelling...";

            try {
                const apptRef = doc(db, "appointments", apptId);
                await updateDoc(apptRef, {
                    status: "Cancelled"
                });
                cancelModal.style.display = "none";
                showAlert("Appointment cancelled successfully.", "success");
                loadUserAppointments(currentOwnerId);
            } catch (error) {
                console.error("Error cancelling appointment:", error);
                showAlert("Failed to cancel appointment.", "error");
            } finally {
                confirmCancelBtn.disabled = false;
                confirmCancelBtn.textContent = "Yes, Cancel";
            }
        });
    }

    // 5. Open Reschedule Modal Logic
    function openRescheduleModal(apptId) {
        rescheduleApptIdInput.value = apptId;
        rescheduleDateInput.value = "";
        selectedRescheduleTimeSlot = null;
        rescheduleTimeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Please select a new date first.</p>`;
        rescheduleModal.style.display = "flex";
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            rescheduleModal.style.display = "none";
        });
    }

    if (rescheduleDateInput) {
        rescheduleDateInput.addEventListener("change", async () => {
            const newDate = rescheduleDateInput.value;
            rescheduleTimeSlotsContainer.innerHTML = "";
            selectedRescheduleTimeSlot = null;

            if (!newDate) {
                rescheduleTimeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Please select a new date first.</p>`;
                return;
            }

            let bookedTimes = [];
            try {
                const apptQuery = query(
                    collection(db, "appointments"),
                    where("date", "==", newDate)
                );
                const apptSnap = await getDocs(apptQuery);
                
                apptSnap.forEach((docSnap) => {
                    const data = docSnap.data();
                    const st = (data.status || "").toLowerCase().trim();
                    if (st !== "cancelled" && st !== "archived" && st !== "trash") {
                        if (data.timeSlot) {
                            bookedTimes.push(data.timeSlot.trim());
                        }
                    }
                });
            } catch (error) {
                console.error("Error checking date availability for reschedule:", error);
            }

            availableTimes.forEach((time) => {
                const slotBtn = document.createElement("button");
                slotBtn.type = "button";
                slotBtn.textContent = time;
                slotBtn.className = "res-time-slot-btn";

                const isBooked = bookedTimes.includes(time);

                if (isBooked) {
                    slotBtn.disabled = true;
                    slotBtn.style.cssText = "padding: 6px 10px; margin: 3px; border: 1px solid #fecaca; background: #fee2e2; color: #b91c1c; border-radius: 6px; cursor: not-allowed; opacity: 0.8; font-size: 11px;";
                    slotBtn.textContent = `${time} (Booked)`;
                } else {
                    slotBtn.style.cssText = "padding: 6px 10px; margin: 3px; border: 1px solid #cbd5e1; background: #fff; color: #333; border-radius: 6px; cursor: pointer; transition: all 0.2s; font-size: 11px;";
                    
                    slotBtn.addEventListener("click", () => {
                        document.querySelectorAll(".res-time-slot-btn:not([disabled])").forEach(b => {
                            b.style.background = "#fff";
                            b.style.color = "#333";
                            b.style.borderColor = "#cbd5e1";
                        });
                        slotBtn.style.background = "#5142f5";
                        slotBtn.style.color = "#fff";
                        slotBtn.style.borderColor = "#5142f5";
                        selectedRescheduleTimeSlot = time;
                    });
                }

                rescheduleTimeSlotsContainer.appendChild(slotBtn);
            });
        });
    }

    if (confirmRescheduleBtn) {
        confirmRescheduleBtn.addEventListener("click", async () => {
            const apptId = rescheduleApptIdInput.value;
            const newDate = rescheduleDateInput.value;

            if (!newDate) {
                alert("Please select a new date.");
                return;
            }
            if (!selectedRescheduleTimeSlot) {
                alert("Please select an available time slot.");
                return;
            }

            confirmRescheduleBtn.disabled = true;
            confirmRescheduleBtn.textContent = "Saving...";

            try {
                const doubleCheckQuery = query(
                    collection(db, "appointments"),
                    where("date", "==", newDate),
                    where("timeSlot", "==", selectedRescheduleTimeSlot)
                );
                const dcSnap = await getDocs(doubleCheckQuery);
                let isTaken = false;
                dcSnap.forEach(d => {
                    const st = (d.data().status || "").toLowerCase().trim();
                    if (st !== "cancelled" && st !== "archived" && st !== "trash") {
                        isTaken = true;
                    }
                });

                if (isTaken) {
                    alert("Sorry, this time slot has just been taken. Please choose another.");
                    confirmRescheduleBtn.disabled = false;
                    confirmRescheduleBtn.textContent = "Save New Schedule";
                    rescheduleDateInput.dispatchEvent(new Event('change'));
                    return;
                }

                const apptRef = doc(db, "appointments", apptId);
                await updateDoc(apptRef, {
                    date: newDate,
                    timeSlot: selectedRescheduleTimeSlot,
                    status: "Pending"
                });

                rescheduleModal.style.display = "none";
                showAlert("Appointment successfully rescheduled!", "success");
                loadUserAppointments(currentOwnerId);

            } catch (error) {
                console.error("Error updating appointment schedule:", error);
                showAlert("Failed to reschedule appointment.", "error");
            } finally {
                confirmRescheduleBtn.disabled = false;
                confirmRescheduleBtn.textContent = "Save New Schedule";
            }
        });
    }

    // 6. Booking Process
    if (bookBtn) {
        bookBtn.addEventListener("click", async () => {
            if (!currentOwnerId) {
                showAlert("Authentication session not ready.", "error");
                return;
            }

            const petName = petSelect.value;
            const service = serviceSelect.value;
            const doctor = doctorSelect.value;
            const dateVal = appointmentDateInput.value;
            const timeVal = appointmentTimeInput ? appointmentTimeInput.value : selectedTimeSlot;
            const notes = notesInput.value.trim();

            if (!petName) { showAlert("Please select a pet.", "error"); return; }
            if (!service) { showAlert("Please select a service.", "error"); return; }
            if (!doctor) { showAlert("Please select a doctor.", "error"); return; }
            if (!dateVal) { showAlert("Please select an appointment date.", "error"); return; }
            if (!timeVal) { showAlert("Please select an available time slot.", "error"); return; }

            bookBtn.disabled = true;
            bookBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Submitting...`;

            try {
                const doubleCheckQuery = query(
                    collection(db, "appointments"),
                    where("date", "==", dateVal),
                    where("timeSlot", "==", timeVal)
                );
                const dcSnap = await getDocs(doubleCheckQuery);
                let isTaken = false;
                dcSnap.forEach(d => {
                    const st = (d.data().status || "").toLowerCase().trim();
                    if (st !== "cancelled" && st !== "archived" && st !== "trash") {
                        isTaken = true;
                    }
                });

                if (isTaken) {
                    showAlert("Sorry, this time slot has just been booked by another patient. Please choose another slot.", "error");
                    bookBtn.disabled = false;
                    bookBtn.innerHTML = `<i class="fa-solid fa-calendar-plus"></i> Book Appointment`;
                    appointmentDateInput.dispatchEvent(new Event('change'));
                    return;
                }

                await addDoc(collection(db, "appointments"), {
                    ownerId: currentOwnerId,
                    petName: petName,
                    service: service,
                    doctor: doctor,
                    date: dateVal,
                    timeSlot: timeVal,
                    notes: notes,
                    status: "Pending",
                    createdAt: Timestamp.now()
                });

                showAlert("Appointment successfully booked!", "success");

                petSelect.selectedIndex = 0;
                serviceSelect.selectedIndex = 0;
                doctorSelect.selectedIndex = 0;
                appointmentDateInput.value = "";
                if (appointmentTimeInput) appointmentTimeInput.value = "";
                if (timeSlotsContainer) timeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Please select a date first.</p>`;
                notesInput.value = "";
                selectedTimeSlot = null;

                const dateTextEl = document.getElementById("selectedDateText");
                const timeTextEl = document.getElementById("selectedTimeText");
                if (dateTextEl) dateTextEl.textContent = "Select Date";
                if (timeTextEl) timeTextEl.textContent = "--:-- --";

                loadUserAppointments(currentOwnerId);

            } catch (error) {
                console.error("Error booking appointment:", error);
                showAlert("Failed to book appointment. Please try again.", "error");
            } finally {
                bookBtn.disabled = false;
                bookBtn.innerHTML = `<i class="fa-solid fa-calendar-plus"></i> Book Appointment`;
            }
        });
    }

    function showAlert(message, type) {
        if (!alertBox) return;
        alertBox.textContent = message;
        alertBox.className = type === 'success' ? 'alert-success' : 'alert-error';
        alertBox.style.display = 'block';

        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }
});