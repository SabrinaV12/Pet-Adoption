const loginForm = document.querySelector('.login form');
loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    document.getElementById('success-message').style.display = 'none';
    document.getElementById('error-message').style.display = 'none';

    const formData = new FormData(loginForm);

    try {
        const response = await fetch('http://localhost/Pet_Adoption/backend/controllers/LoginController.php', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = '../pages/home.html';
            document.getElementById('success-message').textContent = result.message || 'Login successful!';
            document.getElementById('success-message').style.display = 'block';

        } else {
            document.getElementById('error-message').textContent = result.message || 'Username or Password are incorrect!';
            document.getElementById('error-message').style.display = 'block';
        }
    } catch (error) {
        console.error('Error during login:', error);
        document.getElementById('error-message').textContent = 'An unexpected error occurred. Please try again.';
        document.getElementById('error-message').style.display = 'block';
    }
});