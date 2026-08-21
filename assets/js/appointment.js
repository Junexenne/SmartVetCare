import { auth, db } from "./firebase-config.js";
import {
    collection,
    query,
    where,
    getDocs,
    addDoc,
    Timestamp
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import {
    onAuthStateChanged
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

document.addEventListener("DOMContentLoaded", () => {
    const petSelect = document.getElementById("petSelect");
    const appointmentDateInput = document.getElementById("appointmentDate");
    const timeSlotsContainer = document.getElementById("timeSlots");
    const serviceSelect = document.getElementById("service");
    const doctorSelect = document.getElementById("doctor");
    const notesInput = document.getElementById("notes");
    const bookBtn = document.getElementById("bookAppointmentBtn");
    const alertBox = document.getElementById("alertBox");

    let selectedTimeSlot = null;
    let currentOwnerId = null;

    // 1. Authentication at pag-load ng mga pet at appointments ng user
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

    // 2. Kunin ang mga rehistradong alaga para sa dropdown
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

    // 3. Mag-generate ng Available Time Slots kapag namili ng petsa
    const availableTimes = ["09:00 AM", "10:30 AM", "01:00 PM", "02:30 PM", "04:00 PM"];
    
    if (appointmentDateInput) {
        appointmentDateInput.addEventListener("change", () => {
            timeSlotsContainer.innerHTML = "";
            selectedTimeSlot = null;

            availableTimes.forEach((time) => {
                const slotBtn = document.createElement("button");
                slotBtn.type = "button";
                slotBtn.textContent = time;
                slotBtn.className = "time-slot-btn";
                slotBtn.style.cssText = "padding: 8px 14px; margin: 5px; border: 1px solid #ddd; background: #fff; border-radius: 6px; cursor: pointer; transition: all 0.2s;";
                
                slotBtn.addEventListener("click", () => {
                    document.querySelectorAll(".time-slot-btn").forEach(b => {
                        b.style.background = "#fff";
                        b.style.color = "#333";
                        b.style.borderColor = "#ddd";
                    });
                    slotBtn.style.background = "#5142f5";
                    slotBtn.style.color = "#fff";
                    slotBtn.style.borderColor = "#5142f5";
                    selectedTimeSlot = time;
                });

                timeSlotsContainer.appendChild(slotBtn);
            });
        });
    }

    // Helper function para gawing standard Date object kahit iba-iba ang format sa DB
    function parseDateString(dateStr) {
        if (!dateStr) return new Date(0);
        // Kung format ay DD/MM/YYYY (hal. 30/07/2026)
        if (dateStr.includes('/')) {
            const parts = dateStr.split('/');
            if (parts.length === 3) {
                return new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
            }
        }
        // Kung format ay YYYY-MM-DD (hal. 2026-08-21)
        return new Date(dateStr);
    }

    // 4. Kunin at I-display ang Booked Appointments na may Grouping per Month at Tamang Sorting
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
                appointmentsList.push({ id: docSnap.id, ...docSnap.data() });
            });

            // Sorting: Pinakabagong petsa ang mauuna (Descending)
            appointmentsList.sort((a, b) => {
                const dateA = parseDateString(a.date);
                const dateB = parseDateString(b.date);
                return dateB - dateA; 
            });

            // I-group natin per Month & Year para hindi nakakalito
            let groupedAppointments = {};
            appointmentsList.forEach(appt => {
                const d = parseDateString(appt.date);
                const monthYear = d.toLocaleString('en-US', { month: 'long', year: 'numeric' }); // Halimbawa: "August 2026"
                
                if (!groupedAppointments[monthYear]) {
                    groupedAppointments[monthYear] = [];
                }
                groupedAppointments[monthYear].push(appt);
            });

            // I-render sa HTML base sa grupo ng buwan
            for (const [monthYear, appts] of Object.entries(groupedAppointments)) {
                // Maglagay ng Header para sa Buwan
                container.insertAdjacentHTML("beforeend", `
                    <div style="grid-column: 1 / -1; margin-top: 15px; margin-bottom: 5px; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px;">
                        <h3 style="color: #4f46e5; font-size: 18px; font-weight: 700;"><i class="fa-regular fa-calendar-days"></i> For the Month of ${monthYear}</h3>
                    </div>
                `);

                appts.forEach((appt) => {
                    let statusBg = "#fef3c7";
                    let statusColor = "#d97706";

                    if (appt.status === "Completed") {
                        statusBg = "#E1FDF4";
                        statusColor = "#065F46";
                    } else if (appt.status === "Confirmed") {
                        statusBg = "#dcfce7";
                        statusColor = "#16a34a";
                    } else if (appt.status === "Cancelled") {
                        statusBg = "#fee2e2";
                        statusColor = "#dc2626";
                    }

                    const isUrgent = appt.service === "Surgery" || (appt.notes && (appt.notes.toLowerCase().includes("emergency") || appt.notes.toLowerCase().includes("parvo")));

                    container.insertAdjacentHTML("beforeend", `
                        <div style="background: #f8fafc; border: 1px solid ${isUrgent ? '#f87171' : '#e2e8f0'}; border-radius: 14px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <span style="background: ${statusBg}; color: ${statusColor}; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        ${appt.status || "Pending"}
                                    </span>
                                    <small style="color: #64748b; font-size: 12px;"><i class="fa-solid fa-paw"></i> ${appt.petName || "Unnamed Pet"}</small>
                                </div>
                                
                                ${isUrgent ? '<div style="margin-bottom: 8px;"><span style="background: #fee2e2; color: #b91c1c; padding: 3px 8px; font-size: 10px; font-weight: 700; border-radius: 6px; text-transform: uppercase; display: inline-block;"><i class="fa-solid fa-triangle-exclamation"></i> Urgent / Emergency</span></div>' : ''}

                                <h4 style="color: #1e1b4b; font-size: 16px; margin-bottom: 8px;">${appt.service}</h4>
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-user-doctor" style="width: 18px; color: #5142f5;"></i> ${appt.doctor || "Doctor not specified"}</p>
                                <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-calendar-days" style="width: 18px; color: #5142f5;"></i> ${appt.date} (${appt.timeSlot || "Time not set"})</p>
                                ${appt.notes ? `<p style="color: #64748b; font-size: 12px; margin-top: 8px; font-style: italic;">Note: "${appt.notes}"</p>` : ""}
                            </div>
                        </div>
                    `);
                });
            }

        } catch (error) {
            console.error("Error loading appointments:", error);
            container.innerHTML = `<p style="color: #dc2626; font-size: 14px;">Failed to load your appointments.</p>`;
        }
    }

    // 5. Proseso ng pag-book ng appointment papuntang Firestore
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
            const notes = notesInput.value.trim();

            if (!petName) { showAlert("Please select a pet.", "error"); return; }
            if (!service) { showAlert("Please select a service.", "error"); return; }
            if (!doctor) { showAlert("Please select a doctor.", "error"); return; }
            if (!dateVal) { showAlert("Please select an appointment date.", "error"); return; }
            if (!selectedTimeSlot) { showAlert("Please select an available time slot.", "error"); return; }

            bookBtn.disabled = true;
            bookBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Submitting...`;

            try {
                await addDoc(collection(db, "appointments"), {
                    ownerId: currentOwnerId,
                    petName: petName,
                    service: service,
                    doctor: doctor,
                    date: dateVal,
                    timeSlot: selectedTimeSlot,
                    notes: notes,
                    status: "Pending",
                    createdAt: Timestamp.now()
                });

                await addDoc(collection(db, "activities"), {
                    ownerId: currentOwnerId,
                    description: `Booked ${service} for ${petName}`,
                    timestamp: Timestamp.now()
                });

                showAlert("Appointment successfully booked!", "success");

                petSelect.selectedIndex = 0;
                serviceSelect.selectedIndex = 0;
                doctorSelect.selectedIndex = 0;
                appointmentDateInput.value = "";
                timeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Please select a date first.</p>`;
                notesInput.value = "";
                selectedTimeSlot = null;

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