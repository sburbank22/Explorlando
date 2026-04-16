document.addEventListener('DOMContentLoaded', async function () {
    const savedList = document.getElementById('saved-list');
    const favoriteList = document.getElementById('favorite-list');

    function getAttractionPage(name) {
        if (name === 'Orange Grove Mini Golf') return '../attractions/orange-grove-mini-golf.html';
        if (name === 'Alligator Farm') return '../attractions/alligator-farm.html';
        if (name === 'Skyline') return '../attractions/skyline-rooftop.html';
        if (name === 'Winter Town') return '../attractions/wintertown-botanical-garden.html';
        return '#';
    }

    function updateEmptyMessages() {
        if (savedList && savedList.children.length === 0) {
            savedList.innerHTML = '<p>No saved attractions yet.</p>';
        }

        if (favoriteList && favoriteList.children.length === 0) {
            favoriteList.innerHTML = '<p>No favorite attractions yet.</p>';
        }
    }

    async function removeAttraction(attractionId, wrap, type) {
        try {
            const res = await fetch('/api/favorites/removeFavorite.php', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    attraction_id: attractionId
                })
            });

            const data = await res.json();

            if (!res.ok) {
                alert(data.error || 'Failed to remove attraction');
                return;
            }

            wrap.remove();
            updateEmptyMessages();

        } catch (e) {
            console.error('Remove attraction error:', e);
            alert('Something went wrong while removing the attraction.');
        }
    }

    try {
        const res = await fetch('/api/favorites/getFavorites.php', {
            credentials: 'include'
        });

        const items = await res.json();
        console.log('Favorites response:', items);

        if (!Array.isArray(items)) {
            savedList.innerHTML = '<p style="color:red">Error: ' + (items.error || 'API unreachable') + '</p>';
            favoriteList.innerHTML = '<p style="color:red">Error: ' + (items.error || 'API unreachable') + '</p>';
            return;
        }

        if (items.length === 0) {
            savedList.innerHTML = '<p>No saved attractions yet.</p>';
            favoriteList.innerHTML = '<p>No favorite attractions yet.</p>';
            return;
        }

        savedList.innerHTML = '';
        favoriteList.innerHTML = '';

        items.forEach(function (item) {
            const wrap = document.createElement('div');
            wrap.className = 'mini-card-wrap';

            const card = document.createElement('div');
            card.className = 'mini-card';

            const link = document.createElement('a');
            link.href = getAttractionPage(item.name);
            link.className = 'saved-link';

            const img = document.createElement('img');
            img.src = item.image_url || '../../images/user-1.png';
            img.alt = item.name || 'Attraction';

            const name = document.createElement('p');
            name.textContent = item.name || 'Unnamed attraction';

            link.appendChild(img);
            link.appendChild(name);

            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-btn';
            removeBtn.textContent = '✕';
            removeBtn.dataset.attractionId = item.attraction_id;

            removeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                removeAttraction(item.attraction_id, wrap, item.type);
            });

            card.appendChild(link);
            card.appendChild(removeBtn);
            wrap.appendChild(card);

            if (item.type === 'saved') {
                savedList.appendChild(wrap);
            } else {
                favoriteList.appendChild(wrap);
            }
        });
    } catch (e) {
        console.error('Favorites load error:', e);
        savedList.innerHTML = '<p style="color:red">Could not reach API. Is Docker running?</p>';
        favoriteList.innerHTML = '<p style="color:red">Could not reach API. Is Docker running?</p>';
    }
});