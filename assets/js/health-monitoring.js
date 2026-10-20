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

// =========================
// HELPER: VITAL SIGNS COLOR STYLES (BACKGROUND & TEXT)
// =========================
function getVitalBoxStyle(type, value) {
    if (value === null || value === undefined || isNaN(value)) {
        return { bg: "#f8fafc", text: "#173F81", border: "#edf2f7" };
    }
    const num = parseFloat(value);
    
    if (type === "heartRate") {
        // Normal HR: ~60 - 140 bpm
        if (num < 60) return { bg: "#eff6ff", text: "#1e40af", border: "#bfdbfe" }; // Low - Blue
        if (num > 140) return { bg: "#fef2f2", text: "#991b1b", border: "#fecaca" }; // High - Red
        return { bg: "#f0fdf4", text: "#166534", border: "#bbf7d0" }; // Normal - Green
    } else if (type === "respiratoryRate") {
        // Normal RR: ~10 - 30 breaths/min
        if (num < 10) return { bg: "#eff6ff", text: "#1e40af", border: "#bfdbfe" }; // Low - Blue
        if (num > 30) return { bg: "#fef2f2", text: "#991b1b", border: "#fecaca" }; // High - Red
        return { bg: "#f0fdf4", text: "#166534", border: "#bbf7d0" }; // Normal - Green
    } else if (type === "temperature") {
        // Normal Temp: ~37.5°C - 39.2°C
        if (num < 37.5) return { bg: "#eff6ff", text: "#1e40af", border: "#bfdbfe" }; // Low - Blue
        if (num > 39.2) return { bg: "#fef2f2", text: "#991b1b", border: "#fecaca" }; // High - Red
        return { bg: "#f0fdf4", text: "#166534", border: "#bbf7d0" }; // Normal - Green
    }
    return { bg: "#f8fafc", text: "#173F81", border: "#edf2f7" };
}

document.addEventListener("DOMContentLoaded", async () => {
    const container = document.getElementById("healthRecordsContainer");
    
    const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00005";
    try {
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
            
            let admittedDate = "N/A";
            if (data.admittedAt && typeof data.admittedAt.toDate === "function") {
                admittedDate = data.admittedAt.toDate().toLocaleString();
            }
            
            const lastUpdatedText = formatTimestamp(data.updatedAt || data.createdAt);

            // Kunin ang kulay para sa buong box base sa ranges
            const hrStyle = getVitalBoxStyle("heartRate", vitals.heartRate);
            const rrStyle = getVitalBoxStyle("respiratoryRate", vitals.respiratoryRate);
            const tempStyle = getVitalBoxStyle("temperature", vitals.temperature);

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
            <div class="vital-box" style="background-color: ${hrStyle.bg}; border-color: ${hrStyle.border};">
                <h4 style="color: ${hrStyle.text};">Heart Rate</h4>
                <p style="color: ${hrStyle.text};">${vitals.heartRate || "--"} <span style="font-size: 13px; font-weight: 500; opacity: 0.8;">bpm</span></p>
                <i class="fa-solid fa-heart-pulse" style="position: absolute; right: 15px; bottom: 15px; color: ${hrStyle.text}; opacity: 0.3; font-size: 18px;"></i>
            </div>
            <div class="vital-box" style="background-color: ${rrStyle.bg}; border-color: ${rrStyle.border};">
                <h4 style="color: ${rrStyle.text};">Respiratory Rate</h4>
                <p style="color: ${rrStyle.text};">${vitals.respiratoryRate || "--"} <span style="font-size: 13px; font-weight: 500; opacity: 0.8;">breaths/min</span></p>
                <i class="fa-solid fa-lungs" style="position: absolute; right: 15px; bottom: 15px; color: ${rrStyle.text}; opacity: 0.3; font-size: 18px;"></i>
            </div>
            <div class="vital-box" style="background-color: ${tempStyle.bg}; border-color: ${tempStyle.border};">
                <h4 style="color: ${tempStyle.text};">Temperature</h4>
                <p style="color: ${tempStyle.text};">${vitals.temperature || "--"} <span style="font-size: 13px; font-weight: 500; opacity: 0.8;">°C</span></p>
                <i class="fa-solid fa-temperature-half" style="position: absolute; right: 15px; bottom: 15px; color: ${tempStyle.text}; opacity: 0.3; font-size: 18px;"></i>
            </div>
            <div class="vital-box" style="background: #f8fafc; border-color: #edf2f7;">
                <h4>Weight</h4>
                <p>${vitals.weight || "--"} <span style="font-size: 13px; font-weight: 500; color: #64748b;">kg</span></p>
                <i class="fa-solid fa-weight-scale" style="position: absolute; right: 15px; bottom: 15px; color: #cbd5e1; font-size: 18px;"></i>
            </div>
        </div>
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