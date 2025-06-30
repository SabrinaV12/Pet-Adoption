import User from '../model/adminEditUserModel.js';

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
    const editUserForm = document.getElementById('edit-user-form');
    const statusMessageDiv = document.getElementById('status-message');

    let currentUser;

    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    if (!userId) {
        displayMessage('error', 'No user ID provided. Please return to the user list.');
        editUserForm.style.display = 'none';
        return;
    }

    fetchAndPopulateForm(userId);

    editUserForm.addEventListener('submit', handleFormSubmit);

    async function fetchAndPopulateForm(id) {
        const controllerUrl = `http://localhost/Pet_Adoption/backend/api/index.php/admin/user/details?id=${id}`;
        try {
            const response = await fetch(controllerUrl, { credentials: 'include' });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
            }
            const userData = await response.json();

            console.log(userData);
            currentUser = new User(userData);

            populateForm(currentUser);

        } catch (error) {
            console.error('Fetch Error:', error);
            displayMessage('error', `Could not load user data: ${error.message}`);
            editUserForm.style.display = 'none';
        }
    }

    function populateForm(user) {
        document.getElementById('username-header').textContent = user.username;

        document.getElementById('username-header').textContent = escapeHtml(user.username);

        document.getElementById('id').value = user.id;
        document.getElementById('first_name').value = escapeHtml(user.firstName);
        document.getElementById('last_name').value = escapeHtml(user.lastName);
        document.getElementById('username').value = escapeHtml(user.username);
        document.getElementById('email').value = escapeHtml(user.email);
        document.getElementById('phone_number').value = escapeHtml(user.phoneNumber || '');
        document.getElementById('role').value = escapeHtml(user.role);
        document.getElementById('description').value = escapeHtml(user.description || '');
        document.getElementById('country').value = escapeHtml(user.country || '');
        document.getElementById('county').value = escapeHtml(user.county || '');
        document.getElementById('telegram_handle').value = escapeHtml(user.telegramHandle || '');
        document.getElementById('profile_picture').src = '/Pet_Adoption/public' + user.profilePicture || '';
        document.getElementById('banner_picture').src = '/Pet_Adoption/public' + user.bannerPicture || '';

    }

    async function handleFormSubmit(event) {
        event.preventDefault();
        const serviceUrl = 'http://localhost/Pet_Adoption/backend/api/index.php/admin/user/edit';
        const formData = new FormData(editUserForm);

        try {
            const response = await fetch(serviceUrl, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            const result = await response.json();
            if (!response.ok) {
                const errorMessages = result.messages ? result.messages.join(', ') : result.message;
                throw new Error(errorMessages);
            }

            displayMessage('success', result.message);
            setTimeout(() => {
                window.location.href = `../adminUsers.html?status=user_updated`;
            }, 2000);

        } catch (error) {
            console.error('Submit Error:', error);
            displayMessage('error', `Update failed: ${error.message}`);
        }
    }

    function displayMessage(type, message) {
        statusMessageDiv.className = type;
        statusMessageDiv.textContent = escapeHtml(message);
        statusMessageDiv.style.display = 'block';
    }
});