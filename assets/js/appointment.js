import { auth, db } from "./firebase-config.js";
import {
    collection,
    query,
    where,
    getDocs,
    doc,
    getDoc,
    setDoc,
    updateDoc,
    Timestamp
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import {
    onAuthStateChanged
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";
document.addEventListener("DOMContentLoaded", () => {
    const petsCheckboxContainer = document.getElementById("petsCheckboxContainer");
    const appointmentDateInput = document.getElementById("appointmentDate");
    const appointmentTimeInput = document.getElementById("appointmentTime");
    const timeSlotsContainer = document.getElementById("pickerSlotsContainer");
    const assignedDoctorSelect = document.getElementById("assignedDoctor");
    const referenceNumberInput = document.getElementById("referenceNumberInput");
    
    if (referenceNumberInput) {
        referenceNumberInput.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, "");
        });
    }
    // New Pet Toggle Elements para sa First-time Users
    const toggleNewPetInput = document.getElementById("toggleNewPetInput");
    const newPetFieldsContainer = document.getElementById("newPetFieldsContainer");
    const newPetNameInput = document.getElementById("newPetNameInput");
    const newPetSpeciesInput = document.getElementById("newPetSpeciesInput");
    const newPetBreedInput = document.getElementById("newPetBreedInput");
    if (toggleNewPetInput && newPetFieldsContainer) {
        toggleNewPetInput.addEventListener("change", (e) => {
            if (e.target.checked) {
                newPetFieldsContainer.style.display = "flex";
            } else {
                newPetFieldsContainer.style.display = "none";
                if (newPetNameInput) newPetNameInput.value = "";
                if (newPetBreedInput) newPetBreedInput.value = "";
            }
        });
    }
    
    const notesInput = document.getElementById("notes");
    const bookBtn = document.getElementById("bookAppointmentBtn");
    const alertBox = document.getElementById("alertBox");
    // Reschedule Elements
    const rescheduleModal = document.getElementById("rescheduleModal");
    const rescheduleApptIdInput = document.getElementById("rescheduleApptId");
    const rescheduleDoctorSelect = document.getElementById("rescheduleDoctor");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const confirmRescheduleBtn = document.getElementById("confirmRescheduleBtn");
    // Reschedule Inline Calendar Elements
    const reschedHiddenDateInput = document.getElementById("rescheduleDate");
    const reschedHiddenTimeInput = document.getElementById("rescheduleTime");
    const reschedPickerSlotsContainer = document.getElementById("reschedPickerSlotsContainer");
    const reschedHolidayNotice = document.getElementById("reschedHolidayNotice");
    const reschedMonthSelect = document.getElementById("reschedMonthSelect");
    const reschedYearSelect = document.getElementById("reschedYearSelect");
    const reschedPrevMonthBtn = document.getElementById("reschedPrevMonthBtn");
    const reschedNextMonthBtn = document.getElementById("reschedNextMonthBtn");
    const reschedCalendarDaysGrid = document.getElementById("reschedCalendarDaysGrid");
    let reschedYear = new Date().getFullYear();
    let reschedMonth = new Date().getMonth();
    let reschedDay = null;
    let reschedSelectedTimeValue = "";
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    if (reschedMonthSelect && reschedYearSelect) {
        monthNames.forEach((m, idx) => {
            const opt = document.createElement('option');
            opt.value = idx;
            opt.textContent = m;
            reschedMonthSelect.appendChild(opt);
        });
        for (let y = reschedYear - 2; y <= reschedYear + 5; y++) {
            const opt = document.createElement('option');
            opt.value = y;
            opt.textContent = y;
            reschedYearSelect.appendChild(opt);
        }
    }
    if (reschedMonthSelect) {
        reschedMonthSelect.addEventListener('change', (e) => {
            reschedMonth = parseInt(e.target.value);
            updateReschedCalendarUI();
        });
    }
    if (reschedYearSelect) {
        reschedYearSelect.addEventListener('change', (e) => {
            reschedYear = parseInt(e.target.value);
            updateReschedCalendarUI();
        });
    }
    if (reschedPrevMonthBtn) {
        reschedPrevMonthBtn.addEventListener('click', () => {
            reschedMonth--;
            if (reschedMonth < 0) { reschedMonth = 11; reschedYear--; }
            updateReschedCalendarUI();
        });
    }
    if (reschedNextMonthBtn) {
        reschedNextMonthBtn.addEventListener('click', () => {
            reschedMonth++;
            if (reschedMonth > 11) { reschedMonth = 0; reschedYear++; }
            updateReschedCalendarUI();
        });
    }
    function updateReschedCalendarUI() {
        if (reschedMonthSelect) reschedMonthSelect.value = reschedMonth;
        if (reschedYearSelect) reschedYearSelect.value = reschedYear;
        renderReschedCalendarDays();
        loadReschedTimeSlots();
    }
    function renderReschedCalendarDays() {
        if (!reschedCalendarDaysGrid) return;
        reschedCalendarDaysGrid.innerHTML = '';
        const firstDayIndex = new Date(reschedYear, reschedMonth, 1).getDay();
        const totalDays = new Date(reschedYear, reschedMonth + 1, 0).getDate();
        const prevTotalDays = new Date(reschedYear, reschedMonth, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const activeDoctor = rescheduleDoctorSelect ? rescheduleDoctorSelect.value : "";
        for (let i = firstDayIndex; i > 0; i--) {
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day muted';
            dayEl.textContent = prevTotalDays - i + 1;
            dayEl.style.cssText = "aspect-ratio: 1; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #cbd5e1;";
            reschedCalendarDaysGrid.appendChild(dayEl);
        }
        for (let d = 1; d <= totalDays; d++) {
            const dayEl = document.createElement('div');
            dayEl.className = 'calendar-day';
            dayEl.textContent = d;
            dayEl.style.cssText = "aspect-ratio: 1; display: flex; align-items: center; justify-content: center; font-size: 11.5px; font-weight: 500; color: #334155; border-radius: 6px; cursor: pointer;";
            
            const currentCellDate = new Date(reschedYear, reschedMonth, d);
            currentCellDate.setHours(0, 0, 0, 0);
            let holidayName = getHolidayName(currentCellDate);
            let isHoliday = holidayName !== null;
            let docAvailable = activeDoctor ? isDoctorAvailableOnDay(currentCellDate, activeDoctor) : true;
            let isDisabled = false;
            if (currentCellDate < today || isHoliday || !docAvailable) {
                isDisabled = true;
            }
            if (isDisabled) {
                dayEl.style.backgroundColor = isHoliday ? '#fee2e2' : '#f1f5f9';
                dayEl.style.color = isHoliday ? '#991b1b' : '#cbd5e1';
                dayEl.style.cursor = 'not-allowed';
                dayEl.style.opacity = '0.7';
            } else {
                dayEl.addEventListener('click', () => {
                    if (isDisabled) return;
                    reschedDay = d;
                    const formattedMonth = String(reschedMonth + 1).padStart(2, '0');
                    const formattedDay = String(d).padStart(2, '0');
                    reschedHiddenDateInput.value = `${reschedYear}-${formattedMonth}-${formattedDay}`;
                    
                    renderReschedCalendarDays();
                    loadReschedTimeSlots();
                    if (holidayName) {
                        reschedHolidayNotice.textContent = `${holidayName} (${currentCellDate.toLocaleDateString()})`;
                        reschedHolidayNotice.style.display = "block";
                    } else {
                        reschedHolidayNotice.style.display = "none";
                    }
                });
            }
            const formattedMonth = String(reschedMonth + 1).padStart(2, '0');
            const formattedDay = String(d).padStart(2, '0');
            const cellDateStr = `${reschedYear}-${formattedMonth}-${formattedDay}`;
            if (!isDisabled && reschedHiddenDateInput.value === cellDateStr) {
                dayEl.style.background = "#5142f5";
                dayEl.style.color = "#ffffff";
                dayEl.style.fontWeight = "600";
            }
            reschedCalendarDaysGrid.appendChild(dayEl);
        }
    }
    function loadReschedTimeSlots() {
        if (!reschedPickerSlotsContainer) return;
        reschedPickerSlotsContainer.innerHTML = '';
        
        if (!reschedHiddenDateInput.value) {
            reschedPickerSlotsContainer.innerHTML = '<p style="color: #888; font-size: 11px; text-align: center; margin-top: 20px;">Select a date first.</p>';
            return;
        }
        let standardSlots = ["10:00 AM", "11:30 AM", "01:00 PM", "02:30 PM", "04:00 PM", "05:30 PM"];
        const selectedDateObj = new Date(reschedHiddenDateInput.value + "T00:00:00");
        const holidayTitle = getHolidayName(selectedDateObj);
        if (holidayTitle) {
            reschedPickerSlotsContainer.innerHTML = `<div style="background: #fee2e2; color: #991b1b; padding: 8px; border-radius: 6px; font-size: 11px; text-align: center;">Clinic Closed (${holidayTitle}).</div>`;
            return;
        }
        standardSlots.forEach(timeStr => {
            const slotDiv = document.createElement('div');
            slotDiv.className = 'picker-time-slot';
            slotDiv.textContent = timeStr;
            slotDiv.style.cssText = "padding: 6px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 11.5px; text-align: center; cursor: pointer;";
            
            if (reschedSelectedTimeValue === timeStr) {
                slotDiv.style.background = "#5142f5";
                slotDiv.style.color = "#fff";
                slotDiv.style.borderColor = "#5142f5";
            }
            slotDiv.addEventListener('click', () => {
                document.querySelectorAll('#reschedPickerSlotsContainer .picker-time-slot').forEach(el => {
                    el.style.background = "#f8fafc";
                    el.style.color = "#334155";
                    el.style.borderColor = "#e2e8f0";
                });
                slotDiv.style.background = "#5142f5";
                slotDiv.style.color = "#ffffff";
                slotDiv.style.borderColor = "#5142f5";
                reschedSelectedTimeValue = timeStr;
                reschedHiddenTimeInput.value = timeStr;
            });
            reschedPickerSlotsContainer.appendChild(slotDiv);
        });
    }
    if (rescheduleDoctorSelect) {
        rescheduleDoctorSelect.addEventListener('change', () => {
            updateReschedCalendarUI();
        });
    }
    // Dynamic Custom Cancel Modal na may Notes for Cancellation
    let cancelModal = document.getElementById("cancelModal");
    if (!cancelModal) {
        cancelModal = document.createElement("div");
        cancelModal.id = "cancelModal";
        cancelModal.style.cssText = "display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; padding: 15px;";
        cancelModal.innerHTML = `
            <div style="background: #fff; padding: 25px; border-radius: 14px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: left; font-family: inherit;">
                <div style="text-align: center; font-size: 36px; color: #dc2626; margin-bottom: 10px;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <h3 style="color: #1e1b4b; margin-bottom: 8px; font-size: 18px; font-weight: 700; text-align: center;">Cancel Appointment</h3>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 15px; line-height: 1.5; text-align: center;">Are you sure you want to cancel this appointment?</p>
                <input type="hidden" id="cancelApptIdTarget">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 5px; color: #475569;">Cancellation Reason / Notes for Doctor</label>
                    <textarea id="cancelReasonInput" placeholder="Type your reason or notes here..." style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; outline: none; min-height: 80px; resize: vertical; box-sizing: border-box;"></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button id="closeCancelModalBtn" style="flex: 1; padding: 10px; border: 1px solid #cbd5e1; background: #fff; color: #334155; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">No, Keep It</button>
                    <button id="confirmCancelBtn" style="flex: 1; padding: 10px; border: none; background: #dc2626; color: #fff; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">Yes, Cancel</button>
                </div>
            </div>
        `;
        document.body.appendChild(cancelModal);
    }
    const cancelApptIdTarget = document.getElementById("cancelApptIdTarget");
    const cancelReasonInput = document.getElementById("cancelReasonInput");
    const closeCancelModalBtn = document.getElementById("closeCancelModalBtn");
    const confirmCancelBtn = document.getElementById("confirmCancelBtn");
    let currentOwnerId = null;
    onAuthStateChanged(auth, async (user) => {
        if (!user) {
            showAlert("Please log in to book an appointment.", "error");
            return;
        }
        try {
            currentOwnerId = localStorage.getItem("ownerId") || localStorage.getItem("userUID");
            if (!currentOwnerId) {
                const userQuery = query(collection(db, "users"), where("email", "==", user.email));
                const userSnap = await getDocs(userQuery);
                if (!userSnap.empty) {
                    const userData = userSnap.docs[0].data();
                    currentOwnerId = userData.ownerId || userSnap.docs[0].id;
                } else {
                    const userDocRef = doc(db, "users", user.uid);
                    const userDocSnap = await getDoc(userDocRef);
                    if (userDocSnap.exists()) {
                        currentOwnerId = userDocSnap.data().ownerId || user.uid;
                    } else {
                        currentOwnerId = user.uid;
                    }
                }
            }
            if (currentOwnerId) {
                localStorage.setItem("ownerId", currentOwnerId);
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
    async function loadUserPets(ownerId) {
        if (!petsCheckboxContainer) return;
        try {
            const petsQuery = query(collection(db, "pets"), where("ownerId", "==", ownerId));
            const petsSnap = await getDocs(petsQuery);
            petsCheckboxContainer.innerHTML = "";
            
            if (petsSnap.empty) {
                petsCheckboxContainer.innerHTML = '<span style="font-size: 13px; color: #888;">No registered pets found under your account.</span>';
                return;
            }
            petsSnap.forEach((docSnap) => {
                const petData = docSnap.data();
                const petName = petData.petName || petData.name || 'Unnamed Pet';
                const petId = docSnap.id;
                const label = document.createElement("label");
                label.className = "multi-checkbox-item";
                label.innerHTML = `
                    <input type="checkbox" name="selectedPets" value="${petName}" data-petid="${petId}">
                    <span>${petName} (${petData.species || 'Pet'}) - ${petData.breed || 'N/A'}</span>
                `;
                petsCheckboxContainer.appendChild(label);
            });
        } catch (error) {
            console.error("Error loading pets:", error);
            petsCheckboxContainer.innerHTML = '<span style="font-size: 13px; color: #dc2626;">Error loading pets from database.</span>';
        }
    }
    function parseDateString(dateStr) {
        if (!dateStr) return new Date(0);
        if (dateStr.includes('/')) {
            const parts = dateStr.split('/');
            if (parts.length === 3) {
                return new Date(`${parts[2]}-${parts[0]}-${parts[1]}`);
            }
        }
        return new Date(dateStr);
    }
    async function loadUserAppointments(ownerId) {
        const container = document.getElementById("userAppointmentsContainer");
        if (!container) return;
        try {
            const q = query(collection(db, "appointments_v2"), where("ownerId", "==", ownerId));
            const snapshot = await getDocs(q);
            container.innerHTML = "";
            if (snapshot.empty) {
                container.innerHTML = `<div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #64748b;"><p>You have no booked appointments yet.</p></div>`;
                return;
            }
            let appointmentsList = [];
            snapshot.forEach((docSnap) => {
                const apptData = docSnap.data();
                const status = (apptData.status || "").toLowerCase().trim();
                if (apptData.isArchived !== true && status !== "archived" && status !== "trash" && apptData.isTrash !== true) {
                    appointmentsList.push({ id: docSnap.id, ...apptData });
                }
            });
            if (appointmentsList.length === 0) {
                container.innerHTML = `<div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #64748b;"><p>You have no active appointments.</p></div>`;
                return;
            }
            appointmentsList.sort((a, b) => parseDateString(b.date) - parseDateString(a.date));
            let groupedAppointments = {};
            appointmentsList.forEach(appt => {
                const d = parseDateString(appt.date);
                const monthYear = d.toLocaleString('en-US', { month: 'long', year: 'numeric' });
                if (!groupedAppointments[monthYear]) groupedAppointments[monthYear] = [];
                groupedAppointments[monthYear].push(appt);
            });
            for (const [monthYear, appts] of Object.entries(groupedAppointments)) {
                container.insertAdjacentHTML("beforeend", `
                    <div style="grid-column: 1 / -1; margin-top: 15px; margin-bottom: 5px; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px;">
                        <h3 style="color: #4f46e5; font-size: 16px; font-weight: 700;"><i class="fa-regular fa-calendar-days"></i> For the Month of ${monthYear}</h3>
                    </div>
                `);
                appts.forEach((appt) => {
                    let statusBg = "#fef3c7";
                    let statusColor = "#d97706";
                    const rawStatus = appt.status || "Pending";
                    const statusLower = rawStatus.toLowerCase().trim();
                    if (statusLower === "completed") { statusBg = "#E1FDF4"; statusColor = "#065F46"; }
                    else if (statusLower === "confirmed" || statusLower === "approved") { statusBg = "#dcfce7"; statusColor = "#16a34a"; }
                    else if (statusLower === "cancelled") { statusBg = "#fee2e2"; statusColor = "#991b1b"; }
                    
                    const canModify = statusLower === "pending" || statusLower === "confirmed" || statusLower === "approved";
                    const canArchive = statusLower === "completed" || statusLower === "cancelled";
                    let servicesDisplay = appt.service || "";
                    if (appt.selectedProcedures && Array.isArray(appt.selectedProcedures) && appt.selectedProcedures.length > 0) {
                        servicesDisplay = appt.selectedProcedures.join(", ");
                    }
                    container.insertAdjacentHTML("beforeend", `
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <span style="background: ${statusBg}; color: ${statusColor}; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">${rawStatus} (Waiting for Approval)</span>
                                    <small style="color: #64748b; font-size: 12px;"><i class="fa-solid fa-paw"></i> ${appt.petName || "Unnamed Pet"}</small>
                                </div>
                                <h4 style="color: #1e1b4b; font-size: 15px; margin-bottom: 8px; font-weight: 600;">${servicesDisplay}</h4>
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-user-doctor" style="width: 18px; color: #5142f5;"></i> ${appt.doctorName || appt.assignedDoctor || "Doctor"}</p>
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-calendar-days" style="width: 18px; color: #5142f5;"></i> ${appt.date} (${appt.timeSlot || appt.time || "Time not set"})</p>
                                ${appt.referenceNumber ? `<p style="color: #0369a1; font-size: 12px; margin: 4px 0;"><i class="fa-solid fa-receipt" style="width: 18px;"></i> Ref: ${appt.referenceNumber} (Fee: ₱${appt.downPaymentFee || 150})</p>` : ""}
                                ${appt.notes ? `<p style="color: #64748b; font-size: 12px; margin-top: 8px; font-style: italic;">Note: "${appt.notes}"</p>` : ""}
                                ${appt.cancellationReason ? `<p style="color: #dc2626; font-size: 12px; margin-top: 4px; font-style: italic;">Cancellation Notes: "${appt.cancellationReason}"</p>` : ""}
                            </div>
                            ${canModify ? `
                                <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; justify-content: flex-end; gap: 8px;">
                                    <button class="cancel-appt-btn" data-id="${appt.id}" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;"><i class="fa-solid fa-ban"></i> Cancel</button>
                                    <button class="reschedule-trigger-btn" data-id="${appt.id}" style="background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;"><i class="fa-solid fa-calendar-pen"></i> Reschedule</button>
                                </div>
                            ` : ''}
                            ${canArchive ? `
                                <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; justify-content: flex-end; gap: 8px;">
                                    <button class="archive-appt-btn" data-id="${appt.id}" style="background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;"><i class="fa-solid fa-box-archive"></i> Archive</button>
                                </div>
                            ` : ''}
                        </div>
                    `);
                });
            }
            document.querySelectorAll(".reschedule-trigger-btn").forEach(btn => {
                btn.addEventListener("click", () => openRescheduleModal(btn.getAttribute("data-id")));
            });
            document.querySelectorAll(".cancel-appt-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    cancelApptIdTarget.value = btn.getAttribute("data-id");
                    if (cancelReasonInput) cancelReasonInput.value = "";
                    cancelModal.style.display = "flex";
                });
            });
            document.querySelectorAll(".archive-appt-btn").forEach(btn => {
                btn.addEventListener("click", () => archiveAppointment(btn.getAttribute("data-id")));
            });
        } catch (error) {
            console.error("Error loading appointments:", error);
            container.innerHTML = `<p style="color: #dc2626; font-size: 14px;">Failed to load your appointments.</p>`;
        }
    }
    async function archiveAppointment(apptId) {
        if (!apptId) return;
        try {
            const apptRef = doc(db, "appointments_v2", apptId);
            await updateDoc(apptRef, { isArchived: true, status: "Archived", archivedAt: Timestamp.now() });
            showAlert("Appointment successfully archived to Records & History.", "success");
            loadUserAppointments(currentOwnerId);
        } catch (error) {
            console.error("Error archiving appointment:", error);
            showAlert("Failed to archive appointment.", "error");
        }
    }
    if (closeCancelModalBtn) {
        closeCancelModalBtn.addEventListener("click", () => cancelModal.style.display = "none");
    }
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener("click", async () => {
            const apptId = cancelApptIdTarget.value;
            const cancelNotes = cancelReasonInput ? cancelReasonInput.value.trim() : "";
            if (!apptId) return;
            try {
                const apptRef = doc(db, "appointments_v2", apptId);
                await updateDoc(apptRef, { status: "Cancelled", cancellationReason: cancelNotes || "Cancelled by client/owner", cancelledAt: Timestamp.now() });
                cancelModal.style.display = "none";
                showAlert("Appointment cancelled successfully.", "success");
                loadUserAppointments(currentOwnerId);
            } catch (error) {
                console.error("Error cancelling appointment:", error);
                showAlert("Failed to cancel appointment.", "error");
            }
        });
    }
    function getHolidayName(dateObj) {
        const year = dateObj.getFullYear();
        const month = dateObj.getMonth();
        const day = dateObj.getDate();
        const specificHolidays = {
            "0-1": "New Year's Day", "3-9": "Araw ng Kagitingan", "4-1": "Labor Day",
            "5-12": "Independence Day", "7-21": "Ninoy Aquino Day", "10-1": "All Saints' Day (Undas)",
            "10-2": "All Souls' Day", "10-30": "Bonifacio Day", "11-8": "Feast of the Immaculate Conception",
            "11-24": "Christmas Eve", "11-25": "Christmas Day", "11-30": "Rizal Day", "11-31": "New Year's Eve"
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
        if (doctorName === "Dr. Alfie Tamesis") return (dayOfWeek === 1 || dayOfWeek === 0);
        else if (doctorName === "Dr. JK" || doctorName.includes("Jan Karlo")) return (dayOfWeek >= 1 && dayOfWeek <= 4) || (dayOfWeek === 6 || dayOfWeek === 0);
        else if (doctorName === "Dr. Edmond") return (dayOfWeek === 2 || dayOfWeek === 4 || dayOfWeek === 6);
        else if (doctorName === "Dr. Vitz") return (dayOfWeek === 5);
        return true;
    }
    function openRescheduleModal(apptId) {
        rescheduleApptIdInput.value = apptId;
        reschedHiddenDateInput.value = "";
        reschedHiddenTimeInput.value = "";
        reschedSelectedTimeValue = "";
        if (reschedHolidayNotice) reschedHolidayNotice.style.display = "none";
        
        let now = new Date();
        reschedYear = now.getFullYear();
        reschedMonth = now.getMonth();
        reschedDay = null;
        updateReschedCalendarUI();
        rescheduleModal.style.display = "flex";
    }
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => rescheduleModal.style.display = "none");
    }
    if (confirmRescheduleBtn) {
        confirmRescheduleBtn.addEventListener("click", async () => {
            const apptId = rescheduleApptIdInput.value;
            const newDate = reschedHiddenDateInput.value;
            const newDoc = rescheduleDoctorSelect.value;
            const newTime = reschedHiddenTimeInput.value;
            
            if (!newDate) { alert("Please select a new date."); return; }
            const chosenDateObj = new Date(newDate + "T00:00:00");
            const todayObj = new Date();
            todayObj.setHours(0, 0, 0, 0);
            if (chosenDateObj < todayObj) { alert("Cannot reschedule to past dates."); return; }
            if (getHolidayName(chosenDateObj)) { alert("Cannot select a holiday."); return; }
            if (!isDoctorAvailableOnDay(chosenDateObj, newDoc)) { alert("Selected doctor is not available on this date."); return; }
            if (!newTime) { alert("Please select a time slot."); return; }
            try {
                const apptRef = doc(db, "appointments_v2", apptId);
                await updateDoc(apptRef, {
                    date: newDate,
                    timeSlot: newTime,
                    assignedDoctor: newDoc,
                    doctorName: newDoc,
                    status: "Pending"
                });
                rescheduleModal.style.display = "none";
                showAlert("Appointment successfully rescheduled!", "success");
                loadUserAppointments(currentOwnerId);
            } catch (error) {
                console.error("Error rescheduling appointment:", error);
                alert("Failed to reschedule appointment.");
            }
        });
    }
    async function generateNextAppointmentId() {
        try {
            const querySnapshot = await getDocs(collection(db, "appointments_v2"));
            let maxNum = 0;
            querySnapshot.forEach((docSnap) => {
                const data = docSnap.data();
                const idVal = data.appointmentId || docSnap.id;
                if (idVal && idVal.startsWith("APT-")) {
                    const numPart = parseInt(idVal.split("-")[1], 10);
                    if (!isNaN(numPart) && numPart < 90000 && numPart > maxNum) {
                        maxNum = numPart;
                    }
                }
            });
            return `APT-${String(maxNum + 1).padStart(5, '0')}`;
        } catch (e) {
            return `APT-00009`;
        }
    }
    if (bookBtn) {
        bookBtn.addEventListener("click", async () => {
            if (!currentOwnerId) { showAlert("Authentication session not ready.", "error"); return; }
            const assignedDoctor = assignedDoctorSelect ? assignedDoctorSelect.value : "";
            if (!assignedDoctor) { showAlert("Please select a doctor.", "error"); return; }
            
            const checkedPets = document.querySelectorAll('input[name="selectedPets"]:checked');
            let selectedPetNames = [];
            let selectedPetIds = [];
            checkedPets.forEach(cb => {
                selectedPetNames.push(cb.value.trim());
                selectedPetIds.push(cb.getAttribute("data-petid"));
            });
            // Handle First-time user / Unregistered New Pet input
            const isNewPetChecked = toggleNewPetInput && toggleNewPetInput.checked;
            if (isNewPetChecked && newPetNameInput && newPetNameInput.value.trim() !== "") {
                const newName = newPetNameInput.value.trim();
                const newSpecies = newPetSpeciesInput ? newPetSpeciesInput.value : "Pet";
                const newBreed = newPetBreedInput ? newPetBreedInput.value.trim() : "N/A";
                
                selectedPetNames.push(`${newName} (${newSpecies} - ${newBreed})`);
                selectedPetIds.push("NEW_UNREGISTERED_PET");
            }
            const checkedServices = document.querySelectorAll('input[name="selectedServices"]:checked');
            let selectedServicesList = [];
            checkedServices.forEach(cb => selectedServicesList.push(cb.value.trim()));
            const dateVal = appointmentDateInput.value;
            const timeVal = appointmentTimeInput.value;
            const referenceNum = referenceNumberInput ? referenceNumberInput.value.trim() : "";
            const notes = notesInput.value.trim();
            if (selectedPetNames.length === 0) { showAlert("Please select a registered pet or register a new one.", "error"); return; }
            if (selectedServicesList.length === 0) { showAlert("Please select at least one service.", "error"); return; }
            if (!dateVal) { showAlert("Please select an appointment date.", "error"); return; }
            if (!timeVal) { showAlert("Please select an available time slot.", "error"); return; }
            if (!referenceNum) { showAlert("Please enter your GCash or PayMaya reference number.", "error"); return; }
            bookBtn.disabled = true;
            bookBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Submitting...`;
            try {
                const petNameStr = selectedPetNames.join(", ");
                const petIdStr = selectedPetIds.join(", ");
                const primaryService = selectedServicesList.join(", ");
                const generatedApptId = await generateNextAppointmentId();
                await setDoc(doc(db, "appointments_v2", generatedApptId), {
                    appointmentId: generatedApptId,
                    ownerId: currentOwnerId,
                    petName: petNameStr,
                    petId: petIdStr,
                    service: primaryService,
                    selectedProcedures: selectedServicesList,
                    assignedDoctor: assignedDoctor,
                    doctorName: assignedDoctor,
                    date: dateVal,
                    timeSlot: timeVal,
                    referenceNumber: referenceNum,
                    downPaymentFee: 150,
                    notes: notes,
                    status: "Pending",
                    bookingsSource: "Online Portal",
                    isArchived: false,
                    isUrgent: false,
                    createdAt: Timestamp.now()
                });
                showAlert("Appointment successfully booked! Status is Pending (Waiting for approval).", "success");
                
                document.querySelectorAll('input[name="selectedPets"]').forEach(cb => cb.checked = false);
                if (toggleNewPetInput) {
                    toggleNewPetInput.checked = false;
                    if (newPetFieldsContainer) newPetFieldsContainer.style.display = "none";
                    if (newPetNameInput) newPetNameInput.value = "";
                    if (newPetBreedInput) newPetBreedInput.value = "";
                }
                document.querySelectorAll('input[name="selectedServices"]').forEach(cb => cb.checked = false);
                if (assignedDoctorSelect) assignedDoctorSelect.value = "";
                appointmentDateInput.value = "";
                if (appointmentTimeInput) appointmentTimeInput.value = "";
                if (referenceNumberInput) referenceNumberInput.value = "";
                if (timeSlotsContainer) timeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Select a doctor and date first.</p>`;
                notesInput.value = "";
                
                const dateTextEl = document.getElementById("selectedDateText");
                const timeTextEl = document.getElementById("selectedTimeText");
                const totalFeeDisplay = document.getElementById("totalFeeDisplay");
                if (dateTextEl) dateTextEl.textContent = "Select Date";
                if (timeTextEl) timeTextEl.textContent = "--:-- --";
                if (totalFeeDisplay) totalFeeDisplay.textContent = "₱150";
                
                loadUserAppointments(currentOwnerId);
            } catch (error) {
                console.error("Error booking appointment:", error);
                showAlert("Failed to book appointment. Please try again.", "error");
            } finally {
                bookBtn.disabled = false;
                bookBtn.innerHTML = `<i class="fa-solid fa-calendar-plus"></i> Book Appointment (Pending / Waiting for Approval)`;
            }
        });
    }
    function showAlert(message, type) {
        if (!alertBox) return;
        alertBox.textContent = message;
        alertBox.style.background = type === 'success' ? '#dcfce7' : '#fee2e2';
        alertBox.style.color = type === 'success' ? '#16a34a' : '#991b1b';
        alertBox.style.border = type === 'success' ? '1px solid #bbf7d0' : '1px solid #fca5a5';
        alertBox.style.display = 'block';
        setTimeout(() => { alertBox.style.display = 'none'; }, 5000);
    }
});