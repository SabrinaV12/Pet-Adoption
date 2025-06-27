const AuthAPI = {
    getUser: async () => {
        try {
            const response = await fetch('http://localhost/Pet_Adoption/backend/controllers/AuthController.php', {
                method: 'GET',
                credentials: 'include'
            });

            if (response.ok) {
                return await response.json();
            } else if (response.status === 401) {
                console.log('User not authenticated (401).');
                return null;
            } else {
                const errorData = await response.json();
                console.error(`Error fetching user data: ${response.status} ${response.statusText}`, errorData.message);
                return null;
            }
        } catch (error) {
            console.error('Network error during user data fetch:', error);
            return null;
        }
    },

    logout: async () => {
        try {
            const response = await fetch('http://localhost/Pet_Adoption/backend/controllers/LogoutController.php', {
                method: 'POST',
                credentials: 'include'
            });

            if (response.ok) {
                console.log('Logout successful from backend.');
                return true;
            } else {
                const errorData = await response.json();
                console.error('Logout failed:', response.status, errorData.message);
                return false;
            }
        } catch (error) {
            console.error('Network error during logout:', error);
            return false;
        }
    }
};

document.addEventListener('DOMContentLoaded', async () => {
    const user = await AuthAPI.getUser();
    const usernameElement = document.getElementById('admin_username');

    if (user && user.username) {
        usernameElement.textContent = `Welcome, ${user.username}!`;
    } else {
        window.location.href = '../../pages/login.html';
    }

    const logoutLink = document.getElementById('logout_link');
    logoutLink.addEventListener('click', async (event) => {
        event.preventDefault();
        const loggedOut = await AuthAPI.logout();
        if (loggedOut) {
            window.location.href = '../../pages/login.html';
        }
    });
});