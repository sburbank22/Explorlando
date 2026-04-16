document.addEventListener('DOMContentLoaded', () => {
	// Find all primary buttons on attraction detail pages
	const btns = document.querySelectorAll('.primary-btn');

	btns.forEach(btn => {
		// Only add behavior if button text mentions "Save" (so it targets save buttons)
		if (!/save/i.test(btn.textContent)) return;

		btn.addEventListener('click', (e) => {
			e.preventDefault();

			// Switch text to 'View Attraction'
			btn.textContent = 'View Attraction';

			// Convert button into a link that navigates to saved-attractions.html on click
			btn.addEventListener('click', () => {
				window.location.href = '../pages/saved-attractions.html';
			}, { once: true });
		}, { once: true });
	});
});

// Discounts button: navigate to the featured discounts landing page created by a collaborator
const discountsBtn = document.getElementById('discounts-btn');
if (discountsBtn) {
  discountsBtn.addEventListener('click', (e) => {
    e.preventDefault();
    window.location.href = 'featureddiscountspage.html';
  });
}

// Map specific button text to collaborator pages; supports page-specific Available Discounts
;(function wireNamedButtons() {
	const path = (window.location.pathname || '');
	const page = path.substring(path.lastIndexOf('/') + 1).toLowerCase();

	// Find all buttons on the page and attach navigation when text matches
	const buttons = Array.from(document.querySelectorAll('button'));
	buttons.forEach(btn => {
		const txt = (btn.textContent || '').trim();

		// Available Discounts: map to page-specific collaborator pages when available
		if (/available discounts/i.test(txt)) {
			let href = 'availablediscounts.html';
			if (page === 'alligator-farm.html') href = 'babyjoeysalligatorfarmavailablediscounts.html';
			else if (page === 'skyline-rooftop.html') href = 'skylinerooftoploungeavailablediscounts.html';

			btn.addEventListener('click', (e) => {
				e.preventDefault();
				window.location.href = href;
			});
			return;
		}

		// Open Lobby mapping
		if (/open lobby/i.test(txt)) {
			btn.addEventListener('click', (e) => {
				e.preventDefault();
				window.location.href = 'lobby.html';
			});
			return;
		}
	});
})();

// Filters: simple keyword-based client-side filtering for attraction cards
(function () {
	const filtersRoot = document.querySelector('.filters-dropdown');
	if (!filtersRoot) return;

	const cards = Array.from(document.querySelectorAll('.card-list .card'));

	function getCheckedValues(name) {
		return Array.from(filtersRoot.querySelectorAll(`input[name="${name}"]:checked`)).map(i => i.value.toLowerCase());
	}

	function cardMatches(card, types, amusements) {
		const text = (card.textContent || '').toLowerCase();

		// If no filters selected in a group, treat it as matching
		const typeOk = types.length === 0 || types.some(t => text.includes(t));
		const amuseOk = amusements.length === 0 || amusements.some(a => text.includes(a));
		return typeOk && amuseOk;
	}

	function applyFilters() {
		const types = getCheckedValues('type');
		const amusements = getCheckedValues('amusement');

		cards.forEach(card => {
			if (cardMatches(card, types, amusements)) {
				card.style.display = '';
			} else {
				card.style.display = 'none';
			}
		});
	}

	// Listen for changes inside the filters dropdown
	filtersRoot.addEventListener('change', applyFilters);
	// Also apply filters when the dropdown opens/closes (in case defaults exist)
	filtersRoot.addEventListener('toggle', applyFilters);
})();
