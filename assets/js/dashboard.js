import { auth, db } from "./firebase-config.js";
import {
    collection, query, where, getDocs, onSnapshot, limit, orderBy
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

// --- 1. Seasonal Reminder & Greeting ---
const greeting = document.getElementById("greeting");
if (greeting) {
    const hour = new Date().getHours();
    greeting.textContent = hour >= 5 && hour < 12 ? "Good Morning!" : hour < 18 ? "Good Afternoon!" : "Good Evening!";
}

export function loadSeasonalReminder() {
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
export async function loadTopbarAppointments(ownerId) {
    const container = document.getElementById("topbarAppointmentsList");
    if (!container) return;

    const q = query(collection(db, "appointments"), where("ownerId", "==", ownerId), orderBy("date", "asc"));
    const snapshot = await getDocs(q);

    const todayStr = new Date().toISOString().split('T')[0];
    let html = '';
    let validCount = 0;

    snapshot.forEach((doc) => {
        const data = doc.data();
        // I-filter: Confirmed lang at hindi pa lumipas ang petsa
        if (data.status === 'Confirmed' && data.date >= todayStr) {
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
}

// --- 3. Main Dashboard Data Loader ---
function loadDashboardData(ownerId) {
    const upcomingContainer = document.getElementById("upcomingAppointmentContainer");
    const apptQuery = query(collection(db, "appointments"), where("ownerId", "==", ownerId), orderBy("date", "asc"));
    
    onSnapshot(apptQuery, (snapshot) => {
        if (!upcomingContainer) return;

        const todayStr = new Date().toISOString().split('T')[0];
        let nextAppt = null;

        snapshot.forEach((doc) => {
            const data = doc.data();
            // Hanapin ang pinakamalapit na appointment na Confirmed na at hindi pa lumipas ang petsa
            if (!nextAppt && data.status === 'Confirmed' && data.date >= todayStr) {
                nextAppt = data;
            }
        });

        if (!nextAppt) {
            upcomingContainer.innerHTML = `<p class="empty-state">You don't have any upcoming appointments.</p>`;
            return;
        }

        upcomingContainer.innerHTML = `
            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; border-left: 4px solid #5142f5; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #1e1e2d; font-size: 15px;">${nextAppt.service || 'Vet Consultation'} - ${nextAppt.petName || 'Pet'}</h4>
                    <p style="margin: 0; color: #4a5568; font-size: 13px;"><i class="fa-regular fa-calendar" style="margin-right: 5px; color: #5142f5;"></i> ${nextAppt.date}</p>
                </div>
                <span style="background: #ebf4ff; color: #5142f5; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${nextAppt.status || 'Confirmed'}</span>
            </div>
        `;
    });

    // Recent Activities Loader
    const activitiesContainer = document.getElementById("recentActivitiesContainer");
    const actQuery = query(collection(db, "activities"), where("ownerId", "==", ownerId), orderBy("timestamp", "desc"), limit(5));
    
    onSnapshot(actQuery, (snapshot) => {
        if (!activitiesContainer) return;

        if (snapshot.empty) {
            activitiesContainer.innerHTML = `<p class="empty-state">No recent activities yet.</p>`;
            return;
        }

        let html = '<div style="display: flex; flex-direction: column; gap: 10px;">';
        snapshot.forEach((doc) => {
            const act = doc.data();
            const desc = act.description || act.title || 'Activity performed';
            const timeStr = act.time || 'Recently';

            html += '<div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8f9fa; border-radius: 8px;">';
            html += '<div style="background: #ebf4ff; color: #5142f5; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;"><i class="fa-solid fa-history"></i></div>';
            html += '<div style="flex: 1;">';
            html += '<p style="margin: 0; font-size: 13px; color: #2d3748; font-weight: 500;">' + desc + '</p>';
            html += '<span style="font-size: 11px; color: #a0aec0;">' + timeStr + '</span>';
            html += '</div></div>';
        });
        html += '</div>';
        activitiesContainer.innerHTML = html;
    });
}

// --- 4. Main Auth Listener ---
onAuthStateChanged(auth, async (user) => {
    if (!user) return;

    const userQuery = query(collection(db, "users"), where("email", "==", user.email), limit(1));
    const userSnapshot = await getDocs(userQuery);
    if (userSnapshot.empty) return;

    const ownerId = userSnapshot.docs[0].data().ownerId;

    loadTopbarAppointments(ownerId);
    loadDashboardData(ownerId);

    // Real-time Dashboard Counters para sa Pets
    onSnapshot(query(collection(db, "pets"), where("ownerId", "==", ownerId)), (s) => {
        const el = document.getElementById("totalPets");
        if (el) el.innerText = s.size;
    });

    // Real-time Counter para sa Appointments (Confirmed / Approved lang ang bibilangin)
    onSnapshot(query(collection(db, "appointments"), where("ownerId", "==", ownerId)), (s) => {
        let count = 0;
        const todayStr = new Date().toISOString().split('T')[0];
        
        s.forEach(d => {
            const data = d.data();
            if (data.status === 'Confirmed' && data.date >= todayStr) {
                count++;
            }
        });
        
        const el = document.getElementById("appointmentCount");
        if (el) el.innerText = count;
    });
});