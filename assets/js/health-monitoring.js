import { db } from "./firebase-config.js";
import {
    collection,
    query,
    where,
    getDocs
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

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

            // Format ng Timestamp kung sakaling Firestore Timestamp ito
            let admittedDate = "N/A";
            if (data.admittedAt && typeof data.admittedAt.toDate === "function") {
                admittedDate = data.admittedAt.toDate().toLocaleString();
            }

           htmlContent += `
    <div class="record-card">
        <div class="pet-title-area">
            <div>
                <h2>${data.petName || "Unknown Pet"}</h2>
                <div class="pet-meta">Breed: <strong>${data.breed || "N/A"}</strong> &bull; ID: ${data.petId || ""}</div>
            </div>
            <span class="badge ${statusClass}">${data.status || "UNKNOWN"}</span>
        </div>
        
        <div class="clinical-details">
            <div class="clinical-item">Chief Complaint:<br><span>${data.chiefComplaint || "None"}</span></div>
            <div class="clinical-item">Location Bay:<br><span>${data.locationBay || "N/A"}</span></div>
            <div class="clinical-item">Attending Doctor:<br><span>${data.doctorName || "N/A"}</span></div>
            <div class="clinical-item">Admitted At:<br><span>${admittedDate}</span></div>
        </div>
        
        <div class="vitals-section-title"><i class="fa-solid fa-heart-pulse"></i> Real-Time Vital Signs</div>
        <div class="vitals-grid">
            <div class="vital-box">
                <h4>Heart Rate</h4>
                <p>${vitals.heartRate || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">bpm</span></p>
                <i class="fa-solid fa-heart-pulse"></i>
            </div>
            <div class="vital-box">
                <h4>Respiratory Rate</h4>
                <p>${vitals.respiratoryRate || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">breaths/min</span></p>
                <i class="fa-solid fa-lungs"></i>
            </div>
            <div class="vital-box">
                <h4>Temperature</h4>
                <p>${vitals.temperature || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">°C</span></p>
                <i class="fa-solid fa-temperature-half"></i>
            </div>
            <div class="vital-box">
                <h4>Weight</h4>
                <p>${vitals.weight || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">kg</span></p>
                <i class="fa-solid fa-weight-scale"></i>
            </div>
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