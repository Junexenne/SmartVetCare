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
</head>
<body>
<div class="dashboard">

    <?php include("../includes/sidebar.php"); ?>

    <div class="main-content">

        <?php include("../includes/topbar.php"); ?>

        <div class="profile-main-container">
            
            <!-- Profile Banner -->
            <div class="profile-banner">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-container" id="avatarContainer">
                        <span id="avatarInitial">U</span>
                        <img id="profileImagePreview" src="" alt="Profile" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    </div>
                    <label for="avatarFileInput" class="upload-badge-btn" title="Change Profile Picture">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                    <input type="file" id="avatarFileInput" accept="image/*" style="display: none;">
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
                
                <div id="alertBox" style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 5px;"></div>

                <form id="updateProfileForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" id="fullName" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" id="contactNumber" placeholder="+63 912 345 6789">
                        </div>
                        <div class="form-group">
                            <label>User ID (System Assigned)</label>
                            <input type="text" id="userIdField" disabled>
                        </div>
                        <div class="form-group full-width">
                            <label>Complete Address</label>
                            <input type="text" id="address" placeholder="House No., Street, Barangay, City">
                        </div>
                    </div>
                    
                    <div class="btn-container">
                        <button type="submit" class="btn-update" id="saveBtn">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script type="module">
    import { db, storage } from '../assets/js/firebase-config.js';
    import { collection, query, where, getDocs, setDoc } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
    import { ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-storage.js";

    const currentUserId = localStorage.getItem("ownerId") || localStorage.getItem("userUID");
    const userEmail = localStorage.getItem("userEmail");

    if (!currentUserId || localStorage.getItem("isLoggedIn") !== "true") {
        window.location.href = 'login-user.php';
    }

    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const contactInput = document.getElementById('contactNumber');
    const addressInput = document.getElementById('address');
    
    const bannerName = document.getElementById('bannerName');
    const bannerEmail = document.getElementById('bannerEmail');
    const avatarInitial = document.getElementById('avatarInitial');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const avatarFileInput = document.getElementById('avatarFileInput');
    const userIdField = document.getElementById('userIdField');

    const alertBox = document.getElementById('alertBox');
    const updateForm = document.getElementById('updateProfileForm');
    const saveBtn = document.getElementById('saveBtn');

    let selectedFile = null;
    let activeUserDocRef = null;

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
                if (contactInput) contactInput.value = data.contactNumber || data.phone || '';
                if (addressInput) addressInput.value = data.address || '';
                if (userIdField) userIdField.value = data.ownerId || currentUserId;

                updateBanner(data.fullName || 'User', data.email || 'No email', data.profileImage || '');
            } else {
                if (bannerName) bannerName.textContent = "New User";
                if (bannerEmail) bannerEmail.textContent = "Please complete your profile";
            }
        } catch (error) {
            console.error("Error loading profile:", error);
        }
    }

    function updateBanner(name, email, imageUrl) {
        if (bannerName) bannerName.textContent = name;
        if (bannerEmail) bannerEmail.textContent = email;

        if (avatarInitial) {
            avatarInitial.textContent = name && name !== "New User" ? name.charAt(0).toUpperCase() : "U";
        }

        if (imageUrl && imageUrl.trim() !== "") {
            if (profileImagePreview) {
                profileImagePreview.src = imageUrl;
                profileImagePreview.style.display = 'block';
            }
            if (avatarInitial) avatarInitial.style.display = 'none';
        } else {
            if (profileImagePreview) profileImagePreview.style.display = 'none';
            if (avatarInitial) avatarInitial.style.display = 'block';
        }
    }

    loadUserProfile();

    if (avatarFileInput) {
        avatarFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                selectedFile = file;
                const reader = new FileReader();
                reader.onload = (uploadEvent) => {
                    updateBanner(fullNameInput.value || 'User', emailInput.value || '', uploadEvent.target.result);
                };
                reader.readAsDataURL(file);
            }
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
                let profileImageUrl = profileImagePreview && profileImagePreview.src && profileImagePreview.style.display === 'block' ? profileImagePreview.src : '';

                if (selectedFile) {
                    const storageRef = ref(storage, `profile_images/${currentUserId}_${Date.now()}`);
                    const snapshot = await uploadBytes(storageRef, selectedFile);
                    profileImageUrl = await getDownloadURL(snapshot.ref);
                }

                await setDoc(activeUserDocRef, {
                    fullName: fullNameInput ? fullNameInput.value : '',
                    email: emailInput ? emailInput.value : '',
                    phone: contactInput ? contactInput.value : '',
                    address: addressInput ? addressInput.value : '',
                    profileImage: profileImageUrl,
                    updatedAt: new Date()
                }, { merge: true });

                updateBanner(fullNameInput.value, emailInput.value, profileImageUrl);
                showAlert("Profile successfully updated!", "success");
                selectedFile = null; 
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