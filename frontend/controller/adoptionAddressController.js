document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("address-form");

    let adoptionData = JSON.parse(sessionStorage.getItem('adoptionData')) || {};
    if (!adoptionData.pet_id) {
        alert("Your session has expired or is invalid. Please start the adoption process again.");
        window.location.href = 'searchView.html';
        return;
    }

    if (adoptionData.address) {
        document.getElementById('address_1').value = adoptionData.address.line1 || '';
        document.getElementById('address_2').value = adoptionData.address.line2 || '';
        document.getElementById('postcode').value = adoptionData.address.postcode || '';
        document.getElementById('city').value = adoptionData.address.city || '';
        document.getElementById('phone').value = adoptionData.address.phone || '';
    }

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        const formData = new FormData(form);
        const addressData = {
            line1: formData.get('address_1').trim(),
            line2: formData.get('address_2').trim(),
            postcode: formData.get('postcode').trim(),
            city: formData.get('city').trim(),
            phone: formData.get('phone').trim()
        };

        let errors = {};
        if (!addressData.line1) errors.address_1 = "Address line 1 is required.";
        if (!addressData.postcode) errors.postcode = "Postcode is required.";
        if (!addressData.city) errors.city = "City is required.";
        if (!addressData.phone) errors.phone = "A valid phone number is required.";

        if (Object.keys(errors).length > 0) {
            for (const key in errors) {
                const errorElement = document.getElementById(`${key}-error`);
                if (errorElement) {
                    errorElement.textContent = errors[key];
                }
            }
            return;
        }

        adoptionData.address = addressData;
        sessionStorage.setItem('adoptionData', JSON.stringify(adoptionData));

        window.location.href = '/Pet_Adoption/frontend/view/pages/adoptionHome.html';
    });
});