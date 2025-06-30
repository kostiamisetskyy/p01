<?php
// Enforce strict typing to prevent common type-related errors.
declare(strict_types=1);

// Include the configuration and authentication functions.
require_once "config.php";
// This function will redirect to login.php if the user is not authenticated.
require_login();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'administration - BKK Street Food</title>
    <!-- Include Bootstrap for a modern and responsive design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons for better visual elements -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Custom styles for a cleaner admin panel interface */
        body {
            background-color: #f4f7f6;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .dish-card {
            cursor: grab;
            transition: transform 0.2s ease-in-out;
        }
        .dish-card:active {
            cursor: grabbing;
            transform: scale(1.05);
        }
        .day-column .card-body {
            min-height: 150px;
            border: 2px dashed #dce0e3;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        /* Style for when a draggable element is over a drop zone */
        .day-column .card-body.drag-over {
            background-color: #e9f5ff;
            border-color: #0d6efd;
        }
        .dish-in-menu {
            position: relative;
            padding-right: 2rem; /* Make space for the delete button */
        }
        .remove-dish-btn {
            position: absolute;
            top: 50%;
            right: 0.5rem;
            transform: translateY(-50%);
            border: none;
            background: none;
            font-size: 1.2rem;
            color: #dc3545;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        .remove-dish-btn:hover {
            opacity: 1;
        }
        #image-preview {
            max-width: 100%;
            height: auto;
            border-radius: 0.25rem;
            display: none; /* Hidden by default */
        }
        .week-off-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #6c757d;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="bi bi-gear-fill"></i> BKK Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php" target="_blank"><i class="bi bi-eye"></i> Voir le site</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">

    <!-- Dishes Management Section -->
    <section class="mb-5">
        <h2 class="mb-3"><i class="bi bi-card-list"></i> Gérer les Plats</h2>
        <div class="row">
            <!-- Form for Adding/Editing Dishes -->
            <div class="col-lg-4 mb-4">
                <div class="card sticky-lg-top">
                    <div class="card-header">
                        <h5 class="card-title mb-0" id="form-title">Ajouter un Plat</h5>
                    </div>
                    <div class="card-body">
                        <form id="dish-form">
                            <input type="hidden" id="dish-id">
                            <div class="mb-3">
                                <label for="dish-name" class="form-label">Nom du plat</label>
                                <input type="text" class="form-control" id="dish-name" required>
                            </div>
                            <div class="mb-3">
                                <label for="dish-description" class="form-label">Description</label>
                                <textarea class="form-control" id="dish-description" rows="3" required></textarea>
                            </div>
                             <div class="mb-3">
                                <label for="dish-veggie" class="form-label">Option Végétarienne</label>
                                <input type="text" class="form-control" id="dish-veggie" placeholder="Ex: Option veggie : au tofu">
                            </div>
                            <div class="mb-3">
                                <label for="dish-image-upload" class="form-label">Image du plat</label>
                                <input class="form-control" type="file" id="dish-image-upload" accept="image/jpeg, image/png, image/webp, image/gif">
                                <input type="hidden" id="dish-image">
                                <img id="image-preview" src="#" alt="Aperçu de l'image" class="mt-2"/>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="dish-spicy">
                                <label class="form-check-label" for="dish-spicy">Épicé</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-circle"></i> Sauvegarder le Plat</button>
                            <button type="button" class="btn btn-secondary w-100 mt-2" id="cancel-edit-btn" style="display: none;"><i class="bi bi-x-circle"></i> Annuler la Modification</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- List of Available Dishes -->
            <div class="col-lg-8">
                <p class="text-muted">Glissez-déposez un plat de cette liste vers le calendrier de la semaine.</p>
                <div class="row row-cols-1 row-cols-md-2 g-4" id="dishes-list">
                    <!-- Dishes will be dynamically inserted here by JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- Weekly Menu Planner -->
    <section>
        <h2 class="mb-3"><i class="bi bi-calendar-week"></i> Planifier le Menu de la Semaine</h2>
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <label for="menu-title" class="form-label">Titre de la section Menu</label>
                        <input type="text" class="form-control" id="menu-title" placeholder="Ex: MENU DU MARDI 2 AU VENDREDI 5 AOÛT">
                    </div>
                    <div class="col-md-4 text-md-end pt-3 pt-md-0">
                        <div class="form-check form-switch form-check-reverse">
                            <input class="form-check-input" type="checkbox" id="week-off-checkbox">
                            <label class="form-check-label" for="week-off-checkbox"><strong>Toute la semaine de congé</strong></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative">
                <div class="row" id="weekly-calendar">
                    <!-- Calendar days (Tuesday to Friday) -->
                    <?php
                    $days = [
                        "tuesday" => "Mardi",
                        "wednesday" => "Mercredi",
                        "thursday" => "Jeudi",
                        "friday" => "Vendredi",
                    ];
                    foreach ($days as $key => $name): ?>
                    <div class="col-lg-3 col-md-6 mb-3 day-column">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?= $name ?></h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input day-off-checkbox" type="checkbox" data-day="<?= $key ?>">
                                    <label class="form-check-label">Off</label>
                                </div>
                            </div>
                            <div class="card-body drop-zone" data-day="<?= $key ?>">
                                <!-- Assigned dish will go here -->
                            </div>
                        </div>
                    </div>
                    <?php endforeach;
                    ?>
                </div>
                <!-- This overlay will appear when the whole week is off -->
                <div id="week-off-overlay" class="week-off-overlay" style="display: none;">
                    <span>Semaine de congé</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Save Button -->
    <div class="position-fixed bottom-0 end-0 p-3">
        <button id="save-all-button" class="btn btn-lg btn-success shadow-lg">
            <i class="bi bi-save"></i> Enregistrer Toutes les Modifications
        </button>
    </div>

</main>

<!-- Include Bootstrap's JavaScript bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // --- CONFIGURATION ---
    const API_URL = 'api.php';

    // --- DOM ELEMENT REFERENCES ---
    const elements = {
        dishForm: document.getElementById('dish-form'),
        dishIdInput: document.getElementById('dish-id'),
        dishNameInput: document.getElementById('dish-name'),
        dishDescriptionInput: document.getElementById('dish-description'),
        dishImageInput: document.getElementById('dish-image'),
        dishImageUploadInput: document.getElementById('dish-image-upload'),
        dishVeggieInput: document.getElementById('dish-veggie'),
        dishSpicyInput: document.getElementById('dish-spicy'),
        dishesList: document.getElementById('dishes-list'),
        formTitle: document.getElementById('form-title'),
        cancelEditBtn: document.getElementById('cancel-edit-btn'),
        imagePreview: document.getElementById('image-preview'),
        menuTitleInput: document.getElementById('menu-title'),
        weeklyCalendar: document.getElementById('weekly-calendar'),
        weekOffCheckbox: document.getElementById('week-off-checkbox'),
        weekOffOverlay: document.getElementById('week-off-overlay'),
        saveAllButton: document.getElementById('save-all-button'),
        dropZones: document.querySelectorAll('.drop-zone'),
    };

    // --- APPLICATION STATE ---
    let appState = {
        dishes: [],
        weeklyMenu: { week: {} },
        menuTitle: ''
    };

    // --- API COMMUNICATION ---

    /**
     * Fetches all data from the server.
     */
    async function loadDataFromServer() {
        try {
            const response = await fetch(API_URL);
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            const data = await response.json();
            // Ensure the state structure is correct, providing defaults if not
            appState.dishes = data.dishes || [];
            appState.weeklyMenu = data.weeklyMenu || { week: {} };
            appState.menuTitle = data.menuTitle || '';
            renderAll();
        } catch (error) {
            console.error('Failed to load data:', error);
            alert('Erreur: Impossible de charger les données du serveur.');
        }
    }

    /**
     * Saves the entire application state to the server.
     */
    async function saveDataToServer() {
        try {
            const response = await fetch(`${API_URL}?action=save`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(appState)
            });
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            const result = await response.json();
            if (result.success) {
                alert('Modifications enregistrées avec succès!');
            } else {
                throw new Error(result.error || 'Unknown server error');
            }
        } catch (error) {
            console.error('Failed to save data:', error);
            alert(`Erreur: Impossible d'enregistrer les données. ${error.message}`);
        }
    }

    /**
     * Uploads an image file to the server.
     * @param {File} file The image file from the input.
     * @returns {Promise<string|null>} The URL path of the uploaded file or null on failure.
     */
    async function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        try {
            const response = await fetch(`${API_URL}?action=upload`, { method: 'POST', body: formData });
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            const result = await response.json();
            if (result.success) return result.filePath;
            throw new Error(result.error || 'Unknown upload error');
        } catch (error) {
            console.error('Image upload failed:', error);
            alert(`L'envoi de l'image a échoué: ${error.message}`);
            return null;
        }
    }

    // --- RENDER & UI FUNCTIONS ---

    /**
     * Renders all parts of the application UI.
     */
    function renderAll() {
        renderDishes();
        renderMenu();
        addDragAndDropListeners();
    }

    /**
     * Renders the list of available dishes.
     */
    function renderDishes() {
        elements.dishesList.innerHTML = '';
        if (appState.dishes.length === 0) {
            elements.dishesList.innerHTML = '<p class="text-muted col-12">Aucun plat disponible. Ajoutez-en un via le formulaire.</p>';
            return;
        }
        appState.dishes.forEach(dish => {
            const dishCardHTML = `
                <div class="col">
                    <div class="card h-100 dish-card" draggable="true" data-id="${dish.id}">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="${dish.image}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="${dish.name}">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h6 class="card-title">${dish.name} ${dish.spicy ? '🌶️' : ''}</h6>
                                    <div class="btn-group btn-group-sm mt-2">
                                        <button class="btn btn-outline-secondary edit-dish-btn" data-id="${dish.id}"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-outline-danger delete-dish-btn" data-id="${dish.id}"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            elements.dishesList.insertAdjacentHTML('beforeend', dishCardHTML);
        });
    }

    /**
     * Renders the weekly menu calendar.
     */
    function renderMenu() {
        elements.menuTitleInput.value = appState.menuTitle;
        const isWeekOff = appState.weeklyMenu.weekOff || false;
        elements.weekOffCheckbox.checked = isWeekOff;
        elements.weekOffOverlay.style.display = isWeekOff ? 'flex' : 'none';

        elements.dropZones.forEach(zone => {
            const day = zone.dataset.day;
            const dayData = appState.weeklyMenu.week[day] || {};
            zone.innerHTML = ''; // Clear previous content

            // Update the day's "off" checkbox
            const dayOffCheckbox = zone.closest('.day-column').querySelector('.day-off-checkbox');
            dayOffCheckbox.checked = dayData.off || false;
            zone.closest('.card').style.opacity = dayData.off ? 0.5 : 1;

            if (dayData.dishId) {
                const dish = appState.dishes.find(d => d.id === dayData.dishId);
                if (dish) {
                    const assignedDishHTML = `
                        <div class="alert alert-primary dish-in-menu">
                            <strong>${dish.name}</strong>
                            <button class="remove-dish-btn" data-day="${day}">&times;</button>
                        </div>`;
                    zone.insertAdjacentHTML('beforeend', assignedDishHTML);
                }
            }
        });
    }

    /**
     * Populates the form with a dish's data for editing.
     * @param {object} dish The dish object to edit.
     */
    function populateFormForEdit(dish) {
        elements.formTitle.textContent = 'Modifier le Plat';
        elements.dishIdInput.value = dish.id;
        elements.dishNameInput.value = dish.name;
        elements.dishDescriptionInput.value = dish.description;
        elements.dishImageInput.value = dish.image;
        elements.dishVeggieInput.value = dish.veggie || '';
        elements.dishSpicyInput.checked = dish.spicy;
        elements.imagePreview.src = dish.image || '#';
        elements.imagePreview.style.display = dish.image ? 'block' : 'none';
        elements.cancelEditBtn.style.display = 'block';
        elements.dishNameInput.focus();
    }

    /**
     * Resets the form to its default state.
     */
    function resetForm() {
        elements.formTitle.textContent = 'Ajouter un Plat';
        elements.dishForm.reset();
        elements.dishIdInput.value = '';
        elements.dishImageInput.value = '';
        elements.imagePreview.style.display = 'none';
        elements.cancelEditBtn.style.display = 'none';
    }

    // --- EVENT LISTENERS & HANDLERS ---

    /**
     * Handles the form submission for both creating and updating dishes.
     */
    async function handleFormSubmit(e) {
        e.preventDefault();

        let imageUrl = elements.dishImageInput.value;
        const imageFile = elements.dishImageUploadInput.files[0];

        if (imageFile) {
            const uploadedPath = await uploadImage(imageFile);
            if (uploadedPath) {
                imageUrl = uploadedPath;
            } else {
                return; // Stop if upload fails
            }
        }

        if (!imageUrl) {
            alert('Veuillez fournir une URL ou télécharger une image pour le plat.');
            return;
        }

        const dishData = {
            id: elements.dishIdInput.value || `dish_${Date.now()}`,
            name: elements.dishNameInput.value.trim(),
            description: elements.dishDescriptionInput.value.trim(),
            image: imageUrl,
            veggie: elements.dishVeggieInput.value.trim(),
            spicy: elements.dishSpicyInput.checked,
        };

        const existingIndex = appState.dishes.findIndex(d => d.id === dishData.id);
        if (existingIndex > -1) {
            appState.dishes[existingIndex] = dishData;
        } else {
            appState.dishes.push(dishData);
        }

        resetForm();
        renderAll();
    }

    /**
     * Handles clicks on the dishes list (for edit/delete).
     */
    function handleDishesListClick(e) {
        const editBtn = e.target.closest('.edit-dish-btn');
        if (editBtn) {
            const dish = appState.dishes.find(d => d.id === editBtn.dataset.id);
            if (dish) populateFormForEdit(dish);
            return;
        }

        const deleteBtn = e.target.closest('.delete-dish-btn');
        if (deleteBtn) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce plat? Cette action est irréversible.')) {
                const dishId = deleteBtn.dataset.id;
                appState.dishes = appState.dishes.filter(d => d.id !== dishId);
                // Also remove from any day in the weekly menu
                Object.keys(appState.weeklyMenu.week).forEach(day => {
                    if (appState.weeklyMenu.week[day].dishId === dishId) {
                        appState.weeklyMenu.week[day].dishId = null;
                    }
                });
                renderAll();
            }
        }
    }

    /**
     * Handles clicks within the calendar (for removing dishes).
     */
    function handleCalendarClick(e) {
        const removeBtn = e.target.closest('.remove-dish-btn');
        if (removeBtn) {
            const day = removeBtn.dataset.day;
            if (appState.weeklyMenu.week[day]) {
                appState.weeklyMenu.week[day].dishId = null;
                renderMenu();
            }
        }
    }

    /**
     * Handles changes to the "day off" checkboxes.
     */
    function handleDayOffChange(e) {
        const checkbox = e.target;
        if (checkbox.classList.contains('day-off-checkbox')) {
            const day = checkbox.dataset.day;
            if (!appState.weeklyMenu.week[day]) {
                appState.weeklyMenu.week[day] = { off: false, dishId: null };
            }
            appState.weeklyMenu.week[day].off = checkbox.checked;
            renderMenu();
        }
    }

    // --- DRAG-AND-DROP LOGIC ---

    function addDragAndDropListeners() {
        const draggables = document.querySelectorAll('.dish-card');
        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', e => {
                e.dataTransfer.setData('text/plain', e.currentTarget.dataset.id);
                e.currentTarget.style.opacity = '0.5';
            });
            draggable.addEventListener('dragend', e => {
                e.currentTarget.style.opacity = '1';
            });
        });

        elements.dropZones.forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                const dayCard = zone.closest('.card');
                if (dayCard.style.opacity !== '0.5') {
                    zone.classList.add('drag-over');
                }
            });
            zone.addEventListener('dragleave', e => zone.classList.remove('drag-over'));
            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
                const dishId = e.dataTransfer.getData('text/plain');
                const day = zone.dataset.day;
                if (!appState.weeklyMenu.week[day]) {
                    appState.weeklyMenu.week[day] = { off: false, dishId: null };
                }
                appState.weeklyMenu.week[day].dishId = dishId;
                renderMenu();
            });
        });
    }

    // --- INITIALIZATION ---

    elements.dishForm.addEventListener('submit', handleFormSubmit);
    elements.cancelEditBtn.addEventListener('click', resetForm);
    elements.dishesList.addEventListener('click', handleDishesListClick);
    elements.weeklyCalendar.addEventListener('click', handleCalendarClick);
    elements.weeklyCalendar.addEventListener('change', handleDayOffChange);
    elements.saveAllButton.addEventListener('click', saveDataToServer);

    elements.menuTitleInput.addEventListener('input', () => {
        appState.menuTitle = elements.menuTitleInput.value;
    });

    elements.weekOffCheckbox.addEventListener('change', () => {
        appState.weeklyMenu.weekOff = elements.weekOffCheckbox.checked;
        renderMenu();
    });

    elements.dishImageUploadInput.addEventListener('change', () => {
        const file = elements.dishImageUploadInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                elements.imagePreview.src = e.target.result;
                elements.imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Load initial data from the server when the page loads.
    loadDataFromServer();
});
</script>

</body>
</html>
