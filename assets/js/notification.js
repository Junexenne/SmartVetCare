import { db } from './firebase-config.js';
import { collection, query, where, onSnapshot } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", () => {
    const badge = document.getElementById('notifBadge');
    const notifCountBadge = document.getElementById('notifCountBadge');
    const listContainer = document.getElementById('notifListContainer');
    
    // Kunin ang ownerId o ownerName mula sa localStorage
    const currentOwnerId = localStorage.getItem("ownerId") || localStorage.getItem("userUID");
    const ownerName = localStorage.getItem("fullName") || localStorage.getItem("userName") || "";
    
    if (!listContainer) return;
    let notificationsList = [];

    function renderNotifications() {
        listContainer.innerHTML = "";
        let unreadCount = notificationsList.length;

        if (unreadCount === 0) {
            listContainer.innerHTML = `
                <div style="text-align: center; padding: 25px 15px; color: #a0aec0;">
                    <i class="fa-regular fa-bell-slash" style="font-size: 22px; margin-bottom: 6px; color: #cbd5e0;"></i>
                    <p style="margin: 0; font-size: 12px; font-weight: 500;">No notifications yet</p>
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
                border-radius: 12px;
                margin-bottom: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                overflow: hidden;
                border: 1px solid #edf2f7;
            `;

            item.innerHTML = `
                <div style="background: ${accentColor}; min-width: 45px; display: flex; align-items: center; justify-content: center; color: #ffffff;">
                    <i class="${iconClass}" style="font-size: 14px;"></i>
                </div>
                <div style="padding: 10px 12px; flex-grow: 1; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                            <p style="font-weight: 600; font-size: 12.5px; color: #1e1b4b; margin: 0;">${notif.title}</p>
                            <span style="width: 6px; height: 6px; background: ${accentColor}; border-radius: 50%; display: inline-block;"></span>
                        </div>
                        <p style="margin: 0; font-size: 11px; color: #64748b; line-height: 1.3;">${notif.message}</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="${notif.link}" style="background: ${accentColor}; color: #ffffff; padding: 4px 10px; font-size: 11px; font-weight: 500; border-radius: 6px; text-decoration: none;">View</a>
                    </div>
                </div>`;
            listContainer.appendChild(item);
        });

        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'block' : 'none';
        }
        if (notifCountBadge) {
            notifCountBadge.textContent = unreadCount;
        }
    }

    // Awtomatikong kukunin ang mga na-approve/confirmed na appointment para maging notification
    if (currentOwnerId || ownerName) {
        const appointmentsQuery = query(collection(db, "appointments"));
        
        onSnapshot(appointmentsQuery, (snapshot) => {
            notificationsList = []; 

            snapshot.forEach((docSnap) => {
                const data = docSnap.data();
                
                // Suriin kung ang appointment na ito ay para sa user na naka-login
                const isMatch = (currentOwnerId && data.ownerId === currentOwnerId) ||
                                (ownerName && (data.ownerName === ownerName || data.clientName === ownerName));

                if (isMatch) {
                    const status = (data.status || "Pending").toLowerCase();
                    
                    // Kapag na-accept na o confirmed na ng admin
                    if (status === 'confirmed' || status === 'approved' || status === 'in progress' || status === 'completed') {
                        notificationsList.push({
                            id: docSnap.id,
                            category: 'appointment',
                            title: `Appointment ${data.status}!`,
                            message: `Your appointment for ${data.service || 'checkup'} of ${data.petName || 'Pet'} has been approved by the Admin.`,
                            link: 'appointment.php',
                            icon: 'fa-solid fa-calendar-check',
                            color: status === 'completed' ? '#3b82f6' : '#10b981'
                        });
                    }
                }
            });
            
            renderNotifications();
        });
    }
});