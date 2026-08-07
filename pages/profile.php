<?php
session_start();
// Kunin ang user ID galing sa session o gamitin ang default kung wala pa
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'OWN-00004';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart Vet Care</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-main-container {
            padding: 30px;
            max-width: 950px;
            margin: 0 auto;
            font-family: 'Inter', sans-serif;
        }
        
        /* Banner / Header Profile Card */
        .profile-banner {
            background: linear-gradient(135deg, #173f81 0%, #7b6eff 100%);
            border-radius: 16px;
            padding: 30px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(81, 66, 245, 0.2);
            position: relative;
            overflow: hidden;
        }

        .profile-avatar-container {
            width: 90px;
            height: 90px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #5142f5;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            flex-shrink: 0;
            border: 3px solid rgba(255,255,255,0.8);
        }

        .profile-banner-info h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 700;
        }

        .profile-banner-info p {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Form Card */
        .profile-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid #eaeaea;
        }

        .profile-card h3 {
            margin-top: 0;
            font-size: 18px;
            color: #222;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-card h3 i {
            color: #173f81;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: all 0.2s;
            background: #fdfdfd;
        }

        .form-group input:focus {
            border-color: #173f81;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(81, 66, 245, 0.1);
        }

        .form-group input:disabled {
            background: #f8fafc;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .btn-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .btn-update {
            background: #173f81;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-update:hover {
            background: #173f81;
        }

        .btn-update:active {
            transform: scale(0.98);
        }

        /* Alert Toast Notification */
        #alertBox {
            display: none;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .profile-avatar-wrapper {
    position: relative;
    flex-shrink: 0;
}
.profile-avatar-container {
    width: 90px;
    height: 90px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: #5142f5;
    font-weight: bold;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 3px solid rgba(255,255,255,0.8);
    overflow: hidden;
}
.upload-badge-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #ffffff;
    color: #5142f5;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: transform 0.2s;
}
.upload-badge-btn:hover {
    transform: scale(1.1);
}
    </style>

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
            <span id="avatarInitial">R</span>
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
    </div>

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
    <script>
    const currentUserId = "<?php echo $userId; ?>";
   </script>
    <script type="module" src="../assets/js/profile.js"></script>

</body>

</html>