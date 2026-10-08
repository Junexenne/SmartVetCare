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
const archivePetBtn = document.getElementById("archivePetBtn"); 

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
// HELPER: GET HARD-CODED ICON & MAPPING
// =========================
function getPetDisplayDetails(species, rawImage) {
    const s = (species || "").toLowerCase();
    let img = rawImage;
    let speciesText = species || "N/A";

    if (s.includes("dog") || s.includes("canine")) {
        speciesText = "Canine";
        if (!img || img.includes("default-pet.png")) {
            img = "../assets/images/dog-icon.png";
        }
    } else if (s.includes("cat") || s.includes("feline")) {
        speciesText = "Feline";
        if (!img || img.includes("default-pet.png")) {
            img = "../assets/images/cat-icon.png";
        }
    }
    return { img: img || "../assets/images/default-pet.png", speciesText };
}

// =========================
// LOAD PETS
// =========================
async function loadPets() {
    if (!petsContainer) return;
    petsContainer.innerHTML = "";
    const q = query(
        collection(db, "pets"),
        where("ownerId", "==", ownerId)
    );
    const snapshot = await getDocs(q);
    if (snapshot.empty) {
        petsContainer.innerHTML = `
        <div class="empty-state">
            <i class="fa-solid fa-paw"></i>
            <h2>No Pets Registered</h2>
            <p>
                Your pet has not yet been registered.<br>
                Please visit Furry Friends Animal Clinic.
            </p>
        </div>
        `;
        return;
    }
    let hasActivePets = false;
    snapshot.forEach((petDoc)=>{
        const pet = petDoc.data();
        if (pet.status === "Archived" || pet.status === "Deceased") {
            return;
        }
        hasActivePets = true;
        
        const details = getPetDisplayDetails(pet.species, pet.petImage);
        const lastUpdatedText = formatTimestamp(pet.updatedAt || pet.createdAt);

        petsContainer.insertAdjacentHTML("beforeend",`
        <div class="pet-card">
            <img src="${details.img}" class="pet-photo">
            <div class="pet-info">
                <h3>${pet.petName}</h3>
                <p><strong>Pet ID:</strong> ${pet.petId}</p>
                <p><strong>Species:</strong> ${details.speciesText}</p>
                <p><strong>Breed:</strong> ${pet.breed}</p>
                <p><strong>Birth Date:</strong> ${pet.birthDate}</p>
                <p><strong>Last Updated:</strong> ${lastUpdatedText}</p>
                <p><strong>Status:</strong> ${pet.status || 'Active'}</p>
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

    if (!hasActivePets) {
        petsContainer.innerHTML = `
        <div class="empty-state">
            <i class="fa-solid fa-paw"></i>
            <h2>No Active Pets Found</h2>
            <p>All registered pets are currently archived or marked as deceased.</p>
        </div>
        `;
        return;
    }

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
// HELPER: FORMAT TIMESTAMP TO READABLE DATE
// =========================
function formatTimestamp(timestamp) {
    if (!timestamp) return "Not available";
    
    let date;
    if (typeof timestamp.toDate === "function") {
        date = timestamp.toDate();
    } else {
        date = new Date(timestamp);
    }
    if (isNaN(date.getTime())) return "Not available";
    return date.toLocaleString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "2-digit",
        hour12: true
    });
}

// =========================
// POPULATE ALL VIEW / SCAN FIELDS HELPER
// =========================
function fillPetDetailsToDOM(pet) {
    const details = getPetDisplayDetails(pet.species, pet.petImage);

    const fields = [
        { ids: ["viewPetImage"], val: details.img, isImg: true },
        { ids: ["viewPetName", "scannedPetName"], val: pet.petName || "" },
        { ids: ["viewPetId", "scannedPetId"], val: pet.petId || "" },
        { ids: ["viewSpecies", "scannedSpecies"], val: details.speciesText || "" },
        { ids: ["viewBreed", "scannedBreed"], val: pet.breed || "" },
        { ids: ["viewGender", "scannedGender"], val: pet.gender || "N/A" },
        { ids: ["viewBirthday", "scannedBirthday"], val: pet.birthDate || "-" },
        { ids: ["viewWeight", "scannedWeight"], val: pet.weight ? pet.weight + " kg" : "N/A" },
        { ids: ["viewColor", "scannedColor"], val: pet.petColorAndMarkings || "-" },
        { ids: ["viewAllergies", "scannedAllergies"], val: pet.allergies || "None recorded" },
        { ids: ["viewLastUpdated", "scannedLastUpdated"], val: formatTimestamp(pet.updatedAt || pet.createdAt) }
    ];
    fields.forEach(f => {
        f.ids.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (f.isImg) {
                    el.src = f.val;
                } else {
                    el.innerText = f.val;
                }
            }
        });
    });
}

// =========================
// VIEW PET & GENERATE QR CODE
// =========================
async function openViewModal(id){
    let snap = await getDoc(doc(db,"pets",id));
    
    if(!snap.exists()) {
        const qCheck = query(collection(db, "pets"), where("petId", "==", id), limit(1));
        const qSnap = await getDocs(qCheck);
        if(!qSnap.empty) {
            snap = qSnap.docs[0];
        }
    }
    if(!snap || !snap.exists()) return;
    const pet = snap.data();
    fillPetDetailsToDOM(pet);

    const qrContainer = document.getElementById("petQRCodeInner") || document.getElementById("petQRCode");
    if (qrContainer && typeof QRCode !== "undefined") {
        qrContainer.innerHTML = ""; 
        if (pet.petId) {
            new QRCode(qrContainer, {
                text: pet.petId,
                width: 110,
                height: 110,
                colorDark: "#173F81",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    }
    const vaccineContainers = [document.getElementById("vaccinationListContainer"), document.getElementById("scannedVaccinationListContainer")];
    
    let vaccineHTML = `<p style="font-size: 13px; color: #7f8c8d; margin: 0;">Loading vaccination records...</p>`;
    
    vaccineContainers.forEach(container => {
        if (container) container.innerHTML = vaccineHTML;
    });
   try {
        const vaccineQuery = query(
            collection(db, "vaccination_records"),
            where("petId", "==", pet.petId)
        );
        const vaccineSnapshot = await getDocs(vaccineQuery);
        if (!vaccineSnapshot.empty) {
            vaccineHTML = `<table style="width:100%; font-size:13px; border-collapse: collapse; margin-top: 5px;">
                <thead>
                    <tr style="border-bottom: 1px solid #ddd; text-align: left; color: #555;">
                        <th style="padding: 6px;">Vaccine</th>
                        <th style="padding: 6px;">Date Given</th>
                        <th style="padding: 6px;">Next Due</th>
                        <th style="padding: 6px;">Veterinarian</th>
                        <th style="padding: 6px;">Status</th>
                    </tr>
                </thead>
                <tbody>`;
            
            // Gamitin ang for...of para sa async/await sa loob ng loop
            for (const docSnap of vaccineSnapshot.docs) {
                const v = docSnap.data();
                let vetName = 'Clinic Staff';

                // Kung may doctorId, hanapin natin sa 'doctors' collection
                const targetDoctorId = v.doctorId || v.administeredBy || v.veterinarian;
                
                if (targetDoctorId) {
                    try {
                        const docQuery = query(
                            collection(db, "doctors"),
                            where("doctorId", "==", targetDoctorId),
                            limit(1)
                        );
                        const docSnapShot = await getDocs(docQuery);
                        
                        if (!docSnapShot.empty) {
                            const doctorData = docSnapShot.docs[0].data();
                            // Kunin ang pangalan ng doktor (maaaring firstName/lastName o fullName)
                            vetName = doctorData.fullName || `${doctorData.firstName || ''} ${doctorData.lastName || ''}`.trim() || targetDoctorId;
                        } else {
                            vetName = targetDoctorId; // Fallback kung walang mahanap na match
                        }
                    } catch (err) {
                        console.error("Error fetching doctor:", err);
                        vetName = targetDoctorId;
                    }
                }
                
                vaccineHTML += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 6px; font-weight: 500;">${v.vaccineName || 'N/A'} <br><small style="color:#888;">Man: ${v.manufacturer || '-'}</small></td>
                        <td style="padding: 6px;">${v.dateAdministered || '-'}</td>
                        <td style="padding: 6px; font-weight: 500; color: #e67e22;">${v.nextDueDate || '-'}</td>
                        <td style="padding: 6px; font-style: italic; color: #475569;">${vetName}</td>
                        <td style="padding: 6px;"><span style="background: #e8f8f5; color: #16a085; padding: 2px 6px; border-radius: 4px; font-size: 11px;">${v.status || 'Completed'}</span></td>
                    </tr>`;
            }
            vaccineHTML += `</tbody></table>`;
        } else {
            vaccineHTML = `<p style="font-size: 13px; color: #7f8c8d; margin: 0; font-style: italic;">No vaccination records found yet.</p>`;
        }
    } catch (error) {
        console.error("Error loading vaccinations:", error);
        vaccineHTML = `<p style="font-size: 13px; color: #e74c3c; margin: 0;">Failed to load vaccination records.</p>`;
    }
    vaccineContainers.forEach(container => {
        if (container) container.innerHTML = vaccineHTML;
    });
    if (viewModal) viewModal.style.display = "flex";
}

// =========================
// QR CODE SCANNER HANDLER
// =========================
async function handleQRCodeScanned(scannedPetId) {
    if (!scannedPetId) return;
    const q = query(
        collection(db, "pets"),
        where("petId", "==", scannedPetId.trim()),
        limit(1)
    );
    const snap = await getDocs(q);
    if (!snap.empty) {
        const docId = snap.docs[0].id;
        openViewModal(docId);
    } else {
        console.error("Pet record not found for scanned ID:", scannedPetId);
    }
}
window.handleQRCodeScanned = handleQRCodeScanned;

// =========================
// EDIT 
// =========================
async function openEditModal(id){
    const snap = await getDoc(doc(db,"pets",id));
    if(!snap.exists()) return;
    const pet = snap.data();
    editingPetId = id;
    
    const weightInput = document.getElementById("weight");
    const colorInput = document.getElementById("color");
    const allergiesInput = document.getElementById("allergiesInput");
    
    if (weightInput) weightInput.value = pet.weight || "";
    if (colorInput) colorInput.value = pet.petColorAndMarkings || "";
    if (allergiesInput) allergiesInput.value = pet.allergies || ""; 
    if (modal) modal.style.display = "flex";
}

// =========================
// UPDATE PET
// =========================
if (petForm) {
    petForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        if (!editingPetId) return;
        try {
            const updateData = {
                weight: Number(document.getElementById("weight").value),
                petColorAndMarkings: document.getElementById("color").value.trim(),
                allergies: document.getElementById("allergiesInput").value.trim(),
                updatedAt: serverTimestamp()
            };
            
            await updateDoc(doc(db, "pets", editingPetId), updateData);
            
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
}

// =========================
// ARCHIVE / DECEASED PET
// =========================
if (archivePetBtn) {
    archivePetBtn.addEventListener("click", async () => {
        if (!editingPetId) return;
        const originalText = archivePetBtn.innerHTML;
        archivePetBtn.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Click again to confirm archive`;
        archivePetBtn.style.background = "#c0392b";
        const confirmHandler = async () => {
            archivePetBtn.removeEventListener("click", confirmHandler);
            try {
                await updateDoc(doc(db, "pets", editingPetId), {
                    status: "Archived",
                    updatedAt: serverTimestamp()
                });
                if (typeof window.showToast === "function") {
                    window.showToast("Success", "Pet has been successfully archived.", "success");
                } else if (typeof showToast === "function") {
                    showToast("Success", "Pet has been successfully archived.", "success");
                }
                closeEditModal();
                await loadPets();
            } catch (error) {
                console.error("Error archiving pet:", error);
                if (typeof window.showToast === "function") {
                    window.showToast("Error", "Failed to archive pet.", "error");
                } else if (typeof showToast === "function") {
                    showToast("Error", "Failed to archive pet.", "error");
                }
            } finally {
                archivePetBtn.innerHTML = originalText;
                archivePetBtn.style.background = "linear-gradient(135deg, #f39c12, #d35400)";
            }
        };
        archivePetBtn.onclick = confirmHandler;
        setTimeout(() => {
            if (archivePetBtn) {
                archivePetBtn.innerHTML = originalText;
                archivePetBtn.style.background = "linear-gradient(135deg, #f39c12, #d35400)";
                archivePetBtn.onclick = null;
            }
        }, 4000);
    });
}

// =========================
// CLOSE EDIT MODAL
// =========================
function closeEditModal() {
    if (modal) modal.style.display = "none";
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
    if (viewModal) viewModal.style.display = "none";
}
if (closeViewModal) {
    closeViewModal.addEventListener("click", closePetViewModal);
}
if (viewModal) {
    viewModal.addEventListener("click", (e) => {
        if (e.target.classList.contains("close-view-modal") || e.target.closest(".close-view-modal")) {
            closePetViewModal();
        }
    });
}

// =========================
// CLICK OUTSIDE MODAL
// =========================
window.addEventListener("click", (e) => {
    if (modal && e.target === modal) {
        closeEditModal();
    }
    if (viewModal && e.target === viewModal) {
        closePetViewModal();
    }
});