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

    // 1. Authentication at pag-load ng mga pet ng user
    onAuthStateChanged(auth, async (user) => {
        if (!user) {
            showAlert("Please log in to book an appointment.", "error");
            return;
        }

        try {
            // Kunin ang ownerId gamit ang email ng naka-login na user
            const userQuery = query(
                collection(db, "users"),
                where("email", "==", user.email)
            );
            const userSnap = await getDocs(userQuery);

            if (!userSnap.empty) {
                currentOwnerId = userSnap.docs[0].data().ownerId;
                loadUserPets(currentOwnerId);
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
                    // Alisin ang active state sa lahat at ilipat sa pinili
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

    // 4. Proseso ng pag-book ng appointment papuntang Firestore
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

            // Validation
            if (!petName) {
                showAlert("Please select a pet.", "error");
                return;
            }
            if (!service) {
                showAlert("Please select a service.", "error");
                return;
            }
            if (!doctor) {
                showAlert("Please select a doctor.", "error");
                return;
            }
            if (!dateVal) {
                showAlert("Please select an appointment date.", "error");
                return;
            }
            if (!selectedTimeSlot) {
                showAlert("Please select an available time slot.", "error");
                return;
            }

            bookBtn.disabled = true;
            bookBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin">Submitting...</i>`;

            try {
                // I-save sa appointments collection
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

                // Mag-log din sa recent activities kung gusto mong mag-sync sa dashboard
                await addDoc(collection(db, "activities"), {
                    ownerId: currentOwnerId,
                    description: `Booked ${service} for ${petName}`,
                    timestamp: Timestamp.now()
                });

                showAlert("Appointment successfully booked!", "success");

                // I-reset ang form
                petSelect.selectedIndex = 0;
                serviceSelect.selectedIndex = 0;
                doctorSelect.selectedIndex = 0;
                appointmentDateInput.value = "";
                timeSlotsContainer.innerHTML = `<p style="color: #888; font-size: 13px;">Please select a date first.</p>`;
                notesInput.value = "";
                selectedTimeSlot = null;

            } catch (error) {
                console.error("Error booking appointment:", error);
                showAlert("Failed to book appointment. Please try again.", "error");
            } finally {
                bookBtn.disabled = false;
                bookBtn.innerHTML = `<i class="fa-solid fa-calendar-plus"></i> Book Appointment`;
            }
        });
    }

    // Helper function para sa Toast Alert
    function showAlert(message, type) {
        if (!alertBox) return;
        alertBox.textContent = message;
        alertBox.className = type === 'success' ? 'alert-success' : 'alert-error';
        alertBox.style.display = 'block';

        // Kusang mawawala pagkalipas ng 4 segundo
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }
});