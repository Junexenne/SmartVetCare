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

    // Dynamic Custom Cancel Modal
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

    let currentOwnerId = null;

    // 1. Authenticate & load pets base sa 'ownerId'
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

    // 2. Fetch pets mula sa 'pets' collection gamit ang ownerId
    async function loadUserPets(ownerId) {
        if (!petsCheckboxContainer) return;
        try {
            const petsQuery = query(
                collection(db, "pets"),
                where("ownerId", "==", ownerId)
            );
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

    // 4. Display Appointments mula sa 'appointments_v2' Firebase Collection
    async function loadUserAppointments(ownerId) {
        const container = document.getElementById("userAppointmentsContainer");
        if (!container) return;
        try {
            const q = query(
                collection(db, "appointments_v2"),
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
                // Isinasama ang Pending, Confirmed, Approved, Completed, Cancelled basta hindi pa naka-archive o nasa trash
                if (apptData.isArchived !== true && status !== "trash" && status !== "archived") {
                    appointmentsList.push({ id: docSnap.id, ...apptData });
                }
            });
            if (appointmentsList.length === 0) {
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #64748b;">
                        <p>You have no active appointments.</p>
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
                        <h3 style="color: #4f46e5; font-size: 16px; font-weight: 700;"><i class="fa-regular fa-calendar-days"></i> For the Month of ${monthYear}</h3>
                    </div>
                `);
                appts.forEach((appt) => {
                    let statusBg = "#fef3c7";
                    let statusColor = "#d97706";
                    
                    const rawStatus = appt.status || "Pending";
                    const statusLower = rawStatus.toLowerCase().trim();
                    if (statusLower === "completed") {
                        statusBg = "#E1FDF4";
                        statusColor = "#065F46";
                    } else if (statusLower === "confirmed" || statusLower === "approved") {
                        statusBg = "#dcfce7";
                        statusColor = "#16a34a";
                    } else if (statusLower === "cancelled") {
                        statusBg = "#fee2e2";
                        statusColor = "#991b1b";
                    }
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
                                    <span style="background: ${statusBg}; color: ${statusColor}; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">${rawStatus}</span>
                                    <small style="color: #64748b; font-size: 12px;"><i class="fa-solid fa-paw"></i> ${appt.petName || "Unnamed Pet"}</small>
                                </div>
                                <h4 style="color: #1e1b4b; font-size: 15px; margin-bottom: 8px; font-weight: 600;">${servicesDisplay}</h4>
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-calendar-days" style="width: 18px; color: #5142f5;"></i> ${appt.date} (${appt.timeSlot || appt.time || "Time not set"})</p>
                                ${appt.notes ? `<p style="color: #64748b; font-size: 12px; margin-top: 8px; font-style: italic;">Note: "${appt.notes}"</p>` : ""}
                            </div>
                            ${canModify ? `
                                <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; justify-content: flex-end; gap: 8px;">
                                    <button class="cancel-appt-btn" data-id="${appt.id}" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                        <i class="fa-solid fa-ban"></i> Cancel
                                    </button>
                                    <button class="reschedule-trigger-btn" data-id="${appt.id}" style="background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                        <i class="fa-solid fa-calendar-pen"></i> Reschedule
                                    </button>
                                </div>
                            ` : ''}
                            ${canArchive ? `
                                <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px; display: flex; justify-content: flex-end; gap: 8px;">
                                    <button class="archive-appt-btn" data-id="${appt.id}" style="background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s ease;">
                                        <i class="fa-solid fa-box-archive"></i> Archive
                                    </button>
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

    // Function para sa pag-archive papunta sa Records & History
    async function archiveAppointment(apptId) {
        if (!apptId) return;
        try {
            const apptRef = doc(db, "appointments_v2", apptId);
            await updateDoc(apptRef, {
                isArchived: true,
                status: "Archived",
                archivedAt: Timestamp.now()
            });
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
            if (!apptId) return;
            try {
                const apptRef = doc(db, "appointments_v2", apptId);
                await updateDoc(apptRef, { 
                    status: "Cancelled",
                    cancellationReason: "Cancelled by client/owner",
                    cancelledAt: Timestamp.now()
                });
                cancelModal.style.display = "none";
                showAlert("Appointment cancelled successfully.", "success");
                loadUserAppointments(currentOwnerId);
            } catch (error) {
                console.error("Error cancelling appointment:", error);
                showAlert("Failed to cancel appointment.", "error");
            }
        });
    }

    function openRescheduleModal(apptId) {
        rescheduleApptIdInput.value = apptId;
        rescheduleDateInput.value = "";
        rescheduleModal.style.display = "flex";
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => rescheduleModal.style.display = "none");
    }

    if (confirmRescheduleBtn) {
        confirmRescheduleBtn.addEventListener("click", async () => {
            const apptId = rescheduleApptIdInput.value;
            const newDate = rescheduleDateInput.value;
            const selectedTimeBtn = rescheduleTimeSlotsContainer.querySelector(".selected-reschedule-time");
            
            if (!newDate) { alert("Please select a new date."); return; }
            if (!selectedTimeBtn) { alert("Please select a new time slot."); return; }
            const newTime = selectedTimeBtn.getAttribute("data-time");
            try {
                const apptRef = doc(db, "appointments_v2", apptId);
                await updateDoc(apptRef, {
                    date: newDate,
                    timeSlot: newTime,
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

    // STRICT SEQUENTIAL ID LOGIC (APT-00001, APT-00002, ...)
    async function generateNextAppointmentId() {
        try {
            const querySnapshot = await getDocs(collection(db, "appointments_v2"));
            let maxNum = 0;
            querySnapshot.forEach((docSnap) => {
                const data = docSnap.data();
                const idVal = data.appointmentId || docSnap.id;
                
                if (idVal && idVal.startsWith("APT-")) {
                    const numPart = parseInt(idVal.split("-")[1], 10);
                    if (!isNaN(numPart) && numPart < 90000) {
                        if (numPart > maxNum) {
                            maxNum = numPart;
                        }
                    }
                }
            });
            const nextNum = maxNum + 1;
            return `APT-${String(nextNum).padStart(5, '0')}`;
        } catch (e) {
            console.warn("Error generating sequential ID, falling back:", e);
            return `APT-00009`;
        }
    }

    // 6. Booking Process
    if (bookBtn) {
        bookBtn.addEventListener("click", async () => {
            if (!currentOwnerId) {
                showAlert("Authentication session not ready.", "error");
                return;
            }
            const checkedPets = document.querySelectorAll('input[name="selectedPets"]:checked');
            let selectedPetNames = [];
            let selectedPetIds = [];
            checkedPets.forEach(cb => {
                selectedPetNames.push(cb.value.trim());
                selectedPetIds.push(cb.getAttribute("data-petid"));
            });
            const checkedServices = document.querySelectorAll('input[name="selectedServices"]:checked');
            let selectedServicesList = [];
            checkedServices.forEach(cb => {
                selectedServicesList.push(cb.value.trim());
            });
            const dateVal = appointmentDateInput.value;
            const timeVal = appointmentTimeInput.value;
            const notes = notesInput.value.trim();

            if (selectedPetNames.length === 0) { 
                showAlert("Please select at least one pet.", "error"); 
                return; 
            }
            if (selectedServicesList.length === 0) { 
                showAlert("Please select at least one service.", "error"); 
                return; 
            }
            if (!dateVal) { 
                showAlert("Please select an appointment date.", "error"); 
                return; 
            }
            if (!timeVal) { 
                showAlert("Please select an available time slot.", "error"); 
                return; 
            }

            try {
                const existingApptQuery = query(
                    collection(db, "appointments_v2"),
                    where("ownerId", "==", currentOwnerId),
                    where("date", "==", dateVal)
                );
                const existingSnap = await getDocs(existingApptQuery);
                
                let doubleBookedPet = null;
                existingSnap.forEach(docSnap => {
                    const dData = docSnap.data();
                    const dStatus = (dData.status || "").toLowerCase().trim();
                    
                    if (dStatus !== "cancelled" && dStatus !== "trash") {
                        const existingPetNameLower = (dData.petName || "").toLowerCase().trim();
                        
                        selectedPetNames.forEach(pName => {
                            if (existingPetNameLower.includes(pName.toLowerCase()) || pName.toLowerCase().includes(existingPetNameLower)) {
                                doubleBookedPet = pName;
                            }
                        });
                    }
                });
                if (doubleBookedPet) {
                     showAlert(`Double booking is not allowed for "${doubleBookedPet}" on this date. Note: An appointment for this pet is already existing for this date; clinic staff will manage additional services on-site.`, "error");
                    return;
                }
            } catch (err) {
                console.error("Error checking double booking:", err);
            }

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
                    assignedDoctor: "Dr. Jan Karlo Fausto",
                    doctorName: "Dr. Jan Karlo Fausto",
                    date: dateVal,
                    timeSlot: timeVal,
                    notes: notes,
                    status: "Pending",
                    bookingsSource: "Online Portal",
                    isArchived: false,
                    isUrgent: false,
                    createdAt: Timestamp.now()
                });
                showAlert("Appointment successfully booked!", "success");
                
                // Reset inputs
                document.querySelectorAll('input[name="selectedPets"]').forEach(cb => cb.checked = false);
                document.querySelectorAll('input[name="selectedServices"]').forEach(cb => cb.checked = false);
                appointmentDateInput.value = "";
                if (appointmentTimeInput) appointmentTimeInput.value = "";
                if (timeSlotsContainer) timeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Select a date first.</p>`;
                notesInput.value = "";
                
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
        alertBox.style.background = type === 'success' ? '#dcfce7' : '#fee2e2';
        alertBox.style.color = type === 'success' ? '#16a34a' : '#991b1b';
        alertBox.style.border = type === 'success' ? '1px solid #bbf7d0' : '1px solid #fca5a5';
        alertBox.style.display = 'block';
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000);
    }
});