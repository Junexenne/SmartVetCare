import { auth, db } from "./firebase-config.js";

import {
    collection,
    query,
    where,
    getDocs,
    doc,
    getDoc,
    updateDoc,
    limit,
    serverTimestamp
}
from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

import {
    onAuthStateChanged
}
from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

// =========================
// VARIABLES
// =========================

let currentUser = null;
let ownerId = "";
let ownerDocId = "";
let editingPetId = null;
let selectedBase64Image = null; // Variable para sa bagong image

// =========================
// ELEMENTS
// =========================
const petsContainer = document.querySelector(".pets-container");
const viewModal = document.getElementById("viewPetModal");
const closeViewModal = document.querySelector(".close-view-modal");
const modal = document.getElementById("petModal");
const closeBtn = document.querySelector(".close-modal");
const cancelBtn = document.querySelector(".cancel");
const petForm = document.getElementById("petForm");
const savePetBtn = document.getElementById("savePetBtn");
const previewImage = document.getElementById("previewImage");
const uploadBtn = document.getElementById("uploadBtn");
const petImage = document.getElementById("petImage");

uploadBtn.addEventListener("click", () => {
    petImage.click();
});

petImage.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event){
        selectedBase64Image = event.target.result; // I-save sa variable
        previewImage.src = selectedBase64Image;
    };
    reader.readAsDataURL(file);
});

// =========================
// LOGIN SESSION
// =========================

onAuthStateChanged(auth, async (user) => {
    if (!user) {
        window.location.href = "../auth/login-user.php";
        return;
    }

    currentUser = user;

    const q = query(
        collection(db, "users"),
        where("email", "==", user.email),
        limit(1)
    );

    const snapshot = await getDocs(q);

    if (snapshot.empty) {
        console.error("User record not found.");
        return;
    }

    const owner = snapshot.docs[0];
    ownerDocId = owner.id;
    ownerId = owner.data().ownerId;

    await loadPets();
});

// =========================
// LOAD PETS
// =========================

async function loadPets() {
    petsContainer.innerHTML = "";

    const q = query(
        collection(db, "pets"),
        where("ownerId", "==", ownerId)
    );

    const snapshot = await getDocs(q);

    if (snapshot.empty) {
        petsContainer.innerHTML = `
        <div class="empty-state">
            <i class="fa-solid fa-dog"></i>
            <h2>No Pets Registered</h2>
            <p>
                Your pet has not yet been registered.<br>
                Please visit Furry Friends Animal Clinic.
            </p>
        </div>
        `;
        return;
    }

    snapshot.forEach((petDoc)=>{
        const pet = petDoc.data();

        petsContainer.insertAdjacentHTML("beforeend",`
        <div class="pet-card">
            <img src="${pet.petImage || "../assets/images/default-pet.png"}" class="pet-photo">
            <div class="pet-info">
                <h3>${pet.petName}</h3>
                <p><strong>Pet ID:</strong> ${pet.petId}</p>
                <p><strong>Species:</strong> ${pet.species}</p>
                <p><strong>Breed:</strong> ${pet.breed}</p>
                <p><strong>Birth Date:</strong> ${pet.birthDate}</p>
                <p><strong>Status:</strong> ${pet.status}</p>
                <div class="pet-actions">
                    <button class="view-btn" data-id="${petDoc.id}">
                        <i class="fa-solid fa-eye"></i> View
                    </button>
                    <button class="edit-btn" data-id="${petDoc.id}">
                        <i class="fa-solid fa-pen"></i> Edit
                    </button>
                </div>
            </div>
        </div>
        `);
    });

    document.querySelectorAll(".view-btn").forEach(btn=>{
        btn.onclick=()=>{
            openViewModal(btn.dataset.id);
        }
    });

    document.querySelectorAll(".edit-btn").forEach(btn=>{
        btn.onclick=()=>{
            openEditModal(btn.dataset.id);
        }
    });
}

// =========================
// VIEW PET
// =========================
async function openViewModal(id){
    const snap = await getDoc(doc(db,"pets",id));
    if(!snap.exists()) return;

    const pet = snap.data();

    document.getElementById("viewPetImage").src =
        pet.petImage || "../assets/images/default-pet.png";

    document.getElementById("viewPetName").innerText = pet.petName;
    document.getElementById("viewPetId").innerText = pet.petId;
    document.getElementById("viewSpecies").innerText = pet.species;
    document.getElementById("viewBreed").innerText = pet.breed;
    document.getElementById("viewGender").innerText = pet.gender || "N/A";
    document.getElementById("viewBirthday").innerText = pet.birthDate || "-";
    document.getElementById("viewWeight").innerText = pet.weight ? pet.weight + " kg" : "N/A";
    document.getElementById("viewColor").innerText = pet.petColorAndMarkings || "-";

    viewModal.style.display = "flex";
}

// =========================
// EDIT 
// =========================

async function openEditModal(id){
    const snap = await getDoc(doc(db,"pets",id));
    if(!snap.exists()) return;

    const pet = snap.data();
    editingPetId = id;
    selectedBase64Image = null; // Reset bago buksan

    document.getElementById("weight").value = pet.weight || "";
    document.getElementById("color").value = pet.petColorAndMarkings || "";
    previewImage.src = pet.petImage || "../assets/images/default-pet.png";

    modal.style.display = "flex";
}

// =========================
// UPDATE PET
// =========================

petForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (!editingPetId) return;

    try {
        // Ihanda ang data na ia-update
        const updateData = {
            weight: Number(document.getElementById("weight").value),
            petColorAndMarkings: document.getElementById("color").value.trim(),
            updatedAt: serverTimestamp()
        };

        // Kung pinalitan ang litrato, isama sa update
        if (selectedBase64Image) {
            updateData.petImage = selectedBase64Image;
        }

        await updateDoc(doc(db, "pets", editingPetId), updateData);

        // Siguraduhing gagana ang toast gamit ang window.showToast o standard showToast
        if (typeof window.showToast === "function") {
            window.showToast("Success", "Pet information updated successfully.", "success");
        } else if (typeof showToast === "function") {
            showToast("Success", "Pet information updated successfully.", "success");
        }

        closeEditModal();
        await loadPets();

    } catch (error) {
        console.error(error);
        
        if (typeof window.showToast === "function") {
            window.showToast("Error", "Failed to update pet.", "error");
        } else if (typeof showToast === "function") {
            showToast("Error", "Failed to update pet.", "error");
        }
    }
});

// =========================
// CLOSE EDIT MODAL
// =========================

function closeEditModal() {
    modal.style.display = "none";
    editingPetId = null;
    selectedBase64Image = null;
    petImage.value = ""; // I-clear ang file input
}

if (closeBtn) {
    closeBtn.addEventListener("click", closeEditModal);
}

if (cancelBtn) {
    cancelBtn.addEventListener("click", closeEditModal);
}

// =========================
// CLOSE VIEW MODAL
// =========================

function closePetViewModal() {
    viewModal.style.display = "none";
}

if (closeViewModal) {
    closeViewModal.addEventListener("click", closePetViewModal);
}

// =========================
// CLICK OUTSIDE MODAL
// =========================

window.addEventListener("click", (e) => {
    if (e.target === modal) {
        closeEditModal();
    }
    if (e.target === viewModal) {
        closePetViewModal();
    }
});
// =========================
// SEARCH PET (Live Highlight Feature)
// =========================
const searchInput = document.getElementById("searchPet");

if (searchInput) {
    searchInput.addEventListener("input", (e) => {
        const searchTerm = e.target.value.toLowerCase().trim();
        const petCards = document.querySelectorAll(".pet-card");

        petCards.forEach(card => {
            const petNameElement = card.querySelector(".pet-info h3");
            const petName = petNameElement ? petNameElement.innerText.toLowerCase() : "";

            // Alisin muna ang lumang highlight sa lahat
            card.classList.remove("highlight-pet");

            // Kung may tinype at nag-match sa pangalan ng pet
            if (searchTerm !== "" && petName.includes(searchTerm)) {
                card.classList.add("highlight-pet");
                
                // Optional: Awtomatikong i-scroll papunta sa pet na na-search
                card.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        });
    });
}