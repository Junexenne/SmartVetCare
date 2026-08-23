import { db } from './firebase-config.js';
import { collection, doc, query, orderBy, onSnapshot, getDocs, writeBatch, updateDoc } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", () => {
    const bellBtn = document.getElementById('notifBellBtn');
    const dropdown = document.getElementById('notifDropdown');
    const badge = document.getElementById('notifBadge');
    const listContainer = document.getElementById('notifListContainer');
    const markAllBtn = document.getElementById('markAllAsReadBtn'); // Siguraduhin na ito ang ID sa HTML

    // Toggle Notifications
    if (bellBtn && dropdown) {
        bellBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
    }

    const userId = typeof currentUserId !== 'undefined' ? currentUserId : 'OWN-00004';
    const itemsRef = collection(doc(collection(db, "notifications"), userId), "items");

    onSnapshot(query(itemsRef, orderBy("timestamp", "desc")), (snapshot) => {
        if (!listContainer) return;
        listContainer.innerHTML = "";
        let unreadCount = 0;

        snapshot.forEach((docSnap) => {
            const notif = docSnap.data();
            if (!notif.isRead) unreadCount++;

            const item = document.createElement('div');
            item.className = `notif-item ${notif.isRead ? '' : 'unread'}`;
            item.style.cursor = 'pointer';
            item.onclick = () => updateDoc(docSnap.ref, { isRead: true });
            
            item.innerHTML = `
                ${!notif.isRead ? `<div class="notif-dot"></div>` : `<div style="width: 8px;"></div>`}
                <div class="notif-content" style="width: 100%;">
                    <p style="font-weight: ${notif.isRead ? '500' : '600'}; margin:0;">${notif.title}</p>
                    <p class="notif-msg" style="margin:0; font-size: 12px;">${notif.message}</p>
                </div>`;
            listContainer.appendChild(item);
        });

        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }
    });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', async () => {
            const snap = await getDocs(itemsRef);
            const batch = writeBatch(db);
            snap.forEach(d => { if (!d.data().isRead) batch.update(d.ref, { isRead: true }); });
            await batch.commit();
        });
    }
});