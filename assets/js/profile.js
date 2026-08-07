import { db, storage } from './firebase-config.js';
import { collection, query, where, getDocs, doc, setDoc } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
import { ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-storage.js";

document.addEventListener("DOMContentLoaded", () => {
    const userId = typeof currentUserId !== 'undefined' ? currentUserId : 'OWN-00004';

    // DOM Elements
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const contactInput = document.getElementById('contactNumber');
    const addressInput = document.getElementById('address');
    
    const bannerName = document.getElementById('bannerName');
    const bannerEmail = document.getElementById('bannerEmail');
    const avatarInitial = document.getElementById('avatarInitial');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const avatarContainer = document.getElementById('avatarContainer');
    const avatarFileInput = document.getElementById('avatarFileInput');
    
    const alertBox = document.getElementById('alertBox');
    const updateForm = document.getElementById('updateProfileForm');
    const saveBtn = document.getElementById('saveBtn');

    let activeDocId = null;
    let selectedFile = null; // Dito ise-save ang napiling file bago i-upload

    // 1. Load User Profile
    async function loadUserProfile() {
        try {
            const usersRef = collection(db, "users");
            const q = query(usersRef, where("ownerId", "==", userId));
            const querySnapshot = await getDocs(q);

            if (!querySnapshot.empty) {
                const userDoc = querySnapshot.docs[0];
                activeDocId = userDoc.id;
                const data = userDoc.data();
                
                if (fullNameInput) fullNameInput.value = data.fullName || '';
                if (emailInput) emailInput.value = data.email || '';
                if (contactInput) contactInput.value = data.contactNumber || data.phone || '';
                if (addressInput) addressInput.value = data.address || '';

                updateBanner(data.fullName || 'User', data.email || 'No email', data.profileImage || '');
            } else {
                activeDocId = userId; 
                if (bannerName) bannerName.textContent = "Rome";
                if (bannerEmail) bannerEmail.textContent = "rome@example.com";
                if (fullNameInput) fullNameInput.value = "Rome";
                if (emailInput) emailInput.value = "rome@example.com";
                updateBanner("Rome", "rome@example.com", '');
            }
        } catch (error) {
            console.error("Error loading profile:", error);
            showAlert("Error loading profile data.", "error");
        }
    }

    function updateBanner(name, email, imageUrl) {
        if (bannerName) bannerName.textContent = name;
        if (bannerEmail) bannerEmail.textContent = email;

        if (imageUrl && imageUrl.trim() !== "") {
            // Kung may naka-save na profile picture URL galing Firebase Storage
            if (profileImagePreview) {
                profileImagePreview.src = imageUrl;
                profileImagePreview.style.display = 'block';
            }
            if (avatarInitial) avatarInitial.style.display = 'none';
        } else {
            // Kung wala, ipakita ang initial letter ng pangalan
            if (profileImagePreview) profileImagePreview.style.display = 'none';
            if (avatarInitial) {
                avatarInitial.textContent = name.charAt(0).toUpperCase();
                avatarInitial.style.display = 'block';
            }
        }
    }

    loadUserProfile();

    // 2. Kapag namili ang user ng bagong profile picture file
    if (avatarFileInput) {
        avatarFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                selectedFile = file;
                // I-preview agad sa UI nang hindi pa ini-upload
                const reader = new FileReader();
                reader.onload = (uploadEvent) => {
                    updateBanner(fullNameInput.value || 'User', emailInput.value || '', uploadEvent.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. I-save ang pagbabago at i-upload ang larawan sa Firebase Storage pag-submit
    if (updateForm) {
        updateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Saving...`;
            }

            try {
                const targetId = activeDocId ? activeDocId : userId;
                const userDocRef = doc(db, "users", targetId);

                let profileImageUrl = profileImagePreview && profileImagePreview.src && profileImagePreview.style.display === 'block' ? profileImagePreview.src : '';

                // Kung may pinili silang bagong litrato, i-upload muna sa Firebase Storage
                if (selectedFile) {
                    const storageRef = ref(storage, `profile_images/${userId}_${Date.now()}`);
                    const snapshot = await uploadBytes(storageRef, selectedFile);
                    profileImageUrl = await getDownloadURL(snapshot.ref);
                }

                // I-save ang buong data kasama ang profileImage URL sa Firestore
                await setDoc(userDocRef, {
                    ownerId: userId,
                    fullName: fullNameInput.value,
                    email: emailInput.value,
                    contactNumber: contactInput.value,
                    address: addressInput.value,
                    profileImage: profileImageUrl,
                    updatedAt: new Date()
                }, { merge: true });

                updateBanner(fullNameInput.value, emailInput.value, profileImageUrl);
                showAlert("Profile and picture successfully updated!", "success");
                selectedFile = null; // I-reset ang file selection
            } catch (error) {
                console.error("Error updating profile: ", error);
                showAlert("Failed to update profile. Please try again.", "error");
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
        alertBox.className = type === 'success' ? 'alert-success' : 'alert-error';
        alertBox.style.display = 'block';
        
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }
});