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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* General Modal Responsive Fixes */
        .modal {
            overflow-y: auto !important;
            padding: 15px !important;
            box-sizing: border-box !important;
            background: rgba(15, 23, 42, 0.65) !important;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            max-height: 90vh !important;
            overflow-y: auto !important;
            box-sizing: border-box !important;
            margin: 30px auto !important;
            width: 100% !important;
            max-width: 520px !important;
        }

        /* ==========================================================
            DESKTOP VIEW (Clean Balanced Side-by-Side Original)
        ========================================================== */
        #viewPetModal .view-modal {
            max-width: 840px !important;
            width: 95vw !important;
            padding: 28px !important;
            box-sizing: border-box !important;
            position: relative !important;
            background: #ffffff !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.18) !important;
        }

        #viewPetModal .close-view-modal {
            position: absolute !important;
            top: 18px !important;
            right: 20px !important;
            z-index: 99999 !important;
            font-size: 18px !important;
            cursor: pointer !important;
            background: #f1f5f9 !important;
            width: 36px !important;
            height: 36px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #4a5568 !important;
            transition: all 0.2s;
        }
        #viewPetModal .close-view-modal:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        #viewPetModal .view-modal-body {
            display: flex !important;
            flex-direction: row !important;
            gap: 22px !important;
            align-items: stretch !important;
            margin-top: 5px !important;
        }

        #viewPetModal .view-top {
            flex: 0 0 260px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px 16px 20px 16px;
            box-sizing: border-box;
            justify-content: space-between;
        }

        #viewPetModal .view-top > div:first-of-type {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        #viewPetModal .view-pet-image {
            width: 88px;
            height: 88px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        #viewPetModal .view-top h2 {
            margin: 10px 0 2px 0;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }

        #viewPetModal .pet-id-text {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        #viewPetModal #petQRCode {
            margin: 0 !important;
            background: #fff !important;
            padding: 8px !important;
            border-radius: 8px !important;
            display: inline-block !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04) !important;
            text-align: center !important;
            width: auto;
            box-sizing: border-box;
        }

        #viewPetModal .pet-badge {
            background: #eef2ff;
            color: #4f46e5;
            font-size: 10.5px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 14px;
        }

        #viewPetModal .view-grid {
            flex: 1 !important;
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
            box-sizing: border-box !important;
            align-content: start !important;
        }

        #viewPetModal .view-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 12px 14px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        #viewPetModal .view-item i {
            color: #4f46e5 !important;
            background: #eef2ff !important;
            width: 34px !important;
            height: 34px !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 14px !important;
            flex-shrink: 0;
            margin-top: 0 !important;
        }

        #viewPetModal .view-item small {
            display: block;
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        #viewPetModal .view-item h4 {
            margin: 2px 0 0 0;
            font-size: 13.5px;
            color: #0f172a;
            font-weight: 600;
            word-break: break-word;
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

        /* ==========================================================
            MOBILE VIEW ONLY: COMPACT EMR DOCUMENT STYLE
        ========================================================== */
        @media (max-width: 768px) {
            #viewPetModal .modal-content.view-modal {
                max-width: 96vw !important;
                width: 96vw !important;
                padding: 14px 10px !important;
                background: #ffffff !important;
                color: #0f172a !important;
                font-family: 'IBM Plex Sans', -apple-system, sans-serif !important;
                border-radius: 8px !important;
                box-shadow: 0 20px 40px rgba(0,0,0,0.25) !important;
            }

            #viewPetModal .view-modal-body::before {
                content: "OFFICIAL PET MEDICAL RECORD / EMR";
                display: block;
                font-size: 9px;
                font-weight: 700;
                letter-spacing: 0.8px;
                color: #475569;
                text-align: center;
                border-bottom: 2px solid #0f172a;
                padding-bottom: 5px;
                margin-bottom: 8px;
            }

            #viewPetModal .view-modal-body {
                flex-direction: column !important;
                gap: 10px !important;
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                padding: 10px !important;
                border-radius: 6px !important;
            }

            #viewPetModal .view-top {
                flex: none !important;
                width: 100% !important;
                border-right: none !important;
                border-bottom: 1px dashed #cbd5e1 !important;
                padding: 8px 0 10px 0 !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                gap: 8px !important;
                background: transparent !important;
                border-top: none;
                border-left: none;
                border-right: none;
            }

            #viewPetModal .view-top > div:first-of-type {
                align-items: center;
                text-align: center;
            }

            #viewPetModal .view-pet-image {
                width: 58px !important;
                height: 58px !important;
                border: 2px solid #cbd5e1 !important;
            }

            #viewPetModal .view-top h2 {
                font-size: 15px !important;
                margin: 4px 0 1px 0 !important;
                color: #0f172a !important;
            }

            #viewPetModal .pet-id-text {
                font-size: 9.5px !important;
                justify-content: center !important;
                color: #475569 !important;
            }

            #viewPetModal .pet-badge {
                display: none !important;
            }

            #viewPetModal #petQRCode {
                margin: 8px auto 2px auto !important;
                padding: 5px !important;
                background: #fff !important;
                border: 1px solid #cbd5e1 !important;
                box-shadow: none !important;
                width: auto !important;
            }

            /* Compact 2-column grid sa mobile para magkasya at hindi masyadong pahaba */
            #viewPetModal .view-grid {
                flex: none !important;
                width: 100% !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 6px !important;
            }

            #viewPetModal .view-item {
                background: #f8fafc !important;
                border: 1px solid #e2e8f0 !important;
                padding: 7px 8px !important;
                border-radius: 5px !important;
                box-shadow: none !important;
                gap: 8px !important;
                align-items: center !important;
            }

            #viewPetModal .view-item i {
                background: #eef2ff !important;
                color: #4f46e5 !important;
                width: 26px !important;
                height: 26px !important;
                font-size: 11px !important;
                border-radius: 5px !important;
            }

            #viewPetModal .view-item small {
                font-size: 7.5px !important;
                color: #64748b !important;
                text-transform: uppercase !important;
                line-height: 1;
            }

            #viewPetModal .view-item h4 {
                font-size: 11px !important;
                color: #0f172a !important;
                font-weight: 600 !important;
                margin-top: 1px !important;
                line-height: 1.2;
            }

            /* Full width para sa allergies at vaccination record */
            #viewPetModal .view-item[style*="span 2"],
            #viewPetModal .view-grid > .view-item:nth-last-child(-n+2) {
                grid-column: span 2 !important;
            }

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
            <div class="page-header">
                <h1>
                    <i class="fa-solid fa-paw" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                    My Pets
                </h1>
                <h2>View, add, and manage your registered pet profiles.</h2>
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
    VIEW PET MODAL
=========================== -->
<div class="modal" id="viewPetModal">
    <div class="modal-content view-modal">
        
        <span class="close-view-modal">&times;</span>

        <div class="view-modal-body">
            <div class="view-top">
                <div>
                    <img id="viewPetImage" class="view-pet-image" alt="Pet Image">
                    <h2 id="viewPetName"></h2>
                    <div class="pet-id-text">
                        <i class="fa-solid fa-id-card"></i>
                        <span id="viewPetId"></span>
                    </div>

                    <!-- QR CODE CONTAINER WITH WRAPPER -->
                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%; margin-top: 10px;">
                        <div id="petQRCode">
                            <div id="petQRCodeInner" style="display: flex; justify-content: center; align-content: center;"></div>
                        </div>
                        <small style="display: block; margin-top: 6px; font-size: 8.5px; color: #4a5568; font-weight: 600; line-height: 1.2; text-align: center;">
                            Screenshot for clinic visits & past transactions
                        </small>
                    </div>
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
                    <i class="fa-solid fa-syringe" style="margin-top: 2px;"></i>
                    <div style="width: 100%;">
                        <small>Vaccination & Immunization Record</small>
                        <div id="vaccinationListContainer" style="margin-top: 6px;">
                            <h4 style="color: #7f8c8d; font-weight: normal; font-size: 11.5px;">No vaccination records found yet.</h4>
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