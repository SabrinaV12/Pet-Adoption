const filterForm = document.querySelector('.search-form');
const resultsContainer = document.getElementById('results');

async function loadPets(filters = {}) {
    const queryParams = new URLSearchParams(filters).toString();
    console.log("Loading pets with:", queryParams);

    try {
        const response = await fetch(`http://localhost/Pet_Adoption/backend/controllers/SearchController.php?${queryParams}`, {
            method: 'GET',
            credentials: 'include'
        });

        let pets = await response.json();
        console.log("Fetched pets:", pets);

        if (!Array.isArray(pets)) {
            pets = pets ? [pets] : [];
        }

        resultsContainer.innerHTML = '';

        if (!pets.length) {
            resultsContainer.innerHTML = '<p>No pets match your filters.</p>';
            return;
        }

        pets.forEach(pet => {
            const petCard = document.createElement('div');
            petCard.classList.add('pet-card');

            const imageUrl = pet.image_path
    ? `http://localhost/Pet_Adoption/frontend/view/${pet.image_path}`
    : `http://localhost/Pet_Adoption/frontend/view/assets/default-pet.jpg`;

            petCard.innerHTML = `
                <img src="${imageUrl}" alt="${pet.name}" style="width: 100%; height: auto;" />
                <h4>${pet.name}</h4>
                <p>
                    Type: ${pet.animal_type} <br>
                    Breed: ${pet.breed} <br>
                    Age: ${pet.age} years<br>
                    Size: ${pet.size}
                </p>
                <a href="/petPage.php?id=${pet.id}">More Info</a>
            `;

            resultsContainer.appendChild(petCard);
        });

    } catch (error) {
        console.error('Error fetching pets:', error);
        resultsContainer.innerHTML = '<p>Error loading pets. Please try again.</p>';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadPets();
});

filterForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(filterForm);
    const filters = Object.fromEntries(formData.entries());
    if (formData.getAll('type[]').length) {
        filters['type[]'] = formData.getAll('type[]');
    }

    loadPets(filters);
});
