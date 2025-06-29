document.addEventListener('DOMContentLoaded', async () => {
    const appId = new URLSearchParams(window.location.search).get('app_id');
    const appDetails = document.getElementById('app-details');
    const actions = document.getElementById('actions');

    if (!appId) {
        appDetails.textContent = "Invalid application ID.";
        return;
    }

    try {
        const res = await fetch(`../../../backend/services/get_applications.php?app_id=${appId}`);
        if (!res.ok) throw new Error("Failed to load application");
        const data = await res.json();

        appDetails.innerHTML = `
            <p><strong>Pet:</strong> ${data.pet_name}</p>
            <p><strong>Applicant:</strong> ${data.applicant_id}</p>
            <p><strong>Address:</strong> ${data.address_line1}, ${data.city}</p>
            <p><strong>Experience:</strong> ${data.experience}</p>
            <p><strong>Has other animals:</strong> ${data.has_other_animals}</p>
            <!-- Add more fields as needed -->
        `;

        actions.style.display = 'block';

        document.getElementById('approveBtn').addEventListener('click', () => handleAction('approve'));
        document.getElementById('denyBtn').addEventListener('click', () => handleAction('deny'));

    } catch (err) {
        appDetails.textContent = "Error loading application.";
        console.error(err);
    }

    async function handleAction(action) {
        try {
            const res = await fetch(`../../../backend/controllers/DecisionController.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ app_id: appId, action })
            });

            const result = await res.json();
            if (!res.ok) throw new Error(result.error || "Request failed");

            alert("Application " + (action === 'approve' ? "approved" : "denied"));
            window.location.href = 'notifications.html';

        } catch (err) {
            alert("Error: " + err.message);
        }
    }
});
