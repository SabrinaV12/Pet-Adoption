const addUserForm = document.getElementById("add_user_form");
const errorMessage = document.getElementById('error-message');

addUserForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    errorMessage.style.display = 'none';

    const formData = new FormData(addUserForm);

    try {
        const response = await fetch('http://localhost/Pet_Adoption/backend/api/index.php/admin/user/', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        });

        const result = await response.json();

        if (response.ok) {
            window.location.href = '../adminUsers.html?status=user_added';
        } else {
            let errorMsg = result.message || 'Failed to create user.';

            if (errorMsg === 'password_mismatch') errorMsg = 'Passwords do not match.';
            if (errorMsg === 'email_exists') errorMsg = 'This email is already registered.';
            if (errorMsg === 'username_exists') errorMsg = 'This username is already taken.';
            if (errorMsg === 'not_admin') errorMsg = 'Authorization failed. You are not an admin.';

            errorMessage.textContent = errorMsg;
            errorMessage.style.display = 'block';
        }
    } catch (error) {
        console.error('Error during user creation:', error);
        errorMessage.textContent = 'An unexpected error occurred. Please try again.';
        errorMessage.style.display = 'block';
    }
});