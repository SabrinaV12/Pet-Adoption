document.addEventListener('DOMContentLoaded', async () => {
    const container = document.querySelector('.notifications-container');

    try {
        const response = await fetch('http://localhost/Pet_Adoption/backend/api/index.php/notifications', {

            method: 'GET',
            credentials: 'include',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const contentType = response.headers.get('content-type');
        console.log('Response Content-Type:', contentType);

        if (!response.ok) {
            throw new Error(`Fetch failed. Status: ${response.status}`);
        }

        const text = await response.text();
        console.log('Raw response:', text);

        let notifications;
        try {
            notifications = JSON.parse(text);
        } catch (parseErr) {
            throw new Error('Invalid JSON from server');
        }

        console.log('Notifications:', notifications);

        container.innerHTML = '<h1>My Notifications</h1>';

        if (!notifications.length) {
            container.innerHTML += '<p>No new notifications.</p>';
            return;
        }

        notifications.forEach(notification => {
    const notifElement = document.createElement('a');

    const isResolved = ['accepted', 'denied'].includes(
        (notification.status ?? '').toLowerCase()
    );

    notifElement.className = `notification ${notification.is_read ? 'read' : 'unread'}`;

    if (isResolved) {
        notifElement.href = 'javascript:void(0);';
        notifElement.classList.add('disabled');
        notifElement.style.pointerEvents = 'none';
        notifElement.style.opacity = '0.6';
        notifElement.style.cursor = 'not-allowed';
    } else {
        notifElement.href = notification.link || '#';
    }

    notifElement.innerHTML = `
        <div class="notification-message">
          ${notification.message}
          ${isResolved ? `<span class="status-label">${notification.status}</span>` : ''}
        </div>
        <span class="notification-time">${new Date(notification.created_at).toLocaleString()}</span>
    `;

    container.appendChild(notifElement);
});


    } catch (error) {
        console.error('Error loading notifications:', error);
        container.innerHTML = '<p>Error loading notifications.</p>';
    }
});
