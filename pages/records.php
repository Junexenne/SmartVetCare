<?php
// /SmartVetCare/pages/records.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records & History | Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <style>
        .settings-wrapper {
            width: 100%;
            box-sizing: border-box;
        }

        .page-header-custom {
            margin-bottom: 24px;
        }
        .page-header-custom h1 {
            font-size: 24px;
            color: #1e1b4b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }
        .page-header-custom p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }

        .tabs-container { 
            display: flex; 
            gap: 8px; 
            margin-bottom: 25px; 
            border-bottom: 2px solid #e2e8f0; 
            padding-bottom: 12px; 
            flex-wrap: wrap;
        }

        .tab-btn { 
            padding: 8px 12px; 
            background: #f1f5f9; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            font-size: 12px; 
            color: #64748b; 
            cursor: pointer; 
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .tab-btn:hover {
            background: #e2e8f0;
            color: #1e1b4b;
        }

        .tab-btn.active { 
            background: #4f46e5; 
            color: #fff; 
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cards-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 20px; 
        }

        #toastNotification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.35);
            pointer-events: none;
        }

        #toastNotification.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .toast-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .toast-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 10px !important;
                box-sizing: border-box;
                overflow-x: hidden;
                width: 100%;
            }
            .settings-wrapper {
                padding: 0 5px;
                box-sizing: border-box;
                max-width: 100%;
            }
            .page-header-custom h1 {
                font-size: 20px;
            }
            .tabs-container {
                gap: 6px;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 8px;
                scrollbar-width: none;
            }
            .tabs-container::-webkit-scrollbar {
                display: none;
            }
            .cards-grid {
                grid-template-columns: 1fr;
            }
            #toastNotification {
                left: 15px;
                right: 15px;
                bottom: 20px;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard">

    <?php include("../includes/sidebar.php"); ?>

    <div class="main-content" style="background: #f4f7fe; min-height: 100vh; padding: 20px;">

        <?php include("../includes/topbar.php"); ?>

        <section class="dashboard-content">
            <div class="settings-wrapper">

                <!-- Page Header -->
                <div class="page-header-custom">
                    <h1>
                        <i class="fa-solid fa-folder-open" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                        Records & History
                    </h1>
                    <p>Manage archived appointments, archived pet profiles, cancelled bookings, completed transaction histories, and trash items.</p>
                </div>

                <!-- Tabs Navigation -->
                <div class="tabs-container">
                    <button class="tab-btn active" onclick="switchTab('archived', event)">
                        <i class="fa-solid fa-box-archive"></i> Archived Appointments
                    </button>
                    <button class="tab-btn" onclick="switchTab('pets', event)">
                        <i class="fa-solid fa-paw"></i> Archived Pets
                    </button>
                    <button class="tab-btn" onclick="switchTab('cancelled', event)">
                        <i class="fa-solid fa-ban"></i> Cancelled
                    </button>
                    <button class="tab-btn" onclick="switchTab('history', event)">
                        <i class="fa-solid fa-clock-rotate-left"></i> Completed History
                    </button>
                    <button class="tab-btn" onclick="switchTab('trash', event)">
                        <i class="fa-solid fa-trash-can"></i> Trash
                    </button>
                </div>

                <!-- Tab 1: Archived Appointments -->
                <div id="archivedTab" class="tab-content active">
                    <div id="archivedGrid" class="cards-grid">
                        <p style="color: #64748b; font-size: 14px;">Loading archived items...</p>
                    </div>
                </div>

                <!-- Tab 2: Archived Pets -->
                <div id="petsTab" class="tab-content">
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 13px; color: #4338ca; background: #eef2ff; padding: 12px 16px; border-radius: 8px; border: 1px solid #c7d2fe; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-circle-info" style="font-size: 16px;"></i> 
                            Here are your archived or deceased pets. You can restore them back to your active pet records anytime.
                        </p>
                    </div>
                    <div id="petsGrid" class="cards-grid">
                        <p style="color: #64748b; font-size: 14px;">Loading archived pets...</p>
                    </div>
                </div>

                <!-- Tab 3: Cancelled Appointments -->
                <div id="cancelledTab" class="tab-content">
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 13px; color: #b45309; background: #fffbeb; padding: 12px 16px; border-radius: 8px; border: 1px solid #fde68a; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-circle-info" style="font-size: 16px;"></i> 
                            Here are your cancelled appointments. You can restore them back to active status if you wish to reschedule.
                        </p>
                    </div>
                    <div id="cancelledGrid" class="cards-grid">
                        <p style="color: #64748b; font-size: 14px;">Loading cancelled items...</p>
                    </div>
                </div>

                <!-- Tab 4: Completed History Transactions -->
                <div id="historyTab" class="tab-content">
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 13px; color: #065f46; background: #ecfdf5; padding: 12px 16px; border-radius: 8px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-circle-check" style="font-size: 16px;"></i> 
                            Here is the history of your completed appointments and successful vet care transactions.
                        </p>
                    </div>
                    <div id="historyGrid" class="cards-grid">
                        <p style="color: #64748b; font-size: 14px;">Loading completed history...</p>
                    </div>
                </div>

                <!-- Tab 5: Recently Deleted / Trash -->
                <div id="trashTab" class="tab-content">
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 13px; color: #991b1b; background: #fef2f2; padding: 12px 16px; border-radius: 8px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 16px;"></i> 
                            Items in the Trash will be permanently deleted automatically after <strong>100 days</strong>. You can restore them anytime before expiration.
                        </p>
                    </div>
                    <div id="trashGrid" class="cards-grid">
                        <p style="color: #64748b; font-size: 14px;">Loading trash items...</p>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<!-- Modern Floating Toast Container -->
<div id="toastNotification">
    <i id="toastIcon" class="fa-solid"></i>
    <span id="toastMessage"></span>
</div>

<!-- Mobile Sidebar Toggle Handler (Global DOM) -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sidebar = document.querySelector(".dashboard aside") || document.querySelector(".sidebar") || document.querySelector(".main-sidebar");
        const overlay = document.getElementById("sidebarOverlay");

        // Hinahanap ang burger/menu button sa topbar o header
        document.body.addEventListener("click", (e) => {
            const menuTrigger = e.target.closest("#menuBtn, .burger-btn, .menu-toggle, .main-content header .fa-bars")?.closest("button") || 
                                (e.target.classList.contains("fa-bars") && e.target.closest("button"));
            
            if (menuTrigger) {
                if (sidebar) sidebar.classList.toggle("active");
                if (overlay) overlay.style.display = sidebar && sidebar.classList.contains("active") ? "block" : "none";
            }
        });

        if (overlay) {
            overlay.addEventListener("click", () => {
                if (sidebar) sidebar.classList.remove("active");
                overlay.style.display = "none";
            });
        }
    });
</script>

<!-- Script para sa Tab Switching, Firestore Actions, at Toast -->
<script type="module">
    import { auth, db } from "../assets/js/firebase-config.js";
    import {
        collection,
        query,
        where,
        getDocs,
        doc,
        updateDoc,
        deleteDoc,
        serverTimestamp
    } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
    import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

    const toast = document.getElementById("toastNotification");
    const toastMessage = document.getElementById("toastMessage");
    const toastIcon = document.getElementById("toastIcon");
    let currentOwnerId = null;

    window.switchTab = function(tabName, event) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        if (tabName === 'archived') {
            document.getElementById('archivedTab').classList.add('active');
        } else if (tabName === 'pets') {
            document.getElementById('petsTab').classList.add('active');
        } else if (tabName === 'cancelled') {
            document.getElementById('cancelledTab').classList.add('active');
        } else if (tabName === 'history') {
            document.getElementById('historyTab').classList.add('active');
        } else {
            document.getElementById('trashTab').classList.add('active');
        }
        event.currentTarget.classList.add('active');
    };

    onAuthStateChanged(auth, async (user) => {
        if (!user) {
            showToast("Please log in to view records.", "error");
            return;
        }

        try {
            const userQuery = query(collection(db, "users"), where("email", "==", user.email));
            const userSnap = await getDocs(userQuery);
            if (!userSnap.empty) {
                currentOwnerId = userSnap.docs[0].data().ownerId;
                loadSettingsData(currentOwnerId);
                loadArchivedPets(currentOwnerId);
            } else {
                showToast("User profile configuration not found.", "error");
            }
        } catch (error) {
            console.error("Error fetching user data:", error);
            showToast("Failed to load user configuration.", "error");
        }
    });

    async function loadSettingsData(ownerId) {
        const archivedGrid = document.getElementById("archivedGrid");
        const cancelledGrid = document.getElementById("cancelledGrid");
        const historyGrid = document.getElementById("historyGrid");
        const trashGrid = document.getElementById("trashGrid");

        try {
            const q = query(collection(db, "appointments"), where("ownerId", "==", ownerId));
            const snapshot = await getDocs(q);

            archivedGrid.innerHTML = "";
            cancelledGrid.innerHTML = "";
            historyGrid.innerHTML = "";
            trashGrid.innerHTML = "";

            let archiveCount = 0;
            let cancelledCount = 0;
            let historyCount = 0;
            let trashCount = 0;

            for (const docSnap of snapshot.docs) {
                const appt = { id: docSnap.id, ...docSnap.data() };
                const st = (appt.status || "").toLowerCase();

                if (st === "archived") {
                    archiveCount++;
                    archivedGrid.insertAdjacentHTML("beforeend", renderCard(appt, "archived"));
                } else if (st === "cancelled") {
                    cancelledCount++;
                    cancelledGrid.insertAdjacentHTML("beforeend", renderCard(appt, "cancelled"));
                } else if (st === "completed") {
                    historyCount++;
                    historyGrid.insertAdjacentHTML("beforeend", renderCard(appt, "history"));
                } else if (st === "trash" || st === "recently deleted") {
                    const daysLeft = calculateDaysRemaining(appt.deletedAt);
                    
                    if (daysLeft <= 0) {
                        await deleteDoc(doc(db, "appointments", appt.id));
                        continue;
                    }

                    trashCount++;
                    trashGrid.insertAdjacentHTML("beforeend", renderCard(appt, "trash", daysLeft));
                }
            }

            if (archiveCount === 0) {
                archivedGrid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No archived appointments found.</p>`;
            }
            if (cancelledCount === 0) {
                cancelledGrid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No cancelled appointments found.</p>`;
            }
            if (historyCount === 0) {
                historyGrid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No completed transaction history found.</p>`;
            }
            if (trashCount === 0) {
                trashGrid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No items in the recently deleted trash bin.</p>`;
            }

            attachEventListeners();

        } catch (error) {
            console.error("Error loading settings data:", error);
            showToast("Failed to retrieve records.", "error");
        }
    }

    async function loadArchivedPets(ownerId) {
        const petsGrid = document.getElementById("petsGrid");
        try {
            const q = query(collection(db, "pets"), where("ownerId", "==", ownerId));
            const snapshot = await getDocs(q);

            petsGrid.innerHTML = "";
            let count = 0;

            snapshot.forEach(docSnap => {
                const pet = { id: docSnap.id, ...docSnap.data() };
                const status = (pet.status || "").toLowerCase();

                if (status === "archived" || status === "deceased") {
                    count++;
                    petsGrid.insertAdjacentHTML("beforeend", renderPetCard(pet));
                }
            });

            if (count === 0) {
                petsGrid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No archived or deceased pets found.</p>`;
            }

            attachPetEventListeners();

        } catch (error) {
            console.error("Error loading archived pets:", error);
            showToast("Failed to retrieve archived pets.", "error");
        }
    }

    function calculateDaysRemaining(deletedAtTimestamp) {
        if (!deletedAtTimestamp) return 100;
        const deletedDate = deletedAtTimestamp.toDate();
        const currentDate = new Date();
        const diffTime = currentDate - deletedDate;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        const remaining = 100 - diffDays;
        return remaining > 0 ? remaining : 0;
    }

    function renderCard(appt, type, daysLeft = 0) {
        const isTrash = type === "trash";
        const isCancelled = type === "cancelled";
        const isHistory = type === "history";
        
        let bg = "#ffffff";
        let borderColor = "#e2e8f0";
        let badgeBg = "#f1f5f9";
        let badgeColor = "#475569";
        let badgeText = "Archived Record";

        if (isTrash) {
            bg = "#fff1f2";
            borderColor = "#fecaca";
            badgeBg = "#ffe4e6";
            badgeColor = "#be123c";
            badgeText = `Auto-deletes in ${daysLeft} days`;
        } else if (isCancelled) {
            bg = "#fffbeb";
            borderColor = "#fde68a";
            badgeBg = "#fef3c7";
            badgeColor = "#b45309";
            badgeText = "Cancelled Appointment";
        } else if (isHistory) {
            bg = "#f0fdf4";
            borderColor = "#a7f3d0";
            badgeBg = "#dcfce7";
            badgeColor = "#16a34a";
            badgeText = "Completed Transaction";
        }

        let actionButtons = "";
        if (isTrash) {
            actionButtons = `
                <button class="restore-btn" data-id="${appt.id}" style="background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; padding: 7px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-rotate-left"></i> Restore
                </button>
                <button class="delete-permanent-btn" data-id="${appt.id}" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 7px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-trash"></i> Delete Permanent
                </button>
            `;
        } else {
            actionButtons = `
                ${!isHistory ? `<button class="restore-btn" data-id="${appt.id}" style="background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; padding: 7px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-rotate-left"></i> Restore</button>` : ''}
                <button class="move-to-trash-btn" data-id="${appt.id}" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 7px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-trash"></i> Delete to Trash
                </button>
            `;
        }

        return `
            <div style="background: ${bg}; border: 1px solid ${borderColor}; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                        <span style="background: ${badgeBg}; color: ${badgeColor}; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">${badgeText}</span>
                        <small style="color: #64748b; font-size: 12px; font-weight: 500;"><i class="fa-solid fa-paw" style="color: #4f46e5;"></i> ${appt.petName || "Pet"}</small>
                    </div>
                    <h4 style="color: #1e1b4b; font-size: 16px; font-weight: 600; margin-bottom: 8px;">${appt.service || "Consultation"}</h4>
                    <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-user-doctor" style="width: 18px; color: #4f46e5;"></i> ${appt.doctor || "N/A"}</p>
                    <p style="color: #475569; font-size: 13px; margin: 4px 0;"><i class="fa-solid fa-calendar-days" style="width: 18px; color: #4f46e5;"></i> ${appt.date || ""} (${appt.timeSlot || ""})</p>
                    ${appt.notes ? `<p style="color: #64748b; font-size: 12px; margin-top: 8px; font-style: italic;">Note: "${appt.notes}"</p>` : ""}
                </div>
                <div style="margin-top: 18px; border-top: 1px solid ${borderColor}; padding-top: 14px; display: flex; justify-content: flex-end; gap: 8px; flex-wrap: wrap;">
                    ${actionButtons}
                </div>
            </div>
        `;
    }

    function renderPetCard(pet) {
        return `
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div style="display: flex; gap: 15px; align-items: center;">
                    <img src="${pet.petImage || '../assets/images/default-pet.png'}" style="width: 65px; height: 65px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                    <div>
                        <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; text-transform: uppercase;">${pet.status || 'Archived'}</span>
                        <h4 style="color: #1e1b4b; font-size: 16px; font-weight: 600; margin: 4px 0 2px 0;">${pet.petName}</h4>
                        <p style="color: #64748b; font-size: 12px; margin: 0;"><strong>ID:</strong> ${pet.petId || 'N/A'} | <strong>Species:</strong> ${pet.species || 'N/A'}</p>
                    </div>
                </div>
                <div style="margin-top: 16px; border-top: 1px solid #e2e8f0; padding-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                    <button class="restore-pet-btn" data-id="${pet.id}" style="background: #eef2ff; color: #4f46e5; border: 1px solid #c7d2fe; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-rotate-left"></i> Restore
                    </button>
                    <button class="delete-pet-btn" data-id="${pet.id}" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        `;
    }

    function attachEventListeners() {
        document.querySelectorAll(".restore-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const id = btn.getAttribute("data-id");
                try {
                    await updateDoc(doc(db, "appointments", id), { status: "Pending", deletedAt: null });
                    showToast("Record successfully restored to active appointments!", "success");
                    loadSettingsData(currentOwnerId);
                } catch (e) {
                    console.error(e);
                    showToast("Failed to restore record.", "error");
                }
            });
        });

        document.querySelectorAll(".move-to-trash-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const id = btn.getAttribute("data-id");
                try {
                    await updateDoc(doc(db, "appointments", id), { 
                        status: "trash", 
                        deletedAt: serverTimestamp() 
                    });
                    showToast("Record moved to Trash bin.", "success");
                    loadSettingsData(currentOwnerId);
                } catch (e) {
                    console.error(e);
                    showToast("Failed to move record to trash.", "error");
                }
            });
        });

        document.querySelectorAll(".delete-permanent-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const id = btn.getAttribute("data-id");
                try {
                    await deleteDoc(doc(db, "appointments", id));
                    showToast("Record permanently deleted.", "success");
                    loadSettingsData(currentOwnerId);
                } catch (e) {
                    console.error(e);
                    showToast("Failed to permanently delete record.", "error");
                }
            });
        });
    }

    function attachPetEventListeners() {
        document.querySelectorAll(".restore-pet-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const id = btn.getAttribute("data-id");
                try {
                    await updateDoc(doc(db, "pets", id), { 
                        status: "Active", 
                        updatedAt: serverTimestamp() 
                    });
                    showToast("Pet successfully restored to active status!", "success");
                    loadArchivedPets(currentOwnerId);
                } catch (e) {
                    console.error(e);
                    showToast("Failed to restore pet.", "error");
                }
            });
        });

        document.querySelectorAll(".delete-pet-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const id = btn.getAttribute("data-id");
                try {
                    await deleteDoc(doc(db, "pets", id));
                    showToast("Pet record permanently deleted.", "success");
                    loadArchivedPets(currentOwnerId);
                } catch (e) {
                    console.error(e);
                    showToast("Failed to delete pet record.", "error");
                }
            });
        });
    }

    function showToast(msg, type) {
        if (!toast) return;
        toastMessage.textContent = msg;
        toast.className = '';
        toast.classList.add(type === "success" ? "toast-success" : "toast-error");
        toastIcon.className = type === "success" ? "fa-solid fa-circle-check" : "fa-solid fa-triangle-exclamation";
        toast.classList.add("show");

        setTimeout(() => {
            toast.classList.remove("show");
        }, 3000);
    }
</script>

</body>
</html>