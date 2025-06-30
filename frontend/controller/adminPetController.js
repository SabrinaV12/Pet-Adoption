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

function displayStatusMessage() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const msg = urlParams.get('msg');
    const statusDiv = document.getElementById('status-message');

    if (status) {
        let messageText = '';
        switch (status) {
            case 'pet_updated':
                messageText = 'Pet details have been updated successfully!';
                break;
            case 'pet_added':
                messageText = 'Pet has been added successfully!';
                break;
            case 'pet_deleted':
                messageText = 'Pet has been deleted successfully!';
                break;
            case 'error':
                messageText = 'An error occurred: ' + (msg ? escapeHtml(msg) : 'Unknown error.');
                break;
        }
        statusDiv.textContent = messageText;
        statusDiv.style.display = 'block';
        setTimeout(() => { statusDiv.style.display = 'none'; }, 5000);
    }
}

async function fetchAndPopulatePets() {
    const tbody = document.getElementById('pet-table-body');
    const controllerUrl = 'http://localhost/Pet_Adoption/backend/controllers/AdminPetController.php';

    try {
        const response = await fetch(controllerUrl, { credentials: 'include' });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
        }

        const pets = await response.json();
        populatePetTable(pets);

    } catch (error) {
        console.error('Fetch Error:', error);
        tbody.innerHTML = `<tr><td colspan="7" class="error">Error: ${error.message}. Please check console for details.</td></tr>`;
    }
}

async function deletePet(petId) {
    if (!confirm('Are you sure you want to delete this pet? This action cannot be undone.')) {
        return;
    }

    const controllerUrl = 'http://localhost/Pet_Adoption/backend/controllers/AdminPetController.php';

    try {
        const response = await fetch(controllerUrl, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: petId }),
            credentials: 'include'
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to delete pet.');
        }

        const statusDiv = document.getElementById('status-message');
        statusDiv.textContent = 'Pet has been deleted successfully!';
        statusDiv.style.display = 'block';
        setTimeout(() => { statusDiv.style.display = 'none'; }, 5000);

        fetchAndPopulatePets();

    } catch (error) {
        console.error('Delete Error:', error);
        const statusDiv = document.getElementById('status-message');
        statusDiv.textContent = `${error.message}`;
        statusDiv.style.display = 'block';
        setTimeout(() => { statusDiv.style.display = 'none'; }, 5000);
    }
}

function populatePetTable(pets) {
    const tbody = document.getElementById('pet-table-body');
    tbody.innerHTML = '';

    if (pets.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7">No pets found in the database.</td></tr>';
        return;
    }

    pets.forEach(pet => {
        const row = document.createElement('tr');
        const statusCell = pet.adopted ? '<span style="color: green;">Adopted</span>' : 'Available';

        row.innerHTML = `
            <td>${pet.id}</td>
            <td>${escapeHtml(pet.name)}</td>
            <td>${escapeHtml(pet.animal_type)}</td>
            <td>${escapeHtml(pet.breed)}</td>
            <td>${escapeHtml(pet.gender)}</td>
            <td>${statusCell}</td>
            <td class="actions">
                <a href="animal_pages/details_animal.html?id=${pet.id}" class="view">Details</a>
                <a href="animal_pages/edit_animal.html?id=${pet.id}" class="edit">Edit</a>
                <button class="delete" onclick="deletePet(${pet.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    displayStatusMessage();
    fetchAndPopulatePets();
});