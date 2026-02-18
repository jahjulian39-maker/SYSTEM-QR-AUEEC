document.addEventListener("DOMContentLoaded", function () {
    const labelInputs = document.querySelectorAll(".label-input");

    labelInputs.forEach((input) => {
        input.addEventListener("change", function () {
            const imageId = this.getAttribute("data-id");
            const labelValue = this.value.trim(); // Get input value

            console.log("Sending label update:", { imageId, labelValue }); // Debugging log

            fetch("updated_label.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `image_id=${encodeURIComponent(imageId)}&label=${encodeURIComponent(labelValue)}`
            })
            .then(response => response.text())
            .then(data => console.log("Server Response:", data)) // Check server response
            .catch(error => console.error("Error:", error));
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".mark-checkbox");

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const imageId = this.getAttribute("data-id");
            const checkedValue = this.checked ? "true" : "false"; // Convert to string for PHP

            console.log("Sending checkbox update:", { imageId, checkedValue }); // Debugging log

            fetch("update_status.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `image_id=${encodeURIComponent(imageId)}&checked=${encodeURIComponent(checkedValue)}`
            })
            .then(response => response.text())
            .then(data => console.log("Server Response:", data)) // Check server response
            .catch(error => console.error("Error:", error));
        });
    });
});
