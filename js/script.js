// --- Stephanie's navigation functions ---
function goToLogin() {
  window.location.href = "login.html";
}

function goToCreateAccount1() {
  window.location.href = "create-account1.html";
}

function goToCreateAccount2() {
  window.location.href = "create-account2.html";
}

function goToProfile() {
  window.location.href = "profile.html";
}

function goToEditProfile() {
  window.location.href = "edit-profile.html";
}

// --- Connery's filter menu logic ---
const filterToggle = document.getElementById("filterToggle");
const filterMenu = document.getElementById("filterMenu");

if (filterToggle && filterMenu) {
  filterToggle.addEventListener("click", () => {
    filterMenu.style.display =
      filterMenu.style.display === "block" ? "none" : "block";
  });

  document.addEventListener("click", (e) => {
    if (!filterToggle.contains(e.target) && !filterMenu.contains(e.target)) {
      filterMenu.style.display = "none";
    }
  });
}
