const AuthAPI = {
    getUser: async () => {
        try {
            const response = await fetch('http://localhost/Pet_Adoption/backend/api/index.php/auth/me', { // Point to your backend /api/user/me
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
            const response = await fetch('http://localhost/Pet_Adoption/backend/api/index.php/auth/logout', { // Point to your backend /api/logout
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
    await fetch('../components/header.html')
        .then(res => res.text())
        .then(html => document.getElementById('header-placeholder').innerHTML = html);
    const authLinksDiv = document.getElementById("auth-links");
    console.log(authLinksDiv);
    const renderAuthLinks = (user) => {
        authLinksDiv.innerHTML = '';

        if (user) {
            const usernameLink = document.createElement('a');
            usernameLink.href = 'User.html';
            usernameLink.textContent = user.username;
            authLinksDiv.appendChild(usernameLink);

            if (user.role === 'admin') {
                authLinksDiv.appendChild(document.createTextNode(' | '));
                const adminPanelLink = document.createElement('a');
                adminPanelLink.href = 'admin/admin.html';
                adminPanelLink.textContent = 'Admin Panel';
                adminPanelLink.style.color = '#9990DA';
                adminPanelLink.style.fontWeight = 'bold';
                authLinksDiv.appendChild(adminPanelLink);
            }

            authLinksDiv.appendChild(document.createTextNode(' | '));
            const logoutLink = document.createElement('a');
            logoutLink.textContent = 'Logout';
            logoutLink.addEventListener('click', async (e) => {
                e.preventDefault();
                const success = await AuthAPI.logout();
                if (success) {

                    renderAuthLinks(null);
                    window.location.href = '../pages/login.html';
                }
            });
            authLinksDiv.appendChild(logoutLink);

        } else {
            const loginLink = document.createElement('a');
            loginLink.href = 'login.html';
            loginLink.textContent = 'Login';
            authLinksDiv.appendChild(loginLink);

            authLinksDiv.appendChild(document.createTextNode(' | '));

            const registerLink = document.createElement('a');
            registerLink.href = 'register.html';
            registerLink.textContent = 'Register';
            authLinksDiv.appendChild(registerLink);
        }
    };

    const currentUser = await AuthAPI.getUser();
    renderAuthLinks(currentUser);
});