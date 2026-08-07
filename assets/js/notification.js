import { db } from './firebase-config.js';
import { collection, doc, query, orderBy, onSnapshot, getDocs, writeBatch } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", () => {
    const bellBtn = document.getElementById('notifBellBtn');
    const dropdown = document.getElementById('notifDropdown');
    const badge = document.getElementById('notifBadge');
    const listContainer = document.getElementById('notifListContainer');
    const markAllBtn = document.getElementById('markAllAsReadBtn');

    // Toggle dropdown kapag pinindot ang bell icon
    if (bellBtn && dropdown) {
        bellBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        window.addEventListener('click', () => {
            dropdown.style.display = 'none';
        });

        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    // Kunin ang user ID galing sa global variable na nilagay ng PHP o default
    const userId = typeof currentUserId !== 'undefined' ? currentUserId : 'OWN-00004';

    if (db) {
        // Buuin ang path gamit ang modular syntax: notifications/{userId}/items
        const itemsRef = collection(doc(collection(db, "notifications"), userId), "items");
        const q = query(itemsRef, orderBy("timestamp", "desc"));

        onSnapshot(q, (snapshot) => {
            if (listContainer) listContainer.innerHTML = "";
            let unreadCount = 0;

            if (snapshot.empty) {
                if (listContainer) {
                    listContainer.innerHTML = `<p style="text-align: center; color: #888; font-size: 12px; padding: 15px;">No new notifications yet.</p>`;
                }
                if (badge) badge.style.display = 'none';
                return;
            }

           snapshot.forEach((docSnapshot) => {
                const notif = docSnapshot.data();

                if (!notif.isRead) {
                    unreadCount++;
                }

                const item = document.createElement('div');
                item.className = `notif-item ${notif.isRead ? '' : 'unread'}`;
                
                // Gawing clickable ang cursor para halatang Pindutin ito
                item.style.cursor = 'pointer';

                // Kapag pinindot ang indibidwal na notification, i-update ito sa Firestore bilang read na
                item.addEventListener('click', async () => {
                    if (!notif.isRead) {
                        try {
                            const { updateDoc } = await import("https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js");
                            await updateDoc(docSnapshot.ref, { isRead: true });
                        } catch (error) {
                            console.error("Error updating notification status: ", error);
                        }
                    }
                });

                // Kung unread, lagyan natin ng maliit na blue indicator dot sa gilid
                let dotHtml = !notif.isRead ? `<div class="notif-dot"></div>` : `<div style="width: 8px; flex-shrink: 0;"></div>`;

                item.innerHTML = `
                    ${dotHtml}
                    <div class="notif-content" style="width: 100%;">
                        <p style="font-weight: ${notif.isRead ? '500' : '600'};">${notif.title || 'Update'}</p>
                        <p class="notif-msg">${notif.message}</p>
                    </div>
                `;

                listContainer.appendChild(item);
            });

            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        }, (error) => {
            console.error("Error fetching real-time notifications: ", error);
        });

        // Mark All as Read Event Listener
        if (markAllBtn) {
            markAllBtn.addEventListener('click', async () => {
                try {
                    const querySnapshot = await getDocs(itemsRef);
                    if (querySnapshot.empty) return;

                    const batch = writeBatch(db);
                    let hasUnread = false;

                    querySnapshot.forEach((docSnap) => {
                        const data = docSnap.data();
                        if (!data.isRead) {
                            batch.update(docSnap.ref, { isRead: true });
                            hasUnread = true;
                        }
                    });

                    if (hasUnread) {
                        await batch.commit();
                    }
                } catch (error) {
                    console.error("Error marking all as read: ", error);
                }
            });
        }

    } else {
        console.error("Firebase db is not initialized yet. Check your firebase-config.js export.");
    }
});