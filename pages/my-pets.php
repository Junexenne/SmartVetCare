<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Pets | Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* General Modal Responsive Fixes */
        .modal {
            overflow-y: auto !important;
            padding: 15px !important;
            box-sizing: border-box !important;
        }

        .modal-content {
            max-height: 90vh !important;
            overflow-y: auto !important;
            box-sizing: border-box !important;
            margin: 30px auto !important;
            width: 100% !important;
            max-width: 520px !important;
        }

        /* Desktop Layout Container (Flex) */
        #viewPetModal .view-modal-body {
            display: flex !important;
            flex-direction: row !important;
            gap: 20px !important;
            align-items: stretch !important;
            margin-top: 10px !important;
        }

        /* Left Column (Image & Name) */
        #viewPetModal .view-top {
            flex: 1 1 260px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            border-right: 1px solid #edf2f7 !important;
            padding-right: 20px !important;
            box-sizing: border-box !important;
        }

        #viewPetModal .view-grid {
            flex: 2 1 420px !important;
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
            box-sizing: border-box !important;
            align-content: center !important;
        }

        /* Ibalik sa White ang Icon */
        #viewPetModal .view-item i {
            color: #ffffff !important;
        }

        /* Sidebar Overlay para sa Mobile */
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
            #viewPetModal .modal-content.view-modal {
                max-width: 95% !important;
                padding: 45px 15px 20px 15px !important;
            }
            #viewPetModal .view-modal-body {
                flex-direction: column !important;
            }
            #viewPetModal .view-top {
                border-right: none !important;
                border-bottom: 1px solid #edf2f7 !important;
                padding-right: 0 !important;
                padding-bottom: 20px !important;
                margin-bottom: 10px !important;
            }
            #viewPetModal .view-grid {
                grid-template-columns: 1fr !important;
            }

            /* Responsive Sidebar Styles para sa Mobile */
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1001;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
                background: var(--sidebar-bg, #173F81) !important;
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

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/topbar.php"; ?>

        <section class="dashboard-content">

            <!-- Page Header -->
            <div class="page-header" style="margin-bottom: 25px;">
                <h1 style="font-size: 32px; font-weight: 700;">
                    <i class="fa-solid fa-paw" style="font-size: 28px; margin-right: 8px;"></i>
                    My Pets
                </h1>
            </div>

            <!-- Pets Container -->
            <div class="pets-container" id="petsContainer">

                <!-- Empty State -->
                <div class="empty-state" id="emptyState" style="display: none;">
                    <i class="fa-solid fa-paw"></i>
                    <h2>No Pets Yet</h2>
                    <p>
                        Register your first pet by clicking
                        <strong>Add New Pet</strong>
                    </p>
                </div>

            </div>

        </section>

    </div>

</div>

<!-- ===========================
    EDIT PET MODAL
=========================== -->
<div class="modal" id="petModal">
    <div class="modal-content" style="padding: 25px;">
        <div class="modal-header" style="margin-bottom: 15px;">
            <h2>
                <i class="fa-solid fa-pen"></i>
                Edit Pet Information
            </h2>
            <span class="close-modal">&times;</span>
        </div>

        <form id="petForm">
            <!-- Pet Picture -->
            <div class="pet-image-upload" style="margin-bottom: 15px;">
                <img
                    src="../assets/images/default-pet.png"
                    id="previewImage"
                    alt="Pet Photo">

                <input
                    type="file"
                    id="petImage"
                    accept="image/*"
                    hidden>

                <button
                    type="button"
                    id="uploadBtn">
                    <i class="fa-solid fa-camera"></i>
                    Change Photo
                </button>
            </div>

            <!-- Editable Fields -->
            <div class="form-grid" style="gap: 12px;">
                <div class="form-group">
                    <label>Weight (kg)</label>
                    <input
                        type="number"
                        step="0.1"
                        id="weight"
                        placeholder="Enter current weight">
                </div>

                <div class="form-group">
                    <label>Color / Markings</label>
                    <input
                        type="text"
                        id="color"
                        placeholder="Example: Brown with white spots">
                </div>

                <div class="form-group" style="grid-column: span 2; width: 100%;">
                    <label>Allergies / Behavioral & Medical Notes</label>
                    <textarea
                        id="allergiesInput"
                        rows="3"
                        style="width: 100%; resize: vertical; box-sizing: border-box;"
                        placeholder="Example: Allergic to chicken, or aggressive when touched on ears"></textarea>
                </div>
            </div>

            <div class="modal-note" style="margin: 12px 0; font-size: 12px;">
                <i class="fa-solid fa-circle-info"></i>
                You can update your pet's <strong>photo</strong>,
                <strong>weight</strong>, <strong>color/markings</strong>, and 
                <strong>allergies/medical notes</strong>. For changes to name, breed, or species, please contact the clinic.
            </div>

            <!-- Modern & Clean Button Layout -->
            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px; border-top: 1px solid #edf2f7; padding-top: 18px;">
                
                <!-- Archive / Deceased Button -->
                <button
                    type="button"
                    id="archivePetBtn"
                    style="background: linear-gradient(135deg, #f39c12, #d35400); color: white; border: none; padding: 12px 16px; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; box-shadow: 0 4px 6px rgba(211, 84, 0, 0.15); transition: all 0.2s ease;">
                    <i class="fa-solid fa-box-archive"></i> Mark as Deceased / Archive Pet
                </button>

                <!-- Cancel & Update Buttons Row -->
                <div style="display: flex; justify-content: flex-end; gap: 10px; width: 100%; margin-top: 4px;">
                    <button
                        type="button"
                        class="cancel"
                        style="background: #edf2f7; color: #4a5568; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 500; font-size: 13px; transition: background 0.2s;">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="save"
                        id="savePetBtn"
                        style="background: #5b21b6; color: white; border: none; padding: 10px 22px; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(91, 33, 182, 0.2); transition: all 0.2s;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Update Pet
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- ===========================
    VIEW PET MODAL (Pet Card)
=========================== -->
<div class="modal" id="viewPetModal">
    <div class="modal-content view-modal" style="position: relative !important; max-width: 860px !important; width: 100% !important; padding: 30px !important; box-sizing: border-box !important;">
        
        <span class="close-view-modal" style="position: absolute !important; top: 15px !important; right: 18px !important; z-index: 99999 !important; font-size: 20px !important; cursor: pointer !important; background: #f1f5f9 !important; width: 34px !important; height: 34px !important; border-radius: 50% !important; display: flex !important; align-items: center !important; justify-content: center !important; color: #4a5568 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.08);">&times;</span>

        <div class="view-modal-body">
            <div class="view-top">
                <img id="viewPetImage" class="view-pet-image" alt="Pet Image">
                <h2 id="viewPetName"></h2>

                <div class="pet-id-text">
                    <i class="fa-solid fa-id-card"></i>
                    <span id="viewPetId"></span>
                </div>

                <!-- QR CODE CONTAINER -->
                <div style="margin: 15px 0; background: #fff; padding: 12px; border-radius: 12px; display: inline-block; box-shadow: 0 2px 8px rgba(0,0,0,0.05); text-align: center; width: 100%; max-width: 220px; box-sizing: border-box;">
                    <div id="petQRCode" style="display: flex; justify-content: center; align-content: center;"></div>
                    <small style="display: block; margin-top: 8px; font-size: 11px; color: #4a5568; font-weight: 600; line-height: 1.3; text-align: center;">
                        <i class="fa-solid fa-camera-retro" style="color: #173F81; margin-right: 3px;"></i> Take a screenshot for past transaction & clinic visits
                    </small>
                </div>

                <span class="pet-badge">
                    Pet Profile Card
                </span>
            </div>

            <div class="view-grid">
                <div class="view-item">
                    <i class="fa-solid fa-dog"></i>
                    <div>
                        <small>Species</small>
                        <h4 id="viewSpecies"></h4>
                    </div>
                </div>

                <div class="view-item">
                    <i class="fa-solid fa-paw"></i>
                    <div>
                        <small>Breed</small>
                        <h4 id="viewBreed"></h4>
                    </div>
                </div>

                <div class="view-item">
                    <i class="fa-solid fa-venus-mars"></i>
                    <div>
                        <small>Gender</small>
                        <h4 id="viewGender"></h4>
                    </div>
                </div>

                <div class="view-item">
                    <i class="fa-solid fa-cake-candles"></i>
                    <div>
                        <small>Birth Date</small>
                        <h4 id="viewBirthday"></h4>
                    </div>
                </div>

                <div class="view-item">
                    <i class="fa-solid fa-weight-scale"></i>
                    <div>
                        <small>Weight</small>
                        <h4 id="viewWeight"></h4>
                    </div>
                </div>

                <div class="view-item">
                    <i class="fa-solid fa-palette"></i>
                    <div>
                        <small>Color</small>
                        <h4 id="viewColor"></h4>
                    </div>
                </div>

                <!-- Allergy / Medical Notes Display -->
                <div class="view-item" style="grid-column: span 2;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <small>Allergies / Medical Notes</small>
                        <h4 id="viewAllergies" style="color: #c0392b;">-</h4>
                    </div>
                </div>

                <!-- DIGITAL VACCINATION CARD SECTION -->
                <div class="view-item" style="grid-column: span 2; align-items: flex-start;">
                    <i class="fa-solid fa-syringe" style="margin-top: 4px;"></i>
                    <div style="width: 100%;">
                        <small>Vaccination & Immunization Record</small>
                        <div id="vaccinationListContainer" style="margin-top: 8px;">
                            <h4 style="color: #7f8c8d; font-weight: normal; font-size: 13px;">No vaccination records found yet.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast">
    <i id="toastIcon" class="fa-solid fa-circle-check"></i>
    <div>
        <h4 id="toastTitle" style="margin:0; font-size:14px;">Success</h4>
        <p id="toastMessage" style="margin:0; font-size:12px; opacity:0.9;">Action completed successfully.</p>
    </div>
</div>

<script>
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

<!-- QRCode JS Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script src="../assets/js/toast.js"></script>
<script type="module" src="../assets/js/my-pets.js"></script>

</body>
</html>