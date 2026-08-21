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
</head>

<body>

<div class="dashboard">

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <?php include "../includes/topbar.php"; ?>

        <section class="dashboard-content">

            <!-- Page Header -->
            <div class="page-header">
                <h1>
                    <i class="fa-solid fa-paw"></i>
                    My Pets
                </h1>
            </div>

            <!-- Toolbar / Search -->
            <div class="pets-toolbar">
                <input
                    type="text"
                    placeholder="Search your pet..."
                    id="searchPet">
            </div>

            <!-- Pets Container -->
            <div class="pets-container" id="petsContainer">

                <!-- Empty State (Makikita kung walang pet) -->
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
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <i class="fa-solid fa-pen"></i>
                Edit Pet Information
            </h2>
            <span class="close-modal">&times;</span>
        </div>

        <form id="petForm">
            <!-- Pet Picture -->
            <div class="pet-image-upload">
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
            <div class="form-grid">
                <div class="form-group">
                    <label>Weight (kg)</label>
                    <input
                        type="number"
                        step="0.1"
                        id="weight"
                        placeholder="Enter current weight">
                </div>

                <div class="form-group full">
                    <label>Color / Markings</label>
                    <input
                        type="text"
                        id="color"
                        placeholder="Example: Brown with white spots">
                </div>
            </div>

            <div class="modal-note">
                <i class="fa-solid fa-circle-info"></i>
                Only the pet's <strong>photo</strong>,
                <strong>weight</strong>, and
                <strong>color/markings</strong> can be updated by the pet owner.
                For changes to the pet's name, breed, species, birth date, or other medical information,
                please contact the clinic.
            </div>

            <div class="modal-buttons">
                <button
                    type="button"
                    class="cancel">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="save"
                    id="savePetBtn">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Update Pet
                </button>
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

        <div class="view-top">
            <img id="viewPetImage" class="view-pet-image" alt="Pet Image">
            <h2 id="viewPetName"></h2>

            <div class="pet-id-text">
                <i class="fa-solid fa-id-card"></i>
                <span id="viewPetId"></span>
            </div>

            <span class="pet-badge">
                Pet Profile
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
                    <small>Birthday</small>
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

<script src="../assets/js/toast.js"></script>
<script type="module" src="../assets/js/my-pets.js"></script>

</body>
</html>