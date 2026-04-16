// Applies saved dark mode on page load (for all pages)
(function () {
  const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";

  if (darkModeEnabled) {
    document.body.classList.add("dark-mode");
  }
})();


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
const lobbyFilterToggle = document.getElementById("filterToggle");
const lobbyFilterMenu = document.getElementById("filterMenu");

if (lobbyFilterToggle && lobbyFilterMenu) {
  lobbyFilterToggle.addEventListener("click", () => {
    lobbyFilterMenu.style.display =
      lobbyFilterMenu.style.display === "block" ? "none" : "block";
  });

  document.addEventListener("click", (e) => {
    if (
      !lobbyFilterToggle.contains(e.target) &&
      !lobbyFilterMenu.contains(e.target)
    ) {
      lobbyFilterMenu.style.display = "none";
    }
  });
}

// ----- Open Lobby Search Message ------ //
document.addEventListener("DOMContentLoaded", function () {
  const searchBtn = document.querySelector(".search-btn");
  console.log("found button:", searchBtn);

  if (searchBtn) {
    searchBtn.addEventListener("click", function () {
      alert("No groups available yet. Check back soon!");
    });
  }
});

// -------- Group Members Contact Message ----- //
function contactMember() {
  alert("Messaging feature coming soon! Stay tuned to connect with group members 👀");
}


// -------- Dark Mode ----- //
document.addEventListener("DOMContentLoaded", function () {
  const darkModeToggle = document.getElementById("darkModeToggle");

  // Check saved theme
  const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";

  if (darkModeEnabled) {
    document.body.classList.add("dark-mode");

    if (darkModeToggle) {
      darkModeToggle.checked = true;
    }
  }

  // Toggle dark mode when switch is clicked
  if (darkModeToggle) {
    darkModeToggle.addEventListener("change", function () {
      if (this.checked) {
        document.body.classList.add("dark-mode");
        localStorage.setItem("darkMode", "enabled");
      } else {
        document.body.classList.remove("dark-mode");
        localStorage.setItem("darkMode", "disabled");
      }
    });
  }
});