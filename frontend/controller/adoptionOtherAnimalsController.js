document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("pets-form");
    const hasAnimalsRadios = document.querySelectorAll('input[name="has_animals"]');
    const conditionalFields = document.getElementById('conditional-animal-fields');

    const toggleConditionalFields = () => {
        const choice = document.querySelector('input[name="has_animals"]:checked');
        if (choice && choice.value === 'yes') {
            conditionalFields.style.display = 'block';
        } else {
            conditionalFields.style.display = 'none';
        }
    };

    hasAnimalsRadios.forEach(radio => radio.addEventListener('change', toggleConditionalFields));

    let adoptionData = JSON.parse(sessionStorage.getItem('adoptionData')) || {};
    if (!adoptionData.pet_id || !adoptionData.address || !adoptionData.home || !adoptionData.roommates) {
        alert("Your application session is incomplete or has expired. Please start over.");
        window.location.href = 'adoptionStart.html';
        return;
    }

    if (adoptionData.pets) {
        const data = adoptionData.pets;
        document.getElementById('allergies').value = data.allergies || '';
        document.getElementById('experience').value = data.experience || '';
        if (data.has_animals === 'yes') {
            document.getElementById('choiceYesAnimal').checked = true;
            document.getElementById('other_animals_info').value = data.other_animals_info || '';
            document.querySelector(`input[name="neutered"][value="${data.neutered}"]`).checked = true;
            document.querySelector(`input[name="vaccinated"][value="${data.vaccinated}"]`).checked = true;
        } else if (data.has_animals === 'no') {
            document.getElementById('choiceNoAnimal').checked = true;
        }
    }

    toggleConditionalFields();

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const submitButton = form.querySelector('.continue-button');
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';

        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        const formData = new FormData(form);
        console.log(formData)
        const petsData = {
            allergies: formData.get('allergies').trim(),
            experience: formData.get('experience').trim(),
            has_animals: formData.get('has_animals'),
            other_animals_info: formData.get('other_animals_info')?.trim(),
            neutered: formData.get('neutered') ?? 'Not Aplicable',
            vaccinated: formData.get('vaccinated') ?? 'Not Aplicable'
        };

        let errors = {};
        if (!petsData.allergies) errors.allergies = "This field is required.";
        if (!petsData.experience) errors.experience = "Please describe your experience.";
        if (!petsData.has_animals) errors.has_animals = "Please select an option.";

        if (petsData.has_animals === 'yes') {
            if (!petsData.other_animals_info) errors.other_animals_info = "Please describe your other animals.";
            if (!petsData.neutered) errors.neutered = "Please select the neutered status.";
            if (!petsData.vaccinated) errors.vaccinated = "Please select the vaccination status.";
        }

        if (Object.keys(errors).length > 0) {
            for (const key in errors) {
                document.getElementById(`${key}-error`).textContent = errors[key];
            }
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Application';
            return;
        }

        adoptionData.pets = petsData;

        try {
            const response = await fetch('../../../backend/controllers/AdoptionSubmitController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(adoptionData)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.details?.database || result.error || 'An unknown error occurred.');
            }

            sessionStorage.removeItem('adoptionData'); // Clear the session data
            window.location.href = `adoptionConfirm.html?app_id=${result['app_id']}`; // Redirect to a success page

        } catch (error) {
            console.error('Submission failed:', error);
            alert(`Submission Failed: ${error.message}`);
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Application';
        }
    });
});