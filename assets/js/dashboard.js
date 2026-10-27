import { auth, db } from "./firebase-config.js";
import {
    collection, query, where, getDocs, onSnapshot, limit
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

// --- Clinic Status Checker (Philippine Time) ---
function checkClinicStatus() {
    const options = { timeZone: 'Asia/Manila', hour: 'numeric', minute: 'numeric', hour12: false };
    const formatter = new Intl.DateTimeFormat([], options);
    const timeString = formatter.format(new Date());
    
    const [hourStr, minuteStr] = timeString.split(':');
    const currentHour = parseInt(hourStr, 10);
    const currentMinute = parseInt(minuteStr, 10);
    const currentTimeDecimal = currentHour + (currentMinute / 60);
    const openingHour = 10.0;
    const closingHour = 19.0;
    const statusBadge = document.getElementById('clinicStatusBadge');
    if (statusBadge) {
        if (currentTimeDecimal >= openingHour && currentTimeDecimal < closingHour) {
            statusBadge.textContent = "Open";
            statusBadge.style.backgroundColor = "#dcfce7";
            statusBadge.style.color = "#16a34a";
        } else {
            statusBadge.textContent = "Closed";
            statusBadge.style.backgroundColor = "#fee2e2";
            statusBadge.style.color = "#dc2626";
        }
    }
}
checkClinicStatus();
setInterval(checkClinicStatus, 60000);

// --- 1. Seasonal Reminder & Greeting (Welcome Back First Name Integration) ---
function updateGreeting(fullName = "") {
    const greeting = document.getElementById("greeting");
    if (greeting) {
        const nameToUse = fullName || localStorage.getItem("fullName") || "User";
        const firstName = nameToUse.trim().split(" ")[0] || "User";
        greeting.textContent = `Welcome back, ${firstName}!`;
    }
}
updateGreeting();

function loadSeasonalReminder() {
    const month = new Date().getMonth();
    const titleEl = document.getElementById('reminderTitle');
    const textEl = document.getElementById('reminderText');
    const iconEl = document.getElementById('reminderIcon');
    const cardEl = document.getElementById('seasonalReminderCard');
    if (!titleEl || !textEl) return;
    if (month >= 5 && month <= 10) {
        titleEl.textContent = "Wet Season Safety: No Pet Left Behind, Smart Vet Fam!";
        textEl.textContent = "Hey Smart Vet Fam! Heavy rains and floods mean extra care for our furry friends. Keep them away from stagnant floodwaters to prevent Leptospirosis and Remember: during evacuations, always bring your pets along. No pet left behind!";
        if (iconEl) iconEl.className = "fa-solid fa-shield-dog";
        if (cardEl) cardEl.style.borderLeftColor = "#3b82f6";
    } else {
        titleEl.textContent = "Dry Season Reminder: Keep Cool, Smart Vet Fam!";
        textEl.textContent = "Hey Smart Vet Fam! The weather is getting quite warm. Make sure your pets have continuous access to fresh drinking water and avoid letting them stay too long under direct sunlight to protect them from heatstroke!";
        if (iconEl) iconEl.className = "fa-solid fa-sun";
        if (cardEl) cardEl.style.borderLeftColor = "#f59e0b";
    }
}
loadSeasonalReminder();

// --- 2. Topbar Calendar Appointments Loader (appointments_v2) ---
async function loadTopbarAppointments(ownerId) {
    const container = document.getElementById("topbarAppointmentsList");
    if (!container) return;
    try {
        const q = query(collection(db, "appointments_v2"), where("ownerId", "==", ownerId));
        const snapshot = await getDocs(q);
        const todayStr = new Date().toISOString().split('T')[0];
        let html = '';
        let validCount = 0;
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const statusLower = (data.status || "").toLowerCase().trim();
            if (data.isArchived !== true && statusLower !== "trash" && statusLower !== "cancelled" && data.date >= todayStr) {
                validCount++;
                html += `
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; border-left: 3px solid #5142f5; font-size: 12px; margin-bottom: 5px;">
                        <div style="font-weight: 600; color: #173f81;">${data.service || 'Consultation'} (${data.petName || 'Pet'})</div>
                        <div style="color: #4a5568; margin-top: 2px;"><i class="fa-regular fa-clock" style="margin-right: 4px;"></i> ${data.date} (${data.timeSlot || data.time || '10:00 AM - 7:00 PM'})</div>
                    </div>`;
            }
        });
        if (validCount === 0) {
            container.innerHTML = `<p style="text-align: center; color: #a0aec0; font-size: 12px; margin: 15px 0;">No upcoming appointments.</p>`;
            return;
        }
        container.innerHTML = html;
    } catch (error) {
        console.error("Error loading topbar appointments:", error);
    }
}

// --- 2.3 Appointment Status Tracking with Faded Green Completed/Passed Steps ---
function loadDashboardAppointmentTracker(ownerId) {
    const trackerContainer = document.getElementById("dashboardAppointmentTrackerContainer");
    if (!trackerContainer) return;
    const apptQuery = query(collection(db, "appointments_v2"), where("ownerId", "==", ownerId));
    onSnapshot(apptQuery, (snapshot) => {
        if (snapshot.empty) {
            trackerContainer.innerHTML = `<p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">No appointment tracking records found.</p>`;
            return;
        }
        let trackerHtml = '';
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const status = (data.status || "pending").toLowerCase().trim();
            let step1Class = 'step active';    
            let step2Class = 'step';          
            let step3Class = 'step';          
            let lineActive1 = '';
            let lineActive2 = '';
            let statusBadgeBg = "#fef9c3";
            let statusBadgeColor = "#854d0e";
            let statusBadgeText = "Pending";
            if (status === "approved" || status === "confirmed") {
                step1Class = 'step completed'; 
                step2Class = 'step active';    
                lineActive1 = 'active';
                statusBadgeBg = "#dcfce7";
                statusBadgeColor = "#166534";
                statusBadgeText = "Approved";
            } else if (status === "completed") {
                step1Class = 'step completed'; 
                step2Class = 'step completed'; 
                step3Class = 'step completed'; 
                lineActive1 = 'active';
                lineActive2 = 'active';
                statusBadgeBg = "#e0e7ff";
                statusBadgeColor = "#3730a3";
                statusBadgeText = "Completed";
            } else if (status === "cancelled") {
                statusBadgeBg = "#fee2e2";
                statusBadgeColor = "#991b1b";
                statusBadgeText = "Cancelled";
            }
            trackerHtml += `
                <div style="background: #ffffff; padding: 15px; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <h4 style="margin: 0 0 2px 0; color: #1e1b4b; font-size: 15px; font-weight: 600;"><i class="fa-solid fa-calendar-day" style="color: #5142f5; margin-right: 5px;"></i>${data.service || "Consultation"}</h4>
                            <div style="font-size: 12px; color: #64748b;">Pet: <strong>${data.petName || "N/A"}</strong> &bull; Date: <strong>${data.date || "N/A"} (${data.timeSlot || data.time || "N/A"})</strong></div>
                        </div>
                        <span style="background: ${statusBadgeBg}; color: ${statusBadgeColor}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">${statusBadgeText}</span>
                    </div>
                    <div class="progress-tracker-container" style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9; margin-top: 10px;">
                        <div class="stepper">
                            <div class="${step1Class}" data-step="1">
                                <div class="step-circle"><i class="fa-solid fa-clock"></i></div>
                                <div class="step-text">Pending</div>
                            </div>
                            <div class="step-line ${lineActive1}"></div>
                            <div class="${step2Class}" data-step="2">
                                <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                                <div class="step-text">Approved</div>
                            </div>
                            <div class="step-line ${lineActive2}"></div>
                            <div class="${step3Class}" data-step="3">
                                <div class="step-circle"><i class="fa-solid fa-circle-check"></i></div>
                                <div class="step-text">Completed</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 12px; color: #475569; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 5px;">
                        <span><strong>Notes:</strong> ${data.notes || data.reason || "None"}</span>
                        <a href="../pages/appointment.php" style="color: #5142f5; text-decoration: none; font-weight: 600;">View Details &rarr;</a>
                    </div>
                </div>
            `;
        });
        trackerContainer.innerHTML = trackerHtml;
    }, (error) => {
        console.error("Appointment tracking snapshot error:", error);
    });
}

// --- 3. Main Dashboard Health Monitoring Data Loader ---
function loadDashboardHealthData(ownerId) {
    const healthContainer = document.getElementById("dashboardHealthContainer");
    if (!healthContainer) return;
    const healthQuery = query(collection(db, "health_monitoring"), where("ownerId", "==", ownerId));
    
    onSnapshot(healthQuery, (snapshot) => {
        if (snapshot.empty) {
            healthContainer.innerHTML = `<p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">No active health monitoring records found for your pets.</p>`;
            return;
        }
        
        let containerHtml = '';
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const vitals = data.vitals || {};
            
            let admittedDate = "N/A";
            if (data.admittedAt && typeof data.admittedAt.toDate === "function") {
                admittedDate = data.admittedAt.toDate().toLocaleString("en-US", { month: "short", day: "numeric", year: "numeric", hour: "numeric", minute: "2-digit", hour12: true });
            }
            
            let step1Class = 'step completed';
            let step2Class = 'step active';
            let step3Class = 'step';
            let lineActive = '';
            const sUpper = (data.status || "").toUpperCase();
            if (sUpper === 'DISCHARGED' || sUpper === 'COMPLETED') {
                step1Class = 'step completed';
                step2Class = 'step completed';
                step3Class = 'step completed';
                lineActive = 'active';
            }
            containerHtml += `
                <div style="background: #ffffff; padding: 15px; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <h4 style="margin: 0 0 2px 0; color: #1e1b4b; font-size: 15px; font-weight: 600;"><i class="fa-solid fa-paw" style="color: #5142f5; margin-right: 5px;"></i>${data.petName || "Unknown Pet"}</h4>
                            <div style="font-size: 12px; color: #64748b;">${data.petId || ""} &bull; Breed: <strong>${data.breed || "N/A"}</strong></div>
                        </div>
                        <span style="background: ${sUpper === 'STABLE' ? '#dcfce7' : '#fee2e2'}; color: ${sUpper === 'STABLE' ? '#166534' : '#991b1b'}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">${data.status || "UNKNOWN"}</span>
                    </div>
                    <div class="progress-tracker-container" style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9; margin-top: 10px;">
                        <div class="stepper">
                            <div class="${step1Class}" data-step="1">
                                <div class="step-circle"><i class="fa-solid fa-clipboard-list"></i></div>
                                <div class="step-text">Admitted</div>
                            </div>
                            <div class="step-line ${lineActive}"></div>
                            <div class="${step2Class}" data-step="2">
                                <div class="step-circle"><i class="fa-solid fa-stethoscope"></i></div>
                                <div class="step-text">Monitoring</div>
                            </div>
                            <div class="step-line ${lineActive}"></div>
                            <div class="${step3Class}" data-step="3">
                                <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                                <div class="step-text">Discharged</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 12px; color: #475569; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 5px;">
                        <span><strong>Temp:</strong> ${vitals.temperature || "--"} °C &bull; <strong>Weight:</strong> ${vitals.weight || "--"} kg</span>
                        <a href="../pages/health.php" style="color: #5142f5; text-decoration: none; font-weight: 600;">View Details &rarr;</a>
                    </div>
                </div>
            `;
        });
        healthContainer.innerHTML = containerHtml;
    }, (error) => {
        console.error("Health monitoring snapshot error:", error);
    });
    const activitiesContainer = document.getElementById("recentActivitiesContainer");
    const actQuery = query(
        collection(db, "activities"), 
        where("ownerId", "==", ownerId)
    );
    
    onSnapshot(actQuery, (snapshot) => {
        if (!activitiesContainer) return;
        if (snapshot.empty) {
            activitiesContainer.innerHTML = `<p class="empty-state">No recent activities yet.</p>`;
            return;
        }
        let activitiesList = [];
        snapshot.forEach((docSnap) => {
            activitiesList.push({ id: docSnap.id, ...docSnap.data() });
        });
        activitiesList.sort((a, b) => {
            let timeA = a.timestamp?.toDate ? a.timestamp.toDate() : new Date(a.timestamp || 0);
            let timeB = b.timestamp?.toDate ? b.timestamp.toDate() : new Date(b.timestamp || 0);
            return timeB - timeA;
        });
        activitiesList = activitiesList.slice(0, 5);
        let html = '<div style="display: flex; flex-direction: column; gap: 10px;">';
        activitiesList.forEach((act) => {
            const desc = act.description || act.title || 'Activity performed';
            let timeStr = 'Recently';
            if (act.timestamp) {
                if (typeof act.timestamp.toDate === 'function') {
                    const dateObj = act.timestamp.toDate();
                    timeStr = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' at ' + dateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                } else {
                    timeStr = act.timestamp;
                }
            }
            html += '<div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8f9fa; border-radius: 8px;">';
            html += '<div style="background: #ebf4ff; color: #5142f5; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;"><i class="fa-solid fa-history"></i></div>';
            html += '<div style="flex: 1;">';
            html += '<p style="margin: 0; font-size: 13px; color: #2d3748; font-weight: 500;">' + desc + '</p>';
            html += '<span style="font-size: 11px; color: #a0aec0;"><i class="fa-regular fa-clock" style="margin-right: 3px;"></i>' + timeStr + '</span>';
            html += '</div></div>';
        });
        html += '</div>';
        activitiesContainer.innerHTML = html;
    }, (error) => {
        console.error("Activities snapshot error:", error);
    });
}

// --- 4. Main Auth Listener ---
onAuthStateChanged(auth, async (user) => {
    if (!user) return;
    try {
        const userQuery = query(collection(db, "users"), where("email", "==", user.email), limit(1));
        const userSnapshot = await getDocs(userQuery);
        
        if (userSnapshot.empty) return;
        const userData = userSnapshot.docs[0].data();
        const ownerId = userData.ownerId;
        if (!ownerId) return;
        if (userData.fullName) {
            localStorage.setItem("fullName", userData.fullName);
            updateGreeting(userData.fullName);
        }
        
        // Dynamic Address handling para sa Weather/Tandang component
        if (userData.address) {
            localStorage.setItem("userAddress", userData.address);
            // I-dispatch ang event para masagap ng dashboard.php ang dynamic address pagka-login
            window.dispatchEvent(new CustomEvent("userAddressLoaded", { detail: userData.address }));
        }

        loadTopbarAppointments(ownerId);
        loadDashboardAppointmentTracker(ownerId); 
        loadDashboardHealthData(ownerId);
        
        onSnapshot(query(collection(db, "pets"), where("ownerId", "==", ownerId)), (s) => {
            const el = document.getElementById("totalPets");
            if (el) el.innerText = s.size;
        });
        onSnapshot(query(collection(db, "appointments_v2"), where("ownerId", "==", ownerId)), (s) => {
            let count = 0;
            const todayStr = new Date().toISOString().split('T')[0];
            s.forEach(d => {
                const data = d.data();
                const statusLower = (data.status || "").toLowerCase().trim();
                if (data.isArchived !== true && statusLower !== "trash" && statusLower !== "cancelled" && data.date >= todayStr) {
                    count++;
                }
            });
            const el = document.getElementById("appointmentCount");
            if (el) el.innerText = count;
        });
    } catch (err) {
        console.error("Auth state resolution error:", err);
    }
});