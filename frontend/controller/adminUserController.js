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
            case 'user_updated':
                messageText = 'User details have been updated successfully!';
                break;
            case 'user_added':
                messageText = 'User has been added successfully!';
                break;
            case 'user_deleted':
                messageText = 'User has been deleted successfully!';
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

async function fetchAndPopulateUsers() {
    const tbody = document.getElementById('user-table-body');
    const controllerUrl = 'http://localhost/Pet_Adoption/backend/controllers/AdminUserController.php';

    try {
        const response = await fetch(controllerUrl, { credentials: 'include' });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log(data);
        populateTable(data);

    } catch (error) {
        console.error('Fetch Error:', error);
        tbody.innerHTML = `<tr><td colspan="5" class="error">Error: ${error.message}. Please check console for details.</td></tr>`;
    }
}

async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }

    const controllerUrl = 'http://localhost/Pet_Adoption/backend/controllers/AdminUserController.php';

    try {
        const response = await fetch(controllerUrl, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: userId }),
            credentials: 'include'
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Failed to delete user.');
        }

        const statusDiv = document.getElementById('status-message');
        statusDiv.textContent = 'User has been deleted successfully!';
        statusDiv.style.display = 'block';
        setTimeout(() => { statusDiv.style.display = 'none'; }, 5000);

        fetchAndPopulateUsers();

    } catch (error) {
        console.error('Delete Error:', error);
        const statusDiv = document.getElementById('status-message');
        statusDiv.textContent = `${error.message}`;
        statusDiv.style.display = 'block';
        setTimeout(() => { statusDiv.style.display = 'none'; }, 5000);
    }
}

function populateTable(users) {
    const tbody = document.getElementById('user-table-body');
    tbody.innerHTML = '';

    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5">No users found in the database.</td></tr>';
        return;
    }

    users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${escapeHtml(user.username)}</td>
                    <td>${escapeHtml(user.email)}</td>
                    <td>${escapeHtml(user.role)}</td>
                    <td class="actions">
                        <a href="user_pages/details_user.html?id=${user.id}" class="view">Details</a>
                        <a href="user_pages/edit_user.html?id=${user.id}" class="edit">Edit</a>
                        <button class="delete" onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                `;
        tbody.appendChild(row);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    displayStatusMessage();
    fetchAndPopulateUsers();
});

//TO DO: SA DESPART CONTROLLER DE MODEL!!!!