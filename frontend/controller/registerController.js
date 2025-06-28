const registerForm = document.getElementById("register_form")

registerForm.addEventListener('submit', async (event) => {

    event.preventDefault();

    document.getElementById('success-message').style.display = 'none';
    document.getElementById('error-message').style.display = 'none';

    const formData = new FormData(registerForm);
    console.log(formData);
    try {
        const response = await fetch('http://localhost/Pet_Adoption/backend/api/index.php/auth/register', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        });

        const result = await response.json();

        if (response.ok) {
            document.getElementById('success-message').textContent = result.message || 'Registration successful!';
            document.getElementById('success-message').style.display = 'block';
            registerForm.reset();
            // window.location.href = 'login.html';
        } else {
            let errorMsg = result.message || 'Registration failed.';

            if (errorMsg === 'password_mismatch') errorMsg = 'Passwords do not match.';
            if (errorMsg === 'email_exists') errorMsg = 'Email is already registered.';
            if (errorMsg === 'username_exists') errorMsg = 'Username is already taken.';
            if (errorMsg === 'Field is required') errorMsg = 'Please fill in all required fields.';

            document.getElementById('error-message').textContent = errorMsg;
            document.getElementById('error-message').style.display = 'block';
        }
    } catch (error) {
        console.error('Error during registration:', error);
        document.getElementById('error-message').textContent = 'An unexpected error occurred. Please try again.';
        document.getElementById('error-message').style.display = 'block';
    }
});
