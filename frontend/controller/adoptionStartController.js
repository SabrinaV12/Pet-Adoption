import { User } from '../model/adoptionStartModel.js';

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

document.addEventListener("DOMContentLoaded", async () => {
    try {
        const response = await fetch('/Pet_Adoption/backend/controllers/UserDetailsController.php');

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Failed to fetch user data');
        }

        const user = new User(
            data.id,
            data.profile_picture,
            data.first_name,
            data.last_name,
            data.username,
            data.email,
            data.phone_number,
            data.description,
            data.county,
            data.country,
            data.telegram_handle,
            data.banner_picture
        );

        populateUserData(user);

    } catch (error) {
        console.error('Error:', error);
        alert('Could not load user information: ' + escapeHtml(error.message));
    }
});

function populateUserData(user) {
    const profileCard = document.querySelector('.profile-card');
    if (!profileCard) return;

    const profileImage = profileCard.querySelector('.image-card img');
    if (user.profilePicture) {
        profileImage.src = `/Pet_Adoption/public/${user.profilePicture}`;
    } else {
        profileImage.src = '/frontend/view/assets/profile_head.png';
    }

    const infoRows = profileCard.querySelectorAll('.info-row');
    if (infoRows.length >= 4) {
        infoRows[0].querySelector('p').textContent = user.username || 'Not available';
        infoRows[1].querySelector('p').textContent = user.firstName || 'Not available';
        infoRows[2].querySelector('p').textContent = user.lastName || 'Not available';
        infoRows[3].querySelector('p').textContent = user.email || 'Not available';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const startButton = document.querySelector('.start-button');
    if (startButton) {
        startButton.addEventListener('click', (event) => {
            event.preventDefault();

            const params = new URLSearchParams(window.location.search);
            const petId = params.get("pet_id");

            if (petId) {
                const adoptionData = { pet_id: petId };
                sessionStorage.setItem('adoptionData', JSON.stringify(adoptionData));
            } else {
                alert("Could not determine which pet to adopt. Please try again.");
                return;
            }

            window.location.href = startButton.href;
        });
    }
});