import { auth, db } from "./firebase-config.js";
import {
    collection,
    query,
    where,
    getDocs,
    onSnapshot,
    orderBy,
    limit
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import {
    onAuthStateChanged
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

// 1. Dynamic Greeting ayon sa oras
const greeting = document.getElementById("greeting");
if (greeting) {
    const hour = new Date().getHours();
    if (hour >= 5 && hour < 12) {
        greeting.textContent = "Good Morning!";
    } else if (hour >= 12 && hour < 18) {
        greeting.textContent = "Good Afternoon!";
    } else {
        greeting.textContent = "Good Evening!";
    }
}

// 2. Real-time Authentication & Firestore Sync
onAuthStateChanged(auth, async (user) => {
    if (!user) return;

    try {
        // Kunin ang user profile para makuha ang ownerId gamit ang email
        const userQuery = query(
            collection(db, "users"),
            where("email", "==", user.email),
            limit(1)
        );

        const userSnapshot = await getDocs(userQuery);
        if (userSnapshot.empty) return;

        const ownerId = userSnapshot.docs[0].data().ownerId;

        // 3. Real-time listener para sa Total Pets (Counter)
        const petQuery = query(
            collection(db, "pets"),
            where("ownerId", "==", ownerId)
        );
        onSnapshot(petQuery, (petSnapshot) => {
            const totalPetsEl = document.getElementById("totalPets");
            if (totalPetsEl) {
                totalPetsEl.innerText = petSnapshot.size;
            }
        });

        // 4. Real-time listener para sa Appointments (Counter & Upcoming Appointment Card)
        const appointmentsQuery = query(
            collection(db, "appointments"),
            where("ownerId", "==", ownerId)
        );
        onSnapshot(appointmentsQuery, (snapshot) => {
            let upcomingCount = 0;
            const now = new Date();
            let nearestAppointment = null;

            snapshot.forEach((docSnap) => {
                const data = docSnap.data();
                
                // Kunin ang petsa (sinusuportahan ang parehong string date o Firestore Timestamp)
                let apptDate = data.date;
                if (apptDate && typeof apptDate.toDate === 'function') {
                    apptDate = apptDate.toDate();
                } else {
                    apptDate = new Date(data.date);
                }

                // Bilangin kung ang status ay hindi cancelled/completed at hindi pa lumilipas ang oras
                if (data.status !== 'Cancelled' && data.status !== 'Completed' && apptDate >= now) {
                    upcomingCount++;
                    // Hanapin ang pinakamalapit na schedule
                    if (!nearestAppointment || apptDate < nearestAppointment.dateObj) {
                        nearestAppointment = { ...data, dateObj: apptDate };
                    }
                }
            });

            // Update Appointment Counter sa itaas
            const appointmentCountEl = document.getElementById("appointmentCount") || document.getElementById("totalAppointments");
            if (appointmentCountEl) {
                appointmentCountEl.innerText = upcomingCount;
            }

            // Update Upcoming Appointment UI Container sa baba (tulad ng reminder card)
            const upcomingContainer = document.getElementById("upcomingAppointmentContainer");
            if (upcomingContainer) {
                if (nearestAppointment) {
                    const formattedDate = nearestAppointment.dateObj.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    
                    upcomingContainer.innerHTML = `
                        <div style="padding: 20px; background: #fafbff; border-radius: 10px; border-left: 5px solid #5142f5; border: 1px solid #edf2f7;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <h4 style="margin: 0; color: #173f81; font-size: 16px;">
                                    <i class="fa-solid fa-stethoscope" style="color: #5142f5; margin-right: 8px;"></i> ${nearestAppointment.service || 'Vet Consultation'}
                                </h4>
                                <span style="background: #ebf8ff; color: #3182ce; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${nearestAppointment.status || 'Pending'}</span>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 8px; color: #173f81; font-size: 14px;">
                                <p style="margin: 0;"><strong>Pet:</strong> ${nearestAppointment.petName || 'N/A'}</p>
                                <p style="margin: 0;"><strong>Doctor:</strong> ${nearestAppointment.doctor || 'N/A'}</p>
                                <p style="margin: 0;"><strong>Schedule:</strong> ${formattedDate} (${nearestAppointment.timeSlot || 'N/A'})</p>
                            </div>
                            ${nearestAppointment.notes ? `<p style="margin: 10px 0 0 0; color: #718096; font-size: 13px; font-style: italic;">Notes: "${nearestAppointment.notes}"</p>` : ''}
                        </div>
                    `;
                } else {
                    upcomingContainer.innerHTML = `<p style="text-align: center; color: #173f81; padding: 20px;">You don't have any upcoming appointments.</p>`;
                }
            }
        });

        // 5. Real-time listener para sa Unread Messages Counter
        const messagesQuery = query(
            collection(db, "messages"),
            where("ownerId", "==", ownerId),
            where("status", "==", "Unread")
        );
        onSnapshot(messagesQuery, (snapshot) => {
            const messageCountEl = document.getElementById("messageCount") || document.getElementById("unreadMessagesCount");
            if (messageCountEl) {
                messageCountEl.innerText = snapshot.size;
            }
        });

        // 6. Real-time listener para sa Recent Activities
        const activitiesQuery = query(
            collection(db, "activities"),
            where("ownerId", "==", ownerId)
        );

        onSnapshot(activitiesQuery, (snapshot) => {
            const activitiesContainer = document.getElementById("recentActivitiesContainer");
            if (!activitiesContainer) return;

            if (snapshot.empty) {
                activitiesContainer.innerHTML = `<p style="text-align: center; color: #173F81; padding: 20px; margin: 0;">No recent activities yet.</p>`;
                return;
            }

            let activitiesHTML = '<div style="display: flex; flex-direction: column; gap: 12px;">';
            
            const activities = [];
            snapshot.forEach((docSnap) => {
                activities.push(docSnap.data());
            });

            // Sort batay sa timestamp mula sa pinakabago patungong luma
            activities.sort((a, b) => {
                const timeA = a.timestamp && typeof a.timestamp.toDate === 'function' ? a.timestamp.toDate() : new Date(0);
                const timeB = b.timestamp && typeof b.timestamp.toDate === 'function' ? b.timestamp.toDate() : new Date(0);
                return timeB - timeA;
            });

            activities.forEach((act) => {
                const timeString = act.timestamp && typeof act.timestamp.toDate === 'function' 
                    ? act.timestamp.toDate().toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' })
                    : 'Just now';

                activitiesHTML += `
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #5142f5;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fa-solid fa-circle-dot" style="color: #5142f5; font-size: 10px;"></i>
                            <span style="color: #173F81; font-size: 14px; font-weight: 500;">${act.description || 'Performed an action'}</span>
                        </div>
                        <span style="color: #a0aec0; font-size: 12px;">${timeString}</span>
                    </div>
                `;
            });

            activitiesHTML += '</div>';
            activitiesContainer.innerHTML = activitiesHTML;
        }, (error) => {
            const recentActivitiesContainer = document.getElementById("recentActivitiesContainer");
            if (recentActivitiesContainer) {
                recentActivitiesContainer.innerHTML = `<p style="text-align: center; color: #888;">No recent activities yet.</p>`;
            }
        });

    } catch (error) {
        console.error("Error loading dashboard data:", error);
    }
});