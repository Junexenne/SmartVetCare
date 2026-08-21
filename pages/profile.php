<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Kunin ang user ID galing sa session o gamitin ang default kung wala pa
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'OWN-00004';
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
                    <!-- Dito ipapakita ang kasalukuyang profile picture o ang initial kung wala pa -->
                    <div class="profile-avatar-container" id="avatarContainer">
                        <span id="avatarInitial">J</span>
                        <img id="profileImagePreview" src="" alt="Profile" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    </div>
                    <!-- Button para i-trigger ang pagpili ng file -->
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
                
                <div id="alertBox"></div>

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
                            <input type="text" id="userIdField" value="<?php echo $userId; ?>" disabled>
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

        </div> <!-- Closing para sa main-content -->
    </div> <!-- Closing para sa dashboard -->

    <!-- Firebase Realtime Fetch & Update Logic -->
    <script type="module">
        import { db } from '../assets/js/firebase-config.js';
        import { doc, getDoc, setDoc } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

        const currentUserId = "<?php echo $userId; ?>";

        // DOM Elements
        const fullNameInput = document.getElementById('fullName');
        const emailInput = document.getElementById('email');
        const contactInput = document.getElementById('contactNumber');
        const addressInput = document.getElementById('address');
        
        const bannerName = document.getElementById('bannerName');
        const bannerEmail = document.getElementById('bannerEmail');
        const avatarInitial = document.getElementById('avatarInitial');
        const alertBox = document.getElementById('alertBox');
        const updateForm = document.getElementById('updateProfileForm');
        const saveBtn = document.getElementById('saveBtn');

        // 1. Kunin ang lumang data mula sa Firebase (Firestore: users/{userId})
        async function loadUserProfile() {
            try {
                const userDocRef = doc(db, "users", currentUserId);
                const docSnap = await getDoc(userDocRef);

                if (docSnap.exists()) {
                    const data = docSnap.data();
                    
                    // Ilagay ang value sa form inputs
                    fullNameInput.value = data.fullName || '';
                    emailInput.value = data.email || '';
                    contactInput.value = data.contactNumber || '';
                    addressInput.value = data.address || '';

                    // I-update ang Banner sa taas
                    updateBanner(data.fullName || 'User', data.email || 'No email');
                } else {
                    // Kung wala pang record sa Firestore, gamitin ang default
                    bannerName.textContent = "Jerome Polo";
                    bannerEmail.textContent = "jerome.polo@furryfriends.com";
                    fullNameInput.value = "Jerome Polo";
                    emailInput.value = "jerome.polo@furryfriends.com";
                }
            } catch (error) {
                console.error("Error loading profile:", error);
                showAlert("Error loading profile data.", "error");
            }
        }

        function updateBanner(name, email) {
            bannerName.textContent = name;
            bannerEmail.textContent = email;
            avatarInitial.textContent = name.charAt(0).toUpperCase();
        }

        // Tawagin ang function pag-load ng page
        loadUserProfile();

        // 2. I-save ang pagbabago papuntang Firebase kapag pinindot ang Submit
        updateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            saveBtn.disabled = true;
            saveBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Saving...`;

            try {
                const userDocRef = doc(db, "users", currentUserId);

                // I-save ang data gamit ang setDoc (merge: true para hindi mabura ang ibang fields kung meron man)
                await setDoc(userDocRef, {
                    fullName: fullNameInput.value,
                    email: emailInput.value,
                    contactNumber: contactInput.value,
                    address: addressInput.value,
                    updatedAt: new Date()
                }, { merge: true });

                // I-update agad ang banner sa taas
                updateBanner(fullNameInput.value, emailInput.value);

                showAlert("Profile successfully updated in Firebase!", "success");
            } catch (error) {
                console.error("Error updating profile: ", error);
                showAlert("Failed to update profile. Please try again.", "error");
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Save Changes`;
            }
        });

        function showAlert(message, type) {
            alertBox.textContent = message;
            alertBox.className = type === 'success' ? 'alert-success' : 'alert-error';
            alertBox.style.display = 'block';
            
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 4000);
        }
    </script>
</body>
</html>