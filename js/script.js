const filterToggle = document.getElementById("filterToggle");
const filterMenu = document.getElementById("filterMenu");

filterToggle.addEventListener("click", () => {
  filterMenu.style.display =
    filterMenu.style.display === "block" ? "none" : "block";
});

document.addEventListener("click", (e) => {
  if (!filterToggle.contains(e.target) && !filterMenu.contains(e.target)) {
    filterMenu.style.display = "none";
  }
});
