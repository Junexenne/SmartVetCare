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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Records & History | Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f7fe;
        }
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
        .main-content {
            padding: 16px !important;
            box-sizing: border-box !important;
            background: #f4f7fe;
            min-height: 100vh;
        }
        .health-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        /* Puti at bilugang Card gaya ng sa health.php */
        .profile-style-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #edf2f7;
            box-sizing: border-box;
        }
        .page-header-custom h1 {
            font-size: 22px;
            color: #1e1b4b;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 6px 0;
        }
        .page-header-custom p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
            line-height: 1.4;
        }
        .tabs-container { 
            display: flex; 
            gap: 8px; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #e2e8f0; 
            padding-bottom: 12px; 
            flex-wrap: wrap;
        }
        .pet-tabs-container {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
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
        .pet-tab-btn {
            padding: 6px 14px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .pet-tab-btn:hover {
            background: #e2e8f0;
            color: #1e1b4b;
        }
        .pet-tab-btn.active {
            background: #1e1b4b;
            color: #fff;
            border-color: #1e1b4b;
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
        @media (max-width: 768px) {
            .main-content {
                padding: 12px !important;
            }
            .profile-style-card {
                padding: 18px;
                border-radius: 18px;
            }
            .page-header-custom h1 {
                font-size: 18px !important;
            }
            .tabs-container, .pet-tabs-container {
                gap: 6px;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 8px;
                scrollbar-width: none;
            }
            .tabs-container::-webkit-scrollbar, .pet-tabs-container::-webkit-scrollbar {
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
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1001;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active { left: 0 !important; }
            .sidebar-overlay.active { display: block !important; }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("../includes/topbar.php"); ?>
        
        <div class="health-container">
            <!-- Header Card -->
            <div class="profile-style-card page-header-custom">
                <h1>
                    <i class="fa-solid fa-folder-open" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                    Records & History
                </h1>
                <p>Manage clinical records, vaccination history, medical findings, archived appointments, and pet files.</p>
            </div>
            <!-- Content Card -->
            <div class="profile-style-card">
                <!-- Tabs Navigation -->
                <div class="tabs-container">
                    <button class="tab-btn active" onclick="switchTab('clinical', event)">
                        <i class="fa-solid fa-notes-medical"></i> Clinical Records
                    </button>
                    <button class="tab-btn" onclick="switchTab('archived', event)">
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
                <!-- Tab 0: Clinical Records -->
                <div id="clinicalTab" class="tab-content active">
                    <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.5px;">History</div>
                    <h3 style="font-size: 20px; color: #1e1b4b; font-weight: 700; margin-top: 0; margin-bottom: 15px;">Clinical Records</h3>
                    
                    <!-- Pet Selector Pills -->
                    <div id="clinicalPetTabs" class="pet-tabs-container" style="margin-bottom: 16px;">
                        <!-- Dynamic pet pills galing sa Firestore -->
                    </div>
                    <!-- Sub-tabs: Vaccinations vs Medical History -->
                    <div style="display: flex; gap: 12px; margin-bottom: 20px;">
                        <button id="subTabVaccination" onclick="switchClinicalSubTab('vaccination')" style="flex: 1; padding: 14px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; cursor: pointer; text-align: left; transition: all 0.2s;">
                            <div style="font-size: 14px; font-weight: 700; color: #1e1b4b;">Vaccinations</div>
                            <div id="vaccinationCountBadge" style="font-size: 12px; color: #64748b; margin-top: 2px;">0 records</div>
                        </button>
                        <button id="subTabMedical" onclick="switchClinicalSubTab('medical')" style="flex: 1; padding: 14px; background: #1e1b4b; border: 1px solid #1e1b4b; border-radius: 12px; cursor: pointer; text-align: left; transition: all 0.2s;">
                            <div style="font-size: 14px; font-weight: 700; color: #ffffff;">Medical History</div>
                            <div id="medicalCountBadge" style="font-size: 12px; color: #94a3b8; margin-top: 2px;">0 records</div>
                        </button>
                    </div>
                    <!-- Section Header & Filter / Search Bar -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #1e1b4b;">
                            <i class="fa-solid fa-file-medical" style="color: #4f46e5;"></i> Medical History & Clinical Findings <i class="fa-solid fa-lock" style="font-size: 12px; color: #94a3b8;"></i>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
                        <div style="position: relative; flex: 1; min-width: 240px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px;"></i>
                            <input type="text" id="clinicalSearchInput" placeholder="Search procedures, notes, doctor..." style="width: 100%; padding: 11px 14px 11px 40px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 13px; outline: none; background: #fff; font-family: 'Poppins', sans-serif;" oninput="filterClinicalRecords()">
                        </div>
                        <select id="clinicalYearFilter" style="padding: 11px 14px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 13px; outline: none; background: #fff; color: #1e1b4b; font-weight: 600; font-family: 'Poppins', sans-serif;" onchange="filterClinicalRecords()">
                            <option value="all">All Years</option>
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-size: 12px; color: #64748b;">
                        <span id="clinicalRecordsCountText">0 record(s) found</span>
                    </div>
                    <!-- Clinical Records List Container -->
                    <div id="clinicalRecordsContainer" style="display: flex; flex-direction: column; gap: 15px;">
                        <p style="color: #64748b; font-size: 13px; text-align: center; padding: 20px;">Loading clinical records...</p>
                    </div>
                </div>
                <!-- Tab 1: Archived Appointments -->
                <div id="archivedTab" class="tab-content">
                    <div id="petSubTabsArchived" class="pet-tabs-container">
                        <button class="pet-tab-btn active" onclick="filterByPet('archived', 'All', event)">
                            <i class="fa-solid fa-layer-group"></i> All
                        </button>
                    </div>
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
                    <div id="petSubTabsCancelled" class="pet-tabs-container">
                        <button class="pet-tab-btn active" onclick="filterByPet('cancelled', 'All', event)">
                            <i class="fa-solid fa-layer-group"></i> All
                        </button>
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
                    <div id="petSubTabsHistory" class="pet-tabs-container">
                        <button class="pet-tab-btn active" onclick="filterByPet('history', 'All', event)">
                            <i class="fa-solid fa-layer-group"></i> All
                        </button>
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
                    <div id="petSubTabsTrash" class="pet-tabs-container">
                        <button class="pet-tab-btn active" onclick="filterByPet('trash', 'All', event)">
                            <i class="fa-solid fa-layer-group"></i> All
                        </button>
                    </div>
                    <div id="trashGrid" class="cards-grid">
                        <p style="color: #64748b; font-size: 14px;">Loading trash items...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modern Floating Toast Container -->
<div id="toastNotification">
    <i id="toastIcon" class="fa-solid"></i>
    <span id="toastMessage"></span>
</div>
<?php include("../includes/bottom-nav.php"); ?>
<!-- Mobile Sidebar Toggle Handler (Global DOM) -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sidebar = document.querySelector(".dashboard aside") || document.querySelector(".sidebar") || document.querySelector(".main-sidebar");
        const overlay = document.getElementById("sidebarOverlay");
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
    
    let allArchivedAppointments = [];
    let allCancelledAppointments = [];
    let allHistoryAppointments = [];
    let allTrashAppointments = [];
    let ownerPetsList = [];
    let selectedClinicalPet = null;
    let selectedClinicalSubTab = 'medical'; // 'vaccination' o 'medical'
    let rawClinicalRecords = [];
    let rawVaccinationRecords = [];
    let activePetFilters = {
        archived: "All",
        cancelled: "All",
        history: "All",
        trash: "All"
    };
    window.switchTab = function(tabName, event) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        if (tabName === 'clinical') {
            document.getElementById('clinicalTab').classList.add('active');
        } else if (tabName === 'archived') {
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
    window.switchClinicalSubTab = function(subTab) {
        selectedClinicalSubTab = subTab;
        const btnVac = document.getElementById('subTabVaccination');
        const btnMed = document.getElementById('subTabMedical');
        if (subTab === 'vaccination') {
            btnVac.style.background = '#1e1b4b';
            btnVac.style.borderColor = '#1e1b4b';
            btnVac.querySelector('div:nth-child(1)').style.color = '#ffffff';
            btnVac.querySelector('div:nth-child(2)').style.color = '#94a3b8';
            btnMed.style.background = '#ffffff';
            btnMed.style.borderColor = '#e2e8f0';
            btnMed.querySelector('div:nth-child(1)').style.color = '#1e1b4b';
            btnMed.querySelector('div:nth-child(2)').style.color = '#64748b';
        } else {
            btnMed.style.background = '#1e1b4b';
            btnMed.style.borderColor = '#1e1b4b';
            btnMed.querySelector('div:nth-child(1)').style.color = '#ffffff';
            btnMed.querySelector('div:nth-child(2)').style.color = '#94a3b8';
            btnVac.style.background = '#ffffff';
            btnVac.style.borderColor = '#e2e8f0';
            btnVac.querySelector('div:nth-child(1)').style.color = '#1e1b4b';
            btnVac.querySelector('div:nth-child(2)').style.color = '#64748b';
        }
        filterClinicalRecords();
    };
    window.selectClinicalPet = function(petName, event) {
        selectedClinicalPet = petName;
        document.querySelectorAll('#clinicalPetTabs .pet-tab-btn').forEach(btn => {
            btn.style.background = '#f1f5f9';
            btn.style.color = '#475569';
            btn.style.borderColor = '#e2e8f0';
        });
        if (event && event.currentTarget) {
            event.currentTarget.style.background = '#1e1b4b';
            event.currentTarget.style.color = '#fff';
            event.currentTarget.style.borderColor = '#1e1b4b';
        }
        const foundPet = ownerPetsList.find(p => (p.petName || "").toLowerCase() === petName.toLowerCase());
        const petId = foundPet ? foundPet.petId : null;
        fetchClinicalRecordsForPet(petId, petName);
    };
    window.filterByPet = function(tabType, petName, event) {
        activePetFilters[tabType] = petName;
        const containerId = tabType === 'archived' ? 'petSubTabsArchived' :
                            tabType === 'cancelled' ? 'petSubTabsCancelled' :
                            tabType === 'history' ? 'petSubTabsHistory' : 'petSubTabsTrash';
        const container = document.getElementById(containerId);
        if (container) {
            container.querySelectorAll('.pet-tab-btn').forEach(btn => btn.classList.remove('active'));
        }
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }
        if (tabType === 'archived') renderArchivedAppointments();
        else if (tabType === 'cancelled') renderCancelledAppointments();
        else if (tabType === 'history') renderHistoryAppointments();
        else if (tabType === 'trash') renderTrashAppointments();
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
                loadOwnerPets(currentOwnerId);
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
    async function loadOwnerPets(ownerId) {
        const containers = ['petSubTabsArchived', 'petSubTabsCancelled', 'petSubTabsHistory', 'petSubTabsTrash'];
        try {
            const q = query(collection(db, "pets"), where("ownerId", "==", ownerId));
            const snapshot = await getDocs(q);
            
            ownerPetsList = [];
            snapshot.forEach(docSnap => {
                ownerPetsList.push({ id: docSnap.id, ...docSnap.data() });
            });
            // Render Clinical Pet Selector Pills
            const clinicalPetTabsContainer = document.getElementById('clinicalPetTabs');
            if (clinicalPetTabsContainer) {
                let pillsHtml = '';
                ownerPetsList.forEach((pet, index) => {
                    const petName = pet.petName || "Pet";
                    if (index === 0 && !selectedClinicalPet) {
                        selectedClinicalPet = petName;
                    }
                    const isSelected = selectedClinicalPet === petName;
                    const bgStyle = isSelected ? 'background: #1e1b4b; color: #fff; border-color: #1e1b4b;' : 'background: #f1f5f9; color: #475569; border-color: #e2e8f0;';
                    pillsHtml += `<button class="pet-tab-btn" style="padding: 8px 16px; border-radius: 20px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; ${bgStyle}" onclick="selectClinicalPet('${petName}', event)">
                        <i class="fa-solid fa-paw"></i> ${petName}
                    </button>`;
                });
                clinicalPetTabsContainer.innerHTML = pillsHtml;
                if (selectedClinicalPet) {
                    const found = ownerPetsList.find(p => (p.petName || "").toLowerCase() === selectedClinicalPet.toLowerCase());
                    fetchClinicalRecordsForPet(found ? found.petId : null, selectedClinicalPet);
                }
            }
            
            containers.forEach(contId => {
                const subTabContainer = document.getElementById(contId);
                if (!subTabContainer) return;
                
                const tabType = contId.replace('petSubTabs', '').toLowerCase();
                const currentFilter = activePetFilters[tabType];
                let tabsHTML = `<button class="pet-tab-btn ${currentFilter === 'All' ? 'active' : ''}" onclick="filterByPet('${tabType}', 'All', event)">
                    <i class="fa-solid fa-layer-group"></i> All
                </button>`;
                ownerPetsList.forEach(pet => {
                    const petName = pet.petName || "Pet";
                    const isActive = currentFilter === petName ? 'active' : '';
                    tabsHTML += `<button class="pet-tab-btn ${isActive}" onclick="filterByPet('${tabType}', '${petName}', event)">
                        <i class="fa-solid fa-paw"></i> ${petName}
                    </button>`;
                });
                subTabContainer.innerHTML = tabsHTML;
            });
        } catch (error) {
            console.error("Error loading owner pets for sub-tabs:", error);
        }
    }
    async function fetchClinicalRecordsForPet(petId, petName) {
        const container = document.getElementById('clinicalRecordsContainer');
        container.innerHTML = `<p style="color: #64748b; font-size: 13px; text-align: center; padding: 20px;">Loading clinical records for ${petName}...</p>`;
        try {
            rawClinicalRecords = [];
            rawVaccinationRecords = [];

            if (petId) {
                // Fetch from clinical_records collection (Medical history / findings)
                const clinicalQuery = query(collection(db, "clinical_records"), where("petId", "==", petId));
                const clinicalSnap = await getDocs(clinicalQuery);
                clinicalSnap.forEach(docSnap => {
                    rawClinicalRecords.push({ id: docSnap.id, ...docSnap.data() });
                });

                // Fetch from vaccination_records collection
                const vacQuery = query(collection(db, "vaccination_records"), where("petId", "==", petId));
                const vacSnap = await getDocs(vacQuery);
                vacSnap.forEach(docSnap => {
                    rawVaccinationRecords.push({ id: docSnap.id, ...docSnap.data() });
                });
            }

            document.getElementById('vaccinationCountBadge').textContent = `${rawVaccinationRecords.length} record(s)`;
            document.getElementById('medicalCountBadge').textContent = `${rawClinicalRecords.length} record(s)`;
            filterClinicalRecords();
        } catch (error) {
            console.error("Error fetching clinical records:", error);
            container.innerHTML = `<p style="color: #dc2626; font-size: 13px; text-align: center; padding: 20px;">Failed to load clinical records.</p>`;
        }
    }
    window.filterClinicalRecords = function() {
        const searchVal = (document.getElementById('clinicalSearchInput').value || "").toLowerCase();
        const yearVal = document.getElementById('clinicalYearFilter').value;
        const container = document.getElementById('clinicalRecordsContainer');
        
        let dataSource = selectedClinicalSubTab === 'vaccination' ? rawVaccinationRecords : rawClinicalRecords;

        const filtered = dataSource.filter(item => {
            const dateStr = item.createdAt || item.date || item.administeredDate || "";
            if (yearVal !== 'all' && dateStr && !dateStr.includes(yearVal)) {
                return false;
            }
            if (searchVal) {
                const combined = `${item.serviceName || item.service || ''} ${item.doctorId || item.doctor || ''} ${item.notes || ''} ${dateStr}`.toLowerCase();
                if (!combined.includes(searchVal)) return false;
            }
            return true;
        });

        document.getElementById('clinicalRecordsCountText').textContent = `${filtered.length} record(s) found`;
        if (filtered.length === 0) {
            container.innerHTML = `<p style="color: #64748b; font-size: 13px; text-align: center; padding: 30px;">No clinical records found matching your filter.</p>`;
            return;
        }
        let html = '';
        filtered.forEach(record => {
            const title = record.serviceName || record.service || (selectedClinicalSubTab === 'vaccination' ? 'Vaccination Record' : 'General Consultation');
            const dateVal = record.createdAt || record.date || record.administeredDate || 'N/A';
            const statusVal = record.status || 'Completed';
            const docVal = record.doctorId || record.doctor || 'Dr. Assigned';
            const notesVal = record.notes || 'No clinical findings recorded by the clinic.';

            html += `
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <div style="background: #eef2ff; color: #4f46e5; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fa-solid fa-file-medical"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #1e1b4b;">${title}</h4>
                                <span style="font-size: 12px; color: #64748b;">${dateVal} &bull; <strong style="color: #16a34a;">${statusVal}</strong></span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-up" style="color: #94a3b8; font-size: 12px;"></i>
                    </div>
                    <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 12px 0;">
                    <div style="font-size: 13px; color: #334155; display: flex; flex-direction: column; gap: 6px;">
                        <div><strong>Specific Procedure:</strong> ${title}</div>
                        <div><strong>Attending Veterinarian:</strong> ${docVal}</div>
                        <div style="margin-top: 6px; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #f1f5f9;">
                            <strong style="font-size: 12px; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 2px;">Clinical Findings & Results</strong>
                            <span style="color: #475569; font-size: 12.5px;">${notesVal}</span>
                        </div>
                    </div>
                    <div style="margin-top: 12px; font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-lock" style="font-size: 10px;"></i> Read-only clinic record
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    };
    async function loadSettingsData(ownerId) {
        try {
            const q = query(collection(db, "appointments"), where("ownerId", "==", ownerId));
            const snapshot = await getDocs(q);
            
            allArchivedAppointments = [];
            allCancelledAppointments = [];
            allHistoryAppointments = [];
            allTrashAppointments = [];
            
            for (const docSnap of snapshot.docs) {
                const appt = { id: docSnap.id, ...docSnap.data() };
                const st = (appt.status || "").toLowerCase();
                if (st === "archived") {
                    allArchivedAppointments.push(appt);
                } else if (st === "cancelled") {
                    allCancelledAppointments.push(appt);
                } else if (st === "completed") {
                    allHistoryAppointments.push(appt);
                } else if (st === "trash" || st === "recently deleted") {
                    const daysLeft = calculateDaysRemaining(appt.deletedAt);
                    if (daysLeft <= 0) {
                        await deleteDoc(doc(db, "appointments", appt.id));
                        continue;
                    }
                    appt.daysLeft = daysLeft;
                    allTrashAppointments.push(appt);
                }
            }
            renderArchivedAppointments();
            renderCancelledAppointments();
            renderHistoryAppointments();
            renderTrashAppointments();
        } catch (error) {
            console.error("Error loading settings data:", error);
            showToast("Failed to retrieve records.", "error");
        }
    }
    function renderArchivedAppointments() {
        const grid = document.getElementById("archivedGrid");
        grid.innerHTML = "";
        const filter = activePetFilters.archived;
        const filtered = filter === "All" ? allArchivedAppointments : allArchivedAppointments.filter(a => (a.petName || "").toLowerCase() === filter.toLowerCase());
        if (filtered.length === 0) {
            grid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No archived appointments found.</p>`;
            return;
        }
        filtered.forEach(appt => grid.insertAdjacentHTML("beforeend", renderCard(appt, "archived")));
        attachEventListeners();
    }
    function renderCancelledAppointments() {
        const grid = document.getElementById("cancelledGrid");
        grid.innerHTML = "";
        const filter = activePetFilters.cancelled;
        const filtered = filter === "All" ? allCancelledAppointments : allCancelledAppointments.filter(a => (a.petName || "").toLowerCase() === filter.toLowerCase());
        if (filtered.length === 0) {
            grid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No cancelled appointments found.</p>`;
            return;
        }
        filtered.forEach(appt => grid.insertAdjacentHTML("beforeend", renderCard(appt, "cancelled")));
        attachEventListeners();
    }
    function renderHistoryAppointments() {
        const grid = document.getElementById("historyGrid");
        grid.innerHTML = "";
        const filter = activePetFilters.history;
        const filtered = filter === "All" ? allHistoryAppointments : allHistoryAppointments.filter(a => (a.petName || "").toLowerCase() === filter.toLowerCase());
        if (filtered.length === 0) {
            grid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No completed transaction history found.</p>`;
            return;
        }
        filtered.forEach(appt => grid.insertAdjacentHTML("beforeend", renderCard(appt, "history")));
        attachEventListeners();
    }
    function renderTrashAppointments() {
        const grid = document.getElementById("trashGrid");
        grid.innerHTML = "";
        const filter = activePetFilters.trash;
        const filtered = filter === "All" ? allTrashAppointments : allTrashAppointments.filter(a => (a.petName || "").toLowerCase() === filter.toLowerCase());
        if (filtered.length === 0) {
            grid.innerHTML = `<p style="color: #64748b; font-size: 13px; grid-column: 1/-1; padding: 20px 0;">No items in the recently deleted trash bin.</p>`;
            return;
        }
        filtered.forEach(appt => grid.insertAdjacentHTML("beforeend", renderCard(appt, "trash", appt.daysLeft)));
        attachEventListeners();
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