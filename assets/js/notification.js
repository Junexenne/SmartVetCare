import { db } from './firebase-config.js';
import { collection, doc, query, orderBy, onSnapshot, getDocs, writeBatch, updateDoc } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", () => {
    const badge = document.getElementById('notifBadge');
    const notifCountBadge = document.getElementById('notifCountBadge');
    const listContainer = document.getElementById('notifListContainer');
    const markAllBtn = document.getElementById('markAllAsReadBtn');

    const userId = localStorage.getItem("ownerId") || localStorage.getItem("userUID") || 'OWN-00004';
    const itemsRef = collection(doc(collection(db, "notifications"), userId), "items");

    onSnapshot(query(itemsRef, orderBy("timestamp", "desc")), (snapshot) => {
        if (!listContainer) return;
        listContainer.innerHTML = "";
        let unreadCount = 0;

        if (snapshot.empty) {
            listContainer.innerHTML = `
                <div style="text-align: center; padding: 30px 20px; color: #a0aec0;">
                    <i class="fa-regular fa-bell-slash" style="font-size: 24px; margin-bottom: 8px; color: #cbd5e0;"></i>
                    <p style="margin: 0; font-size: 13px; font-weight: 500;">No notifications yet</p>
                </div>`;
            if (badge) badge.style.display = 'none';
            if (notifCountBadge) notifCountBadge.textContent = '0';
            return;
        }

        snapshot.forEach((docSnap) => {
            const notif = docSnap.data();
            if (!notif.isRead) unreadCount++;

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
                transition: transform 0.2s, box-shadow 0.2s;
            `;
            
            item.onmouseover = () => {
                item.style.transform = 'translateY(-2px)';
                item.style.boxShadow = '0 6px 20px rgba(0, 0, 0, 0.08)';
            };
            item.onmouseout = () => {
                item.style.transform = 'translateY(0)';
                item.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.05)';
            };

            // Kunin ang link o action URL kung meron sa notification data, o default sa page kung saan ito nakatutok
            const actionUrl = notif.link || '#';

            item.innerHTML = `
                <!-- Left Violet Accent box na may Bell Icon -->
                <div style="background: #5142f5; min-width: 55px; display: flex; align-items: center; justify-content: center; color: #ffffff;">
                    <i class="fa-solid fa-bell" style="font-size: 16px;"></i>
                </div>

                <!-- Main Content Area -->
                <div style="padding: 14px 16px; flex-grow: 1; display: flex; align-items: center; justify-content: space-between; gap: 15px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 3px;">
                            <p style="font-weight: 600; font-size: 14px; color: #1e1b4b; margin: 0;">
                                ${notif.title || 'Notification'}
                            </p>
                            ${!notif.isRead ? `<span style="width: 7px; height: 7px; background: #5142f5; border-radius: 50%; display: inline-block;"></span>` : ''}
                        </div>
                        <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.4;">
                            ${notif.message || ''}
                        </p>
                    </div>

                    <!-- Action Button & Close/Dismiss -->
                    <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                        <a href="${actionUrl}" class="notif-action-btn" style="background: #5142f5; color: #ffffff; padding: 6px 14px; font-size: 12px; font-weight: 500; border-radius: 8px; text-decoration: none; transition: background 0.2s;">
                            View
                        </a>
                        <button type="button" class="notif-close-btn" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; font-size: 14px; padding: 4px;" title="Mark as read">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>`;

            // Mark as read kapag pinindot ang "View" o nag-click sa item
            const actionBtn = item.querySelector('.notif-action-btn');
            actionBtn.onclick = async (e) => {
                try {
                    await updateDoc(docSnap.ref, { isRead: true });
                } catch (err) {
                    console.error("Error marking as read:", err);
                }
            };

            // Isara o i-mark as read kapag pinindot ang 'X' button
            const closeBtn = item.querySelector('.notif-close-btn');
            closeBtn.onclick = async (e) => {
                e.stopPropagation();
                try {
                    await updateDoc(docSnap.ref, { isRead: true });
                    item.style.transition = 'opacity 0.3s, transform 0.3s';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.95)';
                    setTimeout(() => item.remove(), 300);
                } catch (err) {
                    console.error("Error dismissing notification:", err);
                }
            };

            listContainer.appendChild(item);
        });

        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }

        if (notifCountBadge) {
            notifCountBadge.textContent = unreadCount;
        }
    });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', async (e) => {
            e.stopPropagation();
            try {
                const snap = await getDocs(itemsRef);
                const batch = writeBatch(db);
                snap.forEach(d => { 
                    if (!d.data().isRead) {
                        batch.update(d.ref, { isRead: true }); 
                    }
                });
                await batch.commit();
            } catch (err) {
                console.error("Error marking all as read:", err);
            }
        });
    }
});