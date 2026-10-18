import { db } from "./firebase-config.js";
import { collection, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("reportProblemModal");
    const closeBtn = document.getElementById("closeReportModalBtn");
    const chips = document.querySelectorAll(".report-chip");
    const categoryInput = document.getElementById("selectedCategory");
    const textarea = document.getElementById("problemDescription");
    const charCount = document.getElementById("charCount");
    const form = document.getElementById("reportProblemForm");

    // 1. Open/Close Modal Handler
    window.openReportProblemModal = () => {
        if (modal) modal.style.display = "flex";
    };

    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            if (modal) modal.style.display = "none";
        });
    }

    // 2. Chip Selection Handler
    chips.forEach(chip => {
        chip.addEventListener("click", () => {
            chips.forEach(c => c.classList.remove("active"));
            chip.classList.add("active");
            if (categoryInput) {
                categoryInput.value = chip.getAttribute("data-category");
            }
        });
    });

    // 3. Character Counter Handler
    if (textarea && charCount) {
        textarea.addEventListener("input", () => {
            charCount.textContent = `${textarea.value.length}/1000`;
        });
    }

    // 4. Submit Report to Firestore
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const category = categoryInput.value;
            const description = textarea.value.trim();
            const email = document.getElementById("userReportEmail").value.trim();
            const currentOwnerId = localStorage.getItem("ownerId") || "ANONYMOUS";

            const submitBtn = document.getElementById("submitReportBtn");
            const originalBtnText = submitBtn.innerHTML;

            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Submitting...`;

                await addDoc(collection(db, "system_reports"), {
                    userId: currentOwnerId,
                    category: category,
                    description: description,
                    email: email || "Not provided",
                    status: "Pending", // Pending, Investigating, Resolved
                    createdAt: serverTimestamp()
                });

                if (typeof window.showToast === "function") {
                    window.showToast("Success", "Thank you! Your report has been submitted.", "success");
                } else {
                    alert("Thank you! Your report has been submitted.");
                }

                // Reset & Close
                form.reset();
                charCount.textContent = "0/1000";
                if (modal) modal.style.display = "none";

            } catch (error) {
                console.error("Error submitting problem report:", error);
                alert("Failed to submit report. Please try again.");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }
});