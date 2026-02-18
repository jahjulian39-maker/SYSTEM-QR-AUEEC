document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    searchInput.addEventListener("keyup", function () {
        let filter = searchInput.value.toLowerCase();
        let userBoxes = document.querySelectorAll(".user-box");

        userBoxes.forEach((box) => {
            let name = box.querySelector("p strong").textContent.toLowerCase();
            let userId = box.querySelector(".user-id").textContent.toLowerCase();
            if (name.includes(filter) || userId.includes(filter)) {
                box.style.display = "block";
            } else {
                box.style.display = "none";
            }
        });
    });
});
