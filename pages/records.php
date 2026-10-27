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
    <link rel="icon" type="image/png" href="../assets/images/logo-transparent.png">
    
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
        /* Kuago Explainer Modal Styling */
        .kuago-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 16px;
            box-sizing: border-box;
        }
        .kuago-modal-overlay.active {
            display: flex;
        }
        .kuago-modal-content {
            background: #ffffff;
            width: 100%;
            max-width: 550px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            max-height: 85vh;
            overflow: hidden;
            animation: modalPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .kuago-modal-header {
            padding: 20px;
            background: #f8faff;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .kuago-modal-body {
            padding: 20px;
            overflow-y: auto;
            font-size: 13.5px;
            color: #334155;
            line-height: 1.6;
        }
        .kuago-modal-footer {
            padding: 16px 20px;
            background: #f8faff;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            .main-content { padding: 12px !important; }
            .profile-style-card { padding: 18px; border-radius: 18px; }
            .page-header-custom h1 { font-size: 18px !important; }
            .tabs-container, .pet-tabs-container { gap: 6px; overflow-x: auto; flex-wrap: nowrap; padding-bottom: 8px; scrollbar-width: none; }
            .cards-grid { grid-template-columns: 1fr; }
            .sidebar { position: fixed !important; top: 0; left: -270px; height: 100% !important; transition: left 0.3s ease; z-index: 1001; box-shadow: 4px 0 15px rgba(0,0,0,0.1); }
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
                    <div id="clinicalPetTabs" class="pet-tabs-container" style="margin-bottom: 16px;"></div>
                    
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
                    <div id="petSubTabsArchived" class="pet-tabs-container"></div>
                    <div id="archivedGrid" class="cards-grid"></div>
                </div>
                <!-- Tab 2: Archived Pets -->
                <div id="petsTab" class="tab-content">
                    <div id="petsGrid" class="cards-grid"></div>
                </div>
                <!-- Tab 3: Cancelled Appointments -->
                <div id="cancelledTab" class="tab-content">
                    <div id="petSubTabsCancelled" class="pet-tabs-container"></div>
                    <div id="cancelledGrid" class="cards-grid"></div>
                </div>
                <!-- Tab 4: Completed History -->
                <div id="historyTab" class="tab-content">
                    <div id="petSubTabsHistory" class="pet-tabs-container"></div>
                    <div id="historyGrid" class="cards-grid"></div>
                </div>
                <!-- Tab 5: Trash -->
                <div id="trashTab" class="tab-content">
                    <div id="petSubTabsTrash" class="pet-tabs-container"></div>
                    <div id="trashGrid" class="cards-grid"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- KuaGO Explainer Modal -->
<div class="kuago-modal-overlay" id="kuagoModalOverlay">
    <div class="kuago-modal-content">
        <div class="kuago-modal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #eef2ff;">
                    <img src="../assets/images/KuaGO.png" alt="KuaGO" style="width: 24px; height: 24px; object-fit: contain;">
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #1e1b4b;">KuaGO · Clinical Record Explainer</h4>
                    <span style="font-size: 11px; color: #64748b;">Your friendly pet-care AI assistant</span>
                </div>
            </div>
            <button onclick="closeKuagoModal()" style="background: transparent; border: none; font-size: 18px; color: #64748b; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="kuago-modal-body" id="kuagoModalBody">
            <!-- Dynamic AI explanation content -->
        </div>
        <div class="kuago-modal-footer">
            <button onclick="regenerateKuagoExplanation()" id="regenerateBtn" style="background: #f1f5f9; border: 1px solid #e2e8f0; color: #1e1b4b; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-rotate-right"></i> Regenerate with KuaGO
            </button>
            <button onclick="closeKuagoModal()" style="background: #2563EB; border: none; color: white; padding: 8px 18px; border-radius: 10px; font-size: 12px; font-weight: 600; cursor: pointer;">Done</button>
        </div>
    </div>
</div>
<!-- Modern Floating Toast Container -->
<div id="toastNotification">
    <i id="toastIcon" class="fa-solid"></i>
    <span id="toastMessage"></span>
</div>
<?php include("../includes/bottom-nav.php"); ?>
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
<!-- Google Gen AI SDK para sa Gemini -->
<script type="importmap">
  {
    "imports": {
      "@google/genai": "https://esm.run/@google/genai"
    }
  }
</script>
<script type="module">
    import { auth, db } from "../assets/js/firebase-config.js";
    import {
        collection,
        query,
        where,
        getDocs,
        doc,
        getDoc,
        setDoc,
        serverTimestamp
    } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
    import { onAuthStateChanged } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";
    import { GoogleGenAI } from "@google/genai";
    
    const apiKey = window.GEMINI_API_KEY || "AQ.Ab8RN6JmzTDrswsgP_2VVHnJriFw7aPjxKCOX4C15pG4QRBqQA";
    const ai = new GoogleGenAI({ apiKey: apiKey });

    const toast = document.getElementById("toastNotification");
    const toastMessage = document.getElementById("toastMessage");
    const toastIcon = document.getElementById("toastIcon");
    let currentOwnerId = null;
    let ownerPetsList = [];
    let selectedClinicalPet = null;
    let selectedClinicalSubTab = 'medical';
    let rawClinicalRecords = [];
    let rawVaccinationRecords = [];
    let currentActiveRecordForAi = null;
    window.switchTab = function(tabName, event) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        if (tabName === 'clinical') document.getElementById('clinicalTab').classList.add('active');
        else if (tabName === 'archived') document.getElementById('archivedTab').classList.add('active');
        else if (tabName === 'pets') document.getElementById('petsTab').classList.add('active');
        else if (tabName === 'cancelled') document.getElementById('cancelledTab').classList.add('active');
        else if (tabName === 'history') document.getElementById('historyTab').classList.add('active');
        else document.getElementById('trashTab').classList.add('active');
        event.currentTarget.classList.add('active');
    };
    window.switchClinicalSubTab = function(subTab) {
        selectedClinicalSubTab = subTab;
        const btnVac = document.getElementById('subTabVaccination');
        const btnMed = document.getElementById('subTabMedical');
        if (subTab === 'vaccination') {
            btnVac.style.background = '#1e1b4b'; btnVac.style.borderColor = '#1e1b4b';
            btnVac.querySelector('div:nth-child(1)').style.color = '#ffffff';
            btnMed.style.background = '#ffffff'; btnMed.style.borderColor = '#e2e8f0';
            btnMed.querySelector('div:nth-child(1)').style.color = '#1e1b4b';
        } else {
            btnMed.style.background = '#1e1b4b'; btnMed.style.borderColor = '#1e1b4b';
            btnMed.querySelector('div:nth-child(1)').style.color = '#ffffff';
            btnVac.style.background = '#ffffff'; btnVac.style.borderColor = '#e2e8f0';
            btnVac.querySelector('div:nth-child(1)').style.color = '#1e1b4b';
        }
        filterClinicalRecords();
    };
    window.selectClinicalPet = function(petName, event) {
        selectedClinicalPet = petName;
        document.querySelectorAll('#clinicalPetTabs .pet-tab-btn').forEach(btn => {
            btn.style.background = '#f1f5f9'; btn.style.color = '#475569'; btn.style.borderColor = '#e2e8f0';
        });
        if (event && event.currentTarget) {
            event.currentTarget.style.background = '#1e1b4b';
            event.currentTarget.style.color = '#fff';
            event.currentTarget.style.borderColor = '#1e1b4b';
        }
        const foundPet = ownerPetsList.find(p => (p.petName || "").toLowerCase() === petName.toLowerCase());
        fetchClinicalRecordsForPet(foundPet ? foundPet.petId : null, petName);
    };
    onAuthStateChanged(auth, async (user) => {
        if (!user) return;
        try {
            const userQuery = query(collection(db, "users"), where("email", "==", user.email));
            const userSnap = await getDocs(userQuery);
            if (!userSnap.empty) {
                currentOwnerId = userSnap.docs[0].data().ownerId;
                loadOwnerPets(currentOwnerId);
            }
        } catch (e) { console.error(e); }
    });
    async function loadOwnerPets(ownerId) {
        try {
            const q = query(collection(db, "pets"), where("ownerId", "==", ownerId));
            const snapshot = await getDocs(q);
            ownerPetsList = [];
            snapshot.forEach(docSnap => ownerPetsList.push({ id: docSnap.id, ...docSnap.data() }));
            
            const clinicalPetTabsContainer = document.getElementById('clinicalPetTabs');
            if (clinicalPetTabsContainer) {
                let pillsHtml = '';
                ownerPetsList.forEach((pet, index) => {
                    const petName = pet.petName || "Pet";
                    if (index === 0 && !selectedClinicalPet) selectedClinicalPet = petName;
                    const isSelected = selectedClinicalPet === petName;
                    const bgStyle = isSelected ? 'background: #1e1b4b; color: #fff; border-color: #1e1b4b;' : 'background: #f1f5f9; color: #475569; border-color: #e2e8f0;';
                    pillsHtml += `<button class="pet-tab-btn" style="padding: 8px 16px; border-radius: 20px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; ${bgStyle}" onclick="selectClinicalPet('${petName}', event)"><i class="fa-solid fa-paw"></i> ${petName}</button>`;
                });
                clinicalPetTabsContainer.innerHTML = pillsHtml;
                if (selectedClinicalPet) {
                    const found = ownerPetsList.find(p => (p.petName || "").toLowerCase() === selectedClinicalPet.toLowerCase());
                    fetchClinicalRecordsForPet(found ? found.petId : null, selectedClinicalPet);
                }
            }
        } catch (e) { console.error(e); }
    }
    async function fetchClinicalRecordsForPet(petId, petName) {
        const container = document.getElementById('clinicalRecordsContainer');
        container.innerHTML = `<p style="color: #64748b; font-size: 13px; text-align: center; padding: 20px;">Loading clinical records for ${petName}...</p>`;
        try {
            rawClinicalRecords = []; 
            rawVaccinationRecords = [];
            if (petId) {
                const clinicalQuery = query(collection(db, "clinical_records"), where("petId", "==", petId));
                const clinicalSnap = await getDocs(clinicalQuery);
                clinicalSnap.forEach(d => rawClinicalRecords.push({ id: d.id, ...d.data() }));
                
                const vacQuery = query(collection(db, "vaccination_records"), where("petId", "==", petId));
                const vacSnap = await getDocs(vacQuery);
                vacSnap.forEach(d => rawVaccinationRecords.push({ id: d.id, ...d.data() }));
            }
            document.getElementById('vaccinationCountBadge').textContent = `${rawVaccinationRecords.length} record(s)`;
            document.getElementById('medicalCountBadge').textContent = `${rawClinicalRecords.length} record(s)`;
            filterClinicalRecords();
        } catch (e) { 
            console.error(e);
            container.innerHTML = `<p style="color: #dc2626; text-align: center;">Failed to load clinical records.</p>`; 
        }
    }
    window.filterClinicalRecords = function() {
        const searchVal = (document.getElementById('clinicalSearchInput').value || "").toLowerCase();
        const yearVal = document.getElementById('clinicalYearFilter').value;
        const container = document.getElementById('clinicalRecordsContainer');
        
        let dataSource = selectedClinicalSubTab === 'vaccination' ? rawVaccinationRecords : rawClinicalRecords;
        const filtered = dataSource.filter(item => {
            const dateStr = item.createdAt || item.date || item.administeredDate || "";
            if (yearVal !== 'all' && dateStr && !dateStr.includes(yearVal)) return false;
            if (searchVal) {
                const combined = `${item.serviceName || item.service || ''} ${item.vaccineName || item.vaccine || item.name || ''} ${item.doctorId || item.doctor || ''} ${item.notes || ''}`.toLowerCase();
                if (!combined.includes(searchVal)) return false;
            }
            return true;
        });
        document.getElementById('clinicalRecordsCountText').textContent = `${filtered.length} record(s) found`;
        if (filtered.length === 0) {
            container.innerHTML = `<p style="color: #64748b; font-size: 13px; text-align: center; padding: 30px;">No clinical records found.</p>`;
            return;
        }
        let html = '';
        filtered.forEach((record) => {
            let title = '';
            let specificProcedure = '';
            if (selectedClinicalSubTab === 'vaccination') {
                title = 'Wellness';
                const vName = record.vaccineName || record.vaccine || record.name || record.serviceName || record.service || 'Anti-Rabies / Vaccine';
                specificProcedure = `Vaccination (${vName})`;
            } else {
                title = record.serviceName || record.service || 'General Consultation';
                specificProcedure = title;
            }
            const dateVal = record.createdAt || record.date || record.administeredDate || 'N/A';
            const statusVal = record.status || 'Completed';
            const docVal = record.doctorId || record.doctor || 'Dr. Assigned';
            const notesVal = record.notes || record.findings || 'No clinical findings recorded by the clinic.';
            
            const recordJson = JSON.stringify(record).replace(/'/g, "&apos;");
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
                    </div>
                    <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 12px 0;">
                    <div style="font-size: 13px; color: #334155; display: flex; flex-direction: column; gap: 6px;">
                        <div><strong>Specific Procedure:</strong> ${specificProcedure}</div>
                        <div><strong>Attending Veterinarian:</strong> ${docVal}</div>
                        <div style="margin-top: 6px; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #f1f5f9;">
                            <strong style="font-size: 12px; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 2px;">Clinical Findings & Results</strong>
                            <span style="color: #475569; font-size: 12.5px;">${notesVal}</span>
                        </div>
                    </div>
                    <div style="margin-top: 14px;">
                        <button onclick='explainWithKuago(${recordJson})' style="width: 100%; background: #f8fafc; border: 1.5px solid #cbd5e1; color: #1e293b; padding: 10px; border-radius: 12px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                            <img src="../assets/images/KuaGO.png" alt="KuaGO" style="width: 18px; height: 18px; object-fit: contain;"> Explain with KuaGO
                        </button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    };

    window.explainWithKuago = async function(record, forceRegenerate = false) {
        currentActiveRecordForAi = record;
        const modal = document.getElementById("kuagoModalOverlay");
        const body = document.getElementById("kuagoModalBody");
        modal.classList.add("active");

        const recordId = record.id || record.petId + "_" + (record.date || record.createdAt || "rec");
        const explanationRef = doc(db, "ai_clinical_explanations", recordId);

        if (!forceRegenerate) {
            try {
                const docSnap = await getDoc(explanationRef);
                if (docSnap.exists()) {
                    const savedData = docSnap.data();
                    if (savedData && savedData.explanation) {
                        body.innerHTML = `<div style="white-space: pre-line; line-height: 1.6;">${savedData.explanation}</div>`;
                        return;
                    }
                }
            } catch (err) {
                console.error("Error fetching cached explanation:", err);
            }
        }

        body.innerHTML = `<div style="text-align: center; padding: 30px;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: #2563EB;"></i><p style="margin-top: 10px; color: #64748b;">KuaGO is analyzing the clinical record...</p></div>`;
        
        try {
            let title = selectedClinicalSubTab === 'vaccination' ? 'Wellness (Vaccination)' : (record.serviceName || record.service || 'Consultation');
            const dateVal = record.createdAt || record.date || record.administeredDate || 'N/A';
            const docVal = record.doctorId || record.doctor || 'Dr. Assigned';
            const notesVal = record.notes || record.findings || 'None';
            const promptText = `As KuaGO, a friendly pet-care AI assistant for Furry Friends Animal Clinic, explain this veterinary clinical record to the pet owner in a clear, reassuring, and easy-to-understand manner:
- Service/Procedure: ${title}
- Date: ${dateVal}
- Veterinarian: ${docVal}
- Clinical Findings/Notes: ${notesVal}
Provide structured response without using markdown symbols like asterisks or hashes:
1. What was done in this visit?
2. What do these findings mean?
3. Why is this important for your pet?`;

            const response = await ai.models.generateContent({
                model: 'gemini-3.6-flash',
                contents: promptText,
            });

            let reply = response.text ? response.text.trim() : "Could not generate explanation at the moment.";
            
            // Tanggalin ang mga markdown symbols na * at #
            reply = reply.replace(/[#*]/g, '');

            // I-save sa `ai_clinical_explanations` kung saan ang recordId ay ang Document ID na mismo
            try {
                await setDoc(explanationRef, {
                    petId: record.petId || "",
                    ownerId: currentOwnerId || "",
                    explanation: reply,
                    createdAt: serverTimestamp()
                });
            } catch (saveErr) {
                console.error("Failed to save explanation to Firestore:", saveErr);
            }

            body.innerHTML = `<div style="white-space: pre-line; line-height: 1.6;">${reply}</div>`;
        } catch (e) {
            console.error("Gemini AI Exception:", e);
            body.innerHTML = `<p style="color: #dc2626; text-align: center;">Failed to connect with KuaGO AI (${e.message}). Please check your internet connection and API Key.</p>`;
        }
    };

    window.regenerateKuagoExplanation = function() {
        if (currentActiveRecordForAi) {
            window.explainWithKuago(currentActiveRecordForAi, true);
        } else {
            alert("Please select a record first.");
        }
    };
    window.closeKuagoModal = function() {
        document.getElementById("kuagoModalOverlay").classList.remove("active");
    };
</script>
</body>
</html>