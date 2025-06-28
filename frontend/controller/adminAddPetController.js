document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('addPetForm');
    const adoptedCheckbox = document.getElementById('adopted');
    const adoptionDateGroup = document.getElementById('adoption_date_group');
    const feedingContainer = document.getElementById('feeding-calendar-container');
    const addFeedingBtn = document.getElementById('add-feeding-row');
    const vaccinationsContainer = document.getElementById('vaccinations-container');
    const addVaccinationBtn = document.getElementById('add-vaccination-row');

    function displayStatusMessage(message, isError = false) {
        const statusDiv = document.getElementById('status-message');
        if (statusDiv) {
            statusDiv.textContent = message;
            statusDiv.className = isError ? 'status-message error' : 'status-message success';
            statusDiv.style.display = 'block';

            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        }
    }

    adoptedCheckbox.addEventListener('change', function () {
        adoptionDateGroup.classList.toggle('hidden', !this.checked);
    });

    addFeedingBtn.addEventListener('click', function () {
        const newRow = createDynamicRow(feedingContainer,
            `<label>Feed Date: <input type="date" name="feed_date[]"></label>
             <label>Food Type: <input type="text" name="food_type[]"></label>`
        );
        feedingContainer.appendChild(newRow);
    });

    addVaccinationBtn.addEventListener('click', function () {
        const newRow = createDynamicRow(vaccinationsContainer,
            `<label>Age in weeks: <input type="number" name="age_in_weeks[]" min="0"></label>
             <label>Vaccine Name: <input type="text" name="vaccine_name[]"></label>`
        );
        vaccinationsContainer.appendChild(newRow);
    });

    function createDynamicRow(container, innerHTML) {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'dynamic-row';
        rowDiv.innerHTML = innerHTML;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-btn';
        removeBtn.textContent = 'Remove';
        removeBtn.onclick = function () {
            container.removeChild(rowDiv);
        };

        rowDiv.appendChild(removeBtn);
        return rowDiv;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!validateForm()) {
            displayStatusMessage('Please fix the errors before submitting.', true);
            return;
        }

        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.textContent = 'Saving...';
        submitButton.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        throw new Error("Received an unexpected response from the server:\n" + text);
                    });
                }
            })
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = '../adminAnimals.html?status=pet_added';
                } else {
                    displayStatusMessage('Error: ' + (data.message || 'An unknown error occurred.'), true);
                }
            })
            .catch(error => {
                console.error('Submission Error:', error);
                displayStatusMessage('An error occurred while submitting. Please check the console.', true);
            })
            .finally(() => {
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
            });
    });

    function validateForm() {
        let isValid = true;
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        document.querySelectorAll('.invalid').forEach(el => el.classList.remove('invalid'));

        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                showError(field, 'This field is required.');
            }
        });

        return isValid;
    }

    function showError(field, message) {
        field.classList.add('invalid');
        const errorContainer = field.parentElement.querySelector('.error-message');
        if (errorContainer) {
            errorContainer.textContent = message;
        }
    }
});