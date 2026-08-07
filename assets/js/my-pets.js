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

        previewImage.src = event.target.result;

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

    // Hanapin ang user sa Firestore USERS collection
    const q = query(
        collection(db, "users"),
        where("email", "==", user.email),
        limit(1)
    );

    const snapshot = await getDocs(q);

    console.log("Logged User:", user.email);
    console.log("Users Found:", snapshot.size);

    if (snapshot.empty) {

        console.error("User record not found.");
        return;

    }

    const owner = snapshot.docs[0];

    ownerDocId = owner.id;
    ownerId = owner.data().ownerId;

    console.log("Owner ID:", ownerId);
    console.log("Owner Doc ID:", ownerDocId);

    await loadPets();

});
// =========================
// LOAD PETS
// =========================

async function loadPets() {

    petsContainer.innerHTML = "";
console.log("Logged User:", currentUser.email);
console.log("Owner ID:", ownerId);
console.log("Owner Doc ID:", ownerDocId);
    const q = query(
    collection(db, "pets"),
    where("ownerId", "==", ownerId)
);

    const snapshot = await getDocs(q);

console.log("User Docs Found:", snapshot.size);

snapshot.forEach(doc => {
    console.log(doc.data());
});

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

    <img
    src="${pet.petImage || "../assets/images/default-pet.png"}"
    class="pet-photo">

    <div class="pet-info">

        <h3>${pet.petName}</h3>

<p><strong>Pet ID:</strong> ${pet.petId}</p>

<p><strong>Species:</strong> ${pet.species}</p>

<p><strong>Breed:</strong> ${pet.breed}</p>

<p><strong>Birth Date:</strong> ${pet.birthDate}</p>

<p><strong>Status:</strong> ${pet.status}</p>

        <div class="pet-actions">

            <button
                class="view-btn"
                data-id="${petDoc.id}">

                <i class="fa-solid fa-eye"></i>

                View

            </button>

            <button
                class="edit-btn"
                data-id="${petDoc.id}">

                <i class="fa-solid fa-pen"></i>

                Edit

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

    document.getElementById("viewPetName").innerText =
        pet.petName;

    document.getElementById("viewPetId").innerText = pet.petId;

    document.getElementById("viewSpecies").innerText =
        pet.species;

    document.getElementById("viewBreed").innerText =
        pet.breed;

    document.getElementById("viewGender").innerText =
        pet.gender || "N/A";

    document.getElementById("viewBirthday").innerText =
        pet.birthDate || "-";

    document.getElementById("viewWeight").innerText =
        pet.weight ? pet.weight + " kg" : "N/A";

    document.getElementById("viewColor").innerText =
        pet.petColorAndMarkings || "-";

    viewModal.style.display = "flex";

}

// =========================
// EDIT 
// =========================

async function openEditModal(id){

    const snap = await getDoc(doc(db,"pets",id));

    if(!snap.exists()) return;

    const pet = snap.data();

    editingPetId=id;

document.getElementById("weight").value =
pet.weight || "";

document.getElementById("color").value =
pet.petColorAndMarkings || "";

    previewImage.src=pet.petImage;

    document.querySelector(".modal-header h2").innerHTML=`
        <i class="fa-solid fa-pen"></i>
        Edit Pet
    `;

    savePetBtn.innerHTML=`
        <i class="fa-solid fa-floppy-disk"></i>
        Update Pet
    `;

    modal.style.display="flex";

}
// =========================
// UPDATE PET
// =========================

petForm.addEventListener("submit", async (e) => {

    e.preventDefault();

    if (!editingPetId) return;

    try {

        await updateDoc(doc(db, "pets", editingPetId), {

            weight: Number(document.getElementById("weight").value),

            petColorAndMarkings: document.getElementById("color").value.trim(),

            updatedAt: serverTimestamp()

        });

        window.showToast(
            "Success",
            "Pet information updated successfully.",
            "success"
        );

        closeEditModal();

        await loadPets();

    } catch (error) {

        console.error(error);

        window.showToast(
            "Error",
            "Failed to update pet.",
            "error"
        );

    }

});
// =========================
// CLOSE EDIT MODAL
// =========================

function closeEditModal() {

    modal.style.display = "none";

    editingPetId = null;

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