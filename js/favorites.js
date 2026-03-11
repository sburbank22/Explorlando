document.addEventListener('DOMContentLoaded', async function () {
    const userId = parseInt(localStorage.getItem('user_id') || '1');
    const savedList = document.getElementById('saved-list');
    const favoriteList = document.getElementById('favorite-list');

    try {
        const res = await fetch('../api/favorites/getFavorites.php?user_id=' + userId);
        const items = await res.json();

        if (!Array.isArray(items)) {
            savedList.innerHTML = '<p style="color:red">Error: ' + (items.error || 'API unreachable') + '</p>';
            favoriteList.innerHTML = '<p style="color:red">Error: ' + (items.error || 'API unreachable') + '</p>';
            return;
        }

        items.forEach(function (item) {
            const wrap = document.createElement('div');
            wrap.className = 'mini-card-wrap';

            const card = document.createElement('div');
            card.className = 'mini-card';

            const img = document.createElement('img');
            img.src = item.image_url || '../img/placeholder.jpg';
            img.alt = item.name;

            const name = document.createElement('p');
            name.textContent = item.name;

            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-btn';
            removeBtn.textContent = '\u00D7';
            removeBtn.addEventListener('click', async function () {
                const r = await fetch('../api/favorites/removeFavorite.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, id: parseInt(item.id) })
                });
                if (r.ok) {
                    wrap.remove();
                }
            });

            card.appendChild(img);
            card.appendChild(name);
            wrap.appendChild(card);
            wrap.appendChild(removeBtn);

            if (item.type === 'saved') {
                savedList.appendChild(wrap);
            } else {
                favoriteList.appendChild(wrap);
            }
        });
    } catch (e) {
        savedList.innerHTML = '<p style="color:red">Could not reach API. Is Docker running?</p>';
        favoriteList.innerHTML = '<p style="color:red">Could not reach API. Is Docker running?</p>';
    }
});