document.addEventListener('DOMContentLoaded', function () {
    var saveBtn = document.querySelector('.save-btn');
    if (!saveBtn) return;

    saveBtn.addEventListener('click', async function (e) {
        e.preventDefault();
        var attractionId = parseInt(saveBtn.getAttribute('data-attraction-id'));
        var userId = parseInt(localStorage.getItem('user_id') || '1');

        try {
            const res = await fetch('../api/favorites/addFavorites.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, attraction_id: attractionId, type: 'saved' })
            });
            if (!res.ok) {
                const body = await res.json();
                alert('Save failed: ' + (body.error || res.status));
                return;
            }
        } catch (err) {
            alert('Could not reach the server. Is Docker running?');
            return;
        }

        window.location.href = 'saved-attractions.html';
    });
});