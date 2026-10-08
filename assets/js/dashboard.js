import { auth, db } from "./firebase-config.js";
import {
    collection, query, where, getDocs, onSnapshot, limit
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

// --- 1. Seasonal Reminder & Greeting ---
const greeting = document.getElementById("greeting");
if (greeting) {
    const hour = new Date().getHours();
    greeting.textContent = hour >= 5 && hour < 12 ? "Good Morning, Furry Friends!" : hour < 18 ? "Good Afternoon, Furry Friends!" : "Good Evening, Furry Friends!";
}

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

// --- 2. Topbar Calendar Appointments Loader ---
async function loadTopbarAppointments(ownerId) {
    const container = document.getElementById("topbarAppointmentsList");
    if (!container) return;
    try {
        const q = query(collection(db, "appointments"), where("ownerId", "==", ownerId));
        const snapshot = await getDocs(q);
        const todayStr = new Date().toISOString().split('T')[0];
        let html = '';
        let validCount = 0;
        snapshot.forEach((doc) => {
            const data = doc.data();
            if ((data.status === 'Confirmed' || data.status === 'In Progress' || data.status === 'Pending') && data.date >= todayStr) {
                validCount++;
                html += `
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; border-left: 3px solid #5142f5; font-size: 12px; margin-bottom: 5px;">
                        <div style="font-weight: 600; color: #173f81;">${data.service || 'Consultation'} (${data.petName || 'Pet'})</div>
                        <div style="color: #4a5568; margin-top: 2px;"><i class="fa-regular fa-clock" style="margin-right: 4px;"></i> ${data.date}</div>
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

// --- 3. Main Dashboard Data Loader (Multiple Appointments for Today) ---
function loadDashboardData(ownerId) {
    const upcomingContainer = document.getElementById("upcomingAppointmentContainer");
    const apptQuery = query(collection(db, "appointments"), where("ownerId", "==", ownerId));
    
    onSnapshot(apptQuery, (snapshot) => {
        if (!upcomingContainer) return;
        const todayStr = new Date().toISOString().split('T')[0];
        let todaysAppointments = [];

        snapshot.forEach((doc) => {
            const data = doc.data();
            // Salain lamang ang mga may status na Pending/Confirmed/In Progress at eksaktong ngayong araw ang appointment date
            if ((data.status === 'Pending' || data.status === 'Confirmed' || data.status === 'In Progress') && data.date === todayStr) {
                todaysAppointments.push(data);
            }
        });

        if (todaysAppointments.length === 0) {
            upcomingContainer.innerHTML = `<p class="empty-state">You don't have any appointments scheduled for today.</p>`;
            return;
        }

        let containerHtml = '<div style="display: flex; flex-direction: column; gap: 12px;">';

        todaysAppointments.forEach((nextAppt) => {
            let currentStatus = nextAppt.status || 'Pending';
            let step1Class = 'step active';
            let step2Class = 'step';
            let step3Class = 'step';

            if (currentStatus === 'Confirmed' || currentStatus === 'In Progress') {
                step1Class = 'step completed';
                step2Class = 'step active';
            } else if (currentStatus === 'Completed') {
                step1Class = 'step completed';
                step2Class = 'step completed';
                step3Class = 'step active completed';
            }

            containerHtml += `
                <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; border-left: 4px solid #2563EB;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <div>
                            <h4 style="margin: 0 0 3px 0; color: #1e1e2d; font-size: 15px;">${nextAppt.service || 'Vet Consultation'} - <span style="color: #2563EB;">${nextAppt.petName || 'Pet'}</span></h4>
                            <p style="margin: 0; color: #4a5568; font-size: 12.5px;"><i class="fa-regular fa-calendar" style="margin-right: 5px; color: #2563EB;"></i> ${nextAppt.date} (Today)</p>
                        </div>
                        <span style="background: #ebf4ff; color: #2563EB; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">${currentStatus}</span>
                    </div>

                    <!-- Delivery-Style Progress Tracker Stepper -->
                    <div class="progress-tracker-container">
                        <div class="stepper">
                            <div class="${step1Class}" data-step="1">
                                <div class="step-circle"><i class="fa-solid fa-clipboard-list"></i></div>
                                <div class="step-text">Pending</div>
                            </div>
                            <div class="${step2Class}" data-step="2">
                                <div class="step-circle"><i class="fa-solid fa-stethoscope"></i></div>
                                <div class="step-text">In Progress</div>
                            </div>
                            <div class="${step3Class}" data-step="3">
                                <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                                <div class="step-text">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        containerHtml += '</div>';
        upcomingContainer.innerHTML = containerHtml;

    }, (error) => {
        console.error("Appointments snapshot error:", error);
    });

    // Recent Activities Loader
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
        snapshot.forEach((doc) => {
            activitiesList.push({ id: doc.id, ...doc.data() });
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
        }
        loadTopbarAppointments(ownerId);
        loadDashboardData(ownerId);
        
        onSnapshot(query(collection(db, "pets"), where("ownerId", "==", ownerId)), (s) => {
            const el = document.getElementById("totalPets");
            if (el) el.innerText = s.size;
        });

        onSnapshot(query(collection(db, "appointments"), where("ownerId", "==", ownerId)), (s) => {
            let count = 0;
            const todayStr = new Date().toISOString().split('T')[0];
            s.forEach(d => {
                const data = d.data();
                if ((data.status === 'Confirmed' || data.status === 'In Progress' || data.status === 'Pending') && data.date >= todayStr) {
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