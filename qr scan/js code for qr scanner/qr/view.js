document.addEventListener("DOMContentLoaded", () => {
    const imageContainer = document.querySelector(".image-container");

    // Create the lightbox container dynamically
    const lightbox = document.createElement("div");
    lightbox.id = "lightbox";
    lightbox.style.position = "fixed";
    lightbox.style.top = "0";
    lightbox.style.left = "0";
    lightbox.style.width = "100%";
    lightbox.style.height = "100%";
    lightbox.style.background = "rgba(0, 0, 0, 0.8)";
    lightbox.style.display = "none";
    lightbox.style.justifyContent = "center";
    lightbox.style.alignItems = "center";
    lightbox.style.zIndex = "1000";
    document.body.appendChild(lightbox);

    // Create the image inside the lightbox
    const lightboxImg = document.createElement("img");
    lightboxImg.style.maxWidth = "90%";
    lightboxImg.style.maxHeight = "90%";
    lightboxImg.style.border = "5px solid white";
    lightboxImg.style.borderRadius = "10px";
    lightbox.appendChild(lightboxImg);

    // Close lightbox when clicked
    lightbox.addEventListener("click", () => {
        lightbox.style.display = "none";
    });

    // Function to handle zoom (lightbox) clicks
    function addZoomFunctionality() {
        const images = document.querySelectorAll(".image-box img");
        images.forEach(img => {
            img.addEventListener("click", (e) => {
                lightboxImg.src = e.target.src;
                lightbox.style.display = "flex";
            });
        });
    }

    // Call zoom functionality for initial images
    addZoomFunctionality();

    // DELETE IMAGE FUNCTION
    imageContainer.addEventListener("click", (event) => {
        if (event.target.classList.contains("delete-btn")) {
            const button = event.target;
            const imageId = button.getAttribute("data-id");
            const filePath = button.getAttribute("data-file");

            if (confirm("Are you sure you want to delete this image?")) {
                fetch("delete.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${imageId}&file_path=${encodeURIComponent(filePath)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        document.getElementById(`image-${imageId}`).remove();
                        addZoomFunctionality(); // Re-add zoom after deleting
                    } else {
                        alert("Error deleting image.");
                    }
                });
            }
        }
    });
});
