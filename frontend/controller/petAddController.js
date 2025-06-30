document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');

  document.getElementById('add-feeding').addEventListener('click', () => {
    const container = document.getElementById('feeding-calendar');
    const entry = document.createElement('div');
    entry.className = 'feeding-entry';
    entry.innerHTML = `
      <input type="date" name="feed_date[]" placeholder="Feed Date" />
      <input type="text" name="food_type[]" placeholder="Food Type" />
      <button type="button" class="remove-entry">Remove</button>
    `;
    container.appendChild(entry);
  });

  document.getElementById('add-vaccination').addEventListener('click', () => {
    const container = document.getElementById('vaccinations');
    const entry = document.createElement('div');
    entry.className = 'vaccination-entry';
    entry.innerHTML = `
      <input type="number" name="age_in_weeks[]" placeholder="Age in weeks" />
      <input type="text" name="vaccine_name[]" placeholder="Vaccine Name" />
      <button type="button" class="remove-entry">Remove</button>
    `;
    container.appendChild(entry);
  });

  document.body.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-entry')) {
      e.target.parentElement.remove();
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    ['vaccinated', 'house_trained', 'neutered', 'microchipped', 'good_with_children', 'shots_up_to_date'].forEach(
      (field) => {
        formData.set(field, formData.get(field) ? 1 : 0);
      }
    );

    try {
      const response = await fetch('/Pet_Adoption/backend/services/handle_pet_request.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'
      });

      const text = await response.text();
      console.log('Raw server response:', text);

      let result;
      try {
        result = JSON.parse(text);
      } catch (jsonError) {
        throw new Error('Server did not return valid JSON:\n' + text);
      }

      if (!response.ok) throw new Error(result.message || 'Request failed');

      window.location.href = 'confirmation.html'; 

    } catch (error) {
      console.error('Error submitting pet request:', error);
      alert('Failed to submit pet request. Please try again.');
    }
  });
});
