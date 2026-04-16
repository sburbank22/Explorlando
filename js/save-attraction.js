document.addEventListener("DOMContentLoaded", () => {
    const saveButtons = document.querySelectorAll(".save-btn");

    saveButtons.forEach((button) => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const attractionId = button.dataset.attractionId;
            console.log("SAVING ID:", attractionId);

            try {
                const response = await fetch("/api/favorites/addFavorites.php", {
                    method: "POST",
                    credentials: "include",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        attraction_id: attractionId,
                    }),
                });

                const data = await response.json();
                console.log("SAVE RESPONSE:", data);

                if (response.ok) {
                    alert(data.message || "Attraction saved!");
                } else {
                    alert(data.error || "Save failed");
                }
            } catch (error) {
                console.error("Error saving attraction:", error);
                alert("Something went wrong while saving.");
            }
        });
    });
});