import User from '../model/adminUserDetailsModel.js';

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
        console.warn(`Element with ID '${id}' was not found in the HTML.`);
    }
}

async function initializeUserDetailsPage() {
    const statusDiv = document.getElementById('status-message');

    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    if (!userId) {
        statusDiv.textContent = 'Error: No user ID specified in the URL.';
        statusDiv.className = 'status error';
        return;
    }

    try {
        const response = await fetch(`http://localhost/Pet_Adoption/backend/api/index.php/admin/user/details?id=${userId}`, {
            credentials: 'include' //for sending auth cookies/tokens
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'An unknown error occurred.');
        }

        const user = new User(data);

        renderUserDetails(user);

    } catch (error) {
        console.error('Failed to load user details:', error);
        statusDiv.textContent = `Error loading user details: ${error.message}`;
        statusDiv.className = 'status error';
    }
}

function renderUserDetails(user) {
    document.title = `Details for: ${escapeHtml(user.username)}`;

    document.getElementById('header-username').textContent = escapeHtml(user.username);
    document.getElementById('edit-user-link').href = `edit_user.html?id=${user.id}`; // Assuming you have an edit page

    document.getElementById('user-id').textContent = user.id;
    document.getElementById('user-first-name').textContent = escapeHtml(user.firstName);
    document.getElementById('user-last-name').textContent = escapeHtml(user.lastName);
    document.getElementById('user-username').textContent = escapeHtml(user.username);
    document.getElementById('user-email').textContent = escapeHtml(user.email);
    document.getElementById('user-phone-number').textContent = escapeHtml(user.phoneNumber);
    document.getElementById('user-role').textContent = escapeHtml(user.role);
    document.getElementById('user-description').textContent = escapeHtml(user.description);
}


document.addEventListener('DOMContentLoaded', initializeUserDetailsPage);