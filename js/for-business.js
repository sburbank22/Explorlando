document.addEventListener('DOMContentLoaded', function () {

    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                navLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    const attractionCards = document.querySelectorAll('.attraction-card');
    attractionCards.forEach(card => {
        card.addEventListener('click', function () {
            const attractionId = this.getAttribute('data-attraction');
            const attractionName = attractionId.replace('-', ' ');
            console.log('Viewing attraction:', attractionId);
            alert(`Navigating to ${attractionName} details...`);
        });
    });

});