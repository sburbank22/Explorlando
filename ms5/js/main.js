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

// once everything is uploaded, link discounts page 
// Discounts button placeholder navigation (attractions page)
const discountsBtn = document.getElementById('discounts-btn');
if (discountsBtn) {
	discountsBtn.addEventListener('click', (e) => {
		e.preventDefault();
		// Placeholder: navigate to discounts.html (teammate will create this page later)
		window.location.href = 'discounts.html';
	});
}
