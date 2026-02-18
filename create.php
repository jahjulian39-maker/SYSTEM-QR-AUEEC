<?php
session_start();
include("connect.php");

// ✅ Ensure only logged-in users can access
if (!isset($_SESSION['id'])) {
    header("Location: index.php?error=Please%20log%20in%20first!");
    exit();
}
?>




<?php
if (!isset($_SESSION['id'])) {
    die("Error: User ID not set in session.");
}
?>





<!DOCTYPE html>
<html>
<head>
    <title>Chief Student Archieve Create</title>
    <link rel="stylesheet" href="create.css">
</head>
<body>
    <div class="container">
        <h1>Upload an Image or File</h1>
        <form id="planForm" action="upload.php" method="POST" enctype="multipart/form-data">  <!-- Added action and method -->
            <input type="hidden" name="userId" value="<?php echo $_SESSION['id']; ?>"> <!-- Assuming you store user ID in session -->
            <label for="planName">Plan Name:</label>
            <input type="text" id="planName" name="planName" required><br><br>

            <label for="items">Add Items:</label>
            <div id="itemsContainer">
                <div class="item">
                    <input type="text" class="itemName" name="items[]" placeholder="Item Name">
                    <button type="button" class="removeItem">-</button>
                </div>
            </div>
            <button type="button" id="addItem">+</button><br><br>

            <label for="fileUpload">Upload Image/File/Video:</label>
            <input type="file" id="fileUpload" name="uploadedFile[]" multiple> <br><br> <!-- Changed name to array -->

            <button type="submit">Save Plan</button>
        </form>

        <h2>Upload</h2>
        <ul id="planList"></ul>
    </div>

    <a href="homepage.php">Back to Main Page</a>
    <script src="script.js"></script>
</body>
</html>
