<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Mobile spacing and alignment fix */
        .main-content {
            box-sizing: border-box;
            overflow-x: hidden;
            width: 100%;
        }
        
        .profile-main-container {
            width: 100%;
            box-sizing: border-box;
            max-width: 100%;
        }
        .profile-banner, .profile-card {
            box-sizing: border-box;
            max-width: 100%;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .form-grid {
            box-sizing: border-box;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: span 2;
        }
        .form-group input, .form-group select {
            box-sizing: border-box;
            max-width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }
        .form-group select:focus, .form-group input:focus {
            border-color: #4f46e5;
        }
        
        /* Disabled input styling */
        .form-group input:disabled, .form-group select:disabled {
            background-color: #f1f5f9 !important;
            color: #64748b !important;
            cursor: not-allowed;
            border-color: #cbd5e1 !important;
        }
        
        /* Last Updated Container Style */
        .last-updated-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .last-updated-box i {
            color: #4f46e5;
        }
        
        /* Sidebar Overlay for Mobile */
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
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
            .main-content {
                padding: 10px !important;
            }
            .profile-main-container {
                margin: 0 !important;
                padding: 5px !important;
            }
            .profile-banner {
                flex-direction: column;
                text-align: center;
                padding: 20px 15px !important;
            }
            .profile-card {
                padding: 15px !important;
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
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
            }
        }
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("../includes/topbar.php"); ?>
        <div class="profile-main-container">
            
            <!-- Profile Banner -->
            <div class="profile-banner">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-container" id="avatarContainer" style="overflow: hidden;">
                        <span id="avatarInitial">U</span>
                        <img id="profileImagePreview" src="" alt="Profile" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    </div>
                </div>
                <div class="profile-banner-info">
                    <h2 id="bannerName">Loading...</h2>
                    <p id="bannerEmail">Loading...</p>
                    <span class="role-badge">Pet Owner</span>
                </div>
            </div>

            <!-- Update Form Card -->
            <div class="profile-card">
                <h3><i class="fa-solid fa-user-pen"></i> Edit Account Information</h3>
                
                <!-- Last Updated Timestamp Badge -->
                <div class="last-updated-box">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span><strong>Last Updated:</strong> <span id="lastUpdatedText">Loading...</span></span>
                </div>
                <div id="alertBox" style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 5px;"></div>
                
                <form id="updateProfileForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Full Name <small style="color: #888;">(Read-only)</small></label>
                            <input type="text" id="fullName" placeholder="Enter your full name" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email Address <small style="color: #888;">(Read-only)</small></label>
                            <input type="email" id="email" placeholder="Enter your email" disabled>
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select id="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" id="contactNumber" placeholder="+63 912 345 6789" required>
                        </div>
                        <div class="form-group">
                            <label>User ID (System Assigned)</label>
                            <input type="text" id="userIdField" disabled>
                        </div>
                        <div class="form-group full-width">
                            <label>Complete Address</label>
                            <input type="text" id="address" placeholder="House No., Street, Barangay, City" required>
                        </div>
                    </div>
                    
                    <div class="btn-container" style="margin-top: 20px;">
                        <button type="submit" class="btn-update" id="saveBtn">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Script for Mobile Sidebar Toggle
    document.addEventListener("DOMContentLoaded", () => {
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('.sidebar'); 
        const overlay = document.getElementById('sidebarOverlay');
        if (mobileBtn && sidebar && overlay) {
            mobileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
    });
</script>

<script type="module">
    import { db } from '../assets/js/firebase-config.js';
    import { collection, query, where, getDocs, setDoc } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

    const currentUserId = localStorage.getItem("ownerId") || localStorage.getItem("userUID");
    const userEmail = localStorage.getItem("userEmail");

    if (!currentUserId || localStorage.getItem("isLoggedIn") !== "true") {
        window.location.href = 'login-user.php';
    }

    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const genderSelect = document.getElementById('gender');
    const contactInput = document.getElementById('contactNumber');
    const addressInput = document.getElementById('address');
    
    const bannerName = document.getElementById('bannerName');
    const bannerEmail = document.getElementById('bannerEmail');
    const avatarInitial = document.getElementById('avatarInitial');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const userIdField = document.getElementById('userIdField');
    const lastUpdatedText = document.getElementById('lastUpdatedText');
    const alertBox = document.getElementById('alertBox');
    const updateForm = document.getElementById('updateProfileForm');
    const saveBtn = document.getElementById('saveBtn');

    let activeUserDocRef = null;

    function formatTimestamp(timestamp) {
        if (!timestamp) return "Never updated";
        let date;
        if (typeof timestamp.toDate === "function") {
            date = timestamp.toDate();
        } else {
            date = new Date(timestamp);
        }
        if (isNaN(date.getTime())) return "Never updated";
        return date.toLocaleString("en-US", {
            month: "short",
            day: "numeric",
            year: "numeric",
            hour: "numeric",
            minute: "2-digit",
            hour12: true
        });
    }

    async function loadUserProfile() {
        try {
            let q = query(collection(db, "users"), where("ownerId", "==", currentUserId));
            let snapshot = await getDocs(q);
            if (snapshot.empty && userEmail) {
                q = query(collection(db, "users"), where("email", "==", userEmail));
                snapshot = await getDocs(q);
            }

            if (!snapshot.empty) {
                const userDoc = snapshot.docs[0];
                activeUserDocRef = userDoc.ref;
                const data = userDoc.data();
                
                if (fullNameInput) fullNameInput.value = data.fullName || '';
                if (emailInput) emailInput.value = data.email || '';
                if (genderSelect) genderSelect.value = data.gender || '';
                if (contactInput) contactInput.value = data.contactNumber || data.phone || '';
                if (addressInput) addressInput.value = data.address || '';
                if (userIdField) userIdField.value = data.ownerId || currentUserId;
                if (lastUpdatedText) lastUpdatedText.textContent = formatTimestamp(data.updatedAt || data.createdAt);
                
                updateBanner(data.fullName || 'User', data.email || 'No email', data.gender || '');
            } else {
                if (bannerName) bannerName.textContent = "New User";
                if (bannerEmail) bannerEmail.textContent = "Please complete your profile";
                if (lastUpdatedText) lastUpdatedText.textContent = "Never updated";
            }
        } catch (error) {
            console.error("Error loading profile:", error);
        }
    }

    function updateBanner(name, email, gender) {
        if (bannerName) bannerName.textContent = name;
        if (bannerEmail) bannerEmail.textContent = email;

        const normalizedGender = (gender || "").toLowerCase();
        let displayImage = "";

        if (normalizedGender === "male") {
            displayImage = "../assets/images/male-owner.png";
        } else if (normalizedGender === "female") {
            displayImage = "../assets/images/female-owner.png";
        }

        if (displayImage !== "") {
            if (profileImagePreview) {
                profileImagePreview.src = displayImage;
                profileImagePreview.style.display = 'block';
            }
            if (avatarInitial) avatarInitial.style.display = 'none';
        } else {
            if (profileImagePreview) profileImagePreview.style.display = 'none';
            if (avatarInitial) {
                avatarInitial.textContent = name && name !== "New User" ? name.charAt(0).toUpperCase() : "U";
                avatarInitial.style.display = 'block';
            }
        }
    }

    loadUserProfile();

    // Real-time update of avatar when gender changes in dropdown
    if (genderSelect) {
        genderSelect.addEventListener('change', (e) => {
            updateBanner(fullNameInput.value || 'User', emailInput.value || '', e.target.value);
        });
    }

    if (updateForm) {
        updateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!activeUserDocRef) {
                showAlert("Error: User document not found for updating.", "error");
                return;
            }

            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Saving...`;
            }

            try {
                const selectedGender = genderSelect ? genderSelect.value : '';
                const now = new Date();

                // Hardcoded image assignment based on gender selection
                let profileImageUrl = "";
                if (selectedGender.toLowerCase() === "male") {
                    profileImageUrl = "../assets/images/male-owner.png";
                } else if (selectedGender.toLowerCase() === "female") {
                    profileImageUrl = "../assets/images/female-owner.png";
                }

                await setDoc(activeUserDocRef, {
                    gender: selectedGender,
                    phone: contactInput ? contactInput.value : '',
                    contactNumber: contactInput ? contactInput.value : '',
                    address: addressInput ? addressInput.value : '',
                    profileImage: profileImageUrl,
                    updatedAt: now
                }, { merge: true });

                if (lastUpdatedText) lastUpdatedText.textContent = formatTimestamp(now);
                updateBanner(fullNameInput.value, emailInput.value, selectedGender);
                showAlert("Profile successfully updated!", "success");
            } catch (error) {
                console.error("Error updating profile: ", error);
                showAlert("Failed to update profile.", "error");
            } finally {
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Save Changes`;
                }
            }
        });
    }

    function showAlert(message, type) {
        if (!alertBox) return;
        alertBox.textContent = message;
        alertBox.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
        alertBox.style.color = type === 'success' ? '#155724' : '#721c24';
        alertBox.style.display = 'block';
        
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }
</script>
</body>
</html>