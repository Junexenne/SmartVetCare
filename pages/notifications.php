<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo-transparent.png">
    <style>
        body {
            background-color: #f4f7fe;
            font-family: 'Poppins', sans-serif;
        }
        .main-content {
            padding: 16px !important;
            box-sizing: border-box !important;
            background: #f4f7fe;
            min-height: 100vh;
        }
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .profile-style-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #edf2f7;
            box-sizing: border-box;
        }
        .page-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .page-header-custom h1 {
            font-size: 22px;
            color: #1e1b4b;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 4px 0;
        }
        .page-header-custom p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }
        .btn-mark-read {
            background: #173F81;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(23, 63, 129, 0.15);
        }
        .btn-mark-read:hover {
            background: #0f2b5a;
            transform: translateY(-1px);
        }
        .notif-filter-bar {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
            border-bottom: 1px solid #edf2f7;
            margin-bottom: 15px;
        }
        .filter-btn {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #64748b;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #173F81;
            color: #ffffff;
            border-color: #173F81;
        }
        .full-notif-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .full-notif-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all 0.2s ease;
            position: relative;
            text-decoration: none;
        }
        .full-notif-card.unread {
            background: #f0f7ff;
            border-color: #bfdbfe;
        }
        .full-notif-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        }
        .full-notif-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #e0e7ff;
            color: #4338ca;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            overflow: hidden;
        }
        /* Solid blue background para kay BANTAY.png */
        .full-notif-icon.bantay-bg {
            background: #d1dfff !important;
            border: 1px solid #d1dfff;
        }
        .full-notif-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .full-notif-card.approved .full-notif-icon {
            background: #dcfce7;
            color: #15803d;
        }
        .full-notif-card.rejected .full-notif-icon {
            background: #fee2e2;
            color: #dc2626;
        }
        .full-notif-content {
            flex: 1;
        }
        .full-notif-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px 0;
        }
        .full-notif-msg {
            font-size: 13px;
            color: #475569;
            margin: 0 0 8px 0;
            line-height: 1.4;
        }
        .full-notif-time {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .unread-dot {
            width: 8px;
            height: 8px;
            background: #2563eb;
            border-radius: 50%;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .empty-notifications-card {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }
        .empty-notifications-card i {
            font-size: 40px;
            margin-bottom: 12px;
            color: #cbd5e1;
        }
        .empty-notifications-card h3 {
            margin: 0 0 4px 0;
            color: #475569;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include "../includes/sidebar.php"; ?>
    <div class="main-content">
        <?php include "../includes/topbar.php"; ?>
        <div class="page-container">
            <!-- Header Card -->
            <div class="profile-style-card page-header-custom">
                <div>
                    <h1>
                        <i class="fa-solid fa-bell" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                        Notifications Center
                    </h1>
                    <p>View, manage, and check all your clinic alerts and reminders in one place.</p>
                </div>
                <button class="btn-mark-read" id="pageMarkAllBtn">
                    <i class="fa-solid fa-check-double"></i> Mark All as Read
                </button>
            </div>
            <!-- List Card -->
            <div class="profile-style-card">
                <!-- Filter Buttons -->
                <div class="notif-filter-bar">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="appointment">Appointments</button>
                    <button class="filter-btn" data-filter="health">Health</button>
                    <button class="filter-btn" data-filter="vaccination">Vaccinations</button>
                    <button class="filter-btn" data-filter="clinic">Clinic</button>
                </div>
                <!-- Notifications Grid Container -->
                <div class="full-notif-list" id="fullNotifContainer">
                    <div class="empty-notifications-card">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <h3>Loading notifications...</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("../includes/bottom-nav.php"); ?>
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
    import { 
        getFirestore, 
        collection, 
        query, 
        where, 
        onSnapshot, 
        getDocs, 
        doc, 
        updateDoc 
    } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";
    
    const firebaseConfig = {
        apiKey: "AIzaSyBwHmTjg_rT-bU0NL1c71f5qkonf7H7eNM",
        authDomain: "furryfriendsanimalclinic-13da3.firebaseapp.com",
        projectId: "furryfriendsanimalclinic-13da3",
        storageBucket: "furryfriendsanimalclinic-13da3.firebasestorage.app",
        messagingSenderId: "214577366989",
        appId: "1:214577366989:web:fa96249b2a0321f3f52684",
        measurementId: "G-RZEKK3R3B2"
    };
    
    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    document.addEventListener("DOMContentLoaded", () => {
        const currentOwnerId = localStorage.getItem("ownerId") || localStorage.getItem("userUID");
        const currentOwnerName = localStorage.getItem("fullName") || localStorage.getItem("userName") || "Junexenne Agravante";
        const notifContainer = document.getElementById("fullNotifContainer");
        const filterBtns = document.querySelectorAll(".filter-btn");
        const markAllBtn = document.getElementById("pageMarkAllBtn");
        
        let allNotifications = [];
        let currentFilter = "all";

        if (!currentOwnerId) {
            notifContainer.innerHTML = `
                <div class="empty-notifications-card">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <h3>User Session Not Found</h3>
                    <p>Please log in to view your notifications.</p>
                </div>
            `;
            return;
        }

        // Render Function
        function renderNotifications() {
            let filtered = allNotifications;
            if (currentFilter !== "all") {
                filtered = allNotifications.filter(n => (n.category || "").toLowerCase() === currentFilter.toLowerCase());
            }

            filtered.sort((a, b) => {
                const timeA = a.createdAt?.toDate ? a.createdAt.toDate() : new Date(a.createdAt || 0);
                const timeB = b.createdAt?.toDate ? b.createdAt.toDate() : new Date(b.createdAt || 0);
                return timeB - timeA;
            });

            if (filtered.length === 0) {
                notifContainer.innerHTML = `
                    <div class="empty-notifications-card">
                        <i class="fa-regular fa-bell-slash"></i>
                        <h3>No Notifications Found</h3>
                        <p>There are no alerts matching your selected filter.</p>
                    </div>
                `;
                return;
            }

            let html = "";
            filtered.forEach(item => {
                const isUnread = !item.read ? "unread" : "";
                const category = (item.category || "").toLowerCase();
                const titleStr = (item.title || "").toLowerCase();
                const msgStr = (item.message || "").toLowerCase();

                let iconContent = `<i class="fa-solid fa-bell"></i>`;
                let iconClass = "full-notif-icon";
                let itemClass = "";

                // Gagamitin ang BANTAY.png at blue background (.bantay-bg) para sa lahat ng BANTAY at Vaccination reminder
                if (titleStr.includes("bantay") || titleStr.includes("vaccination") || msgStr.includes("bantay") || category === "health" || category === "vaccination") {
                    iconContent = `<img src="../assets/images/BANTAY.png" alt="BANTAY" onerror="this.outerHTML='<i class=\'fa-solid fa-shield-dog\'></i>'">`;
                    iconClass = "full-notif-icon bantay-bg";
                    itemClass = "approved";
                } else if (category === "appointment") {
                    iconContent = `<i class="fa-solid fa-calendar-check"></i>`;
                    itemClass = "approved";
                } else if (category === "clinic") {
                    iconContent = `<i class="fa-solid fa-bullhorn"></i>`;
                }

                let timeFormatted = "Just now";
                if (item.createdAt) {
                    const date = item.createdAt.toDate ? item.createdAt.toDate() : new Date(item.createdAt);
                    if (!isNaN(date.getTime())) {
                        timeFormatted = date.toLocaleString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                    }
                }

                html += `
                    <a href="${item.link || '#'}" class="full-notif-card ${isUnread} ${itemClass}" data-id="${item.id}" data-type="${item.sourceType}">
                        <div class="${iconClass}">
                            ${iconContent}
                        </div>
                        <div class="full-notif-content">
                            <h4 class="full-notif-title">${item.title || "Clinic Notification"}</h4>
                            <p class="full-notif-msg">${item.message || ""}</p>
                            <span class="full-notif-time"><i class="fa-regular fa-clock"></i> ${timeFormatted}</span>
                        </div>
                        ${!item.read ? '<div class="unread-dot"></div>' : ''}
                    </a>
                `;
            });

            notifContainer.innerHTML = html;

            document.querySelectorAll(".full-notif-card").forEach(card => {
                card.addEventListener("click", async () => {
                    const notifId = card.getAttribute("data-id");
                    const sourceType = card.getAttribute("data-type");
                    if (notifId && sourceType === "notification") {
                        try {
                            await updateDoc(doc(db, "notifications", notifId), { read: true });
                        } catch (e) {
                            console.error("Error reading notification:", e);
                        }
                    }
                });
            });
        }

        // Filter Handlers
        filterBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                filterBtns.forEach(b => b.classList.remove("active"));
                btn.classList.add("active");
                currentFilter = btn.getAttribute("data-filter");
                renderNotifications();
            });
        });

        // 1. Fetch General Notifications Collection
        const notifQuery = query(collection(db, "notifications"), where("ownerId", "==", currentOwnerId));
        onSnapshot(notifQuery, (snap) => {
            allNotifications = allNotifications.filter(n => n.sourceType !== "notification");
            snap.forEach(docSnap => {
                const data = docSnap.data();
                const titleStr = (data.title || "").toLowerCase();
                let cat = data.category || "clinic";
                if (titleStr.includes("bantay") || titleStr.includes("vaccination")) cat = "health";

                allNotifications.push({ 
                    id: docSnap.id, 
                    sourceType: "notification", 
                    category: cat,
                    ...data 
                });
            });
            renderNotifications();
        });

        // 2. Fetch Appointments (`appointments_v2`)
        const apptQuery = query(collection(db, "appointments_v2"), where("ownerId", "==", currentOwnerId));
        onSnapshot(apptQuery, (snap) => {
            allNotifications = allNotifications.filter(n => n.sourceType !== "appointment");
            snap.forEach(docSnap => {
                const data = docSnap.data();
                const status = (data.status || "").toLowerCase();
                if (["confirmed", "approved", "in progress", "completed", "rejected", "declined", "cancelled"].includes(status)) {
                    allNotifications.push({
                        id: "appt_" + docSnap.id,
                        sourceType: "appointment",
                        category: "appointment",
                        title: `Appointment ${status.charAt(0).toUpperCase() + status.slice(1)}`,
                        message: `Your appointment for ${data.service || 'checkup'} (Pet: ${data.petName || 'N/A'}) is ${status}.`,
                        read: false,
                        createdAt: data.createdAt || new Date(),
                        link: 'appointment.php'
                    });
                }
            });
            renderNotifications();
        });

        // 3. Fetch Health Monitoring (`health_monitoring`) - BANTAY
        const healthQuery = query(collection(db, "health_monitoring"), where("ownerId", "==", currentOwnerId));
        onSnapshot(healthQuery, (snap) => {
            allNotifications = allNotifications.filter(n => n.sourceType !== "health_monitoring");
            snap.forEach(docSnap => {
                const data = docSnap.data();
                const petName = data.petName || "Your Pet";
                const status = data.status || "Updated";
                allNotifications.push({
                    id: "health_" + docSnap.id,
                    sourceType: "health_monitoring",
                    category: "health",
                    title: `BANTAY • Health Status Updated`,
                    message: `BANTAY: ${petName} is now marked as ${status} by the veterinary team. Open Health Monitoring to review.`,
                    read: false,
                    createdAt: data.admittedAt || data.createdAt || new Date(),
                    link: 'health.php'
                });
            });
            renderNotifications();
        });

        // 4. Fetch Vaccination Records (`vaccination_records`) - Makatotohanang Due Date base sa itinurok na vaccine
        const fetchVaccinationRecords = async () => {
            try {
                const vacSnapByName = await getDocs(query(collection(db, "vaccination_records"), where("ownerName", "==", currentOwnerName)));
                const vacSnapById = await getDocs(query(collection(db, "vaccination_records"), where("ownerId", "==", currentOwnerId)));

                allNotifications = allNotifications.filter(n => n.sourceType !== "vaccination_records");

                const processVacDoc = (docSnap) => {
                    const data = docSnap.data();
                    const petName = data.petName || "Your Pet";
                    const petId = data.petId || "N/A";
                    const vaccineName = data.vaccineName || data.vaccine || "5-in-1 Vaccine";
                    
                    // Kalkulahin o kunin ang makatotohanang due date (karaniwan ay 1 taon pagkatapos ng dateAdministered)
                    let administeredDateObj = new Date();
                    if (data.dateAdministered) {
                        administeredDateObj = new Date(data.dateAdministered);
                    } else if (data.createdAt) {
                        administeredDateObj = data.createdAt.toDate ? data.createdAt.toDate() : new Date(data.createdAt);
                    }

                    // Magdagdag ng 1 taon para sa susunod na due date ng bakuna (makatotohanang schedule)
                    let dueDateObj = new Date(administeredDateObj);
                    dueDateObj.setFullYear(dueDateObj.getFullYear() + 1);
                    const dueDateStr = dueDateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });

                    const exists = allNotifications.some(n => n.id === "vac_" + docSnap.id);
                    if (!exists) {
                        allNotifications.push({
                            id: "vac_" + docSnap.id,
                            sourceType: "vaccination_records",
                            category: "vaccination",
                            title: `BANTAY • Vaccination Reminder`,
                            message: `BANTAY: Vaccine (${vaccineName}) for pet ${petName} (ID: ${petId}) is due on ${dueDateStr}.`,
                            read: false,
                            createdAt: data.createdAt || data.dateAdministered || new Date(),
                            link: 'records.php'
                        });
                    }
                };

                vacSnapByName.forEach(processVacDoc);
                vacSnapById.forEach(processVacDoc);
                renderNotifications();
            } catch (err) {
                console.error("Vaccination Fetch Error:", err);
            }
        };
        fetchVaccinationRecords();

        // 5. Fetch Announcements / Clinic
        const announceQuery = query(collection(db, "announcements"));
        onSnapshot(announceQuery, (snap) => {
            allNotifications = allNotifications.filter(n => n.sourceType !== "announcement");
            snap.forEach(docSnap => {
                const data = docSnap.data();
                allNotifications.push({
                    id: "ann_" + docSnap.id,
                    sourceType: "announcement",
                    category: "clinic",
                    title: data.title || "Clinic Announcement",
                    message: data.content || data.message || "",
                    read: false,
                    createdAt: data.createdAt || new Date(),
                    link: 'dashboard.php'
                });
            });
            renderNotifications();
        });

        // Mark All as Read
        if (markAllBtn) {
            markAllBtn.addEventListener("click", async () => {
                try {
                    const unreadQuery = query(collection(db, "notifications"), where("ownerId", "==", currentOwnerId), where("read", "==", false));
                    const unreadSnap = await getDocs(unreadQuery);
                    unreadSnap.forEach(async (docSnap) => {
                        await updateDoc(doc(db, "notifications", docSnap.id), { read: true });
                    });
                    allNotifications.forEach(n => n.read = true);
                    renderNotifications();
                } catch (e) {
                    console.error("Error marking all read:", e);
                }
            });
        }
    });
</script>
</body>
</html>