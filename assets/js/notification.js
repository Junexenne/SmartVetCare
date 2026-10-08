import { db } from './firebase-config.js';
import { collection, query, where, onSnapshot } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", () => {
    const badge = document.getElementById('notifBadge');
    const notifCountBadge = document.getElementById('notifCountBadge');
    const listContainer = document.getElementById('notifListContainer');
    
    // Kunin ang user identifier (halimbawa ay ownerName o ownerId mula sa localStorage)
    const ownerName = localStorage.getItem("fullName") || localStorage.getItem("userName") || "Jerome Polo";
    
    if (!listContainer) return;

    let notificationsList = [];

    function renderNotifications() {
        listContainer.innerHTML = "";
        let unreadCount = notificationsList.length;

        if (unreadCount === 0) {
            listContainer.innerHTML = `
                <div style="text-align: center; padding: 30px 20px; color: #a0aec0;">
                    <i class="fa-regular fa-bell-slash" style="font-size: 24px; margin-bottom: 8px; color: #cbd5e0;"></i>
                    <p style="margin: 0; font-size: 13px; font-weight: 500;">No notifications yet</p>
                </div>`;
            if (badge) badge.style.display = 'none';
            if (notifCountBadge) notifCountBadge.textContent = '0';
            return;
        }

        notificationsList.forEach((notif) => {
            let iconClass = notif.icon || "fa-solid fa-bell";
            let accentColor = notif.color || "#5142f5";

            const item = document.createElement('div');
            item.style.cssText = `
                display: flex;
                align-items: stretch;
                background: #ffffff;
                border-radius: 14px;
                margin-bottom: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                overflow: hidden;
                border: 1px solid #edf2f7;
            `;

            item.innerHTML = `
                <div style="background: ${accentColor}; min-width: 55px; display: flex; align-items: center; justify-content: center; color: #ffffff;">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>
                <div style="padding: 14px 16px; flex-grow: 1; display: flex; align-items: center; justify-content: space-between; gap: 15px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 3px;">
                            <p style="font-weight: 600; font-size: 14px; color: #1e1b4b; margin: 0;">${notif.title}</p>
                            <span style="width: 7px; height: 7px; background: ${accentColor}; border-radius: 50%; display: inline-block;"></span>
                        </div>
                        <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.4;">${notif.message}</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                        <a href="${notif.link}" style="background: ${accentColor}; color: #ffffff; padding: 6px 14px; font-size: 12px; font-weight: 500; border-radius: 8px; text-decoration: none;">View</a>
                    </div>
                </div>`;
            listContainer.appendChild(item);
        });

        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }
        if (notifCountBadge) {
            notifCountBadge.textContent = unreadCount;
        }
    }

    // 1. Makinig sa real-time updates ng Appointments (Kapag na-approve)
    const appointmentsQuery = query(collection(db, "appointments"), where("status", "in", ["Approved", "approved"]));
    onSnapshot(appointmentsQuery, (snapshot) => {
        // Alisin muna ang lumang appointment notifications para i-update
        notificationsList = notificationsList.filter(n => n.category !== 'appointment');
        
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            // I-match kung para sa user na ito ang appointment
            if (data.ownerName === ownerName || data.clientName === ownerName) {
                notificationsList.push({
                    id: docSnap.id,
                    category: 'appointment',
                    title: 'Appointment Approved!',
                    message: `Na-approve na ang iyong appointment para sa ${data.service || 'checkup'} ng iyong alagang si ${data.petName || 'Pet'}.`,
                    link: 'appointment.php',
                    icon: 'fa-solid fa-calendar-check',
                    color: '#10b981'
                });
            }
        });
        renderNotifications();
    });

    // 2. Makinig sa real-time updates ng Health Records (Kapag may bago o na-update)
    const healthQuery = collection(db, "health_records");
    onSnapshot(healthQuery, (snapshot) => {
        notificationsList = notificationsList.filter(n => n.category !== 'health');
        
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            if (data.ownerName === ownerName) {
                notificationsList.push({
                    id: docSnap.id,
                    category: 'health',
                    title: 'New Medical Record',
                    message: `May bagong health record update para kay ${data.petName || 'pets'}.`,
                    link: 'health.php',
                    icon: 'fa-solid fa-notes-medical',
                    color: '#f59e0b'
                });
            }
        });
        renderNotifications();
    });
});