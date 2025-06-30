import { Pet } from '../model/adminEditPetModel.js';

//pt a preveni Cross-Site Scripting
function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') {
        return unsafe;
    }
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}


document.addEventListener('DOMContentLoaded', () => {
    const petNameHeader = document.getElementById('pet_name');
    const addPetForm = document.getElementById('addPetForm');
    const adoptedCheckbox = document.getElementById('adopted');
    const adoptionDateGroup = document.getElementById('adoption_date_group');
    const vaccinationsContainer = document.getElementById('vaccinations-container');
    const addVaccinationBtn = document.getElementById('add-vaccination-row');
    const feedingContainer = document.getElementById('feeding-calendar-container');
    const addFeedingBtn = document.getElementById('add-feeding-row');
    const statusMessage = document.getElementById('status-message');

    const fetchAndPopulatePetData = async (petId) => {
        try {
            const response = await fetch(`http://localhost:80/Pet_Adoption/backend/controllers/AdminGetPetDetailsController.php?id=${petId}`, {
                credentials: 'include',
            });

            if (!response.ok && window.fetch) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            const pet = new Pet(data);

            populateForm(pet);

        } catch (error) {
            console.error('Failed to fetch pet data:', error);
            displayMessage('error', 'Error: Could not load pet data.');
        }
    };

    const populateForm = (pet) => {
        petNameHeader.textContent = `Edit ${escapeHtml(pet.name)}`;

        document.getElementById('name').value = escapeHtml(pet.name);
        document.getElementById('animal_type').value = escapeHtml(pet.animalType);
        document.getElementById('breed').value = escapeHtml(pet.breed);
        document.getElementById('gender').value = escapeHtml(pet.gender);
        document.getElementById('age').value = pet.age;
        document.getElementById('color').value = escapeHtml(pet.color);
        document.getElementById('weight').value = pet.weight;
        document.getElementById('height').value = pet.height;
        document.getElementById('size').value = escapeHtml(pet.size);

        document.getElementById('description').value = escapeHtml(pet.description);
        document.getElementById('restrictions').value = escapeHtml(pet.restrictions);
        document.getElementById('recommended').value = escapeHtml(pet.recommended);


        document.getElementById('vaccinated').checked = pet.vaccinated;
        document.getElementById('neutered').checked = pet.neutered;
        document.getElementById('microchipped').checked = pet.microchipped;
        document.getElementById('good_with_children').checked = pet.goodWithChildren;
        document.getElementById('shots_up_to_date').checked = pet.shotsUpToDate;
        document.getElementById('house_trained').checked = pet.houseTrained;

        adoptedCheckbox.checked = pet.adopted;
        if (pet.adopted) {
            adoptionDateGroup.classList.remove('hidden');
            document.getElementById('adoption_date').value = escapeHtml(pet.adoptionDate);
        }

        pet.vaccinations.forEach(v => addVaccinationRow(v));
        pet.feedingSchedules.forEach(f => addFeedingRow(f));
    };

    const handleFormSubmit = async (event) => {
        event.preventDefault();
        const petId = getPetIdFromUrl();
        const serviceUrl = `http://localhost/Pet_Adoption/backend/controllers/AdminEditPetController.php`; // Your backend endpoint for updates
        const formData = new FormData(addPetForm);

        formData.append('id', petId);

        try {
            const response = await fetch(serviceUrl, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            const result = await response.json();

            if (!response.ok) {
                const errorMessages = result.messages ? result.messages.join(', ') : (result.message || 'An unknown error occurred.');
                throw new Error(errorMessages);
            }

            displayMessage('success', result.message || 'Pet updated successfully!');
            setTimeout(() => {
            }, 2000);

        } catch (error) {
            console.error('Submit Error:', error);
            displayMessage('error', `Update failed: ${error.message}`);
        }
    };

    const addVaccinationRow = (data = null) => {
        const row = document.createElement('div');
        row.className = 'dynamic-row';
        row.innerHTML = `
            <div class="form-group">
                <label>Vaccine Name:</label>
                <input type="text" name="vaccine_name[]" value="${data?.vaccineName || ''}" required>
            </div>
            <div class="form-group">
                <label>Age (Weeks):</label>
                <input type="number" name="age_in_weeks[]" value="${data?.ageInWeeks || ''}" min="0" required>
            </div>
            <button type="button" class="remove-btn">Remove</button>
        `;
        vaccinationsContainer.appendChild(row);
        row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
    };

    const addFeedingRow = (data = null) => {
        const row = document.createElement('div');
        row.className = 'dynamic-row';
        row.innerHTML = `
            <div class="form-group">
                <label>Feed Date:</label>
                <input type="date" name="feed_date[]" value="${data?.feedDate || ''}" required>
            </div>
            <div class="form-group">
                <label>Food Type / Notes:</label>
                <input type="text" name="food_type[]" value="${data?.foodType || ''}" required>
            </div>
            <button type="button" class="remove-btn">Remove</button>
        `;
        feedingContainer.appendChild(row);
        row.querySelector('.remove-btn').addEventListener('click', () => row.remove());
    };

    const displayMessage = (type, message) => {
        if (statusMessage) {
            statusMessage.className = `status-message ${type}`;
            statusMessage.textContent = message;
            statusMessage.style.display = 'block';
        }
    };

    adoptedCheckbox.addEventListener('change', () => {
        adoptionDateGroup.classList.toggle('hidden', !adoptedCheckbox.checked);
    });

    addVaccinationBtn.addEventListener('click', () => addVaccinationRow());
    addFeedingBtn.addEventListener('click', () => addFeedingRow());

    addPetForm.addEventListener('submit', handleFormSubmit);

    const getPetIdFromUrl = () => {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    };

    const petId = getPetIdFromUrl();
    if (petId) {
        fetchAndPopulatePetData(petId);
    } else {
        petNameHeader.textContent = 'Add a New Pet';
        addPetForm.style.display = 'block';
        console.log("No Pet ID found in URL, ready for new entry.");
    }
});