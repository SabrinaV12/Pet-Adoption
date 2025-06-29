document.addEventListener("DOMContentLoaded", async () => {
  const params = new URLSearchParams(window.location.search);
  const petId = params.get("pet_id");

  if (!petId) {
    alert("No pet ID provided.");
    return;
  }

  try {
    const response = await fetch(`${window.location.origin}/Pet_Adoption/backend/services/get_pet_profile.php?pet_id=${petId}`);
    const data = await response.json();
    if (!response.ok) throw new Error(data.error || "Failed to load pet data");

    const container = document.querySelector(".profile-container");
    if (!container) return;

    document.title = `${data.pet.name}'s Profile`;

    const header = container.querySelector("h2");
    if (header) header.textContent = data.pet.name;

    const locationEl = container.querySelector(".location");
    if (locationEl) locationEl.textContent = `${data.location.country ?? ''} · ${data.location.county ?? ''}`;

    const banner = document.querySelector(".banner");
    const profileImg = container.querySelector(".profile-pic img");
    if (data.pet.image_path) {
      const imagePath = `${window.location.origin}/Pet_Adoption/public/pet-uploads/${data.pet.image_path}`;
      if (banner) banner.style.backgroundImage = `url('${imagePath}')`;
      if (profileImg) profileImg.src = imagePath;
    }

    const adoptedBlocks = container.querySelectorAll(".adopted");
    if (data.pet.adopted) {
      if (adoptedBlocks[0]) adoptedBlocks[0].textContent = `Adopted on ${data.pet.adoption_date}`;
      if (adoptedBlocks[1]) adoptedBlocks[1].style.display = "none";
    } else {
      if (adoptedBlocks[0]) adoptedBlocks[0].style.display = "none";
      const adoptLink = adoptedBlocks[1]?.querySelector("a");
      if (adoptLink) adoptLink.href = `/Pet_Adoption/frontend/view/pages/adoptionStart.html?pet_id=${petId}`;
    }

    const infoCards = container.querySelector(".info-cards");
    if (infoCards) {
      infoCards.innerHTML = `
        <div>Gender: ${data.pet.gender}</div>
        <div>Breed: ${data.pet.breed}</div>
        <div>Age: ${data.pet.age} years</div>
        <div>Color: ${data.pet.color}</div>
        <div>Weight: ${data.pet.weight} kg</div>
        <div>Animal: ${data.pet.animal_type}</div>
      `;
    }

    const checksBox = container.querySelector(".checks-box");
    if (checksBox) {
      checksBox.innerHTML = `
        <div>Can live with children: ${data.pet.good_with_children ? "Yes" : "No"}</div>
        <div>Vaccinated: ${data.pet.vaccinated ? "Yes" : "No"}</div>
        <div>House-Trained: ${data.pet.house_trained ? "Yes" : "No"}</div>
        <div>Neutered: ${data.pet.neutered ? "Yes" : "No"}</div>
        <div>Shots up to date: ${data.pet.shots_up_to_date ? "Yes" : "No"}</div>
        <div>Microchipped: ${data.pet.microchipped ? "Yes" : "No"}</div>
      `;
    }

    const storyBox = container.querySelector(".description-box");
    if (storyBox) {
      const storyHeading = storyBox.querySelector("h3");
      const storyText = storyBox.querySelector("p");
      if (storyHeading) storyHeading.textContent = `${data.pet.name}'s Story`;
      if (storyText) storyText.textContent = data.pet.description;
    }

    const sectionEls = container.querySelectorAll(".section");
    if (sectionEls.length >= 4) {
      const restrictionsEl = sectionEls[0].querySelector("p");
      const recommendedEl = sectionEls[1].querySelector("p");
      const vaccineSection = sectionEls[2];
      const firstAidEl = sectionEls[3].querySelector("p");

      if (restrictionsEl) restrictionsEl.textContent = data.pet.restrictions;
      if (recommendedEl) recommendedEl.textContent = data.pet.recommended;
      if (firstAidEl) firstAidEl.textContent = data.pet.first_aid || "Always consult your vet for emergency advice.";

      const vaccineTable = vaccineSection.querySelector("tbody");
      if (vaccineTable && Array.isArray(data.vaccines)) {
        data.vaccines.forEach(vac => {
          const row = document.createElement("tr");
          row.innerHTML = `<td>${vac.age_in_weeks}</td><td>${vac.vaccine_name}</td>`;
          vaccineTable.appendChild(row);
        });
      }
    }

    const calendarHeader = container.querySelector(".calendar-header");
    const calendarGrid = container.querySelector(".calendar-grid");
    if (calendarHeader) calendarHeader.textContent = "Feeding Days";
    if (calendarGrid && Array.isArray(data.feedings)) {
      calendarGrid.innerHTML = "";
      data.feedings.forEach(feed => {
        const day = document.createElement("div");
        day.className = "day feeding";
        day.innerHTML = `<strong>${feed.feed_date}</strong><div class="tooltip">${feed.food_type}</div>`;
        calendarGrid.appendChild(day);
      });
    }

    const token = localStorage.getItem("jwt_token");
    const mediaRes = await fetch(`${window.location.origin}/Pet_Adoption/backend/services/fetch_pet_media.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...(token && { "Authorization": `Bearer ${token}` })
      },
      body: JSON.stringify({ pet_id: petId })
    });
    const mediaData = await mediaRes.json();

    const gallery = container.querySelector(".media-gallery");
    const emptyMsg = container.querySelector(".media-gallery + p");
    if (gallery && Array.isArray(mediaData.media)) {
      gallery.innerHTML = "";

      mediaData.media.forEach(file => {
        const item = document.createElement("div");
        item.className = "media-item";
        const ext = file.file_type.split("/")[0];
        const mediaPath = `${window.location.origin}/Pet_Adoption/public/pet-uploads/${file.file_path}`;

        if (ext === "image") {
          item.innerHTML = `<img src="${mediaPath}" alt="Pet Photo" style="max-width: 100%; border-radius: 10px;" />`;
        } else if (ext === "video") {
          item.innerHTML = `<video controls src="${mediaPath}" style="max-width: 100%; border-radius: 10px;"></video>`;
        } else if (ext === "audio") {
          item.innerHTML = `<audio controls style="width: 100%;"><source src="${mediaPath}" type="${file.file_type}"></audio>`;
        }

        if (file.can_delete) {
          const delBtn = document.createElement("button");
          delBtn.textContent = "Delete";
          delBtn.style.cssText = "margin-top: 5px; padding: 5px 10px; background: red; color: white; border: none; border-radius: 5px; cursor: pointer;";
          delBtn.addEventListener("click", async () => {
            if (!confirm("Are you sure you want to delete this media file?")) return;

            try {
              const res = await fetch(`${window.location.origin}/Pet_Adoption/backend/services/delete_pet_media.php`, {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                  ...(token && { "Authorization": `Bearer ${token}` })
                },
                body: JSON.stringify({ file_path: file.file_path })
              });

              const result = await res.json();
              if (res.ok && result.success) {
                item.remove(); 
              } else {
                alert(result.error || "Failed to delete media");
              }
            } catch (err) {
              console.error("Delete error:", err);
              alert("Error deleting media");
            }
          });
          item.appendChild(delBtn);
        }

        gallery.appendChild(item);
      });

      if (emptyMsg) emptyMsg.style.display = mediaData.media.length ? "none" : "block";
    }

    const uploadInput = container.querySelector('input[name="pet_id"]');
    if (uploadInput) uploadInput.value = petId;

  } catch (err) {
    console.error("Error loading pet data:", err);
    alert("Failed to load pet profile. Please try again.");
  }
});
