import { db } from "./firebase-config.js";
import {
    collection,
    query,
    where,
    getDocs
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

// =========================
// HELPER: FORMAT TIMESTAMP TO READABLE DATE
// =========================
function formatTimestamp(timestamp) {
    if (!timestamp) return "Not available";
    
    let date;
    if (typeof timestamp.toDate === "function") {
        date = timestamp.toDate();
    } else {
        date = new Date(timestamp);
    }
    if (isNaN(date.getTime())) return "Not available";
    return date.toLocaleString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "2-digit",
        hour12: true
    });
}

document.addEventListener("DOMContentLoaded", async () => {
    const container = document.getElementById("healthRecordsContainer");
    
    // Kunin ang ownerId mula sa localStorage (na-save natin nung nag-login)
    const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00005"; // fallback para sa testing

    try {
        // Query sa 'health_monitoring' collection kung saan tugma ang ownerId
        const q = query(
            collection(db, "health_monitoring"),
            where("ownerId", "==", currentOwnerId)
        );

        const querySnapshot = await getDocs(q);

        if (querySnapshot.empty) {
            container.innerHTML = `<p style="text-align:center; color:#777;">No active health monitoring records found for your pets.</p>`;
            return;
        }

        let htmlContent = "";

        querySnapshot.forEach((doc) => {
            const data = doc.data();
            const vitals = data.vitals || {};
            const statusClass = data.status === "STABLE" ? "stable" : "urgent";

            // Format ng Timestamp para sa Admitted At
            let admittedDate = "N/A";
            if (data.admittedAt && typeof data.admittedAt.toDate === "function") {
                admittedDate = data.admittedAt.toDate().toLocaleString();
            }

            // Kunin at i-format ang Last Updated (gumagamit ng updatedAt o kaya ay createdAt)
            const lastUpdatedText = formatTimestamp(data.updatedAt || data.createdAt);

           htmlContent += `
    <div class="record-card">
        <div class="pet-title-area" style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2>${data.petName || "Unknown Pet"}</h2>
                <div class="pet-meta">Breed: <strong>${data.breed || "N/A"}</strong> &bull; ID: ${data.petId || ""}</div>
            </div>
            <span class="badge ${statusClass}">${data.status || "UNKNOWN"}</span>
        </div>
        
        <div class="clinical-details" style="margin-top: 15px;">
            <div class="clinical-item">Chief Complaint:<br><span>${data.chiefComplaint || "None"}</span></div>
            <div class="clinical-item">Location Bay:<br><span>${data.locationBay || "N/A"}</span></div>
            <div class="clinical-item">Attending Doctor:<br><span>${data.doctorName || "N/A"}</span></div>
            <div class="clinical-item">Admitted At:<br><span>${admittedDate}</span></div>
        </div>
        
        <div class="vitals-section-title" style="margin-top: 15px;"><i class="fa-solid fa-heart-pulse"></i> Real-Time Vital Signs</div>
        <div class="vitals-grid">
            <div class="vital-box">
                <h4>Heart Rate</h4>
                <p>${vitals.heartRate || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">bpm</span></p>
                <i class="fa-solid fa-heart-pulse" style="position: absolute; right: 15px; bottom: 15px; color: #cbd5e1; font-size: 18px;"></i>
            </div>
            <div class="vital-box">
                <h4>Respiratory Rate</h4>
                <p>${vitals.respiratoryRate || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">breaths/min</span></p>
                <i class="fa-solid fa-lungs" style="position: absolute; right: 15px; bottom: 15px; color: #cbd5e1; font-size: 18px;"></i>
            </div>
            <div class="vital-box">
                <h4>Temperature</h4>
                <p>${vitals.temperature || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">°C</span></p>
                <i class="fa-solid fa-temperature-half" style="position: absolute; right: 15px; bottom: 15px; color: #cbd5e1; font-size: 18px;"></i>
            </div>
            <div class="vital-box">
                <h4>Weight</h4>
                <p>${vitals.weight || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">kg</span></p>
                <i class="fa-solid fa-weight-scale" style="position: absolute; right: 15px; bottom: 15px; color: #cbd5e1; font-size: 18px;"></i>
            </div>
        </div>

        <!-- Last Updated Footer sa Ibaba ng Card -->
        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #edf2f7; font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-clock-rotate-left"></i> 
            <span><strong>Last Updated:</strong> ${lastUpdatedText}</span>
        </div>
    </div>
`;
        });

        container.innerHTML = htmlContent;

    } catch (error) {
        console.error("Error fetching health records: ", error);
        if (typeof showToast === "function") {
            showToast("Error", "Failed to load health monitoring records.", "error");
        } else {
            container.innerHTML = `<p style="color:red; text-align:center;">Failed to load records.</p>`;
        }
    }
});