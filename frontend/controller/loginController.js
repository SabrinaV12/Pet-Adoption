const loginForm = document.querySelector('.login form');
loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    document.getElementById('success-message').style.display = 'none';
    document.getElementById('error-message').style.display = 'none';

    const formData = new FormData(loginForm);

    try {
        const response = await fetch('http://localhost/Pet_Adoption/backend/api/index.php/auth/login', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        });

        const result = await response.json();

        if (result.success) {
    console.log('✅ Login success. Redirecting now...');
    window.location.href = 'home.html';
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