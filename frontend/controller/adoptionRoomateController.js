document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("roommate-form");
    const numChildrenInput = document.getElementById('childrens-number');
    const youngestAgeSelect = document.getElementById('age-home');
    const visitingChildrenRadios = document.querySelectorAll('input[name="visiting_children"]');
    const visitingAgesSelect = document.getElementById('age-visiting');

    const toggleConditionalFields = () => {
        if (numChildrenInput.value > 0) {
            youngestAgeSelect.disabled = false;
        } else {
            youngestAgeSelect.disabled = true;
            youngestAgeSelect.value = "";
        }

        const visitingChoice = document.querySelector('input[name="visiting_children"]:checked');
        if (visitingChoice && visitingChoice.value === 'yes') {
            visitingAgesSelect.disabled = false;
        } else {
            visitingAgesSelect.disabled = true;
            visitingAgesSelect.value = "";
        }
    };

    numChildrenInput.addEventListener('input', toggleConditionalFields);
    visitingChildrenRadios.forEach(radio => radio.addEventListener('change', toggleConditionalFields));

    let adoptionData = JSON.parse(sessionStorage.getItem('adoptionData')) || {};
    if (!adoptionData.pet_id) {
        alert("Your session has expired. Please start the adoption process again.");
        window.location.href = 'Pet_Adoption/frontend/view/pages/SearchView.html';
        return;
    }

    if (adoptionData.roommates) {
        const data = adoptionData.roommates;
        document.getElementById('adults-number').value = data.num_adults ?? '1';
        numChildrenInput.value = data.num_children ?? '0';
        youngestAgeSelect.value = data.youngest_age ?? '';
        visitingAgesSelect.value = data.visiting_ages ?? '';

        if (data.visiting_children === 'yes') document.getElementById('choiceYesVisiting').checked = true;
        else if (data.visiting_children === 'no') document.getElementById('choiceNoVisiting').checked = true;

        if (data.has_flatmates === 'yes') document.getElementById('choiceYesRoomate').checked = true;
        else if (data.has_flatmates === 'no') document.getElementById('choiceNoRoomate').checked = true;
    }
    else {
        document.getElementById('choiceNoVisiting').checked = true;
        document.getElementById('choiceNoRoomate').checked = true;
    }

    toggleConditionalFields();

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        const formData = new FormData(form);
        const roommateData = {
            num_adults: formData.get('num_adults') ?? 1,
            num_children: formData.get('num_children'),
            youngest_age: formData.get('youngest_age'),
            visiting_children: formData.get('visiting_children'),
            visiting_ages: formData.get('visiting_ages'),
            has_flatmates: formData.get('has_flatmates')
        };

        let errors = {};
        if (!roommateData.num_adults || parseInt(roommateData.num_adults, 10) < 1) errors.num_adults = "Number of adults must be 1 or more.";
        if (roommateData.num_children === null || roommateData.num_children === '' || parseInt(roommateData.num_children, 10) < 0) errors.num_children = "Number of children must be 0 or more.";
        if (roommateData.num_children > 0 && !roommateData.youngest_age) errors.youngest_age = "Please select the age of the youngest child.";
        if (!roommateData.visiting_children) errors.visiting_children = "Please select if you have visiting children.";
        if (roommateData.visiting_children === 'yes' && !roommateData.visiting_ages) errors.visiting_ages = "Please select the age range of visiting children.";
        if (!roommateData.has_flatmates) errors.has_flatmates = "Please select if you have flatmates or lodgers.";

        if (Object.keys(errors).length > 0) {
            for (const key in errors) {
                const errorElement = document.getElementById(`${key}-error`);
                if (errorElement) errorElement.textContent = errors[key];
            }
            return;
        }

        adoptionData.roommates = roommateData;
        sessionStorage.setItem('adoptionData', JSON.stringify(adoptionData));

        window.location.href = 'adoptionOtherAnimals.html';
    });
});