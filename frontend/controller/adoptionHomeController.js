document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("home-form");

    let adoptionData = JSON.parse(sessionStorage.getItem('adoptionData')) || {};
    if (!adoptionData.pet_id) {
        alert("Your session has expired or is invalid. Please start the adoption process again.");
        window.location.href = 'searchView.html';
        return;
    }

    if (adoptionData.home) {
        if (adoptionData.home.has_garden === 'yes') {
            document.getElementById('choiceYes').checked = true;
        } else if (adoptionData.home.has_garden === 'no') {
            document.getElementById('choiceNo').checked = true;
        }

        document.getElementById('situation').value = adoptionData.home.situation || '';
        document.getElementById('setting').value = adoptionData.home.setting || '';

        document.getElementById('activity-level').value = adoptionData.home.level || '';
    }
    else {
        document.getElementById('choiceNo').checked = true;
    }

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        const formData = new FormData(form);
        const homeData = {
            has_garden: formData.get('has_garden'),
            situation: formData.get('situation').trim(),
            setting: formData.get('setting').trim(),
            level: formData.get('level')
        };

        let errors = {};
        if (!homeData.has_garden) errors.has_garden = "Please select whether you have a garden.";
        if (!homeData.situation) errors.situation = "Please describe your living situation.";
        if (!homeData.setting) errors.setting = "Please describe your household setting.";
        if (!homeData.level) errors.level = "Please select a household activity level.";

        if (Object.keys(errors).length > 0) {
            for (const key in errors) {
                const errorElement = document.getElementById(`${key}-error`);
                if (errorElement) {
                    errorElement.textContent = errors[key];
                }
            }
            return;
        }

        adoptionData.home = homeData;
        sessionStorage.setItem('adoptionData', JSON.stringify(adoptionData));

        window.location.href = '/Pet_Adoption/frontend/view/pages/adoptionRoomate.html';
    });
});