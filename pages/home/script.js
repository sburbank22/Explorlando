

document.addEventListener('DOMContentLoaded', function () {

    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            const link = this.getAttribute('data-link');
            if (link) {
                window.location.href = link;
            }
        });
    });

    const profileBtn = document.querySelector('.profile-btn');
    if (profileBtn) {
        profileBtn.addEventListener('click', function () {
            window.location.href = 'profile.html';
        });
    }

});