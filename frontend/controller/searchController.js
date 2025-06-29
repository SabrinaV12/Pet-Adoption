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
                ? `http://localhost/Pet_Adoption/public/pet-uploads/${pet.image_path}`
                : `http://localhost/Pet_Adoption/public/uploads/profile/default-pet.jpg`;

            petCard.innerHTML = `
                <img src="${imageUrl}" alt="${pet.name}" style="width: 100%; height: auto;" />
                <h4>${pet.name}</h4>
                <p>
  Type: ${pet.animal_type} <br>
  Breed: ${pet.breed} <br>
  Age: ${pet.age} years<br>
  Size: ${pet.size} <br>
  Location: ${pet.owner_country ?? 'Unknown'}, ${pet.owner_county ?? 'Unknown'}
</p>

                <a href="/Pet_Adoption/frontend/view/pages/pet_profile.html?pet_id=${pet.id}">
  View ${pet.name}'s profile
</a>

            `;

            resultsContainer.appendChild(petCard);
        });

    } catch (error) {
        console.error('Error fetching pets:', error);
        resultsContainer.innerHTML = '<p>Error loading pets. Please try again.</p>';
    }
}

async function loadLocationFilters() {
  try {
    const response = await fetch('/Pet_Adoption/backend/services/get_locations.php');
    const data = await response.json();

    const countrySelect = document.querySelector('select[name="country"]');
    const countySelect = document.querySelector('select[name="county"]');

    countrySelect.innerHTML = `<option value="">Any</option>`;
    countySelect.innerHTML = `<option value="">Any</option>`;

    data.countries.forEach(country => {
      const option = document.createElement('option');
      option.value = country;
      option.textContent = country;
      countrySelect.appendChild(option);
    });

    data.counties.forEach(county => {
      const option = document.createElement('option');
      option.value = county;
      option.textContent = county;
      countySelect.appendChild(option);
    });
  } catch (err) {
    console.error("Failed to load country/county filters", err);
  }
}


document.addEventListener('DOMContentLoaded', () => {
    loadLocationFilters();
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
