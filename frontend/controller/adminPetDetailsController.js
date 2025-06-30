import Pet from '../model/adminPetDetailsModel.js';

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

function setText(id, text) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = text;
    } else {
        console.warn(`Element with ID '${id}' was not found.`);
    }
}

async function initializePetDetailsPage() {
    const statusDiv = document.getElementById('status-message');
    const urlParams = new URLSearchParams(window.location.search);
    const petId = urlParams.get('id');

    if (!petId) {
        statusDiv.textContent = 'Error: No pet ID specified in the URL.';
        statusDiv.className = 'status error';
        return;
    }

    try {
        const response = await fetch(`http://localhost/Pet_Adoption/backend/controllers/AdminPetDetailsController.php?id=${petId}`, {
            credentials: 'include'
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || `HTTP error! Status: ${response.status}`);
        }

        const pet = new Pet(data);
        renderPetDetails(pet);

    } catch (error) {
        console.error('Failed to load pet details:', error);
        statusDiv.textContent = `Error loading pet details: ${error.message}`;
        statusDiv.className = 'status error';
    }
}

function renderPetDetails(pet) {
    document.title = `Details for: ${escapeHtml(pet.name)}`;

    setText('header-pet-name', `Details for "${escapeHtml(pet.name)}"`);
    const editLink = document.getElementById('edit-pet-link');
    if (editLink) {
        editLink.href = `edit_animal.html?id=${pet.id}`;
    }
    const petImage = document.getElementById('pet-image-main');
    if (petImage) {
        petImage.src = pet.image_path;
        petImage.alt = `Photo of ${escapeHtml(pet.name)}`;
    }

    setText('pet-name', escapeHtml(pet.name));
    setText('pet-animal-type', escapeHtml(pet.animal_type));
    setText('pet-breed', escapeHtml(pet.breed));
    setText('pet-gender', escapeHtml(pet.gender));
    setText('pet-age', pet.age);
    setText('pet-size', escapeHtml(pet.size));
    setText('pet-color', escapeHtml(pet.color));
    setText('pet-weight', `${pet.weight} kg`);
    setText('pet-height', `${pet.height} cm`);

    setText('pet-vaccinated', pet.vaccinated ? 'Yes' : 'No');
    setText('pet-neutered', pet.neutered ? 'Yes' : 'No');
    setText('pet-house-trained', pet.house_trained ? 'Yes' : 'No');
    setText('pet-microchipped', pet.microchipped ? 'Yes' : 'No');
    setText('pet-good-with-children', pet.good_with_children ? 'Yes' : 'No');
    setText('pet-shots-up-to-date', pet.shots_up_to_date ? 'Yes' : 'No');

    setText('pet-description', escapeHtml(pet.description));
    setText('pet-restrictions', escapeHtml(pet.restrictions));

    setText('pet-recommendations', escapeHtml(pet.recommended));

    const statusEl = document.getElementById('pet-adoption-status');
    if (statusEl) {
        statusEl.innerHTML = '';

        const strongEl = document.createElement('strong');

        if (pet.adopted) {
            strongEl.textContent = 'Adopted';
            strongEl.style.color = 'green';
            statusEl.appendChild(strongEl);

            if (pet.adoption_date) {
                const date = new Date(pet.adoption_date);
                const dateText = ` on ${date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}`;
                statusEl.appendChild(document.createTextNode(dateText));
            }
        } else {
            strongEl.textContent = 'Available for Adoption';
            strongEl.style.color = 'blue';
            statusEl.appendChild(strongEl);
        }
    }
}

document.addEventListener('DOMContentLoaded', initializePetDetailsPage);