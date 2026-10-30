import { auth, db } from "./firebase-config.js";
import {
    collection, query, where, getDocs, onSnapshot, limit
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

// --- Clinic Status Checker (Philippine Time) ---
function checkClinicStatus() {
    const options = { timeZone: 'Asia/Manila', hour: 'numeric', minute: 'numeric', hour12: false };
    const formatter = new Intl.DateTimeFormat([], options);
    const timeString = formatter.format(new Date());
    
    const [hourStr, minuteStr] = timeString.split(':');
    const currentHour = parseInt(hourStr, 10);
    const currentMinute = parseInt(minuteStr, 10);
    const currentTimeDecimal = currentHour + (currentMinute / 60);
    const openingHour = 10.0;
    const closingHour = 19.0;
    const statusBadge = document.getElementById('clinicStatusBadge');
    if (statusBadge) {
        if (currentTimeDecimal >= openingHour && currentTimeDecimal < closingHour) {
            statusBadge.textContent = "Open";
            statusBadge.style.backgroundColor = "#dcfce7";
            statusBadge.style.color = "#16a34a";
        } else {
            statusBadge.textContent = "Closed";
            statusBadge.style.backgroundColor = "#fee2e2";
            statusBadge.style.color = "#dc2626";
        }
    }
}
checkClinicStatus();
setInterval(checkClinicStatus, 60000);

// --- TANDANG FRAME ANIMATION & ROTATING REMINDERS ---
const GROQ_API_KEY_TANDANG = "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"; 
let weatherDataCache = { temp: 30.7, city: "Caloocan", isRainy: false };
const cityCoordinates = {
    "caloocan": { lat: 14.650, lon: 120.967 },
    "valenzuela": { lat: 14.7006, lon: 120.9830 },
    "quezon city": { lat: 14.6760, lon: 121.0437 },
    "manila": { lat: 14.5995, lon: 120.9842 },
    "malabon": { lat: 14.6681, lon: 120.9567 },
    "navotas": { lat: 14.6675, lon: 120.9431 }
};

let userPetsCache = [];
let isFirstTemperatureCheckDone = false; // Flag para isang beses lang lalabas ang temperature message

// 10 iba't ibang pet care reminders sa English
const englishRemindersPool = [
    "Kukurukuku! Please make sure {PETS} drink plenty of fresh water to stay hydrated throughout the day!",
    "Kukurukuku! Rain or wet weather is approaching. Keep {PETS} safe and avoid letting them walk through floodwaters to prevent leptospirosis!",
    "Kukurukuku! Always check {PETS} for ticks and fleas after outdoor walks in the park.",
    "Kukurukuku! Keep toxic human foods like chocolate, onions, and grapes strictly away from {PETS}.",
    "Kukurukuku! Schedule regular veterinary check-ups and keep {PETS} updated on their annual vaccinations.",
    "Kukurukuku! Brush {PETS}'s coat regularly and check their ears weekly to prevent dirt build-up and infections.",
    "Kukurukuku! Maintain good dental hygiene for {PETS} by brushing their teeth or giving them dental chew treats.",
    "Kukurukuku! Ensure {PETS} get balanced daily nutrition and measured food portions to maintain a healthy weight.",
    "Kukurukuku! Provide safe indoor toys and interactive games to keep {PETS} physically active and mentally sharp.",
    "Kukurukuku! Protect {PETS}'s paws by avoiding hot asphalt walks during peak midday sun hours."
];
let reminderPoolIndex = 0;

function initTandangAnimation() {
    const tandangImg = document.getElementById("tandangImage");
    if (!tandangImg) return;
    const frames = [
        "../assets/images/t1.png",
        "../assets/images/t2.png",
        "../assets/images/t3.png",
        "../assets/images/t4.png"
    ];
    let currentFrameIndex = 0;
    setInterval(() => {
        currentFrameIndex = (currentFrameIndex + 1) % frames.length;
        tandangImg.src = frames[currentFrameIndex];
    }, 1200); 
}

async function fetchDynamicWeather() {
    try {
        let userCity = localStorage.getItem("userAddress") || "Caloocan";
        userCity = userCity.split(',')[0].trim();
        weatherDataCache.city = userCity;
        let key = userCity.toLowerCase().trim();
        let coords = cityCoordinates[key] || { lat: 14.650, lon: 120.967 };
        
        const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${coords.lat}&longitude=${coords.lon}&current=temperature_2m,precipitation&timezone=Asia%2FManila`);
        const data = await res.json();
        if (data && data.current) {
            weatherDataCache.temp = data.current.temperature_2m;
            weatherDataCache.isRainy = data.current.precipitation > 0;
        }
    } catch (err) {
        console.error("Weather fetch error:", err);
    }
}

function formatPetNames(pets) {
    if (!pets || pets.length === 0) return "your pets";
    if (pets.length === 1) return pets[0];
    if (pets.length === 2) return `${pets[0]} and ${pets[1]}`;
    return pets.slice(0, -1).join(", ") + `, and ${pets[pets.length - 1]}`;
}

async function updateTandangDisplay() {
    const msgEl = document.getElementById("tandangMessageText");
    const badgeEl = document.getElementById("tandangContextBadge");
    const subGreetingEl = document.getElementById("subGreeting");
    
    const currentHour = new Date().getHours();
    let timeGreet = "Good morning";
    if (currentHour >= 12 && currentHour < 18) {
        timeGreet = "Good afternoon";
    } else if (currentHour >= 18) {
        timeGreet = "Good evening";
    }
    if (subGreetingEl) {
        subGreetingEl.textContent = `${timeGreet}! Your AI pet buddy has a tip for you.`;
    }

    const petsStr = formatPetNames(userPetsCache);
    let tipSentence = "";

    // ISANG BESES LANG LALABAS ANG TEMPERATURE CHECK MESSAGE
    if (!isFirstTemperatureCheckDone) {
        tipSentence = `Kukurukuku! On this ${weatherDataCache.temp} °C day in ${weatherDataCache.city}, welcome! Here are quick pet care tips for ${petsStr}.`;
        isFirstTemperatureCheckDone = true;
    } else {
        // SA MGA KASUNOD NA PAG-IKOT (EVERY 15s), MAG-FOCUS NA PURELY SA PET CARE REMINDERS
        let baseReminder = englishRemindersPool[reminderPoolIndex];
        reminderPoolIndex = (reminderPoolIndex + 1) % englishRemindersPool.length;
        tipSentence = baseReminder.replace(/{PETS}/g, petsStr);
    }

    if (msgEl) {
        msgEl.textContent = tipSentence;
    }
    if (badgeEl) {
        badgeEl.textContent = `AI reminder · Weather-informed · ${weatherDataCache.city}`;
    }

    try {
        const response = await fetch("https://api.groq.com/openai/v1/chat/completions", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${GROQ_API_KEY_TANDANG}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                model: "llama-3.3-70b-versatile",
                messages: [
                    { role: "system", content: "You are Tandang, a friendly pet care AI assistant. Give a clear single-sentence pet care advice in English." },
                    { role: "user", content: `Pet names: ${petsStr}. Message: ${tipSentence}` }
                ],
                max_tokens: 70
            })
        });
        if (response.ok) {
            const data = await response.json();
            if (data.choices && data.choices[0] && data.choices[0].message) {
                const aiText = data.choices[0].message.content.trim();
                if (msgEl) msgEl.textContent = aiText;
            }
        }
    } catch (e) {
        console.log("Using clean localized rotating fallback.");
    }
}

// --- 1. Seasonal Reminder & Greeting ---
function updateGreeting(fullName = "") {
    const greeting = document.getElementById("greeting");
    if (greeting) {
        const nameToUse = fullName || localStorage.getItem("fullName") || "User";
        const firstName = nameToUse.trim().split(" ")[0] || "User";
        greeting.textContent = `Welcome back, ${firstName}!`;
    }
}
updateGreeting();

// --- 2. Topbar Calendar Appointments Loader ---
async function loadTopbarAppointments(ownerId) {
    const container = document.getElementById("topbarAppointmentsList");
    if (!container) return;
    try {
        const q = query(collection(db, "appointments_v2"), where("ownerId", "==", ownerId));
        const snapshot = await getDocs(q);
        const todayStr = new Date().toISOString().split('T')[0];
        let html = '';
        let validCount = 0;
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const statusLower = (data.status || "").toLowerCase().trim();
            if (data.isArchived !== true && statusLower !== "trash" && statusLower !== "cancelled" && data.date >= todayStr) {
                validCount++;
                html += `
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; border-left: 3px solid #5142f5; font-size: 12px; margin-bottom: 5px;">
                        <div style="font-weight: 600; color: #173f81;">${data.service || 'Consultation'} (${data.petName || 'Pet'})</div>
                        <div style="color: #4a5568; margin-top: 2px;"><i class="fa-regular fa-clock" style="margin-right: 4px;"></i> ${data.date} (${data.timeSlot || data.time || '10:00 AM - 7:00 PM'})</div>
                    </div>`;
            }
        });
        if (validCount === 0) {
            container.innerHTML = `<p style="text-align: center; color: #a0aec0; font-size: 12px; margin: 15px 0;">No upcoming appointments.</p>`;
            return;
        }
        container.innerHTML = html;
    } catch (error) {
        console.error("Error loading topbar appointments:", error);
    }
}

// --- 2.3 Appointment Status Tracking with X Button Beside Status Badge ---
window.dismissTrackerItem = function(itemId, storageKeyPrefix) {
    const itemEl = document.getElementById(storageKeyPrefix + "_" + itemId);
    if (itemEl) {
        itemEl.style.display = 'none';
        let dismissed = JSON.parse(localStorage.getItem(storageKeyPrefix + "_dismissed") || "[]");
        if (!dismissed.includes(itemId)) {
            dismissed.push(itemId);
            localStorage.setItem(storageKeyPrefix + "_dismissed", JSON.stringify(dismissed));
        }
    }
};

function loadDashboardAppointmentTracker(ownerId) {
    const trackerContainer = document.getElementById("dashboardAppointmentTrackerContainer");
    if (!trackerContainer) return;
    const apptQuery = query(collection(db, "appointments_v2"), where("ownerId", "==", ownerId));
    onSnapshot(apptQuery, (snapshot) => {
        if (snapshot.empty) {
            trackerContainer.innerHTML = `<p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">No appointment tracking records found.</p>`;
            return;
        }
        let trackerHtml = '';
        let activeCount = 0;
        const now = new Date().getTime();
        const TWENTY_FOUR_HOURS = 24 * 60 * 60 * 1000;
        let dismissedAppts = JSON.parse(localStorage.getItem("appt_dismissed") || "[]");
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const docId = docSnap.id;
            if (dismissedAppts.includes(docId)) return;
            const status = (data.status || "pending").toLowerCase().trim();
            if (status === "completed") {
                let completionTime = now;
                if (data.updatedAt && typeof data.updatedAt.toDate === 'function') {
                    completionTime = data.updatedAt.toDate().getTime();
                } else if (data.completedAt && typeof data.completedAt.toDate === 'function') {
                    completionTime = data.completedAt.toDate().getTime();
                } else if (data.date) {
                    completionTime = new Date(data.date).getTime();
                }
                
                if ((now - completionTime) > TWENTY_FOUR_HOURS) {
                    return;
                }
            }
            activeCount++;
            let step1Class = 'step active';    
            let step2Class = 'step';          
            let step3Class = 'step';          
            let lineActive1 = '';
            let lineActive2 = '';
            let statusBadgeBg = "#fef9c3";
            let statusBadgeColor = "#854d0e";
            let statusBadgeText = "Pending";
            if (status === "approved" || status === "confirmed") {
                step1Class = 'step completed'; 
                step2Class = 'step active';    
                lineActive1 = 'active';
                statusBadgeBg = "#dcfce7";
                statusBadgeColor = "#166534";
                statusBadgeText = "Approved";
            } else if (status === "completed") {
                step1Class = 'step completed'; 
                step2Class = 'step completed'; 
                step3Class = 'step completed'; 
                lineActive1 = 'active';
                lineActive2 = 'active';
                statusBadgeBg = "#e0e7ff";
                statusBadgeColor = "#3730a3";
                statusBadgeText = "Completed";
            } else if (status === "cancelled") {
                statusBadgeBg = "#fee2e2";
                statusBadgeColor = "#991b1b";
                statusBadgeText = "Cancelled";
            }
            trackerHtml += `
                <div id="appt_${docId}" style="background: #ffffff; padding: 15px; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <h4 style="margin: 0 0 2px 0; color: #1e1b4b; font-size: 15px; font-weight: 600;"><i class="fa-solid fa-calendar-day" style="color: #5142f5; margin-right: 5px;"></i>${data.service || "Consultation"}</h4>
                            <div style="font-size: 12px; color: #64748b;">Pet: <strong>${data.petName || "N/A"}</strong> &bull; Date: <strong>${data.date || "N/A"} (${data.timeSlot || data.time || "N/A"})</strong></div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <span style="background: ${statusBadgeBg}; color: ${statusBadgeColor}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">${statusBadgeText}</span>
                            <button onclick="dismissTrackerItem('${docId}', 'appt')" style="background: transparent; border: none; color: #94a3b8; font-size: 13px; cursor: pointer; padding: 2px 6px; border-radius: 4px; transition: all 0.2s ease;" title="Remove appointment"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                    <div class="progress-tracker-container" style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9; margin-top: 10px;">
                        <div class="stepper">
                            <div class="${step1Class}" data-step="1">
                                <div class="step-circle"><i class="fa-solid fa-clock"></i></div>
                                <div class="step-text">Pending</div>
                            </div>
                            <div class="step-line ${lineActive1}"></div>
                            <div class="${step2Class}" data-step="2">
                                <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                                <div class="step-text">Approved</div>
                            </div>
                            <div class="step-line ${lineActive2}"></div>
                            <div class="${step3Class}" data-step="3">
                                <div class="step-circle"><i class="fa-solid fa-circle-check"></i></div>
                                <div class="step-text">Completed</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 12px; color: #475569; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 5px;">
                        <span><strong>Notes:</strong> ${data.notes || data.reason || "None"}</span>
                        <a href="../pages/appointment.php" style="color: #5142f5; text-decoration: none; font-weight: 600;">View Details &rarr;</a>
                    </div>
                </div>
            `;
        });
        if (activeCount === 0) {
            trackerContainer.innerHTML = `<p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">No active appointment tracking records found.</p>`;
        } else {
            trackerContainer.innerHTML = trackerHtml;
        }
    }, (error) => {
        console.error("Appointment tracking snapshot error:", error);
    });
}

// --- 3. Main Dashboard Health Monitoring Data Loader with X Button Beside Status Badge ---
function loadDashboardHealthData(ownerId) {
    const healthContainer = document.getElementById("dashboardHealthContainer");
    if (!healthContainer) return;
    const healthQuery = query(collection(db, "health_monitoring"), where("ownerId", "==", ownerId));
    
    onSnapshot(healthQuery, (snapshot) => {
        if (snapshot.empty) {
            healthContainer.innerHTML = `<p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">No active health monitoring records found for your pets.</p>`;
            return;
        }
        
        let containerHtml = '';
        let activeHealthCount = 0;
        const now = new Date().getTime();
        const TWENTY_FOUR_HOURS = 24 * 60 * 60 * 1000;
        let dismissedHealth = JSON.parse(localStorage.getItem("health_dismissed") || "[]");
        snapshot.forEach((docSnap) => {
            const data = docSnap.data();
            const docId = docSnap.id;
            if (dismissedHealth.includes(docId)) return;
            const vitals = data.vitals || {};
            const sUpper = (data.status || "").toUpperCase();
            if (sUpper === 'DISCHARGED' || sUpper === 'COMPLETED') {
                let dischargeTime = now;
                if (data.dischargedAt && typeof data.dischargedAt.toDate === 'function') {
                    dischargeTime = data.dischargedAt.toDate().getTime();
                } else if (data.updatedAt && typeof data.updatedAt.toDate === 'function') {
                    dischargeTime = data.updatedAt.toDate().getTime();
                }
                if ((now - dischargeTime) > TWENTY_FOUR_HOURS) {
                    return;
                }
            }
            activeHealthCount++;
            
            let step1Class = 'step completed';
            let step2Class = 'step active';
            let step3Class = 'step';
            let lineActive = '';
            if (sUpper === 'DISCHARGED' || sUpper === 'COMPLETED') {
                step1Class = 'step completed';
                step2Class = 'step completed';
                step3Class = 'step completed';
                lineActive = 'active';
            }
            containerHtml += `
                <div id="health_${docId}" style="background: #ffffff; padding: 15px; border-radius: 14px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <h4 style="margin: 0 0 2px 0; color: #1e1b4b; font-size: 15px; font-weight: 600;"><i class="fa-solid fa-paw" style="color: #5142f5; margin-right: 5px;"></i>${data.petName || "Unknown Pet"}</h4>
                            <div style="font-size: 12px; color: #64748b;">${data.petId || ""} &bull; Breed: <strong>${data.breed || "N/A"}</strong></div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <span style="background: ${sUpper === 'STABLE' ? '#dcfce7' : '#fee2e2'}; color: ${sUpper === 'STABLE' ? '#166534' : '#991b1b'}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">${data.status || "UNKNOWN"}</span>
                            <button onclick="dismissTrackerItem('${docId}', 'health')" style="background: transparent; border: none; color: #94a3b8; font-size: 13px; cursor: pointer; padding: 2px 6px; border-radius: 4px; transition: all 0.2s ease;" title="Remove health tracker"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                    <div class="progress-tracker-container" style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9; margin-top: 10px;">
                        <div class="stepper">
                            <div class="${step1Class}" data-step="1">
                                <div class="step-circle"><i class="fa-solid fa-clipboard-list"></i></div>
                                <div class="step-text">Admitted</div>
                            </div>
                            <div class="step-line ${lineActive}"></div>
                            <div class="${step2Class}" data-step="2">
                                <div class="step-circle"><i class="fa-solid fa-stethoscope"></i></div>
                                <div class="step-text">Monitoring</div>
                            </div>
                            <div class="step-line ${lineActive}"></div>
                            <div class="${step3Class}" data-step="3">
                                <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                                <div class="step-text">Discharged</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 10px; font-size: 12px; color: #475569; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 5px;">
                        <span><strong>Temp:</strong> ${vitals.temperature || "--"} °C &bull; <strong>Weight:</strong> ${vitals.weight || "--"} kg</span>
                        <a href="../pages/health.php" style="color: #5142f5; text-decoration: none; font-weight: 600;">View Details &rarr;</a>
                    </div>
                </div>
            `;
        });
        if (activeHealthCount === 0) {
            healthContainer.innerHTML = `<p class="empty-state" style="text-align: center; color: #64748b; padding: 15px; font-size: 13px;">No active health monitoring records found for your pets.</p>`;
        } else {
            healthContainer.innerHTML = containerHtml;
        }
    }, (error) => {
        console.error("Health monitoring snapshot error:", error);
    });

    const activitiesContainer = document.getElementById("recentActivitiesContainer");
    const actQuery = query(collection(db, "activities"), where("ownerId", "==", ownerId));
    
    onSnapshot(actQuery, (snapshot) => {
        if (!activitiesContainer) return;
        if (snapshot.empty) {
            activitiesContainer.innerHTML = `<p class="empty-state">No recent activities yet.</p>`;
            return;
        }
        let activitiesList = [];
        snapshot.forEach((docSnap) => {
            activitiesList.push({ id: docSnap.id, ...docSnap.data() });
        });
        activitiesList.sort((a, b) => {
            let timeA = a.timestamp?.toDate ? a.timestamp.toDate() : new Date(a.timestamp || 0);
            let timeB = b.timestamp?.toDate ? b.timestamp.toDate() : new Date(b.timestamp || 0);
            return timeB - timeA;
        });
        activitiesList = activitiesList.slice(0, 5);
        let html = '<div style="display: flex; flex-direction: column; gap: 10px;">';
        activitiesList.forEach((act) => {
            const desc = act.description || act.title || 'Activity performed';
            let timeStr = 'Recently';
            if (act.timestamp) {
                if (typeof act.timestamp.toDate === 'function') {
                    const dateObj = act.timestamp.toDate();
                    timeStr = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' at ' + dateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                } else {
                    timeStr = act.timestamp;
                }
            }
            html += '<div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8f9fa; border-radius: 8px;">';
            html += '<div style="background: #ebf4ff; color: #5142f5; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;"><i class="fa-solid fa-history"></i></div>';
            html += '<div style="flex: 1;">';
            html += '<p style="margin: 0; font-size: 13px; color: #2d3748; font-weight: 500;">' + desc + '</p>';
            html += '<span style="font-size: 11px; color: #a0aec0;"><i class="fa-regular fa-clock" style="margin-right: 3px;"></i>' + timeStr + '</span>';
            html += '</div></div>';
        });
        html += '</div>';
        activitiesContainer.innerHTML = html;
    }, (error) => {
        console.error("Activities snapshot error:", error);
    });
}

// --- 4. Main Auth Listener ---
onAuthStateChanged(auth, async (user) => {
    initTandangAnimation(); 
    if (!user) return;
    try {
        const userQuery = query(collection(db, "users"), where("email", "==", user.email), limit(1));
        const userSnapshot = await getDocs(userQuery);
        
        if (userSnapshot.empty) return;
        const userData = userSnapshot.docs[0].data();
        const ownerId = userData.ownerId;
        if (!ownerId) return;

        if (userData.fullName) {
            localStorage.setItem("fullName", userData.fullName);
            updateGreeting(userData.fullName);
        }
        
        if (userData.address) {
            localStorage.setItem("userAddress", userData.address);
            window.dispatchEvent(new CustomEvent("userAddressLoaded", { detail: userData.address }));
        }

        const petsQuery = query(collection(db, "pets"), where("ownerId", "==", ownerId));
        const petsSnap = await getDocs(petsQuery);
        let names = [];
        petsSnap.forEach(doc => {
            const pData = doc.data();
            if (pData.petName) names.push(pData.petName);
        });
        if (names.length > 0) {
            userPetsCache = names;
        }

        await fetchDynamicWeather();
        updateTandangDisplay();
        
        // Interval para umikot ang 10 reminders bawat 15 segundo
        setInterval(async () => {
            await fetchDynamicWeather();
            updateTandangDisplay();
        }, 15000);

        const refreshBtn = document.getElementById("refreshTandangBtn");
        if (refreshBtn) {
            refreshBtn.addEventListener("click", async () => {
                const icon = document.getElementById("refreshIcon");
                if (icon) {
                    icon.style.transform = "rotate(360deg)";
                    icon.style.transition = "transform 0.5s ease";
                    setTimeout(() => { icon.style.transform = "rotate(0deg)"; }, 500);
                }
                await fetchDynamicWeather();
                updateTandangDisplay();
            });
        }

        loadTopbarAppointments(ownerId);
        loadDashboardAppointmentTracker(ownerId); 
        loadDashboardHealthData(ownerId);
        
        onSnapshot(query(collection(db, "pets"), where("ownerId", "==", ownerId)), (s) => {
            const el = document.getElementById("totalPets");
            if (el) el.innerText = s.size;
            let names = [];
            s.forEach(doc => {
                const pData = doc.data();
                if (pData.petName) names.push(pData.petName);
            });
            if (names.length > 0) {
                userPetsCache = names;
            }
        });
        onSnapshot(query(collection(db, "appointments_v2"), where("ownerId", "==", ownerId)), (s) => {
            let count = 0;
            const todayStr = new Date().toISOString().split('T')[0];
            s.forEach(d => {
                const data = d.data();
                const statusLower = (data.status || "").toLowerCase().trim();
                if (data.isArchived !== true && statusLower !== "trash" && statusLower !== "cancelled" && data.date >= todayStr) {
                    count++;
                }
            });
            const el = document.getElementById("appointmentCount");
            if (el) el.innerText = count;
        });
        onSnapshot(query(collection(db, "messages"), where("ownerId", "==", ownerId)), (s) => {
            let unread = 0;
            s.forEach(d => {
                const data = d.data();
                if (data.isRead !== true) unread++;
            });
            const el = document.getElementById("messageCount");
            if (el) el.innerText = unread;
        });
    } catch (err) {
        console.error("Auth state resolution error:", err);
    }
});