<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['id'])) {
    echo "Error: User not logged in.";
    exit;
}

if (isset($_POST['image_id']) && isset($_POST['checked'])) {
    $userId = $_SESSION['id']; 
    $imageId = $_POST['image_id'];
    $checked = $_POST['checked'] == "true" ? 1 : 0; // Ensure it's stored as 1 or 0

    // Debugging: Print received data
    echo "Received Data: image_id = $imageId, checked = $checked <br>";

    // Ensure the row exists before updating
    $checkSql = "SELECT * FROM marks WHERE user_id = '$userId' AND image_id = '$imageId'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Update existing record
        $sql = "UPDATE marks SET checked='$checked' WHERE user_id='$userId' AND image_id='$imageId'";
    } else {
        // Insert new record if it doesn’t exist
        $sql = "INSERT INTO marks (user_id, image_id, label, checked) VALUES ('$userId', '$imageId', '', '$checked')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Success: Checkbox status saved!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Error: Missing image_id or checked.";
}
?>
