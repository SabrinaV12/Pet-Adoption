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

document.addEventListener('DOMContentLoaded', function () {
    const displayStatusMessage = () => {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const msg = urlParams.get('msg');
        const statusContainer = document.querySelector('.status-message');

        if (status) {
            let message = '';
            switch (status) {
                case 'updated':
                    message = 'Pet details have been updated successfully!';
                    break;
                case 'added':
                    message = 'Pet has been added successfully!';
                    break;
                case 'deleted':
                    message = 'Pet has been deleted successfully!';
                    break;
                case 'error':
                    message = `An error occurred: ${msg ? decodeURIComponent(msg) : 'Unknown error'}`;
                    break;
            }
            if (message) {
                statusContainer.textContent = message;
                statusContainer.style.display = 'block';
            }
        }
    };


    const fetchPets = () => {
        fetch('http://localhost/Pet_Adoption/backend/controllers/AdminPetController.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'include'

        })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = '../login.html';
                    throw new Error('Unauthorized');
                }
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(pets => {
                const tableBody = document.querySelector('table tbody');
                tableBody.innerHTML = '';

                if (pets.length > 0) {
                    pets.forEach(pet => {
                        const row = document.createElement('tr');

                        const statusCell = pet.adopted ? '<span style="color: green;">Adopted</span>' : 'Available';

                        const actionsCell = `
                                <td class="actions">
                                    <a href="animal_pages/details_animal.html?id=${pet.id}" class="view">Details</a>
                                    <a href="animal_pages/edit_animal.html?id=${pet.id}" class="edit">Edit</a>
                                    <a href="animal_pages/delete_animal.php?id=${pet.id}" class="delete" onclick="return confirm('Are you sure you want to delete this pet?');">Delete</a>
                                </td>
                            `;

                        row.innerHTML = `
                                <td>${pet.id}</td>
                                <td>${escapeHTML(pet.name)}</td>
                                <td>${escapeHTML(pet.animal_type)}</td>
                                <td>${escapeHTML(pet.breed)}</td>
                                <td>${escapeHTML(pet.gender)}</td>
                                <td>${statusCell}</td>
                                ${actionsCell}
                            `;
                        tableBody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.colSpan = 7;
                    cell.textContent = 'No pets found.';
                    row.appendChild(cell);
                    tableBody.appendChild(row);
                }
            })
            .catch(error => {
                console.error('Error fetching pets:', error);
                const tableBody = document.querySelector('table tbody');
                tableBody.innerHTML = `<tr><td colspan="7">Error loading data: ${error.message}</td></tr>`;
            });
    };

    const escapeHTML = (str) => {
        const p = document.createElement('p');
        p.appendChild(document.createTextNode(str));
        return p.innerHTML;
    }

    displayStatusMessage();
    fetchPets();
});

//TO DO: DESPARTE MODEL DE VIEWER ++ DELETE !!!!!