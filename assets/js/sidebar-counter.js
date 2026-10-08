import { db } from "./firebase-config.js";
import { collection, query, where, getDocs } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", async function() {
    const currentOwnerId = localStorage.getItem("ownerId");
    
    // Kunin ang mga badge elements
    const healthBadge = document.getElementById("health-badge");
    const apptBadge = document.getElementById("appointment-badge");
    const msgBadge = document.getElementById("message-badge");

    // Hulaan o itago muna lahat sa simula para walang lumitaw na "0"
    if (healthBadge) healthBadge.style.display = "none";
    if (apptBadge) apptBadge.style.display = "none";
    if (msgBadge) msgBadge.style.display = "none";

    if (!currentOwnerId) return;

    try {
        // 1. Bilangin ang Health Records / Monitoring
        const healthQuery = query(collection(db, "health_monitoring"), where("ownerId", "==", currentOwnerId));
        const healthSnapshot = await getDocs(healthQuery);
        
        if (healthBadge && healthSnapshot.size > 0) {
            healthBadge.textContent = healthSnapshot.size;
            healthBadge.style.display = "inline-block";
        }

        // 2. Bilangin ang Pending Appointments
        const apptQuery = query(collection(db, "appointments"), where("ownerId", "==", currentOwnerId), where("status", "==", "Pending"));
        const apptSnapshot = await getDocs(apptQuery);
        
        if (apptBadge && apptSnapshot.size > 0) {
            apptBadge.textContent = apptSnapshot.size;
            apptBadge.style.display = "inline-block";
        }

        // 3. Bilangin ang Unread Messages
        const msgQuery = query(collection(db, "messages"), where("ownerId", "==", currentOwnerId), where("status", "==", "Unread"));
        const msgSnapshot = await getDocs(msgQuery);
        
        if (msgBadge && msgSnapshot.size > 0) {
            msgBadge.textContent = msgSnapshot.size;
            msgBadge.style.display = "inline-block";
        }
        
    } catch (error) {
        console.error("Error updating sidebar badge counters:", error);
    }
});