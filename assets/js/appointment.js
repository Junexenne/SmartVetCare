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
    const doctorSuggestionBox = document.getElementById("doctorSuggestionBox");
    const doctorSuggestionText = document.getElementById("doctorSuggestionText");

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

    // Smart Suggestion at Auto-Disable para sa Grooming & Doctor Specialization
    if (serviceSelect && doctorSelect) {
        serviceSelect.addEventListener("change", () => {
            const serviceVal = serviceSelect.value;
            
            if (serviceVal === "Pet Grooming") {
                // I-disable ang doctor dropdown at i-set sa None / Grooming Staff
                doctorSelect.value = "None / Grooming Staff";
                doctorSelect.disabled = true;
                if (doctorSuggestionBox && doctorSuggestionText) {
                    doctorSuggestionBox.style.display = "flex";
                    doctorSuggestionText.innerHTML = "<strong>Pet Grooming:</strong> Doctor selection is disabled for grooming services.";
                }
            } else {
                // I-enable pabalik ang doctor dropdown
                doctorSelect.disabled = false;

                if (serviceVal === "Vaccination") {
                    doctorSuggestionBox.style.display = "flex";
                    doctorSuggestionText.innerHTML = "<strong>Recommended Doctor:</strong> Dr. Crachzel Kyle Asistio specializes in Vaccinations & Emergency Care.";
                    doctorSelect.value = "Dr. Crachzel Kyle Asistio";
                } else if (serviceVal === "Surgery") {
                    doctorSuggestionBox.style.display = "flex";
                    doctorSuggestionText.innerHTML = "<strong>Recommended Doctor:</strong> Dr. Alfie Tamesis specializes in Surgery & Internal Medicine.";
                    doctorSelect.value = "Dr. Alfie Tamesis";
                } else if (serviceVal === "General Check Up") {
                    doctorSuggestionBox.style.display = "flex";
                    doctorSuggestionText.innerHTML = "<strong>Recommended Doctor:</strong> Dr. Alfie Tamesis or Dr. James Nico Martinez for General Check Up.";
                    doctorSelect.value = "Dr. Alfie Tamesis";
                } else {
                    if (doctorSuggestionBox) doctorSuggestionBox.style.display = "none";
                }
            }
            
            // I-trigger ang change event para ma-update ang kalendaryo at oras
            doctorSelect.dispatchEvent(new Event("change"));
        });
    }

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

    // 3. Time slots & availability logic
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
                return new Date(`${parts[2]}-${parts[0]}-${parts[1]}`);
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
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-user-doctor" style="width: 18px; color: #5142f5;"></i> ${appt.doctor || "None / Grooming Staff"}</p>
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

    // 5. Reschedule Modal Logic
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
            let doctor = doctorSelect.disabled ? "None / Grooming Staff" : doctorSelect.value;
            const dateVal = appointmentDateInput.value;
            const timeVal = appointmentTimeInput ? appointmentTimeInput.value : selectedTimeSlot;
            const notes = notesInput.value.trim();

            if (!petName) { showAlert("Please select a pet.", "error"); return; }
            if (!service || service === "Select a Service") { showAlert("Please select a service.", "error"); return; }
            if (!doctor && service !== "Pet Grooming") { showAlert("Please select a doctor.", "error"); return; }
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
                doctorSelect.disabled = false;
                appointmentDateInput.value = "";
                if (appointmentTimeInput) appointmentTimeInput.value = "";
                if (timeSlotsContainer) timeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Please select a date first.</p>`;
                if (doctorSuggestionBox) doctorSuggestionBox.style.display = "none";
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